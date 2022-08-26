<?php
/**
 * Cart items rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Discount_Deals_Workflow_Rule_Cart_Items
 */
class Discount_Deals_Workflow_Rule_Cart_Items extends Discount_Deals_Workflow_Rule_Product_Select_Abstract {

	public $data_item = "cart";


	function init() {
		$this->title = __( 'Cart - Items', 'discount-deals' );
		parent::init();
	}


	/**
	 * @param \AutomateWoo\Cart $data_item
	 * @param $compare_type
	 * @param $value
	 *
	 * @return bool
	 */
	function validate( $data_item, $compare_type, $value ) {
		$product = wc_get_product( absint( $value ) );

		if ( ! $product ) {
			return false;
		}

		$target_product_id = $product->get_id();
		$is_variation = $product->is_type( 'variation' );

		$includes = false;

		foreach ( $data_item->get_items() as $item ) {
			$id = $is_variation ? $item->get_variation_id() : $item->get_product_id();
			if ( $id == $target_product_id ) {
				$includes = true;
				break;
			}
		}

		switch ( $compare_type ) {
			case 'includes':
				return $includes;
			case 'not_includes':
				return ! $includes;
		}
	}
}
