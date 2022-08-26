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
	}

	/**
	 * Init.
	 */
	public function init() {
		$this->title = __( 'Customer - Last Paid Order Date', 'discount-deals' );
	}

	/**
	 * Validates rule.
	 *
	 * @param \AutomateWoo\Customer $customer The customer.
	 * @param string                $compare  What variables we're using to compare.
	 * @param array|null            $value    The values we have to compare. Null is only allowed when $compare is is_not_set.
	 *
	 * @return bool
	 */
	public function validate( $customer, $compare, $value = null ) {
		return $this->validate_date( $compare, $value, $customer->get_date_last_purchased() );
	}
}
