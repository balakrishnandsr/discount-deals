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
 * Class to handle simple product level discount
 */
class Discount_Deals_Workflow_Cart_Discount extends Discount_Deals_Workflow_Discount {

	/**
	 * Class constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->set_supplied_data_items();
		$this->set_title( __( 'Cart Subtotal Based Discount', 'discount-deals' ) );
		$this->set_description( __( 'Give flat or percentage discount for Cart.', 'discount-deals' ) );
	}

	/**
	 * Set valid data items type of the discount
	 */
	public function set_supplied_data_items() {
		$this->supplied_data_items = array( 'customer', 'cart', 'shop' );
	}

	/**
	 * Load fields to get discount details.
	 *
	 * @return string
	 */
	public function load_fields() {
		$discount_details = $this->get_discount_details();
		ob_start();
		require_once DISCOUNT_DEALS_ABSPATH . '/admin/partials/discounts/discount-deals-cart-discount.php';
		$discount_details_html = ob_get_clean();

		ob_start();
		discount_deals_html(
			array(
				'wrapper_class' => 'discount-options-field-container',
				'id'            => 'discount_deals_workflow_discount_type',
				'value'         => discount_deals_get_value_from_array( $discount_details, 'type', 'flat' ),
				'label'         => __( 'How much discount do you want to give?', 'discount-deals' ),
				'html'          => $discount_details_html,
				'required'      => true,
			)
		);

		return ob_get_clean();
	}

	/**
	 * Calculate discount for the product
	 *
	 * @param WC_Cart $data_item Cart object.
	 * @param float $price Cart subtotal.
	 *
	 * @return array|string
	 */
	public function calculate_discount( $data_item, $price ) {
		$discount_details = $this->get_discount_details();
		if ( empty( $discount_details ) ) {
			return 0;
		}
		$cart_subtotal = $data_item->get_subtotal();
		foreach ( $discount_details as $discount_detail ) {
			$type         = discount_deals_get_value_from_array( $discount_detail, 'type', 'free_shipping' );
			$min_subtotal = discount_deals_get_value_from_array( $discount_detail, 'min_subtotal', 0 );
			$max_subtotal = discount_deals_get_value_from_array( $discount_detail, 'max_subtotal', 999999999 );
			$value        = discount_deals_get_value_from_array( $discount_detail, 'value', 0 );
			$max_discount = discount_deals_get_value_from_array( $discount_detail, 'max_discount', 0 );
			if ( ! empty( $type ) && ! empty( $value ) && $cart_subtotal >= $min_subtotal && $cart_subtotal <= $max_subtotal ) {
				if ( 'free_shipping' == $type ) {
					return 'discount_deals_free_shipping';
				} else {
					$discount = $this->calculate_discount_amount( $type, $price, $value );
					if ( ! empty( $max_discount ) ) {
						$discount = min( $max_discount, $discount );
					}

					return $discount;
				}
			}
		}

		return 0;
	}//end calculate_discount()


}//end class
