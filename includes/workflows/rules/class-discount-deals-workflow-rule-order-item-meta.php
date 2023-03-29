<?php
/**
 * Order item meta rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Order item meta rule
 *
 * @credit Inspired by AutomateWoo
 */
class Discount_Deals_Workflow_Rule_Order_Item_Meta extends Discount_Deals_Workflow_Rule_Meta_Abstract {
	/**
	 * What data item should pass in to validate the rule?
	 *
	 * @var string
	 */
	public $data_item = 'order_item';

	/**
	 * Init the rule.
	 *
	 * @return void
	 */
	public function init() {
		$this->title = __( 'Order Line Item - Custom Field', 'discount-deals' );
	}


	/**
	 * Validates rule.
	 *
	 * @param WC_Order $data_item The order.
	 * @param string $compare_type What variables we're using to compare.
	 * @param string $value The values we have to compare.
	 *
	 * @return boolean
	 * @throws Exception
	 */
	public function validate( $data_item, $compare_type, $value ) {

		$value_data = $this->prepare_value_data( $value );

		if ( ! is_array( $value_data ) ) {
			return false;
		}

		return $this->validate_meta( wc_get_order_item_meta( $data_item->get_id(), $value_data['key'] ), $compare_type, $value_data['value'] );
	}
}
