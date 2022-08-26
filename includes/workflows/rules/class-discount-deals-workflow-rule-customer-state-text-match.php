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
 * @class Customer_State_Text_Match
 */
class Discount_Deals_Workflow_Rule_Customer_State_Text_Match extends Discount_Deals_Workflow_Rule_String_Abstract {

	public $data_item = "customer";


	function init() {
		$this->title = __( 'Customer - State - Text Match', 'discount-deals' );
	}


	/**
	 * @param $data_item Customer
	 * @param $compare_type
	 * @param $value
	 *
	 * @return bool
	 */
	function validate( $data_item, $compare_type, $value ) {
		$state   = $this->data_layer()->get_customer_state();
		$country = $this->data_layer()->get_customer_country();

		return $this->validate_string( aw_get_state_name( $country, $state ), $compare_type, $value );
	}

}
