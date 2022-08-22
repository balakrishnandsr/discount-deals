<?php
/**
 * Cart coupons rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Discount_Deals_Workflow_Rule_Cart_Coupons
 */
class Discount_Deals_Workflow_Rule_Cart_Coupons extends Discount_Deals_Workflow_Rule_Searchable_Select_Abstract {

	/**
	 * The rule's primary data item.
	 *
	 * @var string
	 */
	public $data_item = "cart";

	/**
	 * The CSS class to use on the search field.
	 *
	 * @var string
	 */
	public $class = 'wc-product-search';

	/**
	 * Init.
	 */
	public function init() {
		parent::init();

		$this->title = __( 'Cart - Coupons', 'discount-deals' );
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
	 * Validate the rule for a given order.
	 *
	 * @param \WC_Order $order
	 * @param string $compare
	 * @param array $expected_coupons
	 *
	 * @return bool
	 */
	public function validate( $order, $compare, $expected_coupons ) {
		return $this->validate_select_case_insensitive( $order->get_coupon_codes(), $compare, $expected_coupons );
	}
}
