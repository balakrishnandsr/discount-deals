<?php
/**
 * The core plugin class.
 *
 * This file is responsible for defining internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * @package Discount_Deals
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Discount Deals Main class.
 */
class Discount_Deals {


	/**
	 * Contains the instance of the class.
	 *
	 * @var      Discount_Deals $instance Maintains and registers all hooks for the plugin.
	 */
	protected static $instance;

	/**
	 * Workflow handler for discounts.
	 *
	 * @var      Discount_Deals_Workflows $workflow_handler Handler.
	 */
	protected $workflow_handler;

	/**
	 * Class Constructor
	 */
	public function __construct() {
		$this->load_dependencies();
		$this->set_locale();
		$this->init_admin();
		$this->init_public();

	}//end __construct()


	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Discount_Deals_Loader. Orchestrates the hooks of the plugin.
	 * - Discount_Deals_I18n. Defines internationalization functionality.
	 * - Discount_Deals_Admin. Defines all hooks for the admin area.
	 * - Discount_Deals_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @return void
	 */
	private function load_dependencies() {

		/*
		 * Include DB related files.
		 */

		require_once DISCOUNT_DEALS_ABSPATH . 'includes/class-discount-deals-db.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/db/class-discount-deals-workflow-db.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/db/class-discount-deals-analytics-db.php';

		/*
		 * Required files.
		 */

		require_once DISCOUNT_DEALS_ABSPATH . 'includes/class-discount-deals-settings.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/class-discount-deals-workflow-data-layer.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/class-discount-deals-workflow.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/class-discount-deals-workflows.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/class-discount-deals-date-time.php';

		/*
		 * Include I18N related files.
		 */

		require_once DISCOUNT_DEALS_ABSPATH . 'includes/class-discount-deals-i18n.php';

		/*
		 * Include admin area related files.
		 */

		require_once DISCOUNT_DEALS_ABSPATH . 'admin/class-discount-deals-admin.php';

		/*
		 * Include storefront related files.
		 */

		require_once DISCOUNT_DEALS_ABSPATH . 'public/class-discount-deals-public.php';

		$this->workflow_handler = new Discount_Deals_Workflows();

	}//end load_dependencies()


	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Discount_Deals_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @return void
	 */
	private function set_locale() {

		$plugin_i18n = new Discount_Deals_I18n();

		add_action( 'plugins_loaded', array( $plugin_i18n, 'load_plugin_text_domain' ) );

	}//end set_locale()


	/**
	 * Register all the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @return void
	 */
	private function init_admin() {

		new Discount_Deals_Admin();

	}//end init_admin()

	/**
	 * Register all the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @return void
	 */
	private function init_public() {

		new Discount_Deals_Public();

	}//end init_public()


	/**
	 * Run the loader to execute all the hooks with WordPress.
	 *
	 * @return self
	 */
	public static function run() {
		if ( is_null( self::$instance ) || ! self::$instance instanceof Discount_Deals ) {
			self::$instance = new self();
		}

		return self::$instance;
	}//end run()


	/**
	 * Get plugins data
	 *
	 * @return array
	 */
	public static function get_plugin_data() {

		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		return get_plugin_data( DISCOUNT_DEALS_PLUGIN_FILE );
	}//end get_plugin_data()


}//end class
