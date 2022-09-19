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
	 * What data item should pass in to validate the rule?
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
	 * Init the rule.
	 * 
	 * @return void
	 */
	public function init() {
		$this->is_multi = true;
		parent::init();
		$this->title       = __( 'Cart - Coupons', 'discount-deals' );
		$this->placeholder = __( 'Search and Select Coupons...', 'discount-deals' );
	}//end init()


	/**
	 * Get the ajax action to use for the AJAX search.
	 *
	 * @return string
	 */
	public function get_search_ajax_action() {
		return 'discount_deals_json_search_coupons';
	}//end get_search_ajax_action()


	/**
	 * Validate the cart coupons rule.
	 *
	 * @param WC_Cart $data_item    Cart object.
	 * @param string  $compare_type Compare operation.
	 * @param array   $value        Expected output.
	 *
	 * @return boolean
	 */
	public function validate( $data_item, $compare_type, $value ) {
		return $this->validate_select_case_insensitive( $data_item->get_applied_coupons(), $compare_type, $value );
	}//end validate()

}//end class

