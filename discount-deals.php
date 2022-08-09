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
	include_once 'includes/class-discount-deals-activator.php';
	add_option( 'ddfw_do_activation_redirect', true );
	add_option( 'ddfw_db_version', '1.0.0', '', 'no' );
}//end activate_discount_deals()


register_activation_hook( __FILE__, 'activate_discount_deals' );

/**
 * Handle redirect
 */
function ddfw_redirect() {
	if ( get_option( 'ddfw_do_activation_redirect', false ) ) {
		delete_option( 'ddfw_do_activation_redirect' );
		wp_safe_redirect( admin_url( 'admin.php?page=discount-deals-for-woocommerce-documentation' ) );
		exit;
	}
}//end ddfw_redirect()


add_action( 'admin_init', 'ddfw_redirect' );


/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-discount-deals-deactivator.php
 */
function deactivate_discount_deals() {
	include_once 'includes/class-discount-deals-deactivator.php';
}//end deactivate_discount_deals()


register_deactivation_hook( __FILE__, 'deactivate_discount_deals' );

/**
 * Load Discount Deals For WooCommerce only if woocommerce is activated
 */
function initialize_discount_deals_for_woocommerce() {
	define( 'DDFW_PLUGIN_FILE', __FILE__ );
	define( 'DDFW_ABSPATH', dirname( DDFW_PLUGIN_FILE ) . '/' );
	if ( ! defined( 'DDFW_PLUGIN_DIRPATH' ) ) {
		define( 'DDFW_PLUGIN_DIRPATH', dirname( __FILE__ ) );
	}

	$active_plugins = (array) get_option( 'active_plugins', array() );
	if ( is_multisite() ) {
		$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
	}

	if ( ( in_array( 'woocommerce/woocommerce.php', $active_plugins, true ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins ) ) ) {
		require plugin_dir_path( __FILE__ ) . 'includes/class-discount-deals.php';
		$GLOBALS['discount_deals_for_woocommerce'] = Discount_Deals::run();
	} else {
		if ( is_admin() ) {
			?>
			<div class="notice notice-error">
				<p><?php echo esc_html__( 'Discount Deals for WooCommerce requires WooCommerce to be activated.', 'discount-deals-for-woocommerce' ); ?></p>
			</div>
			<?php
		}
	}
}//end initialize_discount_deals_for_woocommerce()


add_action( 'plugins_loaded', 'initialize_discount_deals_for_woocommerce' );
