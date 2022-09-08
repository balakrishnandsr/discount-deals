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
	/**
	 * Init the settings
	 */
	public static function init() {
		add_filter( 'woocommerce_settings_tabs_array', array( __CLASS__, 'add_settings_tab' ), 50 );
		add_action( 'woocommerce_settings_tabs_discount_deals_settings', array( __CLASS__, 'add_settings' ) );
		add_action( 'woocommerce_update_options_discount_deals_settings', array( __CLASS__, 'save_settings' ) );
	}//end init()

	/**
	 * Add Discount deals settings inside Woocommerce settings.
	 *
	 * @param array $settings_tabs Available settings tabs.
	 *
	 * @return array
	 */
	public static function add_settings_tab( $settings_tabs ) {
		$settings_tabs['discount_deals_settings'] = __( 'Discount Deals', 'discount-deals' );

		return $settings_tabs;
	}//end add_settings_tab()

	/**
	 * Save settings into DB.
	 *
	 * @return void
	 */
	public static function save_settings() {
		if ( discount_deals_get_value_from_array( $_POST, 'save', false ) ) {
			$actual_Settings = array();
			foreach ( $_POST as $key => $value ) {
				if ( str_starts_with( $key, 'wc_settings_tab_discount_deals_' ) ) {
					$actual_key = str_replace( 'wc_settings_tab_discount_deals_', '', $key );

					$actual_Settings[ $actual_key ] = wc_clean( $value );
				}
			}
			Discount_Deals_Settings::save_settings( $actual_Settings );
		}
	}//end save_settings()

	/**
	 * Add settings fields
	 */
	public static function add_settings() {
		woocommerce_admin_fields( self::get_settings_fields() );
	}//end add_settings()

	/**
	 * Get settings fields to display
	 *
	 * @return array
	 */
	public static function get_settings_fields() {
		return array(
			'product_section_title'            => array(
				'name' => __( 'Product Discount settings', 'discount-deals' ),
				'type' => 'title',
				'desc' => '',
				'id'   => 'wc_settings_tab_discount_deals_product_settings',
			),
			'calculate_discount_from'          => array(
				'name'    => __( 'Calculate discount from which price?', 'discount-deals' ),
				'type'    => 'select',
				'options' => array(
					'sale_price'    => __( 'Sale Price', 'discount-deals' ),
					'regular_price' => __( 'Regular Price', 'discount-deals' ),
				),
				'value'   => Discount_Deals_Settings::get_settings( 'calculate_discount_from' ),
				'id'      => 'wc_settings_tab_discount_deals_calculate_discount_from',
			),
			'apply_product_discount_to'        => array(
				'name'    => __( 'Which discount should apply to the product?', 'discount-deals' ),
				'type'    => 'select',
				'options' => array(
					'lowest_matched'  => __( 'Lowest Discount', 'discount-deals' ),
					'biggest_matched' => __( 'Biggest Discount', 'discount-deals' ),
					// 'first_matched'   => __( 'First matched Workflow\'s Discount', 'discount-deals' ),
					'all_matched'     => __( 'All matched Workflow\'s Discount', 'discount-deals' ),
				),
				'value'   => Discount_Deals_Settings::get_settings( 'apply_product_discount_to' ),
				'id'      => 'wc_settings_tab_discount_deals_apply_product_discount_to',
			),
			'apply_discount_subsequently'      => array(
				'name'    => __( 'Do you need to calculate discount subsequently for products?', 'discount-deals' ),
				'type'    => 'radio',
				'options' => array(
					'yes' => __( 'Yes', 'discount-deals' ),
					'no'  => __( 'No', 'discount-deals' ),
				),
				'value'   => Discount_Deals_Settings::get_settings( 'apply_discount_subsequently' ),
				'id'      => 'wc_settings_tab_discount_deals_apply_discount_subsequently',
			),
			'product_section_end'              => array(
				'type' => 'sectionend',
				'id'   => 'wc_settings_tab_discount_deals_product_settings_end',
			),
			//Cart discount
			'cart_section_title'               => array(
				'name' => __( 'Cart Discount settings', 'discount-deals' ),
				'type' => 'title',
				'desc' => '',
				'id'   => 'wc_settings_tab_discount_deals_cart_settings',
			),
			'apply_cart_discount_to'           => array(
				'name'    => __( 'Which discount should apply to the cart?', 'discount-deals' ),
				'type'    => 'select',
				'options' => array(
					'biggest_with_free_shipping'    => __( 'Apply biggest discount and free shipping together', 'discount-deals' ),
					'biggest_without_free_shipping' => __( 'Apply biggest discount and ignore free shipping', 'discount-deals' ),
					'lowest_with_free_shipping'     => __( 'Apply lowest discount and free shipping together', 'discount-deals' ),
					'lowest_without_free_shipping'  => __( 'Apply lowest discount and ignore free shipping', 'discount-deals' ),
					'free_shipping_only'            => __( 'Apply free shipping and ignore other discounts', 'discount-deals' ),
					'all_matched'                   => __( 'Apply all matched discounts', 'discount-deals' ),
				),
				'value'   => Discount_Deals_Settings::get_settings( 'apply_cart_discount_to' ),
				'id'      => 'wc_settings_tab_discount_deals_apply_cart_discount_to',
			),
			'apply_cart_discount_subsequently' => array(
				'name'    => __( 'Calculate cart discount subsequently?', 'discount-deals' ),
				'type'    => 'radio',
				'options' => array(
					'yes' => __( 'Yes', 'discount-deals' ),
					'no'  => __( 'No', 'discount-deals' ),
				),
				'value'   => Discount_Deals_Settings::get_settings( 'apply_cart_discount_subsequently' ),
				'id'      => 'wc_settings_tab_discount_deals_apply_cart_discount_subsequently',
			),
			'apply_cart_discount_as'           => array(
				'name'    => __( 'Apply cart discount as?', 'discount-deals' ),
				'type'    => 'radio',
				'options' => array(
					'coupon' => __( 'Coupon', 'discount-deals' ),
					'fee'    => __( 'Fee', 'discount-deals' ),
				),
				'value'   => Discount_Deals_Settings::get_settings( 'apply_cart_discount_as' ),
				'id'      => 'wc_settings_tab_discount_deals_apply_cart_discount_as',
			),
			'apply_coupon_title'               => array(
				'name'        => __( 'Coupon code to apply on storefront, if workflow\'s are matched', 'discount-deals' ),
				'type'        => 'text',
				'placeholder' => __( 'Enter coupon code here...', 'discount-deals' ),
				'value'       => Discount_Deals_Settings::get_settings( 'apply_coupon_title' ),
				'id'          => 'wc_settings_tab_discount_deals_apply_coupon_title',
			),
			'apply_fee_title'                  => array(
				'name'        => __( 'Enter the label for fee if you want to give discount as fee?', 'discount-deals' ),
				'type'        => 'text',
				'placeholder' => __( 'Enter fee title here...', 'discount-deals' ),
				'value'       => Discount_Deals_Settings::get_settings( 'apply_fee_title' ),
				'id'          => 'wc_settings_tab_discount_deals_apply_fee_title',
			),
			'show_strikeout_price_in_cart'     => array(
				'name'    => __( 'Do you need to show strikeout price for each cart item?', 'discount-deals' ),
				'type'    => 'radio',
				'options' => array(
					'yes' => __( 'Yes', 'discount-deals' ),
					'no'  => __( 'No', 'discount-deals' ),
				),
				'value'   => Discount_Deals_Settings::get_settings( 'show_strikeout_price_in_cart' ),
				'id'      => 'wc_settings_tab_discount_deals_show_strikeout_price_in_cart',
			),
			'you_saved_text'                   => array(
				'name'  => __( 'Promotional message to display in cart line item\'s total and cart subtotal', 'discount-deals' ),
				'type'  => 'text',
				'value' => Discount_Deals_Settings::get_settings( 'you_saved_text' ),
				'id'    => 'wc_settings_tab_discount_deals_you_saved_text',
			),
			'where_display_saving_text'        => array(
				'name'    => __( 'Where to show "You saved" text?', 'discount-deals' ),
				'type'    => 'select',
				'options' => array(
					'disabled'                       => __( 'Don\'t show anywhere ', 'discount-deals' ),
					'on_each_line_item'              => __( 'On after each line item\'s total', 'discount-deals' ),
					'after_total'                    => __( 'On after cart total', 'discount-deals' ),
					'both_line_item_and_after_total' => __( 'On both line item\'s total and cart total', 'discount-deals' ),
				),
				'value'   => Discount_Deals_Settings::get_settings( 'where_display_saving_text' ),
				'id'      => 'wc_settings_tab_discount_deals_where_display_saving_text',
			),
			'cart_section_end'                 => array(
				'type' => 'sectionend',
				'id'   => 'wc_settings_tab_discount_deals_cart_settings_end',
			),
		);
	}//end get_settings_fields()

}//end class
