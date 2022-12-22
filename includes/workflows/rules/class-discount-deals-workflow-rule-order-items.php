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
class Discount_Deals_Workflow_Rule_Order_Items extends Discount_Deals_Workflow_Rule_Product_Select_Abstract {
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
		parent::init();

		$this->title = __( 'Order - Items', 'discount-deals' );
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
		$expected_product = wc_get_product( absint( $value ) );
		if ( ! $expected_product ) {
			return false;
		}

		$includes = false;

		foreach ( $data_item->get_items() as $item ) {
			$product  = $item->get_product();
			$includes = false;//TODO

			if ( $includes ) {
				break;
			}
		}

		switch ( $compare_type ) {
			case 'includes':
				return $includes;
			case 'not_includes':
				return ! $includes;
		}

		return false;
	}
}
