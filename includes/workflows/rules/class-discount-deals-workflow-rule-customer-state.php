<?php
/**
 * Customer state  rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Customer state check rule
 *
 * @credit Inspired by AutomateWoo
 */
class Discount_Deals_Workflow_Rule_Customer_State extends Discount_Deals_Workflow_Rule_Preloaded_Select_Abstract {
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
		parent::init();

		$this->title = __( 'Customer - State', 'discount-deals' );
		$this->placeholder = __( 'Select state...', 'discount-deals' );
		$this->has_address_comparison = true;
		$this->address_comparison_types = $this->get_address_compare_types();
	}//end init()

	/**
	 * Load choices for admin to choose from
	 *
	 * @return array
	 */
	public function load_select_choices() {
		$return = array();

		foreach ( WC()->countries->get_states() as $country_code => $states ) {
			foreach ( $states as $state_code => $state_name ) {
				$return[ "$country_code|$state_code" ] = discount_deals_get_country_name( $country_code ) . ' - ' . $state_name;
			}
		}

		return $return;
	}//end load_select_choices()

	/**
	 * Validates rule.
	 *
	 * @param WC_Customer $data_item    The customer.
	 * @param string      $compare_type What variables we're using to compare.
	 * @param array       $value        The values we have to compare. Null is only allowed when $compare is is_not_set.
	 *
	 * @return boolean
	 */
	public function validate( $data_item, $compare_type, $value, $rule = array() ) {
		$state = $this->get_value_to_validate_by_sub_compare( $rule['sub_compare'], 'state', $data_item, '' );
		$country = $this->get_value_to_validate_by_sub_compare( $rule['sub_compare'], 'country', $data_item, '' );

		if ( empty( $state ) || empty( $country ) ) {
			return false;
		}

		return $this->validate_select( "$country|$state", $compare_type, $value );
	}//end validate()

}//end class

