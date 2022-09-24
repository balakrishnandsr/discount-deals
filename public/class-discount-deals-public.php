<?php
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * All stuffs like discount calculation, applying discount finding perfect workflows for products and cart will go here
 */
class Discount_Deals_Public {
	/**
	 * Stores discount for each line item.
	 *
	 * @var float[] $cart_item_discounts cart item discounts.
	 */
	private static $cart_item_discounts = array();

	/**
	 * Need to forcefully calculate product price instead of get from cache.
	 *
	 * @var boolean $force_fetch_price yes/no.
	 */
	private $force_fetch_price = false;

	/**
	 * Need to calculate discount at the time?.
	 *
	 * @var boolean $calculate_discount yes/no.
	 */
	private $calculate_discount = true;

	/**
	 * Stores discount for each item.
	 *
	 * @var float[] $product_discounts item discounts.
	 */
	private static $product_discounts = array();

	/**
	 * Stores free products.
	 *
	 * @var array $free_products cart item discounts.
	 */
	private static $free_products = array();

	/**
	 * Stores free products.
	 *
	 * @var array $priced_bogo_products cart item discounts.
	 */
	private static $priced_bogo_products = array();

	/**
	 * In some case customer will eligible for get BxGY product.
	 *
	 * @var array $eligible_bxgy_products bxgy discount products.
	 */
	private static $eligible_bxgy_products = array();

	/**
	 * Stores products that need to remove from cart.
	 *
	 * @var array $remove_products cart item discounts.
	 */
	private static $remove_products = array();

	/**
	 * Key for free cart item
	 *
	 * @var string $_free_cart_item_key key for free cart item.
	 */
	public $_free_cart_item_key = 'discount_deals_free_gift';

	/**
	 * Key for free cart item gift workflow id
	 *
	 * @var string $_free_cart_item_workflow_key key for free cart item.
	 */
	public $_free_cart_item_workflow_key = 'discount_deals_free_gift_by';

	/**
	 * The ID of this plugin.
	 *
	 * @var      string $plugin_slug The ID of this plugin.
	 */
	private $plugin_slug;

	/**
	 * The version of this plugin.
	 *
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_slug = $plugin_name;
		$this->version     = $version;
		if ( ! is_admin() || defined( 'DOING_AJAX' ) ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			add_action( 'woocommerce_init', array( $this, 'init_public_hooks' ) );
		}
	}//end __construct()

	/**
	 * Init all public facing hooks
	 *
	 * @return void
	 */
	public function init_public_hooks() {
		$this->load_files();
		add_filter( 'woocommerce_product_get_price', array( $this, 'get_product_price' ), 99, 2 );
		add_filter( 'woocommerce_product_variation_get_price', array( $this, 'get_product_price' ), 99, 2 );
		add_filter( 'woocommerce_product_get_sale_price', array( $this, 'get_sale_price' ), 99, 2 );
		add_filter( 'woocommerce_product_variation_get_sale_price', array( $this, 'get_sale_price' ), 99, 2 );
		add_filter( 'woocommerce_add_to_cart', array( $this, 'item_added_to_cart' ), 99, 6 );
		add_filter( 'woocommerce_variation_prices', array( $this, 'get_variation_prices' ), 99, 3 );
		// Cart Discount.
		$show_strikeout_for_cart_item = Discount_Deals_Settings::get_settings( 'show_strikeout_price_in_cart', 'yes' );
		if ( 'yes' == $show_strikeout_for_cart_item ) {
			add_filter( 'woocommerce_cart_item_price', array( $this, 'show_price_strikeout' ), 98, 3 );
		}
		$save_text_position = Discount_Deals_Settings::get_settings( 'where_display_saving_text', 'disabled' );
		if ( 'disabled' != $save_text_position ) {
			if ( 'both_line_item_and_after_total' == $save_text_position || 'after_total' == $save_text_position ) {
				add_filter(
					'woocommerce_cart_totals_order_total_html',
					array(
						$this,
						'show_you_saved_text_in_cart_total',
					),
					99,
					3
				);
				add_filter(
					'woocommerce_get_formatted_order_total',
					array(
						$this,
						'order_formatted_subtotal',
					),
					99,
					3
				);
			}
			if ( 'both_line_item_and_after_total' == $save_text_position || 'on_each_line_item' == $save_text_position ) {
				add_filter( 'woocommerce_cart_item_subtotal', array( $this, 'show_you_saved_text' ), 99, 3 );
				add_filter(
					'woocommerce_order_formatted_line_subtotal',
					array(
						$this,
						'order_formatted_line_subtotal',
					),
					99,
					3
				);
			}
		}
		$cart_discount_type = Discount_Deals_Settings::get_settings( 'apply_cart_discount_as', 'coupon' );
		if ( 'coupon' == $cart_discount_type ) {
			add_filter( 'woocommerce_before_cart_table', array( $this, 'apply_cart_discount_as_coupon' ), 99, 3 );
			add_filter( 'woocommerce_coupon_get_amount', array( $this, 'set_coupon_amount' ), 99, 2 );
			add_filter( 'woocommerce_coupon_get_discount_type', array( $this, 'set_coupon_discount_type' ), 99, 2 );
			add_filter( 'woocommerce_cart_totals_coupon_html', array( $this, 'hide_remove_coupon' ), 10, 3 );
		} else {
			add_action( 'woocommerce_cart_calculate_fees', array( $this, 'apply_cart_discount_as_fee' ), 99 );
		}
		// Free shipping.
		add_filter( 'woocommerce_shipping_methods', array( $this, 'register_free_shipping_method' ) );
		add_filter(
			'woocommerce_shipping_discount_deals_free_shipping_is_available',
			array(
				$this,
				'is_free_shipping_available',
			)
		);
		// BOGO.
		add_action( 'woocommerce_before_calculate_totals', array( $this, 'calculate_and_add_bogo_discount' ), 97 );
		add_action( 'woocommerce_before_calculate_totals', array( $this, 'remove_free_products_from_cart' ), 98 );
		add_action( 'woocommerce_before_calculate_totals', array( $this, 'add_free_products_to_cart' ), 99 );
		add_action( 'woocommerce_before_calculate_totals', array( $this, 'set_price_for_free_products' ), 100 );
		add_filter( 'woocommerce_cart_item_remove_link', array( $this, 'hide_cart_item_remove_link' ), 99, 2 );
		add_filter( 'woocommerce_cart_item_quantity', array( $this, 'hide_cart_item_quantity_update' ), 99, 3 );
		add_action( 'woocommerce_after_cart_item_name', array( $this, 'highlight_free_gifts' ), 99, 2 );
		add_filter( 'woocommerce_cart_item_price', array( $this, 'show_price_strikeout_for_bogo' ), 99, 3 );
		add_filter( 'woocommerce_before_cart', array( $this, 'show_bxgy_eligible_notices' ), 99 );
		// Promotional messages.
		add_action(
			'woocommerce_before_add_to_cart_form',
			array(
				$this,
				'show_promotional_message_before_add_to_cart',
			)
		);
		add_action(
			'woocommerce_after_add_to_cart_form',
			array(
				$this,
				'show_promotional_message_after_add_to_cart',
			)
		);
		add_action(
			'woocommerce_after_single_product_summary',
			array(
				$this,
				'show_promotional_message_after_product_summary',
			)
		);
		// Get product price by ajax.
		add_action(
			'wp_ajax_nopriv_discount_deals_get_product_discount_price',
			array(
				$this,
				'get_product_discount_price',
			)
		);
		add_action(
			'wp_ajax_discount_deals_get_product_discount_price',
			array(
				$this,
				'get_product_discount_price',
			)
		);
		// Remove unwanted stuffs after order placed.
		add_action( 'woocommerce_new_order', array( $this, 'on_after_new_order' ), 99 );
		// Show applied discounts.
		add_action( 'woocommerce_before_cart', array( $this, 'show_applied_workflow_notices' ), 98 );
		// Save discount information for orders.
		add_action( 'woocommerce_checkout_create_order', array( $this, 'before_checkout_create_order' ), 99, 2 );
		add_action(
			'woocommerce_checkout_create_order_line_item',
			array(
				$this,
				'before_checkout_create_order_line_item',
			),
			99,
			4
		);
		add_action( 'woocommerce_checkout_update_order_review', array( $this, 'update_order_review' ), 9 );
	}//end init_public_hooks()

