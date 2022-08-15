<?php
/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @package    Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Discount_Deals_Deactivator' ) ) {

	/**
	 * Class to handle installation of the plugin
	 */
	class Discount_Deals_Deactivator {

		/**
		 * Function to handle uninstall process
		 */
		public static function deactivate() {

		}//end deactivate()

	}//end class

}



