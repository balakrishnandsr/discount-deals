<?php
/**
 * Customer city rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Discount_Deals_Workflow_Rule_Customer_City
 */
class Discount_Deals_Workflow_Rule_Customer_City extends Discount_Deals_Workflow_Rule_String_Abstract {

	/**
	 * What data we're using to validate.
	 *
	 * @var string
	 */
	public $data_item = 'customer';

	/**
	 * Init.
	 */
	public function init() {
		$this->title = __( 'Customer - City', 'discount-deals' );
	}

	/**
	 * Validates rule.
	 *
	 * @param WC_Customer $customer The customer.
	 * @param string      $compare What variables we're using to compare.
	 * @param array|null  $value The values we have to compare. Null is only allowed when $compare is is_not_set.
	 *
	 * @return bool
	 */
	public function validate( $customer, $compare, $value ) {
		return $this->validate_string( '', $compare, $value );
	}

}
