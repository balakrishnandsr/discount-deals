<?php
/**
 * Cart item count rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Discount_Deals_Workflow_Rule_Cart_Item_count.
 */
class Discount_Deals_Workflow_Rule_Cart_Item_Count extends Discount_Deals_Workflow_Rule_Number_Abstract {

	public $data_item = "cart";

	public $support_floats = false;


	function init() {
		$this->title = __( 'Cart - Item Count', 'discount-deals' );
	}


	/**
	 * @param Cart $cart
	 * @param $compare
	 * @param $value
	 *
	 * @return bool
	 */
	function validate( $cart, $compare, $value ) {
		return $this->validate_number( count( $cart->get_items() ), $compare, $value );
	}

}
