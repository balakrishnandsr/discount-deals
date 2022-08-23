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
 * @class Customer_Meta
 */
class Discount_Deals_Workflow_Rule_Customer_Meta extends Discount_Deals_Workflow_Rule_Meta_Abstract {

	/** @var string */
	public $data_item = 'customer';

	/**
	 * Init the rule
	 */
	public function init() {
		$this->title = __( 'Customer - Custom Field', 'discount-deals' );
	}


	/**
	 * Validate the rule based on options set by a workflow
	 *
	 * @param \AutomateWoo\Customer $customer
	 * @param string $compare
	 * @param array $value_data
	 *
	 * @return bool
	 */
	public function validate( $customer, $compare, $value_data ) {

		$value_data = $this->prepare_value_data( $value_data );

		if ( ! is_array( $value_data ) ) {
			return false;
		}

		return $this->validate_meta( $customer->get_legacy_meta( $value_data['key'] ), $compare, $value_data['value'] );

	}

}
