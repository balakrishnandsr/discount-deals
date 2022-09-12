<?php
/**
 * This class defines all discount deals settings | options.
 *
 * @package Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Discount_Deals_Settings {
	/**
	 * settings constant
	 *
	 * @var string
	 */
	const DISCOUNT_DEALS_OPTION_KEY = 'discount-deals-settings';
	/**
	 * Contains all the configuration details
	 *
	 * @var array
	 */
	private static $config = array();

	private static $default_config = array(

		//Product
		'calculate_discount_from'          => 'regular_price',
		'apply_product_discount_to'        => 'lowest_matched',
		'apply_discount_subsequently'      => 'no',
		//Cart
		'apply_cart_discount_to'           => 'lowest_with_free_shipping',
		'apply_cart_discount_subsequently' => 'no',
		'show_strikeout_price_in_cart'     => 'yes',
		'you_saved_text'                   => 'You saved {{discount}}',
		'where_display_saving_text'        => 'disabled',//on_each_line_item,after_total,both_line_item_and_after_total
		'apply_cart_discount_as'           => 'fee',
		'apply_coupon_title'               => '',
		'apply_fee_title'                  => 'You discount',
		//Free Shipping
		'free_shipping_title'              => 'free shipping',
		//BOGO
		'apply_bogo_discount_to'           => 'lowest_matched',
		'bogo_discount_highlight_message'  => 'Free',
	);

	/**
	 * Save the configuration
	 *
	 * @param $data
	 *
	 * @return boolean
	 */
	public static function save_settings( $data = array() ) {
		$old_settings = get_option( self::DISCOUNT_DEALS_OPTION_KEY, array() );
		$new_settings = wp_parse_args( $data, $old_settings );

		return update_option( self::DISCOUNT_DEALS_OPTION_KEY, $new_settings );
	}//end save_settings()


	/**
	 * @param $key - what configuration need to get
	 * @param string $default - default value if config value not found
	 *
	 * @return string - configuration value
	 */
	public static function get_settings( $key, $default = '' ) {
		if ( empty( self::$config ) ) {
			self::saved_settings();
		}

		return wc_clean( wp_unslash( discount_deals_get_value_from_array( self::$config, $key, $default ) ) );
	}//end get_settings()


	/**
	 * Set rule configuration to static variable
	 *
	 * @return array
	 */
	protected static function saved_settings() {
		$options      = get_option( self::DISCOUNT_DEALS_OPTION_KEY );
		self::$config = wp_parse_args( $options, self::$default_config );

		return self::$config;
	}//end saved_settings()

}//end class

