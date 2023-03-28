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
		global $wpdb, $blog_id;

		// For multisite table prefix.
		if ( is_multisite() ) {
			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs}", 0 ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		} else {
			$blog_ids = array( $blog_id );
		}
		foreach ( $blog_ids as $id ) {
			if ( is_multisite() ) {
				switch_to_blog( $id );
			}

			self::create_tables();

			if ( is_multisite() ) {
				restore_current_blog();
			}
		}

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
			$collate = $wpdb->get_charset_collate();
			if ( ! empty( $wpdb->collate ) ) {
				$collate .= " COLLATE $wpdb->collate";
			}
		}

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$dd_workflows_table = "
				CREATE TABLE IF NOT EXISTS {$wpdb->prefix}dd_workflows (
				    dd_id bigint(20) NOT NULL AUTO_INCREMENT,
					dd_title varchar(255) NOT NULL,
					dd_type enum('simple_discount', 'bulk_discount', 'bxgx_discount', 'bxgy_discount', 'cart_discount', 'noc_discount') DEFAULT 'simple_discount',
					dd_rules text DEFAULT NULL,
					dd_discounts text NOT NULL,
					dd_meta text DEFAULT NULL,
					dd_index text DEFAULT NULL,
					dd_promotion text DEFAULT NULL,
					dd_exclusive tinyint(1) NOT NULL DEFAULT '1',
					dd_status tinyint(1) NOT NULL DEFAULT '1',
					dd_user_id int(11) NOT NULL,
					dd_created_at datetime NOT NULL,
					dd_updated_at datetime NOT NULL, 
					PRIMARY KEY  ( dd_id ),
                    INDEX dd_status_index ( dd_status )
					) $collate;
			";

		dbDelta( $dd_workflows_table );
		$dd_analytics_table = "
				CREATE TABLE IF NOT EXISTS {$wpdb->prefix}dd_analytics(
				    dd_analytics_id bigint(20) NOT NULL AUTO_INCREMENT,
				    dd_workflow_id bigint(20) NOT NULL,
				    dd_order_id bigint(20) NOT NULL,
				    dd_product_id bigint(20) NULL DEFAULT NULL,
				    dd_regular_price FLOAT NULL DEFAULT NULL,
				    dd_sale_price FLOAT NULL DEFAULT NULL,
				    dd_quantity INT NULL DEFAULT NULL,
				    dd_total FLOAT NOT NULL DEFAULT '0',
				    dd_discount FLOAT NOT NULL DEFAULT '0',
				    dd_created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
				    dd_updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
				    PRIMARY KEY(dd_analytics_id)
				) $collate;
			";

		dbDelta( $dd_analytics_table );

		$dd_coupons_table = "
				CREATE TABLE IF NOT EXISTS {$wpdb->prefix}dd_coupons( 
				    dd_coupon_id BIGINT(20) NOT NULL AUTO_INCREMENT,
				    dd_order_id BIGINT(20) NULL DEFAULT NULL,
				    dd_user_id BIGINT(20) NOT NULL DEFAULT '0',
				    dd_masked_code VARCHAR(50) NOT NULL,
				    dd_coupon_value FLOAT NOT NULL,
				    dd_coupon_type ENUM('flat','percent') NOT NULL DEFAULT 'flat',
				    dd_actual_code VARCHAR(255) NOT NULL,
				    dd_coupon_extra TEXT NULL DEFAULT NULL,
				    dd_is_coupon_used ENUM('yes','no') NOT NULL DEFAULT 'no',
				    dd_coupon_expired_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
				    dd_updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
				    dd_created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
				    PRIMARY KEY(dd_coupon_id)
                ) $collate;
			";

		dbDelta( $dd_coupons_table );

		$is_default_workflows_created = get_option( 'discount_deals_auto_workflows_created', 'no' );
		if ( 'no' == $is_default_workflows_created ) {
			$current_time = current_time( 'mysql', true );
			$wpdb->query(
				$wpdb->prepare(
					"
					INSERT INTO `{$wpdb->prefix}dd_workflows` (`dd_title`, `dd_rules`, `dd_type`, `dd_discounts`, `dd_index`, `dd_promotion`, `dd_meta`, `dd_exclusive`, `dd_status`, `dd_user_id`, `dd_language`, `dd_created_at`, `dd_updated_at`) VALUES
					('10 percentage discount for all products in shop', 'a:0:{}', 'simple_discount', 'a:1:{i:1;a:5:{s:9:\"min_price\";s:1:\"0\";s:9:\"max_price\";s:6:\"999999\";s:4:\"type\";s:7:\"percent\";s:5:\"value\";s:2:\"10\";s:12:\"max_discount\";s:0:\"\";}}', 'a:0:{}', 'a:0:{}', 'a:0:{}', 0, 0, 0, NULL, %s, %s),
					('Bulk/tiered discount for all products', 'a:0:{}', 'bulk_discount', 'a:2:{i:1;a:5:{s:12:\"min_quantity\";s:2:\"10\";s:12:\"max_quantity\";s:2:\"15\";s:4:\"type\";s:7:\"percent\";s:5:\"value\";s:2:\"10\";s:12:\"max_discount\";s:0:\"\";}i:2;a:5:{s:12:\"min_quantity\";s:2:\"16\";s:12:\"max_quantity\";s:4:\"9999\";s:4:\"type\";s:7:\"percent\";s:5:\"value\";s:2:\"25\";s:12:\"max_discount\";s:0:\"\";}}', 'a:0:{}', 'a:0:{}', 'a:0:{}', 0, 0, 1, NULL, %s, %s),
					('Purchase over 500 and get 10 percentage discount on subtotal', 'a:0:{}', 'cart_discount', 'a:1:{i:1;a:5:{s:12:\"min_subtotal\";s:3:\"500\";s:12:\"max_subtotal\";s:4:\"5000\";s:4:\"type\";s:7:\"percent\";s:5:\"value\";s:2:\"10\";s:12:\"max_discount\";s:0:\"\";}}', 'a:0:{}', 'a:0:{}', 'a:0:{}', 0, 0, 0, NULL, %s, %s),
					('Buy 5 and get 1 free on same product', 'a:0:{}', 'bxgx_discount', 'a:1:{i:1;a:4:{s:12:\"min_quantity\";s:1:\"5\";s:12:\"max_quantity\";s:1:\"5\";s:13:\"free_quantity\";s:1:\"1\";s:4:\"type\";s:4:\"free\";}}', 'a:0:{}', 'a:0:{}', 'a:0:{}', 0, 0, 1, NULL, %s, %s),
					('Buy 3 and get 1 free on cheapest item in cart', 'a:0:{}', 'bxgy_discount', 'a:1:{i:1;a:5:{s:12:\"min_quantity\";s:1:\"3\";s:12:\"max_quantity\";s:1:\"3\";s:17:\"free_product_type\";s:16:\"cheapest_in_cart\";s:13:\"free_quantity\";s:1:\"1\";s:4:\"type\";s:4:\"free\";}}', 'a:0:{}', 'a:0:{}', 'a:0:{}', 0, 0, 0, NULL, %s, %s);
				",
					$current_time,
					$current_time,
					$current_time,
					$current_time,
					$current_time,
					$current_time,
					$current_time,
					$current_time,
					$current_time,
					$current_time
				)
			);
			update_option( 'discount_deals_auto_workflows_created', 'yes' );
		}
	}//end create_tables()
}//end class

