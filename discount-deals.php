<?php
/**
 * Plugin Name:       Discount Deals for WooCommerce
 * Plugin URI:        https://inperks.org
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Inperks
 * Author URI:        https://inperks.org
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       discount-deals
 * Domain Path:       /languages
 *
 * @package Discount_Deals
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

defined( 'DISCOUNT_DEALS_VERSION' ) || define( 'DISCOUNT_DEALS_VERSION', '1.0.0' );
defined( 'DISCOUNT_DEALS_PLUGIN_FILE' ) || define( 'DISCOUNT_DEALS_PLUGIN_FILE', __FILE__ );
defined( 'DISCOUNT_DEALS_ABSPATH' ) || define( 'DISCOUNT_DEALS_ABSPATH', dirname( DISCOUNT_DEALS_PLUGIN_FILE ) . '/' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-discount-deals-activator.php
 *
 * @return void
 */
function activate_discount_deals() {
	require_once DISCOUNT_DEALS_ABSPATH . 'includes/class-discount-deals-activator.php';
	Discount_Deals_Activator::activate();
}//end activate_discount_deals()

register_activation_hook( __FILE__, 'activate_discount_deals' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-discount-deals-deactivator.php
 *
 * @return void
 */
function deactivate_discount_deals() {
	require_once DISCOUNT_DEALS_ABSPATH . 'includes/class-discount-deals-deactivator.php';
	Discount_Deals_Deactivator::deactivate();
}//end deactivate_discount_deals()

register_deactivation_hook( __FILE__, 'deactivate_discount_deals' );

/**
 * Load Discount Deals For WooCommerce only if woocommerce is activated
 *
 * @return Discount_Deals
 */
function discount_deals() {
	require_once DISCOUNT_DEALS_ABSPATH . 'includes/class-discount-deals.php';
	require_once DISCOUNT_DEALS_ABSPATH . 'discount-deals-functions.php';
	return Discount_Deals::run();
}//end discount_deals()

discount_deals();