	/**
	 * Update checkout form data
	 *
	 * @param string $post_data All post data.
	 *
	 * @return void
	 */
	public function update_order_review( $post_data ) {
		if ( empty( $post_data ) ) {
			return;
		}
		$post = array();
		wp_parse_str( $post_data, $post );
		WC()->customer->set_props(
			array(
				'billing_first_name'  => discount_deals_get_value_from_array( $post, 'billing_first_name' ),
				'billing_last_name'   => discount_deals_get_value_from_array( $post, 'billing_last_name' ),
				'billing_company'     => discount_deals_get_value_from_array( $post, 'billing_company' ),
				'billing_email'       => discount_deals_get_value_from_array( $post, 'billing_email' ),
				'billing_phone'       => discount_deals_get_value_from_array( $post, 'billing_phone' ),
				'shipping_company'    => discount_deals_get_value_from_array( $post, 'billing_phone' ),
				'shipping_first_name' => discount_deals_get_value_from_array( $post, 'billing_phone' ),
				'shipping_last_name'  => discount_deals_get_value_from_array( $post, 'billing_phone' ),
			)
		);
		// By default, price will be fetched from cache. Here we need to recalculate once again.
		// So we are using this flag.
		$this->force_fetch_price = true;
	}//end update_order_review()


	/**
	 * Set order item meta details
	 *
	 * @param WC_Order_Item $item          Order item.
	 * @param string        $cart_item_key Cart item key.
	 * @param array         $cart_item     Cart item details.
	 * @param WC_Order      $order         Order object.
	 *
	 * @return void
	 */
	public function before_checkout_create_order_line_item( $item, $cart_item_key, $cart_item, $order ) {
		/**
		 * Filter to modify cart line item object.
		 *
		 * @since 1.0.0
		 */
		$product = apply_filters( 'discount_deals_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
		if ( ! is_a( $product, 'WC_Product' ) ) {
			return;
		}
		$total_discount = 0;
		$quantity       = intval( $cart_item['quantity'] );
		$sale_price     = $product->get_sale_price();
		$regular_price  = $product->get_regular_price();
		if ( is_numeric( $regular_price ) && is_numeric( $sale_price ) ) {
			if ( $sale_price < $regular_price ) {
				$total_discount = $quantity * ( $regular_price - $sale_price );
			}
		}
		if ( 0 >= $total_discount ) {
			return;
		}
		$props = array(
			'dd_saved_amount' => $this->calculate_tax_for_cart_item( $product, $total_discount ),
		);
		if ( $this->is_free_cart_item( $cart_item ) ) {
			$props['dd_type'] = 'free';
		}
		$item->add_meta_data( 'discount_deals_discount', $props, true );
	}//end before_checkout_create_order_line_item()


	/**
	 * Format the line subtotal
	 *
	 * @param string        $subtotal Subtotal.
	 * @param WC_Order_Item $item     Item object.
	 * @param WC_Order      $order    Order object.
	 *
	 * @return string
	 */
	public function order_formatted_line_subtotal( $subtotal, $item, $order ) {
		$item_meta = $item->get_meta( 'discount_deals_discount', true );
		if ( ! empty( $item_meta ) ) {
			$saved_amount = discount_deals_get_value_from_array( $item_meta, 'dd_saved_amount', 0 );
			$type         = discount_deals_get_value_from_array( $item_meta, 'dd_type', 'amount' );
			if ( 'free' == $type ) {
				return $subtotal;
			}
			if ( 0 < $saved_amount ) {
				$you_save_text = Discount_Deals_Settings::get_settings( 'you_saved_text' );
				$message       = str_replace( '{{discount}}', wc_price( $saved_amount ), $you_save_text );

				return $subtotal . ' <br /><p class="dd-you-save-text" style="color: green;">' . $message . '</p>';
			}
		}

		return $subtotal;
	}//end order_formatted_line_subtotal()


	/**
	 * Format the line subtotal
	 *
	 * @param string   $total Subtotal.
	 * @param WC_Order $order Order object.
	 *
	 * @return string
	 */
	public function order_formatted_subtotal( $total, $order ) {
		$items = $order->get_items();
		if ( ! empty( $items ) ) {
			$total_discount = 0;
			foreach ( $items as $item ) {
				$item_meta = $item->get_meta( 'discount_deals_discount', true );
				if ( ! empty( $item_meta ) ) {
					$type = discount_deals_get_value_from_array( $item_meta, 'dd_type', 'amount' );
					if ( 'free' == $type ) {
						continue;
					}
					$saved_amount   = discount_deals_get_value_from_array( $item_meta, 'dd_saved_amount', 0 );
					$total_discount += floatval( $saved_amount );
				}
			}
			if ( 0 < $total_discount ) {
				$you_save_text = Discount_Deals_Settings::get_settings( 'you_saved_text' );
				$message       = str_replace( '{{discount}}', wc_price( $total_discount ), $you_save_text );

				return $total . ' <br /><p class="dd-you-save-text" style="color: green;">' . $message . '</p>';
			}
		}

		return $total;
	}//end order_formatted_subtotal()


	/**
	 * Save important information before placing order.
	 *
	 * @param WC_Order $order Order object.
	 * @param array    $data  Extra information.
	 *
	 * @return void
	 */
	public function before_checkout_create_order( $order, $data ) {
		$applied_discounts = discount_deals_get_applied_workflow_discounts();
		if ( ! empty( $applied_discounts ) ) {
			$order->update_meta_data( '_discount_deals_has_discount', 'yes' );
			$order->update_meta_data( '_discount_deals_discount_details', $applied_discounts );
		}
	}//end before_checkout_create_order()


	/**
	 * Show applied workflows to the cart users.
	 *
	 * @return void
	 */
	public function show_applied_workflow_notices() {
		discount_deals_get_cart_discount();
		$applied_workflows = discount_deals_get_applied_workflows();

		if ( ! empty( $applied_workflows ) ) {
			$need_to_show_message = Discount_Deals_Settings::get_settings( 'show_applied_discounts_message', 'yes' );
			if ( 'yes' != $need_to_show_message ) {
				return;
			}
			$message = Discount_Deals_Settings::get_settings( 'applied_discount_message' );
			if ( empty( $message ) ) {
				return;
			}
			$combine_message = Discount_Deals_Settings::get_settings( 'combine_applied_discounts_message', 'no' );
			if ( 'yes' == $combine_message ) {
				$titles = '';
				$total  = count( $applied_workflows );
				foreach ( $applied_workflows as $index => $workflow ) {
					$titles .= $workflow->get_title();
					if ( $total > 1 ) {
						if ( $total != $index + 1 ) {
							$titles .= ', ';
						}
						if ( $total - 2 == $index ) {
							$titles .= __( ' and ', 'discount-deals' );
						}
					}
				}
				$new_message = str_replace( '{{workflow_title}}', $titles, $message );
				/**
				 * Filter to modify applied workflows promotional messages.
				 *
				 * @since 1.0.0
				 */
				$new_message = apply_filters( 'discount_deals_applied_workflow_text', $new_message, $applied_workflows, null );
				wc_print_notice( $new_message );
			} else {
				foreach ( $applied_workflows as $workflow ) {
					$new_message = str_replace( '{{workflow_title}}', $workflow->get_title(), $message );
					/**
					 * Filter to modify applied workflows promotional messages.
					 *
					 * @since 1.0.0
					 */
					$new_message = apply_filters( 'discount_deals_applied_workflow_text', $new_message, $applied_workflows, $workflow );
					wc_print_notice( $new_message );
				}
			}
		}
	}//end show_applied_workflow_notices()


	/**
	 * Do remove some session variables and transient after order is newly placed.
	 *
	 * @param integer $order_id Order id.
	 *
	 * @return void
	 */
	public function on_after_new_order( $order_id = 0 ) {
		WC()->session->__unset( 'discount_deals_cart_created_time' );
		$order = wc_get_order( $order_id );
		if ( is_a( $order, 'WC_Order' ) ) {
			$customer_id = $order->get_customer_id();
			delete_transient( 'discount_deals_cpp_' . $customer_id );
		}
	}//end on_after_new_order()


	/**
	 * Set woocommerce product price as per simple discount.
	 *
	 * @return void
	 */
	public function get_product_discount_price() {
		remove_filter( 'woocommerce_product_get_price', array( $this, 'get_product_price' ), 99 );
		remove_filter( 'woocommerce_product_variation_get_price', array( $this, 'get_product_price' ), 99 );
		$nonce = discount_deals_get_post_data( 'nonce', '' );
		if ( ! wp_verify_nonce( $nonce, 'discount-deals-bulk-discount' ) ) {
			die( 0 );
		}
		$product_id  = intval( discount_deals_get_post_data( 'product_id', 0 ) );
		$product_qty = intval( discount_deals_get_post_data( 'quantity', 0 ) );
		if ( 0 >= $product_qty && 0 >= $product_id ) {
			die( 0 );
		}
		$product               = wc_get_product( $product_id );
		$quantity_in_cart      = $this->get_quantities_in_cart( $product );
		$quantity_to_calculate = $product_qty + $quantity_in_cart;
		if ( array_key_exists( $product_id, self::$product_discounts ) ) {
			$price = self::$product_discounts[ $product_id ]['price_before_discount'];
		} else {
			$price = $product->get_price();
		}

		$discount = discount_deals_get_product_discount( $price, $product, $quantity_to_calculate, false );
		/**
		 * Filter to modify the bulk table item quantity count in cart.
		 *
		 * @since 1.0.0
		 */
		$items_in_cart_summary = 0 < $quantity_in_cart ? wp_kses_post( apply_filters( 'discount_deals_bulk_table_summary_items_in_cart_text', sprintf( '%s %d %s %d %s', __( 'Of', 'discount-deals' ), $quantity_to_calculate, __( 'quantities, ', 'discount-deals' ), $quantity_in_cart, __( 'quantities were already in the shopping cart.', 'discount-deals' ) ), $quantity_to_calculate, $quantity_in_cart, $product, $this ) ) : '';
		wp_send_json(
			array(
				'success'                   => true,
				'price_html'                => wc_format_sale_price( $product->get_regular_price(), $discount ),
				'new_quantity'              => $product_qty,
				'quantity_in_cart'          => $quantity_in_cart,
				'discount'                  => wc_price( $discount ),
				'quantity_price_summary'    => $quantity_to_calculate . ' &times; ' . wc_price( $discount ),
				'existing_quantity_summary' => $items_in_cart_summary,
				'total_price_summary'       => wc_price( $quantity_to_calculate * $discount ),
			)
		);
	}//end get_product_discount_price()

	/**
	 * Get how many quantities in cart
	 *
	 * @param WC_Product $product Product obj.
	 *
	 * @return integer
	 */
	public function get_quantities_in_cart( $product ) {
		$cart_items = WC()->cart->get_cart();
		$quantity   = 0;
		// For bulk discount, check cart item quantity and calculate discount.
		if ( ! empty( $cart_items ) ) {
			foreach ( $cart_items as $cart_item_key => $cart_item ) {
				/**
				 * Filter to modify cart line item object.
				 *
				 * @since 1.0.0
				 */
				$cart_item_object = apply_filters( 'discount_deals_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				if ( is_a( $cart_item_object, 'WC_Product' ) ) {
					if ( $product->get_id() == $cart_item_object->get_id() ) {
						$quantity = ! empty( $cart_item['quantity'] ) ? intval( $cart_item['quantity'] ) : 0;
					}
				}
			}
		}

		return $quantity;
	}//end get_quantities_in_cart()


	/**
	 * Show promotional message before add to cart button
	 *
	 * @return void
	 */
	public function show_promotional_message_before_add_to_cart() {
		global $product;
		$position          = 'before_add_to_cart_button';
		$all_promotions    = Discount_Deals_Workflows::get_product_promotional_messages( $product, $position );
		$product_discounts = self::$product_discounts;
		/**
		 * Filter to modify promotional message that was shown before add to cart form.
		 *
		 * @since 1.0.0
		 */
		apply_filters( 'discount_deals_show_promotional_message_before_add_to_cart', $all_promotions, $product );
		wc_get_template(
			'discount-deals-product-promotional-messages.php',
			array(
				'product' => $product,
				'position' => $position,
				'all_promotions' => $all_promotions,
				'product_discounts' => $product_discounts,
				'public_class' => $this,
			),
			'',
			DISCOUNT_DEALS_ABSPATH . '/public/partials/'
		);
	}//end show_promotional_message_before_add_to_cart()


	/**
	 * Show promotional message after product summary
	 *
	 * @return void
	 */
	public function show_promotional_message_after_product_summary() {
		global $product;
		$position          = 'after_single_product_summary';
		$all_promotions    = Discount_Deals_Workflows::get_product_promotional_messages( $product, $position );
		$product_discounts = self::$product_discounts;
		/**
		 * Filter to modify promotional message that was shown after product summary.
		 *
		 * @since 1.0.0
		 */
		apply_filters( 'discount_deals_show_promotional_message_after_product_summary', $all_promotions, $product );
		wc_get_template(
			'discount-deals-product-promotional-messages.php',
			array(
				'product' => $product,
				'position' => $position,
				'all_promotions' => $all_promotions,
				'product_discounts' => $product_discounts,
				'public_class' => $this,
			),
			'',
			DISCOUNT_DEALS_ABSPATH . '/public/partials/'
		);
	}//end show_promotional_message_after_product_summary()


	/**
	 * Show promotional message after add to cart button
	 *
	 * @return void
	 */
	public function show_promotional_message_after_add_to_cart() {
		global $product;
		$position          = 'after_add_to_cart_button';
		$all_promotions    = Discount_Deals_Workflows::get_product_promotional_messages( $product, $position );
		$product_discounts = self::$product_discounts;
		/**
		 * Filter to modify applied workflows promotional message.
		 *
		 * @since 1.0.0
		 */
		apply_filters( 'discount_deals_show_promotional_message_after_add_to_cart', $all_promotions, $product );
		wc_get_template(
			'discount-deals-product-promotional-messages.php',
			array(
				'product' => $product,
				'position' => $position,
				'all_promotions' => $all_promotions,
				'product_discounts' => $product_discounts,
				'public_class' => $this,
			),
			'',
			DISCOUNT_DEALS_ABSPATH . '/public/partials/'
		);
	}//end show_promotional_message_after_add_to_cart()


	/**
	 * Load files required for frontend
	 *
	 * @return void
	 */
	public function load_files() {
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/class-discount-deals-free-shipping.php';
	}//end load_files()


	/**
	 * Check the given cart s free or not.
	 *
	 * @param array $cart_item Cart item details.
	 *
	 * @return boolean
	 */
	public function is_free_cart_item( $cart_item ) {
		return ! empty( $cart_item[ $this->_free_cart_item_key ] );
	}//end is_free_cart_item()


	/**
	 * Remove free product from the cart
	 *
	 * @return void
	 */
	public function remove_free_products_from_cart() {
		remove_action(
			'woocommerce_before_calculate_totals',
			array(
				$this,
				'remove_free_products_from_cart',
			),
			98
		);
		if ( ! empty( self::$remove_products ) && is_array( self::$remove_products ) ) {
			foreach ( self::$remove_products as $cart_item_key ) {
				WC()->cart->remove_cart_item( $cart_item_key );
			}
			self::$remove_products = array();
		}
		add_action( 'woocommerce_before_calculate_totals', array( $this, 'remove_free_products_from_cart' ), 98 );
	}//end remove_free_products_from_cart()


	/**
	 * Add free product to the cart
	 *
	 * @return void
	 */
	public function add_free_products_to_cart() {
		remove_action( 'woocommerce_before_calculate_totals', array( $this, 'add_free_products_to_cart' ), 99 );
		if ( ! empty( self::$free_products ) && is_array( self::$free_products ) ) {
			foreach ( self::$free_products as $gift ) {
				try {
					if ( is_array( $gift ) && array_key_exists( 'product_id', $gift ) && array_key_exists( 'variation_id', $gift ) && array_key_exists( 'quantity', $gift ) && array_key_exists( 'variation', $gift ) && array_key_exists( 'meta', $gift ) ) {
						WC()->cart->add_to_cart( $gift['product_id'], $gift['quantity'], $gift['variation_id'], $gift['variation'], $gift['meta'] );
					}
				} catch ( Exception $exception ) {
					// Maybe the out of stock.
					continue;
				}
			}
			self::$free_products = array();
		}
		add_action( 'woocommerce_before_calculate_totals', array( $this, 'add_free_products_to_cart' ), 99 );
	}//end add_free_products_to_cart()


	/**
	 * Stop showing remove link for gifts
	 *
	 * @param string $remove_link   Link to remove cart item.
	 * @param string $cart_item_key Cart item key.
	 *
	 * @return string
	 */
	public function hide_cart_item_remove_link( $remove_link, $cart_item_key ) {
		$cart_items = WC()->cart->get_cart();
		if ( array_key_exists( $cart_item_key, $cart_items ) ) {
			if ( $this->is_free_cart_item( $cart_items[ $cart_item_key ] ) ) {
				return '';
			}
		}

		return $remove_link;
	}//end hide_cart_item_remove_link()


	/**
	 * Stop showing remove link for gifts
	 *
	 * @return void
	 */
	public function set_price_for_free_products() {
		$cart_items = WC()->cart->get_cart();
		if ( empty( $cart_items ) ) {
			return;
		}
		foreach ( $cart_items as $cart_item_key => $cart_item ) {
			if ( $this->is_free_cart_item( $cart_item ) ) {
				/**
				 * Filter to modify cart line item object.
				 *
				 * @since 1.0.0
				 */
				$product = apply_filters( 'discount_deals_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product->set_price( 0 );
			}
		}
	}//end set_price_for_free_products()


	/**
	 * Stop showing remove link for gifts
	 *
	 * @param array  $cart_item     Cart item.
	 * @param string $cart_item_key Cart item key.
	 *
	 * @return void
	 */
	public function highlight_free_gifts( $cart_item, $cart_item_key ) {
		if ( $this->is_free_cart_item( $cart_item ) ) {
			$message = Discount_Deals_Settings::get_settings( 'bogo_discount_highlight_message' );
			echo wp_kses_post( '<p class="dd-bogo-text" style="color: green;">' . $message . '</p>' );
		}
	}//end highlight_free_gifts()


	/**
	 * Stop showing quantity update for gifts
	 *
	 * @param string $update_field  Link to remove cart item.
	 * @param string $cart_item_key Cart item key.
	 * @param array  $cart_item     Cart item.
	 *
	 * @return string
	 */
	public function hide_cart_item_quantity_update( $update_field, $cart_item_key, $cart_item ) {
		if ( $this->is_free_cart_item( $cart_item ) ) {
			return $cart_item['quantity'];
		}

		return $update_field;
	}//end hide_cart_item_quantity_update()


	/**
	 * What is the free product for particular cart item
	 *
	 * @param string $actual_cart_item_key Check that the cart item has free product.
	 * @param array  $cart_items           All cart items.
	 *
	 * @return false|integer|string
	 */
	public function get_free_product_of_cart_item( $actual_cart_item_key, $cart_items ) {
		foreach ( $cart_items as $cart_item_key => $cart_item ) {
			if ( $this->is_free_cart_item( $cart_item ) ) {
				if ( $cart_item[ $this->_free_cart_item_key ] == $actual_cart_item_key ) {
					return $cart_item_key;
				}
			}
		}

		return false;
	}//end get_free_product_of_cart_item()


	/**
	 * Check that cart item has wrong free item.
	 *
	 * @param array $free_cart_item  Free cart item.
	 * @param array $actual_discount Actual discount for the product.
	 *
	 * @return boolean
	 */
	public function is_wrong_item_in_cart( $free_cart_item, $actual_discount ) {
		if ( $actual_discount['discount_on_same'] ) {
			return $free_cart_item['quantity'] != $actual_discount['discount_quantity'];
		} else {
			$discount_item = $actual_discount['discount_product'];

			return ( $free_cart_item['product_id'] != $discount_item['product_id'] || $free_cart_item['variation_id'] != $discount_item['variation_id'] || $free_cart_item['quantity'] != $actual_discount['discount_quantity'] );
		}
	}//end is_wrong_item_in_cart()


	/**
	 * Add bogo discount to the cart
	 *
	 * @return void
	 */
	public function calculate_and_add_bogo_discount() {
		$cart_items = WC()->cart->get_cart();
		if ( ! empty( $cart_items ) ) {
			foreach ( $cart_items as $cart_item_key => $cart_item ) {
				/**
				 * Filter to modify cart line item object.
				 *
				 * @since 1.0.0
				 */
				$cart_item_object = apply_filters( 'discount_deals_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				if ( is_a( $cart_item_object, 'WC_Product' ) ) {
					if ( $this->is_free_cart_item( $cart_item ) ) {
						$actual_product_key = $cart_item[ $this->_free_cart_item_key ];
						// In some cases, customer will remove the original product. then we didn't need to give discount to the customer.
						if ( ! array_key_exists( $actual_product_key, $cart_items ) ) {
							// Remove it. We don't need anymore.
							self::$remove_products[] = $cart_item_key;
						}
						continue;
					}
					$free_product_cart_key = $this->get_free_product_of_cart_item( $cart_item_key, $cart_items );
					$discount_details      = discount_deals_get_bogo_discount( $cart_item_object, $cart_item['quantity'] );
					// Has no discount but the free product for this item is found in cart. maybe it was previously added one.
					if ( empty( $discount_details ) && $free_product_cart_key ) {
						// Remove it. We don't need anymore.
						self::$remove_products[] = $free_product_cart_key;
						continue;
					}
					// If there is no more discount, to continue to check for net cart item.
					if ( empty( $discount_details ) ) {
						continue;
					}
					$actual_discount = current( $discount_details );
					if ( $actual_discount['is_free'] ) {
						if ( $free_product_cart_key ) {
							$free_cart_item = $cart_items[ $free_product_cart_key ];
							if ( $this->is_wrong_item_in_cart( $free_cart_item, $actual_discount ) ) {
								// If there is any change in quantity, then we need to update the quantity.
								self::$remove_products[] = $free_product_cart_key;
							}
							continue;
						}
						if ( $actual_discount['discount_on_same'] ) {
							$free_product_detail = array(
								'product_id'   => $cart_item['product_id'],
								'quantity'     => $actual_discount['discount_quantity'],
								'variation_id' => $cart_item['variation_id'],
								'variation'    => $cart_item['variation'],
								'meta'         => array(
									$this->_free_cart_item_key          => $cart_item_key,
									$this->_free_cart_item_workflow_key => array_keys( $discount_details )[0],
								),
							);
						} else {
							$free_product_detail = array(
								'product_id'   => $actual_discount['discount_product']['product_id'],
								'quantity'     => $actual_discount['discount_quantity'],
								'variation_id' => $actual_discount['discount_product']['variation_id'],
								'variation'    => $actual_discount['discount_product']['variation'],
								'meta'         => array(
									$this->_free_cart_item_key          => $cart_item_key,
									$this->_free_cart_item_workflow_key => array_keys( $discount_details )[0],
								),
							);
						}
						self::$free_products[] = $free_product_detail;
					} else {
						if ( $free_product_cart_key ) {
							// Remove it. We don't need anymore.
							self::$remove_products[] = $free_product_cart_key;
						}
						if ( $actual_discount['discount_on_same'] ) {
							$discounted_cart_item     = $cart_item;
							$discounted_cart_item_key = $cart_item_key;
						} else {
							// If the item is already found in the cart then give discount.
							if ( $actual_discount['discount_product']['cart_item_key'] ) {
								$discounted_cart_item_key = $actual_discount['discount_product']['cart_item_key'];
								$discounted_cart_item     = $cart_items[ $discounted_cart_item_key ];
							} else {
								$existing_cart_key = $this->is_bxgy_item_found_in_cart( $cart_items, $actual_discount['discount_product'] );
								if ( $existing_cart_key ) {
									$discounted_cart_item_key = $existing_cart_key;
									$discounted_cart_item     = $cart_items[ $discounted_cart_item_key ];
								} else {
									$key = $actual_discount['discount_product']['product_id'] . '-' . $actual_discount['discount_product']['variation_id'];
									if ( ! array_key_exists( $key, self::$eligible_bxgy_products ) && isset( $actual_discount['show_eligible_message'] ) && $actual_discount['show_eligible_message'] ) {
										self::$eligible_bxgy_products[ $key ] = $actual_discount;
									}
									$discounted_cart_item     = false;
									$discounted_cart_item_key = false;
								}
							}
						}
						if ( ! $discounted_cart_item || ! $discounted_cart_item_key ) {
							continue;
						}
						$quantity_in_cart            = $discounted_cart_item['quantity'];
						$discount_quantity           = $actual_discount['discount_quantity'];
						$original_price_quantity     = $quantity_in_cart - $discount_quantity;
						/**
						 * Filter to modify cart line item object.
						 *
						 * @since 1.0.0
						 */
						$discounted_cart_item_object = apply_filters( 'discount_deals_cart_item_product', $discounted_cart_item['data'], $discounted_cart_item, $discounted_cart_item_key );
						$item_price                  = $discounted_cart_item_object->get_price();

						// IF the discount is flat or percentage, then do calculations accordingly.
						if ( $quantity_in_cart < $discount_quantity ) {
							// If free quantity is greater than cart item quantity, set discount as discount for individual product.
							$discount_per_item = $actual_discount['discount'];
							$discount_quantity = $quantity_in_cart;
						} else {
							// Else, take total quantity and calculate accordingly.
							$total_discounted_price = ( $item_price * $discount_quantity ) - $actual_discount['total'];
							if ( 0 >= $total_discounted_price ) {
								$total_discounted_price = ( $item_price * $discount_quantity );
								$total_discount         = ( $item_price * $original_price_quantity ) - $total_discounted_price;
								if ( 0 >= $total_discount ) {
									$discount_per_item = $total_discounted_price / $quantity_in_cart;
								} else {
									$discount_per_item = $total_discount / $quantity_in_cart;
								}
							} else {
								$discount_per_item = $actual_discount['total'] / $quantity_in_cart;
							}
						}

						$price_per_product = $item_price - $discount_per_item;
						if ( 0 >= $price_per_product ) {
							$price_per_product = 0;
						}

						self::$priced_bogo_products[ $discounted_cart_item_key ] = array(
							'original_price'          => $discounted_cart_item_object->get_price(),
							'original_price_quantity' => max( 0, $original_price_quantity ),
							'discount_price'          => $item_price - $actual_discount['discount'],
							'discount_quantity'       => $discount_quantity,
							'meta'                    => $actual_discount,
						);

						$this->calculate_discount = false;
						$discounted_cart_item_object->set_price( $price_per_product );
					}
				}
			}
		}
	}//end calculate_and_add_bogo_discount()

	/**
	 * Show eligible message to the customer
	 *
	 * @return void
	 */
	public function show_bxgy_eligible_notices() {
		if ( ! empty( self::$eligible_bxgy_products ) ) {
			foreach ( self::$eligible_bxgy_products as $eligible_bxgy_product ) {
				$free_product_details = $eligible_bxgy_product['discount_product'];
				if ( ! empty( $free_product_details['variation_id'] ) ) {
					$product = wc_get_product( $free_product_details['variation_id'] );
				} else {
					$product = wc_get_product( $free_product_details['product_id'] );
				}
				if ( is_a( $product, 'WC_Product' ) ) {
					$discount_price   = wc_price( $eligible_bxgy_product['total'] );
					$discount_message = "You are now eligible to get $discount_price discount on {$product->get_title()}, would you like to <a href='{$product->add_to_cart_url()}'>grab this offer?</a>";
					/**
					 * Filter to modify Buy X Get Y product is not found in cart. This filter can be used to change promotion message.
					 *
					 * @since 1.0.0
					 */
					$discount_message = apply_filters( 'discount_deals_bxgy_eligible_notice', $discount_message, $product, $eligible_bxgy_product );
					if ( ! empty( $discount_message ) ) {
						wc_print_notice( $discount_message, 'notice' );
					}
				}
			}
		}
	}//end show_bxgy_eligible_notices()


	/**
	 * Check the cart has eligible BXGY item
	 *
	 * @param array $cart_items    All cart items.
	 * @param array $discount_item Discount item.
	 *
	 * @return false|integer|string
	 */
	public function is_bxgy_item_found_in_cart( $cart_items, $discount_item ) {
		foreach ( $cart_items as $cart_item_key => $cart_item ) {
			if ( $this->is_free_cart_item( $cart_item ) ) {
				continue;
			}

			if ( $cart_item['product_id'] == $discount_item['product_id'] && $cart_item['variation_id'] == $discount_item['variation_id'] ) {
				return $cart_item_key;
			}
		}

		return false;
	}//end is_bxgy_item_found_in_cart()


	/**
	 * Add timestamp to session when 1st product is added to cart.
	 *
	 * @param string  $cart_item_key  Cart item key.
	 * @param integer $product_id     Product id.
	 * @param integer $quantity       Item quantity.
	 * @param integer $variation_id   Variation id.
	 * @param array   $variation      Variation details.
	 * @param array   $cart_item_data Cart item details.
	 *
	 * @return void
	 */
	public function item_added_to_cart( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
		$created_time = WC()->session->get( 'discount_deals_cart_created_time', false );
		if ( ! $created_time ) {
			WC()->session->set( 'discount_deals_cart_created_time', time() );
		}
		WC()->session->set( 'discount_deals_cart_updated_time', time() );
	}//end item_added_to_cart()


	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @return void
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_slug, plugin_dir_url( __FILE__ ) . 'css/discount-deals-public.css', array(), $this->version, 'all' );

	}//end enqueue_styles()


	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug, plugin_dir_url( __FILE__ ) . 'js/discount-deals-public.js', array( 'jquery' ), $this->version, true );
		$localize_params = array(
			'ajax_url'            => admin_url( 'admin-ajax.php' ),
			'product_price_nonce' => wp_create_nonce( 'discount-deals-bulk-discount' ),
		);
		wp_localize_script( $this->plugin_slug, 'discount_deals_params', $localize_params );

	}//end enqueue_scripts()

	/**
	 * Show strikeout for each product
	 *
	 * @param string $item_price    Price html.
	 * @param array  $cart_item     Cart item details.
	 * @param string $cart_item_key Cart item hash.
	 *
	 * @return string
	 */
	public function show_price_strikeout( $item_price, $cart_item, $cart_item_key ) {
		if ( $this->is_free_cart_item( $cart_item ) ) {
			return $item_price;
		}
		/**
		 * Filter to modify cart line item object.
		 *
		 * @since 1.0.0
		 */
		$product = apply_filters( 'discount_deals_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

		/*
		 * @var WC_Product $product product.
		 */

		$sale_price          = $product->get_price();
		/**
		 * Filter to modify "From which price, we need to show strikeout for cart item".
		 *
		 * @since 1.0.0
		 */
		$show_strikeout_from = apply_filters( 'discount_deals_cart_item_show_strikeout_from', 'regular_price', $item_price, $cart_item, $cart_item_key );
		$regular_price       = $product->get_regular_price();

		if ( 'sale_price' == $show_strikeout_from ) {
			$product_id = $product->get_id();
			if ( array_key_exists( $product_id, self::$product_discounts ) ) {
				$regular_price = self::$product_discounts[ $product_id ]['price_before_discount'];
			}
		}

		if ( is_numeric( $regular_price ) && is_numeric( $sale_price ) ) {
			if ( $sale_price < $regular_price ) {
				if ( ! isset( self::$cart_item_discounts[ $cart_item_key ] ) ) {
					self::$cart_item_discounts[ $cart_item_key ] = $regular_price - $sale_price;
				}
				$item_price = wc_format_sale_price( $this->calculate_tax_for_cart_item( $product, $regular_price ), $this->calculate_tax_for_cart_item( $product, $sale_price ) );
			}
		}

		return $item_price;
	}//end show_price_strikeout()


	/**
	 * Show strikeout for each product which has bogo
	 *
	 * @param string $item_price    Price html.
	 * @param array  $cart_item     Cart item details.
	 * @param string $cart_item_key Cart item hash.
	 *
	 * @return string
	 */
	public function show_price_strikeout_for_bogo( $item_price, $cart_item, $cart_item_key ) {
		if ( empty( self::$priced_bogo_products ) ) {
			return $item_price;
		}
		if ( array_key_exists( $cart_item_key, self::$priced_bogo_products ) ) {
			$discount_details = self::$priced_bogo_products[ $cart_item_key ];
			/**
			 * Filter to modify cart line item object.
			 *
			 * @since 1.0.0
			 */
			$product             = apply_filters( 'discount_deals_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			/**
			 * Filter to modify "From which price, we need to show strikeout for cart item".
			 *
			 * @since 1.0.0
			 */
			$show_strikeout_from = apply_filters( 'discount_deals_cart_item_show_strikeout_from', 'regular_price', $item_price, $cart_item, $cart_item_key );
			$regular_price       = $product->get_regular_price();

			if ( 'sale_price' == $show_strikeout_from ) {
				$product_id = $product->get_id();
				if ( array_key_exists( $product_id, self::$product_discounts ) ) {
					$regular_price = self::$product_discounts[ $product_id ]['price_before_discount'];
				}
			}
			$regular_price  = $this->calculate_tax_for_cart_item( $product, $regular_price );
			$discount_price = $this->calculate_tax_for_cart_item( $product, $discount_details['discount_price'] );
			$original_price = $this->calculate_tax_for_cart_item( $product, $discount_details['original_price'] );
			$item_price     = '<div>' . wc_format_sale_price( $regular_price, $discount_price );
			if ( 1 < $discount_details['discount_quantity'] ) {
				$item_price .= ' &times; ' . $discount_details['discount_quantity'];
			}
			$item_price .= ' </div>';
			if ( 0 < $discount_details['original_price_quantity'] ) {
				$item_price .= '<div>' . wc_format_sale_price( $regular_price, $original_price ) . ' &times; ' . $discount_details['original_price_quantity'] . '</div>';
			}

			return $item_price;
		}

		return $item_price;
	}//end show_price_strikeout_for_bogo()


	/**
	 * Show you saved for each cart item
	 *
	 * @param string $item_subtotal Price html.
	 * @param array  $cart_item     Cart item details.
	 * @param string $cart_item_key Cart item hash.
	 *
	 * @return string
	 */
	public function show_you_saved_text( $item_subtotal, $cart_item, $cart_item_key ) {
		if ( $this->is_free_cart_item( $cart_item ) ) {
			return $item_subtotal;
		}
		$quantity = intval( $cart_item['quantity'] );
		if ( 0 >= $quantity ) {
			return $item_subtotal;
		}
		$you_save_text = Discount_Deals_Settings::get_settings( 'you_saved_text' );

		/**
		 * Filter to modify cart line item object.
		 *
		 * @since 1.0.0
		 */
		$product       = apply_filters( 'discount_deals_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
		// Return previously calculated discount.
		if ( isset( self::$cart_item_discounts[ $cart_item_key ] ) && self::$cart_item_discounts[ $cart_item_key ] > 0 ) {
			$saved_price = $this->calculate_tax_for_cart_item( $product, self::$cart_item_discounts[ $cart_item_key ], $quantity );
			$message     = str_replace( '{{discount}}', wc_price( $saved_price ), $you_save_text );

			return $item_subtotal . '<p class="dd-you-save-text" style="color: green;">' . $message . '</p>';
		}

		/*
		 * Variable declaration
		 * @var WC_Product $product Product.
		 */

		$sale_price    = $product->get_sale_price();
		$regular_price = $product->get_regular_price();
		if ( is_numeric( $regular_price ) && is_numeric( $sale_price ) ) {
			if ( $sale_price < $regular_price ) {
				$saved_price = $this->calculate_tax_for_cart_item( $product, $regular_price - $sale_price, $quantity );
				$message     = str_replace( '{{discount}}', wc_price( $saved_price ), $you_save_text );

				return $item_subtotal . '<p class="dd-you-save-text" style="color: green;">' . $message . '</p>';
			}
		}

		return $item_subtotal;
	}//end show_you_saved_text()


	/**
	 * Show you saved for each cart item
	 *
	 * @param string $cart_total Price html.
	 *
	 * @return string
	 */
	public function show_you_saved_text_in_cart_total( $cart_total ) {

		$you_save_text = Discount_Deals_Settings::get_settings( 'you_saved_text' );
		$cart_items    = WC()->cart->get_cart();
		if ( empty( $cart_items ) ) {
			return $cart_total;
		}
		$total_discount = 0;
		foreach ( $cart_items as $cart_item_key => $cart_item ) {
			$quantity = intval( $cart_item['quantity'] );
			if ( 0 >= $quantity ) {
				continue;
			}
			if ( $this->is_free_cart_item( $cart_item ) ) {
				continue;
			}
			/**
			 * Filter to modify cart line item object.
			 *
			 * @since 1.0.0
			 */
			$product = apply_filters( 'discount_deals_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			if ( isset( self::$cart_item_discounts[ $cart_item_key ] ) && self::$cart_item_discounts[ $cart_item_key ] > 0 ) {
				$total_discount += $this->calculate_tax_for_cart_item( $product, self::$cart_item_discounts[ $cart_item_key ], $quantity );
			} else {
				/*
				 * @var WC_Product $product product.
				 */

				$sale_price    = $product->get_sale_price();
				$regular_price = $product->get_regular_price();
				if ( is_numeric( $regular_price ) && is_numeric( $sale_price ) ) {
					if ( $sale_price < $regular_price ) {
						self::$cart_item_discounts[ $cart_item_key ] = $regular_price - $sale_price;

						$total_discount += $this->calculate_tax_for_cart_item( $product, self::$cart_item_discounts[ $cart_item_key ], $quantity );
					}
				}
			}
		}
		// Return previously calculated discount.
		if ( $total_discount > 0 ) {
			$message = str_replace( '{{discount}}', wc_price( $total_discount ), $you_save_text );

			return $cart_total . '<p class="dd-you-save-text" style="color: green;">' . $message . '</p>';
		}

		return $cart_total;
	}//end show_you_saved_text_in_cart_total()


	/**
	 * Set woocommerce product price as per simple discount.
	 *
	 * @param float      $price   Product price.
	 * @param WC_Product $product Product object.
	 *
	 * @return float
	 */
	public function get_product_price( $price, $product ) {
		$quantity = $this->get_quantities_in_cart( $product );
		if ( 0 >= $quantity ) {
			$quantity = 1;
		}
		if ( 0 >= $price ) {
			return $price;
		}
		if ( ! $this->calculate_discount ) {
			$this->calculate_discount = true;
			return $price;
		}
		$product_id = $product->get_id();
		$discounted_price = discount_deals_get_product_discount( $price, $product, $quantity );
		if ( ! array_key_exists( $product_id, self::$product_discounts ) || $this->force_fetch_price ) {
			self::$product_discounts[ $product_id ] = array(
				'discounted_price'           => $discounted_price,
				'price_before_discount'      => $price,
				'quantity_while_calculation' => $quantity,
			);
		}
		$this->force_fetch_price = false;

		return $discounted_price;
	}//end get_product_price()

	/**
	 * Set woocommerce product sale price.
	 *
	 * @param float      $price   Product price.
	 * @param WC_Product $product Product object.
	 *
	 * @return float
	 */
	public function get_sale_price( $price, $product ) {
		$sale_price = ( is_a( $product, 'WC_Product' ) ) ? $product->get_price() : '';
		if ( 0 == $sale_price || empty( $sale_price ) ) {
			return $price;
		}

		return $sale_price;
	}//end get_sale_price()


	/**
	 * Get variation prices.
	 *
	 * @param array      $transient_cached_prices_array Cached prices array.
	 * @param WC_Product $product                       Product.
	 * @param boolean    $for_display                   True | false.
	 *
	 * @return array
	 */
	public function get_variation_prices( $transient_cached_prices_array, $product, $for_display ) {
		if ( ! empty( $transient_cached_prices_array['price'] ) && ! empty( $transient_cached_prices_array['regular_price'] ) && ! empty( $transient_cached_prices_array['sale_price'] ) ) {
			foreach ( $transient_cached_prices_array['price'] as $variation_id => $variation_price ) {
				if ( ! empty( $variation_id ) ) {
					$product                                                      = wc_get_product( $variation_id );
					$sale_price                                                   = ( is_a( $product, 'WC_Product_Variation' ) ) ? $product->get_price() : $variation_price;
					$transient_cached_prices_array['price'][ $variation_id ]      = $sale_price;
					$transient_cached_prices_array['sale_price'][ $variation_id ] = $sale_price;
				}
			}
		}

		return $transient_cached_prices_array;
	}//end get_variation_prices()


	/**
	 * Apply coupon discount.
	 *
	 * @return null
	 */
	public function apply_cart_discount_as_coupon() {
		if ( WC()->cart->is_empty() ) {
			return null;
		}
		$coupon_code = Discount_Deals_Settings::get_settings( 'apply_coupon_title', null );
		if ( empty( $coupon_code ) ) {
			return null;
		}
		$discounted_details = discount_deals_get_cart_discount();
		if ( empty( $discounted_details['discounts'] ) && WC()->cart->has_discount( $coupon_code ) ) {
			WC()->cart->remove_coupon( $coupon_code );

			return null;
		} elseif ( empty( $discounted_details['discounts'] ) ) {
			return null;
		}

		$sum_of_discounts = array_sum( $discounted_details['discounts'] );
		if ( $sum_of_discounts > 0 && WC()->cart->has_discount( $coupon_code ) ) {
			return null;
		}
		if ( $sum_of_discounts <= 0 && WC()->cart->has_discount( $coupon_code ) ) {
			WC()->cart->remove_coupon( $coupon_code );
		}

		WC()->cart->apply_coupon( $coupon_code );

		return null;
	}//end apply_cart_discount_as_coupon()


	/**
	 * Apply discount as fee.
	 *
	 * @param WC_Cart $cart Cart object.
	 *
	 * @return null
	 */
	public function apply_cart_discount_as_fee( $cart ) {
		if ( $cart->is_empty() ) {
			return null;
		}
		$fee_title = Discount_Deals_Settings::get_settings( 'apply_fee_title', 'Fee Title' );
		if ( empty( $fee_title ) ) {
			return null;
		}
		$discounted_details = discount_deals_get_cart_discount();
		if ( empty( $discounted_details['discounts'] ) ) {
			return null;
		}
		$sum_of_discounts = array_sum( $discounted_details['discounts'] );
		if ( $sum_of_discounts > 0 ) {
			$cart->add_fee( $fee_title, $sum_of_discounts * - 1 );
		}

		return null;
	}//end apply_cart_discount_as_fee()


	/**
	 * Set coupon amount.
	 *
	 * @param float     $amount Amount.
	 * @param WC_Coupon $coupon Coupon object.
	 *
	 * @return float
	 */
	public function set_coupon_amount( $amount, $coupon ) {
		$applying_coupon_code = $coupon->get_code();
		$coupon_code          = Discount_Deals_Settings::get_settings( 'apply_coupon_title', null );

		if ( strtolower( $coupon_code ) == strtolower( $applying_coupon_code ) ) {
			$discounted_details = discount_deals_get_cart_discount();

			if ( empty( $discounted_details['discounts'] ) ) {
				return 0;
			}

			return array_sum( $discounted_details['discounts'] );
		}

		return $amount;
	}//end set_coupon_amount()


	/**
	 * Set coupon type.
	 *
	 * @param string    $discount_type Type.
	 * @param WC_Coupon $coupon        Coupon object.
	 *
	 * @return string
	 */
	public function set_coupon_discount_type( $discount_type, $coupon ) {
		$applying_coupon_code = $coupon->get_code();
		$coupon_code          = Discount_Deals_Settings::get_settings( 'apply_coupon_title', null );
		if ( strtolower( $coupon_code ) == strtolower( $applying_coupon_code ) ) {
			return 'fixed_cart';
		}

		return $discount_type;
	}//end set_coupon_discount_type()


	/**
	 * Hide remove option in applied coupon.
	 *
	 * @param string    $coupon_html          Coupon html.
	 * @param WC_Coupon $coupon               Coupon object.
	 * @param string    $discount_amount_html Amount html.
	 *
	 * @return string
	 */
	public function hide_remove_coupon( $coupon_html, $coupon, $discount_amount_html ) {
		$applying_coupon_code = $coupon->get_code();
		$coupon_code          = Discount_Deals_Settings::get_settings( 'apply_coupon_title', null );

		if ( strtolower( $coupon_code ) == strtolower( $applying_coupon_code ) ) {
			return $discount_amount_html;
		}

		return $coupon_html;
	}//end hide_remove_coupon()


	/**
	 * Show the free shipping.
	 *
	 * @return void
	 */
	public function mayHaveFreeShipping() {
		new Discount_Deals_Free_Shipping();
	}//end mayHaveFreeShipping()


	/**
	 * Register the discount deals free shipping method.
	 *
	 * @param array $methods Shipping methods.
	 *
	 * @return array
	 */
	public function register_free_shipping_method( $methods ) {
		$methods['discount_deals_free_shipping'] = 'Discount_Deals_Free_Shipping';

		return $methods;
	}//end register_free_shipping_method()


	/**
	 * Is free shipping available.
	 *
	 * @return boolean
	 */
	public function is_free_shipping_available() {
		$discounted_details = discount_deals_get_cart_discount();

		return ! empty( $discounted_details['free_shipping'] );
	}//end is_free_shipping_available()


	/**
	 * Calculate tax for products
	 *
	 * @param WC_Product $product  Product object.
	 * @param float      $price    Product price.
	 * @param integer    $quantity Product quantity.
	 *
	 * @return float
	 */
	public function calculate_tax_for_cart_item( $product, $price, $quantity = 1 ) {
		if ( ! is_a( $product, 'WC_Product' ) ) {
			return $price;
		}
		if ( empty( $product ) || empty( $price ) || empty( $quantity ) ) {
			return $price;
		}
		if ( get_option( 'woocommerce_tax_display_cart' ) == 'excl' ) {
			$price_with_price = wc_get_price_excluding_tax(
				$product,
				array(
					'qty' => $quantity,
					'price' => $price,
				)
			);
		} else {
			$price_with_price = wc_get_price_including_tax(
				$product,
				array(
					'qty' => $quantity,
					'price' => $price,
				)
			);
		}
		/**
		 * Calculate tax for given product and quantity.
		 *
		 * @since 1.0.0
		 */
		return apply_filters( 'discount_deals_calculate_tax_for_cart_item', $price_with_price, $product, $price, $quantity );
	}//end calculate_tax_for_cart_item()


}//end class

