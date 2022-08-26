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
 * Customer last review date rule.
 *
 * @Class Customer_Last_Review_Date
 */
class Discount_Deals_Workflow_Rule_Customer_Last_Review_Date extends Discount_Deals_Workflow_Rule_Date_Abstract {

	/**
	 * What data we're using to validate.
	 *
	 * @var string
	 */
	public $data_item = 'customer';

	/**
	 * Customer_Last_Review_Date constructor.
	 */
	public function __construct() {
		$this->has_is_past_comparison = true;

		parent::__construct();
	}

	/**
	 * Init.
	 */
	public function init() {
		$this->title = __( 'Customer - Last Review Date', 'discount-deals' );
	}

	/**
	 * Validates rule.
	 *
	 * @param \AutomateWoo\Customer $customer The customer.
	 * @param string $compare What variables we're using to compare.
	 * @param array|null $value The values we have to compare. Null is only allowed when $compare is is_not_set.
	 *
	 * @return bool
	 */
	public function validate( $customer, $compare, $value = null ) {
		return $this->validate_date( $compare, $value, $customer->get_last_review_date() );
	}
}
