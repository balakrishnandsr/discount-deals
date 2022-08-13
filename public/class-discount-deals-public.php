<?php
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * All stuffs like discount calculation, applying discount finding perfect workflows for products and cart will go here
 */
class Discount_Deals_Public {
	/**
	 * The ID of this plugin.
	 *
	 * @var      string $plugin_slug The ID of this plugin.
	 */
	private $plugin_slug;

	/**
	 * The version of this plugin.
	 *
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_slug = $plugin_name;
		$this->version     = $version;

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_action( 'woocommerce_init', array( $this, 'init_public_hooks' ) );

	}//end __construct()

	/**
	 * Init all public facing hooks
	 *
	 * @return void
	 */
	public function init_public_hooks() {
		add_filter( 'woocommerce_product_get_price', array( $this, 'get_product_price' ), 99, 2 );
	}//end init_public_hooks()



	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @return void
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_slug, plugin_dir_url( __FILE__ ) . 'css/discount-deals-public.css', array(), $this->version, 'all' );

	}//end enqueue_styles()


	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_slug, plugin_dir_url( __FILE__ ) . 'js/discount-deals-public.js', array( 'jquery' ), $this->version, false );

	}//end enqueue_scripts()

	/**
	 * Set woocommerce product price as per simple discount.
	 *
	 * @param float  $price   Product price.
	 * @param object $product Product object.
	 * @return float
	 */
	public function get_product_price( $price, $product ) {
		discount_deals_get_product_discount( $product );
		return 10;
	}//end get_product_price()



}//end class

