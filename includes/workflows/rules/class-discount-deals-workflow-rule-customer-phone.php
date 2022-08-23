<?php
/**
 * Customer phone rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * @class Customer_Phone
 */
class Discount_Deals_Workflow_Rule_Customer_Phone extends Discount_Deals_Workflow_Rule_String_Abstract {

	public $data_item = "customer";


	function init() {
		$this->title = __( 'Customer - Phone', 'discount-deals' );
	}


	/**
	 * @param $customer \AutomateWoo\Customer
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $customer, $compare, $value ) {
		return $this->validate_string( $this->data_layer()->get_customer_phone(), $compare, $value );
	}

}
