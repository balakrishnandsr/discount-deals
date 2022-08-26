<?php
/**
 * Product categories rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Discount_Deals_Workflow_Rule_Product_Categories
 */
class Discount_Deals_Workflow_Rule_Product_Categories extends Discount_Deals_Workflow_Rule_Preloaded_Select_Abstract {

	public $data_item = 'product';

	public $is_multi = true;


	function init() {
		parent::init();

		$this->title = __( 'Product - Categories', 'discount-deals' );
	}


	/**
	 * @return array
	 */
	function load_select_choices() {
		return discount_deals_get_all_categories();
	}


	/**
	 * @param $data_item \WC_Product|\WC_Product_Variation
	 * @param $compare_type
	 * @param $value
	 *
	 * @return bool
	 */
	function validate( $data_item, $compare_type, $value ) {
		if ( empty( $value ) ) {
			return false;
		}

		$product_id = $data_item->is_type( 'variation' ) ? $data_item->get_parent_id() : $data_item->get_id();
		$categories = wp_get_object_terms( $product_id, 'product_cat', [ 'fields' => 'ids' ] );

		return $this->validate_select( $categories, $compare_type, $value );
	}
}
