<?php
/**
 * Customer order statuses rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Customer order statuses rule
 *
 * @class Discount_Deals_Workflow_Rule_Customer_Order_Statuses
 */
class Discount_Deals_Workflow_Rule_Customer_Order_Statuses extends Discount_Deals_Workflow_Rule_Preloaded_Select_Abstract {
	/**
	 * What data item should pass in to validate the rule?
	 *
	 * @var string
	 */
	public $data_item = "customer";

	/**
	 * Supports multiple values or not?
	 *
	 * @var boolean
	 */
	public $is_multi = true;

	/**
	 * Init the rule.
	 */
	function init() {
		parent::init();

		$this->title = __( "Customer - Last Order Status", 'discount-deals' );
		$this->placeholder = __( 'Select order statuses...', 'discount-deals' );
		unset( $this->compare_types['matches_all'] );
	}//end init()

	/**
	 * Load choices for admin to choose from
	 *
	 * @return array
	 */
	function load_select_choices() {
		return wc_get_order_statuses();
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
	function validate( $data_item, $compare_type, $value ) {
		$last_order = $data_item->get_last_order();
		if ( ! $last_order ) {
			return false;
		}
		$statuses[] = 'wc-' . $last_order->get_status();

		return $this->validate_select( $statuses, $compare_type, $value );
	}//end validate()


}//end class

