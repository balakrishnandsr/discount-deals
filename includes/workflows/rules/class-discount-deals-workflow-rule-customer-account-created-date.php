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
	}//end __construct()


	/**
	 * Init.
	 *
	 * @return void
	 */
	public function init() {
		$this->title = __( 'Customer - Account Created Date', 'discount-deals' );
	}//end init()


	/**
	 * Validates rule.
	 *
	 * @param WC_Customer $data_item    The customer.
	 * @param string      $compare_type What variables we're using to compare.
	 * @param array|null  $value        The values we have to compare. Null is only allowed when $compare is is_not_set.
	 *
	 * @return boolean
	 * @throws Exception Throws Exception.
	 */
	public function validate( $data_item, $compare_type, $value = null ) {
		return $this->validate_date( $compare_type, $value, discount_deals_normalize_date( $data_item->get_date_created() ) );
	}//end validate()

}//end class

