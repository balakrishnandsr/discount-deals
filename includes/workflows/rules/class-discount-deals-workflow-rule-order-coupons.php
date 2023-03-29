<?php
/**
 * Order coupon rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Order coupon rule
 *
 * @credit Inspired by AutomateWoo
 */
class Discount_Deals_Workflow_Rule_Order_Coupons extends Discount_Deals_Workflow_Rule_Searchable_Select_Abstract {

	/**
	 * What data item should pass in to validate the rule?
	 *
	 * @var string
	 */
	public $data_item = 'order';

	/**
	 * The CSS class to use on the search field.
	 *
	 * @var string
	 */
	public $class = 'wc-product-search';

	/**
	 * This rule supports multiple selections.
	 *
	 * @var bool
	 */
	public $is_multi = true;

	/**
	 * Init the rule.
	 *
	 * @return void
	 */
	public function init() {
		parent::init();

		$this->title = __( 'Order - Coupons', 'discount-deals' );
	}

	/**
	 * Get the ajax action to use for the AJAX search.
	 *
	 * @return string
	 */
	public function get_search_ajax_action() {
		return 'discount_deals_json_search_coupons';
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
		return $this->validate_select_case_insensitive( $data_item->get_coupon_codes(), $compare_type, $value );
	}

}
