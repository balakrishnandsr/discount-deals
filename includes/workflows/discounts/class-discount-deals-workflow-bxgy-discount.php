<?php
/**
 * This class defines all code necessary to workflow discount
 *
 * @package    Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class to handle buy x get y discount
 */
class Discount_Deals_Workflow_Bxgy_Discount extends Discount_Deals_Workflow_Discount {

	/**
	 * Class constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->set_supplied_data_items();
		$this->set_title( __( 'Buy X and Get Y', 'discount-deals' ) );
		$this->set_description( __( 'If the customer buys product X, then give some quantities as discounts of product Y.', 'discount-deals' ) );
	}//end __construct()

	/**
	 * Set valid data items type of the discount
	 *
	 * @return void
	 */
	public function set_supplied_data_items() {
		$this->supplied_data_items = array( 'customer', 'cart', 'shop', 'product' );
	}//end set_supplied_data_items()


	/**
	 * Load fields to get discount details.
	 *
	 * @return string
	 */
	public function load_fields() {
		$discount_details = $this->get_discount_details();
		ob_start();
		require_once DISCOUNT_DEALS_ABSPATH . '/admin/partials/discounts/discount-deals-bxgy-discount.php';
		$discount_details_html = ob_get_clean();

		ob_start();
		discount_deals_html(
			array(
				'wrapper_class' => 'discount-options-field-container',
				'id'            => 'discount_deals_workflow_discount_type',
				'label'         => __( 'Configure the discount you want to give to your customers', 'discount-deals' ),
				'html'          => $discount_details_html,
				'required'      => true,
			)
		);

		return ob_get_clean();
	}//end load_fields()

	/**
	 * Pick the free item frm cart
	 *
	 * @param WC_Cart $cart  cart object
	 * @param string  $which Which discount should apply to the cart?
	 *
	 * @return array|mixed
	 */
	public function pick_item_from_cart( $cart, $which = "lowest" ) {
		$cart_items = $cart->get_cart();
		if ( empty( $cart_items ) ) {
			return array();
		}
		$all_products = array();
		foreach ( $cart_items as $cart_item_key => $cart_item ) {
			if ( ! empty( $cart_item['discount_deals_free_gift'] ) ) {
				continue;
			}
			$all_products[] = array(
				'product_id'    => $cart_item['product_id'],
				'variation_id'  => $cart_item['variation_id'],
				'variation'     => $cart_item['variation'],
				'price'         => $cart_item['data']->get_price(),
				'cart_item_key' => $cart_item_key,
			);
		}
		if ( 'lowest' == $which ) {
			return $all_products[ array_search( min( $totals = array_column( $all_products, 'price' ) ), $totals ) ];
		} else {
			return $all_products[ array_search( max( $totals = array_column( $all_products, 'price' ) ), $totals ) ];
		}
	}//end pick_item_from_cart()


	/**
	 * Pick the free item frm cart
	 *
	 * @param string $which Which discount should apply to the cart?
	 *
	 * @return array
	 */
	public function pick_item_from_store( $which = "lowest" ) {
		$args          = array(
			'posts_per_page' => 1,
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'orderby'        => 'meta_value_num',
			'meta_key'       => '_price',
			'order'          => 'lowest' == $which ? 'asc' : 'desc'
		);
		$the_query     = new WP_Query( $args );
		$product_posts = $the_query->get_posts();
		if ( empty( $product_posts ) ) {
			return array();
		}
		foreach ( $product_posts as $product_post ) {
			$product = wc_get_product( $product_post->ID );
			if ( ! $product ) {
				continue;
			}

			return array(
				'product_id'    => $product->get_id(),
				'variation_id'  => 0,
				'variation'     => array(),
				'price'         => $product->get_sale_price(),
				'cart_item_key' => false,
			);
		}

		return array();
	}//end pick_item_from_store()


