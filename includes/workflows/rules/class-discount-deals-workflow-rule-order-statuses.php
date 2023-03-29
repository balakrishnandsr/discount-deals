<?php
/**
 * Order statuses rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Order statuses rule
 *
 * @credit Inspired by AutomateWoo
 */
class Discount_Deals_Workflow_Rule_Order_Statuses extends Discount_Deals_Workflow_Rule_Preloaded_Select_Abstract {
	/**
	 * What data item should pass in to validate the rule?
	 *
	 * @var string
	 */
	public $data_item = 'order';

	/**
	 * Supports multiple values or not?
	 *
	 * @var boolean
	 */
	public $is_multi = true;

	/**
	 * Init the rule.
	 *
	 * @return void
	 */
	public function init() {
		parent::init();

		$this->title       = __( 'Order - Status', 'discount-deals' );
		$this->placeholder = __( 'Select order statuses...', 'discount-deals' );
		unset( $this->compare_types['matches_all'] );
	}//end init()

	/**
	 * Load choices for admin to choose from
	 *
	 * @return array
	 */
	public function load_select_choices() {
		return wc_get_order_statuses();
	}//end load_select_choices()

	/**
	 * Validates rule.
	 *
	 * @param WC_Order $data_item The customer.
	 * @param string $compare_type What variables we're using to compare.
	 * @param array $value The values we have to compare. 
	 *
	 * @return boolean
	 */
	public function validate( $data_item, $compare_type, $value ) {
		return $this->validate_select( 'wc-' . $data_item->get_status(), $compare_type, $value );
	}//end validate()


}//end class

