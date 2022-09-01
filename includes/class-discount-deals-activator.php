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
		 *
		 * @return void
		 */
		public static function activate() {
			self::create_tables();
			add_option( 'discount_deals_do_activation_redirect', true );
			add_option( 'discount_deals_db_version', '1.0.0', '', 'no' );
		}//end activate()


		/**
		 * Function to create tables.
		 *
		 * @return void
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

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';

			$dd_tables = "
							CREATE TABLE IF NOT EXISTS {$wpdb->prefix}dd_workflows (
							  	dd_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
								dd_title varchar(255) NOT NULL,
								dd_type enum('simple_discount', 'bulk_discount', 'bxgx_discount', 'bxgy_discount', 'cart_discount') DEFAULT 'simple_discount',
								dd_rules text DEFAULT NULL,
								dd_discounts text NOT NULL,
								dd_meta text DEFAULT NULL,
								dd_index text DEFAULT NULL,
								dd_promotion text DEFAULT NULL,
								dd_exclusive tinyint(1) NOT NULL DEFAULT '1',
								dd_status tinyint(1) NOT NULL DEFAULT '1',
								dd_user_id int(11) NOT NULL,
								dd_language varchar(255) DEFAULT NULL,
								dd_created_at datetime NOT NULL,
								dd_updated_at datetime NOT NULL, 
								PRIMARY KEY  ( dd_id ),
							    INDEX dd_language_index ( dd_language ),
                                INDEX dd_status_index ( dd_status )
								) $collate;
							";

			dbDelta( $dd_tables );
		}//end create_tables()
	}//end class
}