	/**
	 * Pick the free item frm cart
	 *
	 * @param integer $product_id product that was given to customers.
	 *
	 * @return array
	 */
	public function format_picked_items( $product_id = 0 ) {
		if ( empty( $product_id ) ) {
			return array();
		}
		$product = wc_get_product( $product_id );
		if ( ! $product ) {
			return array();
		}

		$variation_id = 0;
		$product_id   = $product->get_id();
		if ( is_a( $product, 'WC_Product_Variation' ) ) {
			$product_id   = $product->get_parent_id();
			$variation_id = $product->get_id();
		}

		return array(
			'product_id'    => $product_id,
			'variation_id'  => $variation_id,
			'variation'     => array(),
			'price'         => $product->get_sale_price(),
			'cart_item_key' => false,
		);
	}//end format_picked_items()


	/**
	 * Calculate discount for the product
	 *
	 * @param WC_Product $data_item Calculate discount for which data item.
	 * @param float      $price     Calculate discount subsequently.
	 * @param array      $extra     Extra details for calculate discount.
	 *
	 * @return array
	 */
	public function calculate_discount( $data_item, $price, $extra = array() ) {
		$discount_details = $this->get_discount_details();
		if ( empty( $discount_details ) ) {
			return array();
		}
		$product_quantity = ! empty( $extra['quantity'] ) ? intval( $extra['quantity'] ) : 1;
		if ( $product_quantity <= 0 ) {
			$product_quantity = 1;
		}
		$cart = WC()->cart;
		foreach ( $discount_details as $discount_detail ) {
			$free_quantity = discount_deals_get_value_from_array( $discount_detail, 'free_quantity', 1 );
			if ( 0 >= $free_quantity ) {
				continue;
			}
			$type         = discount_deals_get_value_from_array( $discount_detail, 'type', 'free' );
			$min_quantity = discount_deals_get_value_from_array( $discount_detail, 'min_quantity', 0 );
			$max_quantity = discount_deals_get_value_from_array( $discount_detail, 'max_quantity', 999999999 );
			$max_discount = discount_deals_get_value_from_array( $discount_detail, 'max_discount', 0 );
			if ( ! empty( $type ) && $product_quantity >= $min_quantity && $product_quantity <= $max_quantity ) {
				$value                 = discount_deals_get_value_from_array( $discount_detail, 'value', 0 );
				$free_product          = discount_deals_get_value_from_array( $discount_detail, 'free_product', 0 );
				$free_product_type     = discount_deals_get_value_from_array( $discount_detail, 'free_product_type', 'cheapest_in_cart' );
				$show_eligible_message = discount_deals_get_value_from_array( $discount_detail, 'show_eligible_message', '' );
				switch ( $free_product_type ) {
					default:
					case "cheapest_in_cart":
						$discount_products = $this->pick_item_from_cart( $cart );
						break;
					case "biggest_in_cart":
						$discount_products = $this->pick_item_from_cart( $cart, 'biggest' );
						break;
					case "cheapest_in_store":
						$discount_products = $this->pick_item_from_store( $cart );
						break;
					case "biggest_in_store":
						$discount_products = $this->pick_item_from_store( $cart, 'biggest' );
						break;
					case "products":
						$discount_products = $this->format_picked_items( $free_product );
						break;
				}
				if ( empty( $discount_products ) ) {
					return array();
				}
				$price          = $discount_products['price'];
				$discount       = $this->calculate_discount_amount( $type, $price, $value );
				$total_discount = $free_quantity * $discount;
				if ( 0 < floatval( $max_discount ) && 'percent' == $type ) {
					$total_discount = min( $max_discount, $total_discount );
					$discount       = $total_discount / $free_quantity;
				}
				if ( 0 >= $discount ) {
					return array();
				}

				return array(
					'discount_quantity'     => $free_quantity,
					'discount'              => $discount,
					'total'                 => $total_discount,
					'is_free'               => 'free' == $type,
					'discount_on_same'      => false,
					'show_eligible_message' => ! empty( $show_eligible_message ),
					'discount_product'      => $discount_products
				);
			}
		}

		return array();
	}//end calculate_discount()

}//end class
