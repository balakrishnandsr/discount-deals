<?php
/**
 * Customer meta rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Customer meta
 *
 * @credit Inspired by AutomateWoo
 */
class Discount_Deals_Workflow_Rule_Customer_Meta extends Discount_Deals_Workflow_Rule_Meta_Abstract {

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
		$this->title = __( 'Customer - Custom Field', 'discount-deals' );
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
		$value_data = $this->prepare_value_data( $value );
		if ( ! is_array( $value_data ) ) {
			return false;
		}

		return $this->validate_meta( $data_item->get_meta( $value_data['key'] ), $compare_type, $value_data['value'] );
	}//end validate()

}//end class

