<?php
/**
 * Order item rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Order item rule
 *
 * @credit Inspired by AutomateWoo
 */
class Discount_Deals_Workflow_Rule_Order_Item_Product extends Discount_Deals_Workflow_Rule_Product_Select_Abstract {
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
		parent::init();

		$this->title         = __( 'Order Line Item - Product', 'discount-deals' );
		$this->compare_types = $this->get_is_or_not_compare_types();
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
		$expected_product = wc_get_product( absint( $value ) );

		if ( ! $expected_product ) {
			return false;
		}

		$matched = false;//TODO

		return 'is' === $compare_type ? $matched : ! $matched;
	}

}
