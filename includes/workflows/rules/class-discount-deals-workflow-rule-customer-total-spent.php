<?php
/**
 * Customer total spent rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * @class Customer_Total_Spent
 */
class Discount_Deals_Workflow_Rule_Customer_Total_Spent extends Discount_Deals_Workflow_Rule_Number_Abstract {

	public $data_item = 'customer';

	public $support_floats = true;


	function init() {
		$this->title = __( 'Customer - Total Spent', 'discount-deals' );
	}


	/**
	 * @param $customer \AutomateWoo\Customer
	 * @param $compare
	 * @param $value
	 *
	 * @return bool
	 */
	function validate( $customer, $compare, $value ) {
		return $this->validate_number( $customer->get_total_spent(), $compare, $value );
	}

}
