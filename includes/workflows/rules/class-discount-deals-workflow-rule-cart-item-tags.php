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
 *
 * @credit Inspired by AutomateWoo
 */
class Discount_Deals_Workflow_Rule_Cart_Item_Tags extends Discount_Deals_Workflow_Rule_Preloaded_Select_Abstract {
	/**
	 * What data item should pass in to validate the rule?
	 *
	 * @var string
	 */
	public $data_item = 'cart';

	/**
	 * Has multi select option?
	 *
	 * @var boolean
	 */
	public $is_multi = true;

	/**
	 * Init the rule
	 */
	public function init() {
		parent::init();

		$this->title = __( 'Cart - Item Tags', 'discount-deals' );
		$this->placeholder = __( 'Select tags...', 'discount-deals' );
	}//end init()


	/**
	 * Load choices for admin to choose from
	 *
	 * @return array
	 */
	public function load_select_choices() {
		return discount_deals_get_all_tags();
	}//end load_select_choices()



	/**
	 * Validate the cart item has given tags
	 *
	 * @param WC_Cart $data_item    Data item.
	 * @param string  $compare_type Compare operator.
	 * @param array   $value        List of values.
	 *
	 * @return boolean
	 */
	public function validate( $data_item, $compare_type, $value ) {
		if ( empty( $value ) || ! is_array( $value ) ) {
			return false;
		}
		$cart_items = $data_item->get_cart_contents();
		if ( empty( $cart_items ) ) {
			return false;
		}
		$tag_ids = [];
		foreach ( $cart_items as $item ) {
			$terms   = wp_get_object_terms( $item['product_id'], 'product_tag', [ 'fields' => 'ids' ] );
			$tag_ids = array_merge( $tag_ids, $terms );
		}
		$tag_ids = array_filter( $tag_ids );

		return $this->validate_select( $tag_ids, $compare_type, $value );
	}//end validate()

}//end class

