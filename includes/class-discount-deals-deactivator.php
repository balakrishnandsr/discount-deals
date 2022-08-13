<?php
/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @package    Discount_Deals
 * @subpackage Discount_Deals/includes
 *
 * @since      1.0.0
 * @version    1.0.0
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
		 *
		 * @return void
		 */
		public static function deactivate() {

		}//end deactivate()

	}//end class

}



