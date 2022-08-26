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
 * @class Customer_Postcode
 */
class Discount_Deals_Workflow_Rule_Customer_Postcode extends Discount_Deals_Workflow_Rule_String_Abstract {
	public $data_item = "customer";


	function init() {
		$this->title = __( 'Customer - Postcode', 'discount-deals' );
	}


	/**
	 * @param $data_item \AutomateWoo\Customer
	 * @param $compare_type
	 * @param $value
	 *
	 * @return bool
	 */
	function validate( $data_item, $compare_type, $value ) {
		return $this->validate_string( $this->data_layer()->get_customer_postcode(), $compare_type, $value );
	}
}
