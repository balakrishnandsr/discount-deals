<?php
/**
 * Customer company rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Discount_Deals_Workflow_Rule_Customer_Company
 */
class Discount_Deals_Workflow_Rule_Customer_Company extends Discount_Deals_Workflow_Rule_String_Abstract {

	/**
	 * What data item should pass in to validate the rule?
	 *
	 * @var string
	 */
	public $data_item = 'customer';

	/**
	 * Init the rule.
	 */
	public function init() {
		$this->title = __( 'Customer - Company', 'discount-deals' );
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
		return $this->validate_string( $data_item->get_billing_company(), $compare_type, $value );
	}//end validate()


}//end class

