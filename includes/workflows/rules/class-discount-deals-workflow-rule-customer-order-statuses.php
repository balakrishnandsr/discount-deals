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
	}//end init()



	/**
	 * @return array
	 */
	function load_select_choices() {
		return wc_get_order_statuses();
	}//end load_select_choices()



	/**
	 * @param $data_item \AutomateWoo\Customer
	 * @param $compare_type
	 * @param $value
	 *
	 * @return boolean
	 */
	function validate( $data_item, $compare_type, $value ) {

		$orders = wc_get_orders([
			'type' => 'shop_order',
			'customer' => $data_item->is_registered() ? $data_item->get_user_id() : $data_item->get_email(),
			'limit' => -1
		]);

		$statuses = [];
		foreach ( $orders as $order ) {
			// @var $order \WC_Order
			$statuses[] = 'wc-' . $order->get_status();
		}

		return $this->validate_select( $statuses, $compare_type, $value );
	}//end validate()


}//end class

