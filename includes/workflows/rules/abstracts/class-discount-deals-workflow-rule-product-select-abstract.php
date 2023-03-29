<?php
/**
 * Select Abstract rule class.
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Product select rule
 *
 * @credit Inspired by AutomateWoo
 */
abstract class Discount_Deals_Workflow_Rule_Product_Select_Abstract extends Discount_Deals_Workflow_Rule_Searchable_Select_Abstract {
	/**
	 * The CSS class to use on the search field.
	 *
	 * @var string
	 */
	public $class = 'wc-product-search';

	/**
	 * Init.
	 *
	 * @return void
	 */
	public function init() {
		parent::init();
		$this->is_multi    = true;
		$this->compare_types = $this->get_multi_select_compare_types();
		$this->placeholder = __( 'Search products...', 'discount-deals' );
	}//end init()


	/**
	 * Display product name on frontend.
	 *
	 * @param integer $value Product ID.
	 *
	 * @return string|integer
	 */
	public function get_object_display_value( $value ) {
		$value   = absint( $value );
		$product = wc_get_product( $value );

		return $product ? $product->get_formatted_name() : $value;
	}//end get_object_display_value()


	/**
	 * Get the ajax action to use for the AJAX search.
	 *
	 * @return string
	 */
	public function get_search_ajax_action() {
		return 'woocommerce_json_search_products_and_variations';
	}//end get_search_ajax_action()

}//end class

