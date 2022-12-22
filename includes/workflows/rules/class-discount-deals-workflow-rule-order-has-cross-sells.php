<?php
/**
 * Order cross sells rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Order cross-sells rule
 *
 * @credit Inspired by AutomateWoo
 */
class Discount_Deals_Workflow_Rule_Order_Has_Cross_Sells extends Discount_Deals_Workflow_Rule_Bool_Abstract {
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
		$this->title = __( 'Order - Has Cross-Sells Available', 'discount-deals' );
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
		$cross_sells = aw_get_order_cross_sells( $data_item );// TODO

		switch ( $value ) {
			case 'yes':
				return ! empty( $cross_sells );
			case 'no':
				return empty( $cross_sells );
		}

		return false;
	}

}
