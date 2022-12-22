<?php
/**
 * Order item tags rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Order item tags rule
 *
 * @credit Inspired by AutomateWoo
 */
class Discount_Deals_Workflow_Rule_Order_Item_Tags extends Discount_Deals_Workflow_Rule_Preloaded_Select_Abstract {
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

		$this->title = __( 'Order - Item Tags', 'discount-deals' );
	}


	/**
	 * Load values to select
	 *
	 * @return array
	 */
	public function load_select_choices() {
		return discount_deals_get_all_tags();
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

		$tag_ids = [];

		foreach ( $data_item->get_items() as $item ) {
			$terms = wp_get_object_terms( $item->get_product_id(), 'product_tag', [ 'fields' => 'ids' ] );
			$tag_ids = array_merge( $tag_ids, $terms );
		}

		$tag_ids = array_filter( $tag_ids );

		return $this->validate_select( $tag_ids, $compare_type, $value );
	}
}
