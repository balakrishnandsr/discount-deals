<?php
/**
 * Customer order count rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * @class Customer_Order_Count
 */
class Discount_Deals_Workflow_Rule_Customer_Order_Count extends Discount_Deals_Workflow_Rule_Number_Abstract {

	public $data_item = 'customer';

	public $support_floats = false;


	function init() {
		$this->title = __( 'Customer - Order Count', 'discount-deals' );
	}


	/**
	 * @param $customer \AutomateWoo\Customer
	 * @param $compare
	 * @param $value
	 *
	 * @return bool
	 */
	function validate( $customer, $compare, $value ) {
		return $this->validate_number( $customer->get_order_count(), $compare, $value );
	}

}
