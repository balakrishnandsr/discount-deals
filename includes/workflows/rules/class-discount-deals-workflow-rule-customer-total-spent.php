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
	}//end init()



	/**
	 * @param $data_item \AutomateWoo\Customer
	 * @param $compare_type
	 * @param $value
	 *
	 * @return boolean
	 */
	function validate( $data_item, $compare_type, $value ) {
		return $this->validate_number( $data_item->get_total_spent(), $compare_type, $value );
	}//end validate()


}//end class

