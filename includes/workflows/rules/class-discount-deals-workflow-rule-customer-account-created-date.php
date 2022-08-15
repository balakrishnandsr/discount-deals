<?php
/**
 * Customer account created rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Discount_Deals_Workflow_Rule_Customer_Account_Created_Date
 */
class Discount_Deals_Workflow_Rule_Customer_Account_Created_Date extends Discount_Deals_Workflow_Rule_Date_Abstract {

	/**
	 * What data we're using to validate.
	 *
	 * @var string
	 */
	public $data_item = 'customer';

	/**
	 * Discount_Deals_Workflow_Rule_Customer_Account_Created_Date constructor.
	 */
	public function __construct() {
		$this->has_is_past_comparison = true;

		parent::__construct();
	}

	/**
	 * Init.
	 */
	public function init() {
		$this->title = __( 'Customer - Account Created Date', 'discount-deals' );
	}

	/**
	 * Validates rule.
	 *
	 * @param WC_Customer $customer The customer.
	 * @param string      $compare What variables we're using to compare.
	 * @param array|null  $value The values we have to compare. Null is only allowed when $compare is is_not_set.
	 *
	 * @return bool
	 * @throws Exception Throws Exception.
	 */
	public function validate( $customer, $compare, $value = null ) {
		return $this->validate_date( $compare, $value, discount_deals_normalize_date( $customer->get_date_created() ) );
	}
}
