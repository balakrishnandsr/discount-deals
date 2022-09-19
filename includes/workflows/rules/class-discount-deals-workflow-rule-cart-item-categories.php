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
	/**
	 * What data item should pass in to validate the rule?
	 *
	 * @var string
	 */
	public $data_item = "cart";

	/**
	 * Has multi select option?
	 *
	 * @var boolean
	 */
	public $is_multi = true;

	/**
	 * Init the rule
	 *
	 * @return void
	 */
	function init() {
		parent::init();

		$this->placeholder = __( 'Select categories...', 'discount-deals' );
		$this->title       = __( 'Cart - Item Categories', 'discount-deals' );
	}//end init()


	/**
	 * Load choices for admin to choose from
	 *
	 * @return array
	 */
	function load_select_choices() {
		return discount_deals_get_all_categories();
	}//end load_select_choices()


	/**
	 * Validate the cart item has given categories
	 *
	 * @param WC_Cart $data_item    Data item.
	 * @param string  $compare_type Compare operator.
	 * @param array   $value        List of values.
	 *
	 * @return boolean
	 */
	function validate( $data_item, $compare_type, $value ) {
		if ( empty( $value ) || ! is_array( $value ) ) {
			return false;
		}
		$cart_items = $data_item->get_cart_contents();
		if ( empty( $cart_items ) ) {
			return false;
		}
		$category_ids = [];
		foreach ( $cart_items as $item ) {
			$terms        = wp_get_object_terms( $item['product_id'], 'product_cat', [ 'fields' => 'ids' ] );
			$category_ids = array_merge( $category_ids, $terms );
		}
		$category_ids = array_filter( $category_ids );

		return $this->validate_select( $category_ids, $compare_type, $value );
	}//end validate()

}//end class

