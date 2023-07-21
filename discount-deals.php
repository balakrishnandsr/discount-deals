<?php
/**
 * Plugin Name:       Discount Deals - Dynamic product pricing and discount rules
 * Plugin URI:        https://inperks.org
 * Description:       Create simple to complex dynamic product pricing and discounts. A simple, flexible and powerful extension for dynamic discounts.
 * Version:           1.0.0
 * Author:            Discount Deals
 * Author URI:        https://inperks.org
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       discount-deals
 * Domain Path:       /languages
 *
 * WC requires at least: 6.0
 * WC tested up to: 7.9
 *
 * @package Discount_Deals
 */

// If this file is called directly, abort.
use Automattic\WooCommerce\Utilities\FeaturesUtil;

if ( ! defined( 'WPINC' ) ) {
	die;
}

defined( 'DISCOUNT_DEALS_VERSION' ) || define( 'DISCOUNT_DEALS_VERSION', '1.0.0' );
defined( 'DISCOUNT_DEALS_PLUGIN_FILE' ) || define( 'DISCOUNT_DEALS_PLUGIN_FILE', __FILE__ );
defined( 'DISCOUNT_DEALS_PLUGIN_SLUG' ) || define( 'DISCOUNT_DEALS_PLUGIN_SLUG', 'discount-deals' );
defined( 'DISCOUNT_DEALS_ABSPATH' ) || define( 'DISCOUNT_DEALS_ABSPATH', dirname( DISCOUNT_DEALS_PLUGIN_FILE ) . '/' );

/**
 * Do necessary things on plugin activation.
 *
 * @return void
 */
function activate_discount_deals() {
	require_once DISCOUNT_DEALS_ABSPATH . 'includes/class-discount-deals-activator.php';
	Discount_Deals_Activator::activate();
}//end activate_discount_deals()

register_activation_hook( __FILE__, 'activate_discount_deals' );

/**
 * Do necessary things on plugin de-activation.
 *
 * @return void
 */
function deactivate_discount_deals() {
	require_once DISCOUNT_DEALS_ABSPATH . 'includes/class-discount-deals-deactivator.php';
	Discount_Deals_Deactivator::deactivate();
}//end deactivate_discount_deals()

register_deactivation_hook( __FILE__, 'deactivate_discount_deals' );


/**
 * Declare that the Discount Deals supported WooCommerce High Performance Order Storage.
 */
add_action( 'before_woocommerce_init', function () {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

/**
 * Load Discount Deals only if woocommerce is activated
 *
 * @return Discount_Deals
 */
function discount_deals() {
	require_once DISCOUNT_DEALS_ABSPATH . 'includes/class-discount-deals.php';
	require_once DISCOUNT_DEALS_ABSPATH . 'discount-deals-functions.php';

	return Discount_Deals::run();
}//end discount_deals()

// Don't run our plugin if WooCommerce plugin is not active.
$plugin_path = trailingslashit( WP_PLUGIN_DIR ) . 'woocommerce/woocommerce.php';
if ( in_array( $plugin_path, wp_get_active_and_valid_plugins() ) ) {
	discount_deals();
}
