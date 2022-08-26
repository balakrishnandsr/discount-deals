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
	}//end init()



	/**
	 * @return array
	 */
	function load_select_choices() {
		return WC()->countries->get_allowed_countries();
	}//end load_select_choices()



	/**
	 * @param $data_item \AutomateWoo\Customer
	 * @param $compare_type
	 * @param $value
	 *
	 * @return boolean
	 */
	function validate( $data_item, $compare_type, $value ) {
		return $this->validate_select( $this->data_layer()->get_customer_country(), $compare_type, $value );
	}//end validate()

}//end class

