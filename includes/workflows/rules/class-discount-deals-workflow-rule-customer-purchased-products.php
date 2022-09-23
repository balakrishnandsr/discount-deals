<?php
/**
 * Purchased products rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Customer purchased products - all time
 *
 * @credit Inspired by AutomateWoo
 */
class Discount_Deals_Workflow_Rule_Customer_Purchased_Products extends Discount_Deals_Workflow_Rule_Product_Select_Abstract {
	/**
	 * What data item should pass in to validate the rule?
	 *
	 * @var string
	 */
	public $data_item = 'customer';

	/**
	 * Init the rule.
	 *
	 * @return void
	 */
	public function init() {
		$this->title = __( 'Customer - Purchased Products - All Time', 'discount-deals' );
		parent::init();
	}//end init()


	/**
	 * Validates rule.
	 *
	 * @param WC_Customer $data_item    The customer.
	 * @param string      $compare_type What variables we're using to compare.
	 * @param array       $value        The values we have to compare. Null is only allowed when $compare is is_not_set.
	 *
	 * @return boolean
	 */
	public function validate( $data_item, $compare_type, $value ) {
		if ( empty( $value ) || ! is_array( $value ) ) {
			return false;
		}
		$all_ids = discount_deals_get_customer_purchased_products( $data_item );

		return $this->validate_select( $all_ids, $compare_type, $value );
	}//end validate()

}//end class

