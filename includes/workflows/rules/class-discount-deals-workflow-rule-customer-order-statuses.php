<?php
/**
 * Customer order statuses rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * @class Customer_Order_Statuses
 */
class Discount_Deals_Workflow_Rule_Customer_Order_Statuses extends Discount_Deals_Workflow_Rule_Preloaded_Select_Abstract {

	public $data_item = "customer";

	public $is_multi = true;


	function init() {
		parent::init();

		$this->title = __( "Customer - Current Order Statuses", 'discount-deals' );
		unset( $this->compare_types[ 'matches_all' ] );
	}


	/**
	 * @return array
	 */
	function load_select_choices() {
		return wc_get_order_statuses();
	}


	/**
	 * @param $customer \AutomateWoo\Customer
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $customer, $compare, $value ) {

		$orders = wc_get_orders([
			'type' => 'shop_order',
			'customer' => $customer->is_registered() ? $customer->get_user_id() : $customer->get_email(),
			'limit' => -1
		]);

		$statuses = [];
		foreach ( $orders as $order ) {
			/** @var $order \WC_Order */
			$statuses[] = 'wc-' . $order->get_status();
		}

		return $this->validate_select( $statuses, $compare, $value );
	}

}
