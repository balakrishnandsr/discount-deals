<?php
/**
 * Order items rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Order items rule
 *
 * @credit Inspired by AutomateWoo
 */
class Discount_Deals_Workflow_Rule_Order_Items_Text_Match extends Discount_Deals_Workflow_Rule_String_Abstract {
	/**
	 * What data item should pass in to validate the rule?
	 *
	 * @var string
	 */
	public $data_item = 'order';

	/**
	 * Init the rule.
	 *
	 * @return void
	 */
	public function init() {
		$this->title = __( 'Order - Item Names - Text Match', 'discount-deals' );
		$this->compare_types = $this->get_multi_string_compare_types();
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
		$names = [];

		foreach ( $data_item->get_items() as $item ) {
			$names[] = $item->get_name();
		}

		return $this->validate_string_multi( $names, $compare_type, $value );
	}
}
