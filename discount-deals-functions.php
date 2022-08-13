<?php
/**
 * Common function for both storefront and admin
 *
 * @package Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'discount_deals_get_data' ) ) {
	/**
	 * Get data from the GET request
	 *
	 * @param string $key     Key of the array.
	 * @param mixed  $default If there is no data then return default value.
	 *
	 * @return mixed|string
	 */
	function discount_deals_get_data( $key, $default ) {
		if ( isset( $_GET[ $key ] ) ) {
			return sanitize_text_field( wp_unslash( $_GET[ $key ] ) );
		}

		return $default;
	}//end discount_deals_get_data()
}


if ( ! function_exists( 'discount_deals_get_request_data' ) ) {
	/**
	 * Get data from the REQUEST
	 *
	 * @param string $key     Key of the array.
	 * @param mixed  $default If there is no data then return default value.
	 *
	 * @return mixed|string
	 */
	function discount_deals_get_request_data( $key, $default ) {
		if ( isset( $_REQUEST[ $key ] ) ) {
			return sanitize_text_field( wp_unslash( $_REQUEST[ $key ] ) );
		}

		return $default;
	}//end discount_deals_get_request_data()
}

if ( ! function_exists( 'discount_deals_get_post_data' ) ) {
	/**
	 * Get data from the POST request
	 *
	 * @param string $key     Key of the array.
	 * @param mixed  $default If there is no data then return default value.
	 *
	 * @return mixed|string
	 */
	function discount_deals_get_post_data( $key, $default ) {

		if ( isset( $_REQUEST[ $key ] ) ) {
			return sanitize_text_field( wp_unslash( $_REQUEST[ $key ] ) );
		}

		return $default;
	}//end discount_deals_get_post_data()
}

if ( ! function_exists( 'discount_deals_get_product_discount' ) ) {
	/**
	 * Calculate the discount for the product.
	 *
	 * @param WC_Product $product Product object.
	 *
	 * @return mixed|string
	 */
	function discount_deals_get_product_discount( $product ) {
		Discount_Deals_Workflows::calculate_product_discount( $product );
		return 10;
	}//end discount_deals_get_product_discount()
}

