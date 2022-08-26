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
			'update_workflow_column_value',
			'json_search_coupons',
			'get_rule_select_choices'
		);

		foreach ( $ajax_events as $ajax_event ) {
			add_action( 'wp_ajax_discount_deals_' . $ajax_event, array( __CLASS__, $ajax_event ) );
		}
	}//end init()


	public static function get_rule_select_choices() {

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			die;
		}

		if ( ! $rule_name = discount_deals_get_request_data( 'rule_name' ) ) {
			die;
		}

		$rule_object = Discount_Deals_Workflows::get_rule_type( $rule_name );

		if ( 'select' === $rule_object->type ) {
			wp_send_json_success( [
				'select_choices' => $rule_object->get_select_choices()
			] );
		}

		die;
	}//end get_rule_select_choices()


	public static function json_search_coupons() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			die;
		}
		$term    = discount_deals_get_request_data( 'term' );
		$results = discount_deals_search_coupons( $term, true );

		wp_send_json( $results );
	}//end json_search_coupons()


	public static function update_workflow_column_value() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			die;
		}
		$nonce = discount_deals_get_post_data( 'security' );
		if ( wp_verify_nonce( $nonce, 'discount_deals_change_workflow_column_status' ) ) {
			$column       = discount_deals_get_post_data( 'column' );
			$column_value = discount_deals_get_post_data( 'column_value', 0 );
			$workflow     = discount_deals_get_post_data( 'workflow' );
			if ( $workflow && $column && in_array( $column, array(
					'exclusive',
					'status'
				) ) && in_array( $column_value, array( 0, 1 ) ) ) {
				$workflow_db = new Discount_Deals_Workflow_DB();
				$workflow_db->update( $workflow, array( 'dd_' . $column => $column_value ) );
				wp_send_json_success( array(
					'message' => __( 'Action done successfully!!!', 'discount-deals' )
				) );
			}
		}
		die( - 1 );
	}//end update_workflow_column_value()


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
	}//end fill_discount_fields()


}//end class

