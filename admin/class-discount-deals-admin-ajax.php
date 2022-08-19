<?php
/**
 * The admin-specific ajax functionality of the plugin.
 *
 * @package Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Discount_Deals_Admin_Ajax
 */
class Discount_Deals_Admin_Ajax {

	/**
	 * Hook in methods
	 */
	public static function init() {
		$ajax_events = array(
			'fill_discount_fields',
		);

		foreach ( $ajax_events as $ajax_event ) {
			add_action( 'wp_ajax_discount_deals_' . $ajax_event, array( __CLASS__, $ajax_event ) );
		}
	}

	/**
	 * Get discount details for the type
	 *
	 * @return void
	 */
	public static function fill_discount_fields() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			die;
		}

		$discount_type = discount_deals_get_request_data( 'discount_type', '' );
		$discount      = Discount_Deals_Workflows::get_discount_type( $discount_type );

		if ( ! $discount ) {
			die;
		}

		wp_send_json_success(
			array(
				'fields'           => $discount->load_fields(),
				'discount_details' => Discount_Deals_Workflows::get_discount_data( $discount ),
			)
		);
	}

}//end class

