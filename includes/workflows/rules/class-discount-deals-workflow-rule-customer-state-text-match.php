<?php
/**
 * Customer state text rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Customer state name check
 *
 * @credit Inspired by AutomateWoo
 */
class Discount_Deals_Workflow_Rule_Customer_State_Text_Match extends Discount_Deals_Workflow_Rule_String_Abstract {
	/**
	 * What data item should pass in to validate the rule?
	 *
	 * @var string
	 */
	public $data_item = 'customer';

	/**
	 * Init the rule.
	 *
	 * @return void
	 */
	public function init() {
		$this->title = __( 'Customer - State - Text Match', 'discount-deals' );
	}//end init()

	/**
	 * Validates rule.
	 *
	 * @param WC_Customer $data_item    The customer.
	 * @param string      $compare_type What variables we're using to compare.
	 * @param string      $value        The values we have to compare. 
	 *
	 * @return boolean
	 */
	public function validate( $data_item, $compare_type, $value ) {
		$state   = $data_item->get_billing_state();
		$country = $data_item->get_billing_country();
		if ( empty( $state ) || empty( $country ) ) {
			return false;
		}

		return $this->validate_string( discount_deals_get_state_name( $country, $state ), $compare_type, $value );
	}//end validate()


}//end class

