<?php
/**
 * Cart created date time rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Order item quantity rule
 *
 * @credit Inspired by AutomateWoo
 */
class Discount_Deals_Workflow_Rule_Order_Item_Quantity extends Discount_Deals_Workflow_Rule_Number_Abstract {
	/**
	 * What data item should pass in to validate the rule?
	 *
	 * @var string
	 */
	public $data_item = 'order_item';
	/**
	 * Supports float values or not?
	 *
	 * @var boolean
	 */
	public $support_floats = false;

	/**
	 * Init the rule.
	 *
	 * @return void
	 */
	public function init() {
		$this->title = __( 'Order Line Item - Quantity', 'discount-deals' );
	}

	/**
	 * Validates rule.
	 *
	 * @param WC_Order_Item $data_item The order.
	 * @param string $compare_type What variables we're using to compare.
	 * @param string $value The values we have to compare.
	 *
	 * @return boolean
	 */
	public function validate( $data_item, $compare_type, $value ) {
		return $this->validate_number( $data_item->get_quantity(), $compare_type, $value );
	}

}
