<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @package Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Discount_Deals_Admin_Settings
 */
class Discount_Deals_Admin_Settings {
	public static function init() {
		add_filter( 'woocommerce_settings_tabs_array', array( __CLASS__, 'add_settings_tab' ), 50 );
		add_action( 'woocommerce_settings_tabs_discount_deals_settings', array( __CLASS__, 'add_settings' ) );
		add_action( 'woocommerce_update_options_discount_deals_settings', array( __CLASS__, 'save_settings' ) );
	}

	public static function add_settings_tab( $settings_tabs ) {
		$settings_tabs['discount_deals_settings'] = __( 'Discount Deals', 'discount-deals' );

		return $settings_tabs;
	}

	public static function save_settings() {
		if ( discount_deals_get_value_from_array( $_POST, 'save', false ) ) {
			$actual_Settings = array();
			foreach ( $_POST as $key => $value ) {
				if ( str_starts_with( $key, "wc_settings_tab_discount_deals_" ) ) {
					$actual_key = str_replace( 'wc_settings_tab_discount_deals_', '', $key );

					$actual_Settings[ $actual_key ] = wc_clean( $value );
				}
			}
			Discount_Deals_Settings::save_settings( $actual_Settings );
		}
	}

	public static function add_settings() {
		woocommerce_admin_fields( self::get_settings_fields() );
	}

	public static function get_settings_fields() {
		return array(
			'section_title'               => array(
				'name' => __( 'General', 'discount-deals' ),
				'type' => 'title',
				'desc' => '',
				'id'   => 'wc_settings_tab_discount_deals_general_settings'
			),
			'calculate_discount_from'     => array(
				'name'    => __( 'Calculate discount from?', 'discount-deals' ),
				'type'    => 'select',
				'options' => array(
					'sale_price'    => __( 'Sale Price', 'discount-deals' ),
					'regular_price' => __( 'Regular Price', 'discount-deals' ),
				),
				'value'   => Discount_Deals_Settings::get_settings( 'calculate_discount_from' ),
				'id'      => 'wc_settings_tab_discount_deals_calculate_discount_from'
			),
			'apply_product_discount_to'   => array(
				'name'    => __( 'Which discount should apply to the customer?', 'discount-deals' ),
				'type'    => 'select',
				'options' => array(
					'lowest_matched'  => __( 'Lowest Discount', 'discount-deals' ),
					'biggest_matched' => __( 'Biggest Discount', 'discount-deals' ),
					//'first_matched'   => __( 'First matched Workflow\'s Discount', 'discount-deals' ),
					'all_matched'     => __( 'All matched Workflow\'s Discount', 'discount-deals' ),
				),
				'value'   => Discount_Deals_Settings::get_settings( 'apply_product_discount_to' ),
				'id'      => 'wc_settings_tab_discount_deals_apply_product_discount_to'
			),
			'apply_discount_subsequently' => array(
				'name'    => __( 'Calculate discount subsequently?', 'discount-deals' ),
				'type'    => 'radio',
				'options' => array(
					'yes' => __( 'Yes', 'discount-deals' ),
					'no'  => __( 'No', 'discount-deals' )
				),
				'value'   => Discount_Deals_Settings::get_settings( 'apply_discount_subsequently' ),
				'id'      => 'wc_settings_tab_discount_deals_apply_discount_subsequently'
			),
			'section_end'                 => array(
				'type' => 'sectionend',
				'id'   => 'wc_settings_tab_discount_deals_general_settings_end'
			)
		);
	}
}//end class
