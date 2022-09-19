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
 * Customer total spent rule
 *
 * @class Discount_Deals_Workflow_Rule_Customer_Total_Spent
 */
class Discount_Deals_Workflow_Rule_Customer_Total_Spent extends Discount_Deals_Workflow_Rule_Number_Abstract {
	/**
	 * What data item should pass in to validate the rule?
	 *
	 * @var string
	 */
	public $data_item = 'customer';

	/**
	 * Supports float values or not?
	 *
	 * @var boolean
	 */
	public $support_floats = true;

	/**
	 * Init the rule.
	 * 
	 * @return void
	 */
	public function init() {
		$this->title = __( 'Customer - Total Spent', 'discount-deals' );
	}//end init()
	
	/**
	 * Validates rule.
	 *
	 * @param WC_Customer $data_item    The customer.
	 * @param string      $compare_type What variables we're using to compare.
	 * @param string      $value        The values we have to compare. Null is only allowed when $compare is is_not_set.
	 *
	 * @return boolean
	 */
	public function validate( $data_item, $compare_type, $value ) {
		return $this->validate_number( $data_item->get_total_spent(), $compare_type, $value );
	}//end validate()


}//end class

