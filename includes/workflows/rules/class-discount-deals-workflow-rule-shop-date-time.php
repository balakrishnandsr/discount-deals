<?php
/**
 * Shop date time rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Discount_Deals_Workflow_Rule_Shop_Date_Time' ) ) {
	/**
	 * Shop date time based rule
	 */
	class Discount_Deals_Workflow_Rule_Shop_Date_Time extends Discount_Deals_Workflow_Rule_Date_Abstract {
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
		 * @param Discount_Deals_Workflow_Data_Item_Shop $data_item WC Order.
		 * @param string                                 $compare_type Compare type.
		 * @param array                                  $value Rule value.
		 *
		 * @return bool
		 */
		public function validate( $data_item, $compare_type, $value ) {
			try {
				return $this->validate_date( $compare_type, $value, $data_item->get_current_datetime() );
			} catch ( Exception $e ) {
				return false;
			}
		}
	}
}
