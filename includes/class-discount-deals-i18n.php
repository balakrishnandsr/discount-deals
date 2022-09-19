<?php
/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @package Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This class handles all the i18n related stuffs
 */
class Discount_Deals_I18n {

	/**
	 * Load the plugin text domain for translation.
  *
  * @return void
	 */
	public function load_plugin_text_domain() {

		load_plugin_textdomain(
			'discount-deals',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}//end load_plugin_text_domain()




}//end class

