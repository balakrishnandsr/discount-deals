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
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_slug = $plugin_name;
		$this->version     = $version;
		if ( ! is_admin() ) {
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
		add_filter( 'woocommerce_variation_prices', array( $this, 'get_variation_prices' ), 99, 3 );
		add_filter( 'woocommerce_add_to_cart', array( $this, 'item_added_to_cart' ), 99, 6 );
		add_filter( 'woocommerce_variation_prices', array( $this, 'get_variation_prices' ), 99, 3 );
		//Cart Discount.
		$show_strikeout_for_cart_item = Discount_Deals_Settings::get_settings( 'show_strikeout_price_in_cart', 'yes' );
		if ( 'yes' == $show_strikeout_for_cart_item ) {
			add_filter( 'woocommerce_cart_item_price', array( $this, 'show_price_strikeout' ), 98, 3 );
		}
		$save_text_position = Discount_Deals_Settings::get_settings( 'where_display_saving_text', 'disabled' );
		if ( 'disabled' != $save_text_position ) {
			if ( 'both_line_item_and_after_total' == $save_text_position || 'after_total' == $save_text_position ) {
				add_filter( 'woocommerce_cart_totals_order_total_html', array(
					$this,
					'show_you_saved_text_in_cart_total'
				), 99, 3 );
			}
			if ( 'both_line_item_and_after_total' == $save_text_position || 'on_each_line_item' == $save_text_position ) {
				add_filter( 'woocommerce_cart_item_subtotal', array( $this, 'show_you_saved_text' ), 99, 3 );
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
		//Free shipping
		add_filter( 'woocommerce_shipping_methods', array( $this, 'register_free_shipping_method' ) );
		add_filter( 'woocommerce_shipping_discount_deals_free_shipping_is_available', array(
			$this,
			'is_free_shipping_available'
		) );
		//BOGO
		add_action( 'woocommerce_before_calculate_totals', array( $this, 'calculate_and_add_bogo_discount' ), 97 );
		add_action( 'woocommerce_before_calculate_totals', array( __CLASS__, 'remove_free_products_from_cart' ), 98 );
		add_action( 'woocommerce_before_calculate_totals', array( __CLASS__, 'add_free_products_to_cart' ), 99 );
		add_filter( 'woocommerce_cart_item_remove_link', array( $this, 'hide_cart_item_remove_link' ), 99, 2 );
		add_filter( 'woocommerce_cart_item_quantity', array( $this, 'hide_cart_item_quantity_update' ), 99, 3 );
		add_action( 'woocommerce_after_cart_item_name', array( $this, 'highlight_free_gifts' ), 99, 2 );
		add_filter( 'woocommerce_cart_item_price', array( $this, 'show_price_strikeout_for_bogo' ), 99, 3 );
		add_filter( 'woocommerce_before_cart', array( $this, 'show_bxgy_eligible_notices' ), 9 );

        //Promotion Messages
        add_action('woocommerce_before_cart', array($this, 'show_applied_rules_messages'), 1000);
        add_action('woocommerce_before_checkout_form', array($this, 'display_promotion_messages_checkout_container'), 1000);
	}

	/**
	 * Load files required for frontend
	 *
	 * @return void
	 */
	public function load_files() {
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/class-discount-deals-free-shipping.php';
	}

	/**
	 * Check the given cart s free or not.
	 *
	 * @param array $cart_item cart item details.
	 *
	 * @return bool
	 */
	public function is_free_cart_item( $cart_item ) {
		return ! empty( $cart_item[ $this->_free_cart_item_key ] );
	}

	/**
	 * Remove free product from the cart
	 */
	public static function remove_free_products_from_cart() {
		remove_action( 'woocommerce_before_calculate_totals', array(
			__CLASS__,
			'remove_free_products_from_cart'
		), 98 );
		if ( ! empty( self::$remove_products ) && is_array( self::$remove_products ) ) {
			foreach ( self::$remove_products as $cart_item_key ) {
				WC()->cart->remove_cart_item( $cart_item_key );
			}
			self::$remove_products = array();
		}
		add_action( 'woocommerce_before_calculate_totals', array( __CLASS__, 'remove_free_products_from_cart' ), 98 );
	}

	/**
	 * Add free product to the cart
	 */
	public static function add_free_products_to_cart() {
		remove_action( 'woocommerce_before_calculate_totals', array( __CLASS__, 'add_free_products_to_cart' ), 99 );
		if ( ! empty( self::$free_products ) && is_array( self::$free_products ) ) {
			foreach ( self::$free_products as $gift ) {
				try {
					if ( is_array( $gift ) && array_key_exists( 'product_id', $gift ) && array_key_exists( 'variation_id', $gift ) && array_key_exists( 'quantity', $gift ) && array_key_exists( 'variation', $gift ) && array_key_exists( 'meta', $gift ) ) {
						$cart_item_key = WC()->cart->add_to_cart( $gift['product_id'], $gift['quantity'], $gift['variation_id'], $gift['variation'], $gift['meta'] );
						if ( $cart_item_key ) {
							$cart_details = WC()->cart->get_cart();
							if ( array_key_exists( $cart_item_key, $cart_details ) ) {
								$cart_details[ $cart_item_key ]['data']->set_price( 0 );
							}
						}
					}
				} catch ( Exception $exception ) {

				}
			}
			self::$free_products = array();
		}
		add_action( 'woocommerce_before_calculate_totals', array( __CLASS__, 'add_free_products_to_cart' ), 99 );
	}

	/**
	 * Stop showing remove link for gifts
	 *
	 * @param string $remove_link link to remove cart item.
	 * @param string $cart_item_key cart item key.
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
	}

	/**
	 * Stop showing remove link for gifts
	 *
	 * @param array $cart_item cart item.
	 * @param string $cart_item_key cart item key.
	 *
	 * @return void
	 */
	public function highlight_free_gifts( $cart_item, $cart_item_key ) {
		if ( $this->is_free_cart_item( $cart_item ) ) {
			$message = Discount_Deals_Settings::get_settings( 'bogo_discount_highlight_message' );
			echo '<p class="dd-bogo-text" style="color: green;">' . $message . '</p>';
		}
	}

	/**
	 * Stop showing quantity update for gifts
	 *
	 * @param string $update_field link to remove cart item.
	 * @param string $cart_item_key cart item key.
	 * @param array $cart_item cart item.
	 *
	 * @return string
	 */
	public function hide_cart_item_quantity_update( $update_field, $cart_item_key, $cart_item ) {
		if ( $this->is_free_cart_item( $cart_item ) ) {
			return $cart_item['quantity'];
		}

		return $update_field;
	}

	/**
	 * What is the free product for particular cart item
	 *
	 * @param string $actual_cart_item_key Check that the cart item has free product?
	 * @param array $cart_items all cart items.
	 *
	 * @return false|int|string
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
	}

	/**
	 * Check that cart item has wrong free item.
	 *
	 * @param array $free_cart_item free cart item.
	 * @param array $actual_discount actual discount for the product.
	 *
	 * @return bool
	 */
	public function is_wrong_item_in_cart( $free_cart_item, $actual_discount ) {
		if ( $actual_discount['discount_on_same'] ) {
			return $free_cart_item['quantity'] != $actual_discount['discount_quantity'];
		} else {
			$discount_item = $actual_discount['discount_product'];

			return ( $free_cart_item['product_id'] != $discount_item['product_id'] || $free_cart_item['variation_id'] != $discount_item['variation_id'] || $free_cart_item['quantity'] != $actual_discount['discount_quantity'] );
		}
	}

	/**
	 * Add bogo discount to the cart
	 *
	 * @return void
	 */
	public function calculate_and_add_bogo_discount() {
		$cart_items = WC()->cart->get_cart();
		if ( ! empty( $cart_items ) ) {
			foreach ( $cart_items as $cart_item_key => $cart_item ) {
				$cart_item_object = $cart_item['data'];
				if ( is_a( $cart_item_object, 'WC_Product' ) ) {
					if ( $this->is_free_cart_item( $cart_item ) ) {
						$cart_item['data']->set_price( 0 );
						$actual_product_key = $cart_item[ $this->_free_cart_item_key ];
						//In some cases, customer will remove the original product. then we didn't need to give discount to the customer.
						if ( ! array_key_exists( $actual_product_key, $cart_items ) ) {
							// Remove it. We don't need anymore.
							array_push( self::$remove_products, $cart_item_key );
						}
						continue;
					}
					$free_product_cart_key = $this->get_free_product_of_cart_item( $cart_item_key, $cart_items );
					$discount_details      = discount_deals_get_bogo_discount( $cart_item_object, $cart_item['quantity'] );
					// has no discount but the free product for this item is found in cart. maybe it was previously added one.
					if ( empty( $discount_details ) && $free_product_cart_key ) {
						// Remove it. We don't need anymore.
						array_push( self::$remove_products, $free_product_cart_key );
						continue;
					}
					//If there is no more discount, to continue to check for net cart item.
					if ( empty( $discount_details ) ) {
						continue;
					}
					$actual_discount = current( $discount_details );
					if ( $actual_discount['is_free'] ) {
						if ( $free_product_cart_key ) {
							$free_cart_item = $cart_items[ $free_product_cart_key ];
							if ( $this->is_wrong_item_in_cart( $free_cart_item, $actual_discount ) ) {
								//If there is any change in quantity, then we need to update the quantity.
								array_push( self::$remove_products, $free_product_cart_key );
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
									$this->_free_cart_item_workflow_key => array_keys( $discount_details )[0]
								)
							);
						} else {
							$free_product_detail = array(
								'product_id'   => $actual_discount['discount_product']['product_id'],
								'quantity'     => $actual_discount['discount_quantity'],
								'variation_id' => $actual_discount['discount_product']['variation_id'],
								'variation'    => $actual_discount['discount_product']['variation'],
								'meta'         => array(
									$this->_free_cart_item_key          => $cart_item_key,
									$this->_free_cart_item_workflow_key => array_keys( $discount_details )[0]
								)
							);
						}
						array_push( self::$free_products, $free_product_detail );
					} else {
						// No free product in discount abut previous free product traces found.
						if ( $free_product_cart_key ) {
							// Remove it. We don't need anymore.
							array_push( self::$remove_products, $free_product_cart_key );
						}
						if ( $actual_discount['discount_on_same'] ) {
							$discounted_cart_item     = $cart_item;
							$discounted_cart_item_key = $cart_item_key;
						} else {
							//If the item is already found in the cart then give discount.
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
						$discounted_cart_item_object = $discounted_cart_item['data'];
						//IF the discount is flat or percentage, then do calculations accordingly.
						if ( $actual_discount['discount_quantity'] > $quantity_in_cart ) {
							//If free quantity is greater than cart item quantity, set discount as discount for individual product
							$discount_per_item = $actual_discount['discount'];
						} else {
							//else, take total quantity and calculate accordingly.
							$discount_per_item = $actual_discount['total'] / $quantity_in_cart;
						}
						$price_per_product = $discounted_cart_item_object->get_sale_price() - $discount_per_item;

						$actual_discount_quantity = $actual_discount['discount_quantity'];
						if ( $quantity_in_cart < $actual_discount['discount_quantity'] ) {
							$actual_discount_quantity = $quantity_in_cart;
						}

						$actual_discounted_price = $discounted_cart_item_object->get_sale_price() - $actual_discount['discount'];
						if ( 0 >= $price_per_product ) {
							$actual_discounted_price = 0;
						}

						self::$priced_bogo_products[ $discounted_cart_item_key ] = array(
							'original_price'          => $discounted_cart_item_object->get_sale_price(),
							'original_price_quantity' => $discounted_cart_item['quantity'] - $actual_discount['discount_quantity'],
							'discount_price'          => $actual_discounted_price,
							'discount_quantity'       => $actual_discount_quantity,
							'meta'                    => $actual_discount,
						);
						if ( 0 >= $price_per_product ) {
							$price_per_product = 0;
						}
						$discounted_cart_item_object->set_price( $price_per_product );
					}
				}
			}
		}
	}//end init_public_hooks()

	/**
	 * Show eligible message to the customer
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
					$discount_message = apply_filters( 'discount_deals_bxgy_eligible_notice', $discount_message, $product, $eligible_bxgy_product );
					if ( ! empty( $discount_message ) ) {
						wc_print_notice( $discount_message, 'notice' );
					}
				}
			}
		}
	}

	/**
	 * Check the cart has eligible BXGY item
	 *
	 * @param array $cart_items all cart items.
	 * @param array $discount_item discount item.
	 *
	 * @return false|int|string
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
	}

	/**
	 * Add timestamp to session when 1st product is added to cart.
	 *
	 * @param string $cart_item_key cart item key.
	 * @param int $product_id product id.
	 * @param int $quantity item quantity.
	 * @param int $variation_id variation id.
	 * @param array $variation variation details.
	 * @param array $cart_item_data cart item details.
	 */
	public function item_added_to_cart( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
		// TODO: remove session value after order placement
		$created_time = WC()->session->get( 'discount_deals_cart_created_time', false );
		if ( ! $created_time ) {
			WC()->session->set( 'discount_deals_cart_created_time', current_time( 'U', true ) );
		}
		WC()->session->set( 'discount_deals_cart_updated_time', current_time( 'U', true ) );
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

		wp_enqueue_script( $this->plugin_slug, plugin_dir_url( __FILE__ ) . 'js/discount-deals-public.js', array( 'jquery' ), $this->version, false );

	}//end enqueue_scripts()

	/**
	 * Show strikeout for each product
	 *
	 * @param string $item_price price html.
	 * @param array $cart_item cart item details.
	 * @param string $cart_item_key cart item hash.
	 */
	public function show_price_strikeout( $item_price, $cart_item, $cart_item_key ) {
		if ( $this->is_free_cart_item( $cart_item ) ) {
			return $item_price;
		}
		$product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
		/**
		 * @var WC_Product $product product.
		 */
		$sale_price    = $product->get_sale_price();
		$regular_price = $product->get_regular_price();
		if ( is_numeric( $regular_price ) && is_numeric( $sale_price ) ) {
			if ( $sale_price < $regular_price ) {
				if ( ! isset( self::$cart_item_discounts[ $cart_item_key ] ) ) {
					self::$cart_item_discounts[ $cart_item_key ] = $regular_price - $sale_price;
				}
				$item_price = wc_format_sale_price( $regular_price, $sale_price );
			}
		}

		return $item_price;
	}

	/**
	 * Show strikeout for each product which has bogo
	 *
	 * @param string $item_price price html.
	 * @param array $cart_item cart item details.
	 * @param string $cart_item_key cart item hash.
	 */
	public function show_price_strikeout_for_bogo( $item_price, $cart_item, $cart_item_key ) {
		if ( empty( self::$priced_bogo_products ) ) {
			return $item_price;
		}
		if ( array_key_exists( $cart_item_key, self::$priced_bogo_products ) ) {
			$discount_details = self::$priced_bogo_products[ $cart_item_key ];
			$item_price       = '<div>' . wc_format_sale_price( $discount_details['original_price'], $discount_details['discount_price'] ) . ' &times; ' . $discount_details['discount_quantity'] . '</div>';
			if ( 0 < $discount_details['original_price_quantity'] ) {
				$item_price .= '<div>' . wc_price( $discount_details['original_price'] ) . ' &times; ' . $discount_details['original_price_quantity'] . '</div>';
			}

			return $item_price;
		}

		return $item_price;
	}

	/**
	 * Show you saved for each cart item
	 *
	 * @param string $item_subtotal price html.
	 * @param array $cart_item cart item details.
	 * @param string $cart_item_key cart item hash.
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
		// Return previously calculated discount
		if ( isset( self::$cart_item_discounts[ $cart_item_key ] ) && self::$cart_item_discounts[ $cart_item_key ] > 0 ) {
			$message = str_replace( '{{discount}}', wc_price( $quantity * self::$cart_item_discounts[ $cart_item_key ] ), $you_save_text );

			return $item_subtotal . '<p class="dd-you-save-text" style="color: green;">' . $message . '</p>';
		}
		$product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
		/**
		 * @var WC_Product $product product.
		 */
		$sale_price    = $product->get_sale_price();
		$regular_price = $product->get_regular_price();
		if ( is_numeric( $regular_price ) && is_numeric( $sale_price ) ) {
			if ( $sale_price < $regular_price ) {
				self::$cart_item_discounts[ $cart_item_key ] = $regular_price - $sale_price;

				$message = str_replace( '{{discount}}', wc_price( $quantity * self::$cart_item_discounts[ $cart_item_key ] ), $you_save_text );

				return $item_subtotal . '<p class="dd-you-save-text" style="color: green;">' . $message . '</p>';
			}
		}

		return $item_subtotal;
	}

	/**
	 * Show you saved for each cart item
	 *
	 * @param string $cart_total price html.
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
			if ( isset( self::$cart_item_discounts[ $cart_item_key ] ) && self::$cart_item_discounts[ $cart_item_key ] > 0 ) {
				$total_discount += $quantity * self::$cart_item_discounts[ $cart_item_key ];
			} else {
				$product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				/**
				 * @var WC_Product $product product.
				 */
				$sale_price    = $product->get_sale_price();
				$regular_price = $product->get_regular_price();
				if ( is_numeric( $regular_price ) && is_numeric( $sale_price ) ) {
					if ( $sale_price < $regular_price ) {
						self::$cart_item_discounts[ $cart_item_key ] = $regular_price - $sale_price;

						$total_discount += $quantity * self::$cart_item_discounts[ $cart_item_key ];
					}
				}
			}
		}
		// Return previously calculated discount
		if ( $total_discount > 0 ) {
			$message = str_replace( '{{discount}}', wc_price( $total_discount ), $you_save_text );

			return $cart_total . '<p class="dd-you-save-text" style="color: green;">' . $message . '</p>';
		}

		return $cart_total;
	}

	/**
	 * Set woocommerce product price as per simple discount.
	 *
	 * @param float $price Product price.
	 * @param WC_Product $product Product object.
	 *
	 * @return float
	 */
	public function get_product_price( $price, $product ) {
		$cart_items = WC()->cart->get_cart();
		$quantity   = 1;
		//For bulk discount, check cart item quantity and calculate discount
		if ( ! empty( $cart_items ) ) {
			foreach ( $cart_items as $cart_item ) {
				$cart_item_object = $cart_item['data'];
				if ( is_a( $cart_item_object, 'WC_Product' ) ) {
					if ( $product->get_id() == $cart_item_object->get_id() ) {
						$quantity = ! empty( $cart_item['quantity'] ) ? intval( $cart_item['quantity'] ) : 1;
					}
				}
			}
		}

		return discount_deals_get_product_discount( $price, $product, $quantity );
	}//end get_product_price()

	/**
	 * Set woocommerce product sale price.
	 *
	 * @param $price
	 * @param $product
	 *
	 * @return mixed|string
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
	 * @param array $transient_cached_prices_array Cached prices array
	 * @param WC_Product $product Product.
	 * @param boolean $for_display true | false
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
	}

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
	}

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
	}

	/**
	 * Set coupon amount.
	 *
	 * @param float $amount Amount.
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
	}

	/**
	 * Set coupon type.
	 *
	 * @param string $discount_type Type.
	 * @param WC_Coupon $coupon Coupon object.
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
	}

	/**
	 * Hide remove option in applied coupon.
	 *
	 * @param string $coupon_html Coupon html.
	 * @param WC_Coupon $coupon Coupon object.
	 * @param string $discount_amount_html Amount html.
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
	}

	/**
	 * Show the free shipping
	 */
	public function mayHaveFreeShipping() {
		new Discount_Deals_Free_Shipping();
	}

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
	}

	/**
	 * Is free shipping available.
	 *
	 * @return boolean
	 */
	public function is_free_shipping_available() {
		$discounted_details = discount_deals_get_cart_discount();

		return ! empty( $discounted_details['free_shipping'] );
	}

    /**
     * Displaying promotional message in check out
     * */
    public function display_promotion_messages_checkout_container(){
        echo "<div id='discount_deals_checkout_promotion_messages'>";
        $this->show_applied_rules_messages();
        echo "</div>";
    }

    /**
     * Show the discount promotion message
     */
    function show_applied_rules_messages()
    {
        $message = 'Discount <strong>{{title}}</strong> has been applied to your cart';
        $title = 'default workflow';
        $message_to_display = str_replace('{{title}}', $title, $message);
        wc_print_notice(wp_unslash( $message_to_display ), 'success');
        wc_print_notice(wp_unslash( $message_to_display ), 'error');
        wc_print_notice(wp_unslash( $message_to_display ), 'notice');
    }


}//end class

