<?php
/**
 * Cart item tags rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Discount_Deals_Workflow_Rule_Cart_Item_Tags
 */
class Discount_Deals_Workflow_Rule_Cart_Item_Tags extends Discount_Deals_Workflow_Rule_Preloaded_Select_Abstract {

	public $data_item = "cart";

	public $is_multi = true;


	function init() {
		parent::init();

		$this->title = __( 'Cart - Item Tags', 'discount-deals' );
	}


	/**
	 * @return array
	 */
	function load_select_choices() {
		return discount_deals_get_all_tags();
	}


	/**
	 * @param $cart \AutomateWoo\Cart
	 * @param $compare
	 * @param $expected
	 *
	 * @return bool
	 */
	function validate( $cart, $compare, $expected ) {
		if ( empty( $expected ) ) {
			return false;
		}

		$tag_ids = [];

		foreach ( $cart->get_items() as $item ) {
			$terms   = wp_get_object_terms( $item->get_product_id(), 'product_tag', [ 'fields' => 'ids' ] );
			$tag_ids = array_merge( $tag_ids, $terms );
		}

		$tag_ids = array_filter( $tag_ids );

		return $this->validate_select( $tag_ids, $compare, $expected );
	}
}
