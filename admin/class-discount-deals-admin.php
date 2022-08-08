<?php
/**
 * The admin-specific functionality of the plugin.
 */

class Discount_Deals_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string $plugin_slug The ID of this plugin.
	 */
	private $plugin_slug;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_slug = $plugin_name;
		$this->version     = $version;

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

	}//end __construct()


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_slug, plugin_dir_url( __FILE__ ) . 'css/discount-deals-admin.css', array(), $this->version, 'all' );

	}//end enqueue_styles()


	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_slug, plugin_dir_url( __FILE__ ) . 'js/discount-deals-admin.js', array( 'jquery' ), $this->version, false );

	}//end enqueue_scripts()


}//end class

