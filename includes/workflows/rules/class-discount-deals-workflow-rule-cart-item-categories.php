<?php
/**
 * Cart item categories rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Discount_Deals_Workflow_Rule_Cart_Item_Categories
 */
class Discount_Deals_Workflow_Rule_Cart_Item_Categories extends Discount_Deals_Workflow_Rule_Preloaded_Select_Abstract {

	public $data_item = "cart";

	public $is_multi = true;


	function init() {
		parent::init();

		$this->title = __( 'Cart - Item Categories', 'discount-deals' );
	}


	/**
	 * @return array
	 */
	function load_select_choices() {
		return discount_deals_get_all_categories();
	}


	/**
	 * @param $data_item \AutomateWoo\Cart
	 * @param $compare_type
	 * @param $value
	 *
	 * @return bool
	 */
	function validate( $data_item, $compare_type, $value ) {

		if ( empty( $value ) ) {
			return false;
		}

		$category_ids = [];

		foreach ( $data_item->get_items() as $item ) {
			$terms        = wp_get_object_terms( $item->get_product_id(), 'product_cat', [ 'fields' => 'ids' ] );
			$category_ids = array_merge( $category_ids, $terms );
		}

		$category_ids = array_filter( $category_ids );

		return $this->validate_select( $category_ids, $compare_type, $value );
	}
}
