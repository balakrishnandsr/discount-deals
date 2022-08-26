<?php

/**
 * Cart coupons rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Customer last order date rule.
 *
 * @Class Customer_Last_Order_Date
 */
class Discount_Deals_Workflow_Rule_Customer_Last_Order_Date extends Discount_Deals_Workflow_Rule_Date_Abstract {
	/**
	 * What date we're using to validate.
	 *
	 * @var string
	 */
	public $data_item = 'customer';

	/**
	 * Discount_Deals_Workflow_Rule_Customer_Last_Order_Date constructor.
	 */
	public function __construct() {
		$this->has_is_past_comparison = true;

		parent::__construct();
	}//end __construct()


	/**
	 * Init.
	 */
	public function init() {
		$this->title = __( 'Customer - Last Paid Order Date', 'discount-deals' );
	}//end init()


	/**
	 * Validates rule.
	 *
	 * @param \AutomateWoo\Customer $data_item    The customer.
	 * @param string                $compare_type What variables we're using to compare.
	 * @param array|null            $value        The values we have to compare. Null is only allowed when $compare is is_not_set.
	 *
	 * @return boolean
	 */
	public function validate( $data_item, $compare_type, $value = null ) {
		return $this->validate_date( $compare_type, $value, $data_item->get_date_last_purchased() );
	}//end validate()

}//end class

