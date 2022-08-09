<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @package    Discount_Deals
 * @subpackage Discount_Deals/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Discount_Deals_Activator' ) ) {

	/**
	 * Class to handle installation of the plugin
	 */
	class Discount_Deals_Activator {

		/**
		 * Run the loader to execute all the hooks with WordPress.
		 */
		public static function activate() {
			self::create_tables();
			add_option( 'discount_deals_do_activation_redirect', true );
			add_option( 'discount_deals_db_version', '1.0.0', '', 'no' );
		}//end activate()


		/**
		 * Function to create tables
		 */
		public static function create_tables() {
			global $wpdb;

			$collate = '';

			if ( $wpdb->has_cap( 'collation' ) ) {
				if ( $wpdb->has_cap( 'collation' ) ) {
					$collate = $wpdb->get_charset_collate();
				}
				if ( ! empty( $wpdb->collate ) ) {
					$collate .= " COLLATE $wpdb->collate";
				}
			}

			include_once ABSPATH . 'wp-admin/includes/upgrade.php';

			$dd_tables = "
							CREATE TABLE IF NOT EXISTS {$wpdb->prefix}dd_workflows (
							  	id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
								title varchar(255) NOT NULL,
								type enum('product', 'cart') DEFAULT 'product',
								rules text DEFAULT NULL,
								discount text NOT NULL,
								exclusive enum('yes', 'no') DEFAULT 'no',
								status tinyint(1) DEFAULT '1',
								user_id int(11) NOT NULL,
								language text DEFAULT NULL,
								created_at datetime	 NOT NULL,
								updated_at datetime	 NOT NULL, 
								PRIMARY KEY  (id)
								) $collate;
							";

			dbDelta( $dd_tables );
		}//end create_tables()
	}//end class
}

