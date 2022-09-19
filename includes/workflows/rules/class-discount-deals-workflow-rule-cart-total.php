<?php
/**
 * Cart total rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Discount_Deals_Workflow_Rule_Cart_Total.
 */
class Discount_Deals_Workflow_Rule_Cart_Total extends Discount_Deals_Workflow_Rule_Number_Abstract {

	/**
	 * What data item should pass in to validate the rule?
	 *
	 * @var string
	 */
	public $data_item = "cart";

	/**
	 * Supports float values or not?
	 * @var boolean
	 */
	public $support_floats = true;

	/**
	 * Init the rule
	 */
	function init() {
		$this->title = __( 'Cart - Sub total', 'discount-deals' );
	}//end init()


	/**
	 * Validate the cart subtotal with the given value
	 *
	 * @param WC_Cart $data_item data item.
	 * @param string $compare_type compare operator.
	 * @param integer|float $value list of values.
	 *
	 * @return boolean
	 */
	function validate( $data_item, $compare_type, $value ) {
		$subtotal      = WC()->cart->get_subtotal();
		$subtotal_tax  = WC()->cart->get_subtotal_tax();
		$cart_subtotal = apply_filters( 'discount_deals_cart_subtotal', ( $subtotal + $subtotal_tax ), $subtotal, $subtotal_tax );

		return $this->validate_number( $cart_subtotal, $compare_type, $value );
	}//end validate()


}//end class

