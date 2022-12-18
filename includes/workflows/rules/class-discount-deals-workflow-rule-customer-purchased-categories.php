<?php
/**
 * Customer purchased categories rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Customer purchased categories -all time rule
 *
 * @credit Inspired by AutomateWoo
 */
class Discount_Deals_Workflow_Rule_Customer_Purchased_Categories extends Discount_Deals_Workflow_Rule_Preloaded_Select_Abstract {
	/**
	 * What data item should pass in to validate the rule?
	 *
	 * @var string
	 */
	public $data_item = 'customer';

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

		$this->title       = __( 'Customer - Purchased Categories - All Time', 'discount-deals' );
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
	 * Validates rule.
	 *
	 * @param WC_Customer $data_item    The customer.
	 * @param string      $compare_type What variables we're using to compare.
	 * @param array       $value        The values we have to compare. Null is only allowed when $compare is is_not_set.
	 *
	 * @return boolean
	 */
	public function validate( $data_item, $compare_type, $value, $rule = array() ) {
		if ( empty( $value ) ) {
			return false;
		}
		$category_ids = array();
		foreach ( discount_deals_get_customer_purchased_products( $data_item ) as $id ) {
			$terms        = wp_get_object_terms( $id, 'product_cat', array( 'fields' => 'ids' ) );
			$category_ids = array_merge( $category_ids, $terms );
		}
		$category_ids = array_filter( $category_ids );

		return $this->validate_select( $category_ids, $compare_type, $value );
	}//end validate()

}//end class

