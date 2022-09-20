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

	/**
	 * What data item should pass in to validate the rule?
	 *
	 * @var string
	 */
	public $data_item = 'product';

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

		$this->title       = __( 'Product - Categories', 'discount-deals' );
		$this->placeholder = __( 'Select categories...', 'discount-deals' );
	}//end init()


	/**
	 * Load choices for admin to choose from
	 *
	 * @return array
	 */
	public function load_select_choices() {
		return discount_deals_get_all_categories();
	}//end load_select_choices()


	/**
	 * Validate the product has given categories
	 *
	 * @param WC_Product|WC_Product_Variation $data_item    Data item.
	 * @param string                          $compare_type Compare operator.
	 * @param array                           $value        List of values.
	 *
	 * @return boolean
	 */
	public function validate( $data_item, $compare_type, $value ) {
		if ( empty( $value ) || ! is_array( $value ) ) {
			return false;
		}

		$product_id = $data_item->is_type( 'variation' ) ? $data_item->get_parent_id() : $data_item->get_id();
		$categories = wp_get_object_terms( $product_id, 'product_cat', [ 'fields' => 'ids' ] );

		return $this->validate_select( $categories, $compare_type, $value );
	}//end validate()

}//end class

