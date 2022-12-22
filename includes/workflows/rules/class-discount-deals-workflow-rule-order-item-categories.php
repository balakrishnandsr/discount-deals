<?php
/**
 * Order item categories rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Order item categories rule
 *
 * @credit Inspired by AutomateWoo
 */
class Discount_Deals_Workflow_Rule_Order_Item_Categories extends Discount_Deals_Workflow_Rule_Preloaded_Select_Abstract {
	/**
	 * What data item should pass in to validate the rule?
	 *
	 * @var string
	 */
	public $data_item = 'order';
	/**
	 * Supports multiple values or not?
	 *
	 * @var boolean
	 */
	public $is_multi = true;

	/**
	 * Init the rule.
	 *
	 * @return void
	 */
	public function init() {
		parent::init();

		$this->title = __( 'Order - Item Categories', 'discount-deals' );
	}

	/**
	 * Load values to pick
	 *
	 * @return array
	 */
	public function load_select_choices() {
		return discount_deals_get_all_categories();
	}

	/**
	 * Validates rule.
	 *
	 * @param WC_Order $data_item The order.
	 * @param string $compare_type What variables we're using to compare.
	 * @param string $value The values we have to compare.
	 *
	 * @return boolean
	 */
	public function validate( $data_item, $compare_type, $value ) {

		if ( empty( $expected ) ) {
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
