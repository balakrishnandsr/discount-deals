<?php
/**
 * Select Abstract rule class.
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Discount_Deals_Workflow_Rule_Product_Select_Abstract' ) ) {
	abstract class Discount_Deals_Workflow_Rule_Product_Select_Abstract extends Discount_Deals_Workflow_Rule_Searchable_Select_Abstract {
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

			$this->placeholder = __( 'Search products...', 'discount-deals' );
		}

		/**
		 * Display product name on frontend.
		 *
		 * @param int $value
		 *
		 * @return string|int
		 */
		public function get_object_display_value( $value ) {
			$value   = absint( $value );
			$product = wc_get_product( $value );

			return $product ? $product->get_formatted_name() : $value;
		}

		/**
		 * Get the ajax action to use for the AJAX search.
		 *
		 * @return string
		 */
		public function get_search_ajax_action() {
			return 'woocommerce_json_search_products_and_variations';
		}
	}
}
