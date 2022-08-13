<?php
/**
 * Shop date time rule
 *
 * @package     Email Subscribers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Es_Rule_Order_Items' ) ) {
	class Discount_Deals_Rule_Shop_Date_Time extends Discount_Deals_Workflow_Rule_Date {
		/**
		 * Data Item
		 *
		 * @var string
		 */
		public $data_item = 'shop';

		/**
		 * Use is not set comparison.
		 *
		 * @var bool
		 */
		public $has_is_set = false;

		/**
		 * Use is not set comparison.
		 *
		 * @var bool
		 */
		public $has_is_not_set = false;

		/**
		 * Init the rule.
		 */
		public function init() {
			$this->title = __( 'Shop - Current Date/Time', 'discount-deals' );
		}

		/**
		 * Validate rule against order items
		 *
		 * @param WC_Order $data_item
		 * @param string $compare_type
		 * @param array $value
		 *
		 * @return bool
		 */
		public function validate( $data_item, $compare_type, $value ) {
			return true;
		}
	}
}
