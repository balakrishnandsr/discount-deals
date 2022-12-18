<?php
/**
 * Customer zip code rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Customer postcode rule
 *
 * @credit Inspired by AutomateWoo
 */
class Discount_Deals_Workflow_Rule_Customer_Postcode extends Discount_Deals_Workflow_Rule_String_Abstract {
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
		$this->title = __( 'Customer - Postcode', 'discount-deals' );
		$this->has_address_comparison = true;
		$this->address_comparison_types = $this->get_address_compare_types();
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
	public function validate( $data_item, $compare_type, $value, $rule = array() ) {
		$actual_value = $this->get_value_to_validate_by_sub_compare( $rule['sub_compare'], 'postcode', $data_item, '' );
		return $this->validate_string( $actual_value, $compare_type, $value );
	}//end validate()

}//end class

