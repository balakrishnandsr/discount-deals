<?php
/**
 * Order item total rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Order item total rule
 *
 * @credit Inspired by AutomateWoo
 */
class Discount_Deals_Workflow_Rule_Order_Item_Total extends Discount_Deals_Workflow_Rule_Number_Abstract {

	/**
	 * What data item should pass in to validate the rule?
	 *
	 * @var string
	 */
	public $data_item = 'order_item';

	/**
	 * Support float values.
	 *
	 * @var bool
	 */
	public $support_floats = true;

	/**
	 * Init the rule.
	 *
	 * @return void
	 */
	public function init() {
		$this->title = __( 'Order Line Item - Total', 'discount-deals' );
	}

	/**
	 * Validates rule.
	 *
	 * @param WC_Order $data_item The order.
	 * @param string $compare_type What variables we're using to compare.
	 * @param string $value The values we have to compare.
	 *
	 * @return boolean
	 */
	public function validate( $data_item, $compare_type, $value ) {
		return $this->validate_number( $data_item->get_total(), $compare_type, $value );
	}

}
