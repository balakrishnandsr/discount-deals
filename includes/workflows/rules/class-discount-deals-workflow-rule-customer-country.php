<?php
/**
 * Customer country rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * @class Customer_Country
 */
class Discount_Deals_Workflow_Rule_Customer_Country extends Discount_Deals_Workflow_Rule_Preloaded_Select_Abstract {

	public $data_item = 'customer';


	function init() {
		parent::init();

		$this->title = __( 'Customer - Country', 'discount-deals' );
	}


	/**
	 * @return array
	 */
	function load_select_choices() {
		return WC()->countries->get_allowed_countries();
	}


	/**
	 * @param $data_item \AutomateWoo\Customer
	 * @param $compare_type
	 * @param $value
	 *
	 * @return bool
	 */
	function validate( $data_item, $compare_type, $value ) {
		return $this->validate_select( $this->data_layer()->get_customer_country(), $compare_type, $value );
	}
}
