<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 */
class Discount_Deals {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @var      Discount_Deals $_instance Maintains and registers all hooks for the plugin.
	 */
	protected static $_instance;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @var      string $plugin_slug The string used to uniquely identify this plugin.
	 */
	protected $plugin_slug;

	/**
	 * The current version of the plugin.
	 *
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'DISCOUNT_DEALS_VERSION' ) ) {
			$this->version = DISCOUNT_DEALS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_slug = 'discount-deals';

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
	 * - Discount_Deals_i18n. Defines internationalization functionality.
	 * - Discount_Deals_Admin. Defines all hooks for the admin area.
	 * - Discount_Deals_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 */
	private function load_dependencies() {
		/*
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-discount-deals-i18n.php';

		/*
		 * The class responsible for defining all actions that occur in the admin area.
		 */

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-discount-deals-admin.php';

		/*
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-discount-deals-public.php';

	}//end load_dependencies()


	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Discount_Deals_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 */
	private function set_locale() {

		$plugin_i18n = new Discount_Deals_i18n();

		add_action( 'plugins_loaded', array( $plugin_i18n, 'load_plugin_textdomain' ) );

	}//end set_locale()


	/**
	 * Register all the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 */
	private function init_admin() {

		new Discount_Deals_Admin( $this->get_plugin_slug(), $this->get_version() );

	}//end init_admin()


	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}//end get_plugin_slug()


	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}//end get_version()


	/**
	 * Register all the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 */
	private function init_public() {

		new Discount_Deals_Public( $this->get_plugin_slug(), $this->get_version() );

	}//end init_public()


	/**
	 * Run the loader to execute all the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public static function run() {
		if ( ! self::$_instance instanceof Discount_Deals ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}//end run()


}//end class

