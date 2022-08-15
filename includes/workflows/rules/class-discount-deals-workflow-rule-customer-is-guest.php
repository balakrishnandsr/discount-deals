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
 * Class Discount_Deals_Workflow_Rule_Customer_Is_Guest
 */
class Discount_Deals_Workflow_Rule_Customer_Is_Guest extends Discount_Deals_Workflow_Rule_Bool_Abstract {
	/**
	 * Valid data item
	 *
	 * @var string
	 */
	public $data_item = 'customer';

	/**
	 * Init.
	 */
	public function init() {
		$this->title = __( 'Customer - Is Guest', 'discount-deals' );
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
		$is_guest = ! $customer->get_id();

		switch ( $value ) {
			case 'yes':
				return $is_guest;
			case 'no':
				return ! $is_guest;
		}

		return false;
	}

}
