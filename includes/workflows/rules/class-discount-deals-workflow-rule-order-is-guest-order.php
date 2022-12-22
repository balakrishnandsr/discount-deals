<?php
/**
 * Order customer rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Order customer is gust or not rule
 *
 * @credit Inspired by AutomateWoo
 */
class Discount_Deals_Workflow_Rule_Order_Is_Guest_Order extends Discount_Deals_Workflow_Rule_Bool_Abstract {

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
		$this->title = __( 'Order - Is Placed By Guest', 'discount-deals' );
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

		$is_guest = $data_item->get_user_id() === 0;

		switch ( $value ) {
			case 'yes':
				return $is_guest;

			case 'no':
				return ! $is_guest;
		}

		return false;
	}
}
