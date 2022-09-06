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
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_slug = $plugin_name;
		$this->version     = $version;
		if ( ! is_admin() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			add_action( 'woocommerce_init', array( $this, 'init_public_hooks' ) );
		}


	}//end __construct()

	/**
	 * Init all public facing hooks
	 *
	 * @return void
	 */
	public function init_public_hooks() {
		add_filter( 'woocommerce_product_get_price', array( $this, 'get_product_price' ), 99, 2 );
		add_filter( 'woocommerce_product_variation_get_price', array( $this, 'get_product_price' ), 99, 2 );
		add_filter( 'woocommerce_product_get_sale_price', array( $this, 'get_sale_price' ), 99, 2 );
		add_filter( 'woocommerce_product_variation_get_sale_price', array( $this, 'get_sale_price' ), 99, 2 );
		add_filter( 'woocommerce_variation_prices', array( $this, 'get_variation_prices' ), 99, 3 );
		add_filter( 'woocommerce_add_to_cart', array( $this, 'item_added_to_cart' ), 99, 6 );
		add_filter( 'woocommerce_variation_prices', array( $this, 'get_variation_prices' ), 99, 3 );
		//Cart Discount.
		$cart_discount_type = Discount_Deals_Settings::get_settings( 'apply_cart_discount_as', 'coupon' );
		if ( 'coupon' == $cart_discount_type ) {
			add_filter( 'woocommerce_before_cart_table', array( $this, 'apply_cart_discount_as_coupon' ), 99, 3 );
			add_filter( 'woocommerce_coupon_get_amount', array( $this, 'set_coupon_amount' ), 99, 2 );
			add_filter( 'woocommerce_coupon_get_discount_type', array( $this, 'set_coupon_discount_type' ), 99, 2 );
			add_filter( 'woocommerce_cart_totals_coupon_html', array( $this, 'hide_remove_coupon' ), 10, 3 );
		} else {
			add_action( 'woocommerce_cart_calculate_fees', array( $this, 'apply_cart_discount_as_fee' ), 99 );
		}
	}//end init_public_hooks()

	public function item_added_to_cart( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
		// TODO: remove session value after order placement
		$created_time = WC()->session->get( 'discount_deals_cart_created_time', false );
		if ( ! $created_time ) {
			WC()->session->set( 'discount_deals_cart_created_time', current_time( 'U', true ) );
		}
		WC()->session->set( 'discount_deals_cart_updated_time', current_time( 'U', true ) );
	}//end item_added_to_cart()


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
	 * @param float $price Product price.
	 * @param WC_Product $product Product object.
	 *
	 * @return float
	 */
	public function get_product_price( $price, $product ) {
		return discount_deals_get_product_discount( $price, $product );
	}//end get_product_price()

	/**
	 * Set woocommerce product sale price.
	 *
	 * @param $price
	 * @param $product
	 *
	 * @return mixed|string
	 */
	public function get_sale_price( $price, $product ) {
		$sale_price = ( is_a( $product, 'WC_Product' ) ) ? $product->get_price() : '';
		if ( 0 == $sale_price || empty( $sale_price ) ) {
			return $price;
		}

		return $sale_price;
	}//end get_sale_price()


	/**
	 * Get variation prices.
	 *
	 * @param array $transient_cached_prices_array Cached prices array
	 * @param WC_Product $product Product.
	 * @param boolean $for_display true | false
	 *
	 * @return array
	 */
	public function get_variation_prices( $transient_cached_prices_array, $product, $for_display ) {
		if ( ! empty( $transient_cached_prices_array['price'] ) && ! empty( $transient_cached_prices_array['regular_price'] ) && ! empty( $transient_cached_prices_array['sale_price'] ) ) {
			foreach ( $transient_cached_prices_array['price'] as $variation_id => $variation_price ) {
				if ( ! empty( $variation_id ) ) {
					$product                                                      = wc_get_product( $variation_id );
					$sale_price                                                   = ( is_a( $product, 'WC_Product_Variation' ) ) ? $product->get_price() : $variation_price;
					$transient_cached_prices_array['price'][ $variation_id ]      = $sale_price;
					$transient_cached_prices_array['sale_price'][ $variation_id ] = $sale_price;
				}
			}
		}

		return $transient_cached_prices_array;
	}

	/**
	 * Apply coupon discount.
	 *
	 * @return null
	 */
	public function apply_cart_discount_as_coupon() {
		if ( WC()->cart->is_empty() ) {
			return null;
		}
		$coupon_code = Discount_Deals_Settings::get_settings( 'apply_coupon_title', null );
		if ( empty( $coupon_code ) ) {
			return null;
		}
		$discounted_details = discount_deals_apply_cart_discount();
		if ( empty( $discounted_details['discounts'] ) && WC()->cart->has_discount( $coupon_code ) ) {
			WC()->cart->remove_coupon( $coupon_code );

			return null;
		} elseif ( empty( $discounted_details['discounts'] ) ) {
			return null;
		}

		$sum_of_discounts = array_sum( $discounted_details['discounts'] );
		if ( $sum_of_discounts > 0 && WC()->cart->has_discount( $coupon_code ) ) {
			return null;
		}
		if ( $sum_of_discounts <= 0 && WC()->cart->has_discount( $coupon_code ) ) {
			WC()->cart->remove_coupon( $coupon_code );
		}

		WC()->cart->apply_coupon( $coupon_code );

		return null;
	}

	/**
	 * Apply discount as fee.
	 *
	 * @param WC_Cart $cart Cart object.
	 *
	 * @return null
	 */
	public function apply_cart_discount_as_fee( $cart ) {
		if ( $cart->is_empty() ) {
			return null;
		}
		$fee_title = Discount_Deals_Settings::get_settings( 'apply_fee_title', 'Fee Title' );
		if ( empty( $fee_title ) ) {
			return null;
		}
		$discounted_details = discount_deals_apply_cart_discount();
		if ( empty( $discounted_details['discounts'] ) ) {
			return null;
		}
		$sum_of_discounts = array_sum( $discounted_details['discounts'] );
		if ( $sum_of_discounts > 0 ) {
			$cart->add_fee( $fee_title, $sum_of_discounts * - 1 );
		}

		return null;
	}

	/**
	 * Set coupon amount.
	 *
	 * @param float $amount Amount.
	 * @param WC_Coupon $coupon Coupon object.
	 *
	 * @return float
	 */
	public function set_coupon_amount( $amount, $coupon ) {
		$applying_coupon_code = $coupon->get_code();
		$coupon_code          = Discount_Deals_Settings::get_settings( 'apply_coupon_title', null );

		if ( strtolower( $coupon_code ) == strtolower( $applying_coupon_code ) ) {
			$discounted_details = discount_deals_apply_cart_discount();

			if ( empty( $discounted_details['discounts'] ) ) {
				return 0;
			}

			return array_sum( $discounted_details['discounts'] );
		}

		return $amount;
	}

	/**
	 * Set coupon type.
	 *
	 * @param string $discount_type Type.
	 * @param WC_Coupon $coupon Coupon object.
	 *
	 * @return string
	 */
	public function set_coupon_discount_type( $discount_type, $coupon ) {
		$applying_coupon_code = $coupon->get_code();
		$coupon_code          = Discount_Deals_Settings::get_settings( 'apply_coupon_title', null );
		if ( strtolower( $coupon_code ) == strtolower( $applying_coupon_code ) ) {
			return 'fixed_cart';
		}

		return $discount_type;
	}

	/**
	 * Hide remove option in applied coupon.
	 *
	 * @param string $coupon_html Coupon html.
	 * @param WC_Coupon $coupon Coupon object.
	 * @param string $discount_amount_html Amount html.
	 *
	 * @return string
	 */
	public function hide_remove_coupon( $coupon_html, $coupon, $discount_amount_html ) {
		$applying_coupon_code = $coupon->get_code();
		$coupon_code          = Discount_Deals_Settings::get_settings( 'apply_coupon_title', null );

		if ( strtolower( $coupon_code ) == strtolower( $applying_coupon_code ) ) {
			return $discount_amount_html;
		}

		return $coupon_html;
	}


}//end class

