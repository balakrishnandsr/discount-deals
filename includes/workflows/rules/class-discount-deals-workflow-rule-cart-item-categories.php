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
	 * @var bool
	 */
	public $is_multi = true;

	/**
	 * Init the rule
	 */
	function init() {
		parent::init();

		$this->title = __( 'Cart - Item Categories', 'discount-deals' );
	}

	/**
	 * Load choices for admin to choose from
	 *
	 * @return array
	 */
	function load_select_choices() {
		return discount_deals_get_all_categories();
	}

	/**
	 * Validate the cart item has given categories
	 *
	 * @param WC_Cart $data_item data item.
	 * @param string $compare_type compare operator.
	 * @param array $value list of values.
	 *
	 * @return bool
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
	}
}
