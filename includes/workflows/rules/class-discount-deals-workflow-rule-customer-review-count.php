<?php
/**
 * Reviews count rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * @class Customer_Review_Count
 */
class Discount_Deals_Workflow_Rule_Customer_Review_Count extends Discount_Deals_Workflow_Rule_Number_Abstract {

	public $data_item = "customer";

	public $support_floats = false;


	function init() {
		$this->title = __( 'Customer - Review Count', 'discount-deals' );
	}//end init()



	/**
	 * @param $data_item \AutomateWoo\Customer
	 * @param $compare_type
	 * @param $value
	 *
	 * @return boolean
	 */
	function validate( $data_item, $compare_type, $value ) {
		return $this->validate_number( $data_item->get_review_count(), $compare_type, $value );
	}//end validate()


}//end class

