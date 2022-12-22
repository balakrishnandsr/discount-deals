<?php
/**
 * Order payment gateway rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Order payment gateway rule
 *
 * @credit Inspired by AutomateWoo
 */
class Discount_Deals_Workflow_Rule_Order_Payment_Gateway extends Discount_Deals_Workflow_Rule_Preloaded_Select_Abstract {

	/**
	 * What data item should pass in to validate the rule?
	 *
	 * @var string
	 */
	public $data_item = 'order';

	/**
	 * Init the rule.
	 *
	 * @return void
	 */
	public function init() {
		parent::init();

		$this->title = __( 'Order - Payment Gateway', 'discount-deals' );
	}

	/**
	 * Values to select
	 *
	 * @return array
	 */
	public function load_select_choices() {
		$choices = [];

		foreach ( WC()->payment_gateways()->payment_gateways() as $gateway ) {
			if ( 'yes' === $gateway->enabled ) {
				$choices[ $gateway->id ] = $gateway->get_title();
			}
		}

		return $choices;
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
		return $this->validate_select( $data_item->get_payment_method(), $compare_type, $value );
	}

}
