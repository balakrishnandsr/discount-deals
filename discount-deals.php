<?php
/**
 * Plugin Name:       Discount Deals for WooCommerce
 * Plugin URI:        https://inperks.org
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Inperks
 * Author URI:        https://inperks.org
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       discount-deals
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
defined( 'DISCOUNT_DEALS_VERSION' ) or define( 'DISCOUNT_DEALS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-discount-deals-activator.php
 */
function activate_discount_deals() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-discount-deals-activator.php';
	Discount_Deals_Activator::activate();
}//end activate_discount_deals()


/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-discount-deals-deactivator.php
 */
function deactivate_discount_deals() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-discount-deals-deactivator.php';
	Discount_Deals_Deactivator::deactivate();
}//end deactivate_discount_deals()


register_activation_hook( __FILE__, 'activate_discount_deals' );
register_deactivation_hook( __FILE__, 'deactivate_discount_deals' );

/*
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-discount-deals.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function discount_deals() {

	return Discount_Deals::run();

}//end discount_deals()


discount_deals();
