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
	 * @param string $key Key of the array.
	 * @param mixed  $default If there is no data then return default value.
	 *
	 * @return mixed|string
	 */
	function discount_deals_get_data( $key, $default ) {
		if ( isset( $_GET[ $key ] ) ) {
			return sanitize_text_field( wp_unslash( $_GET[ $key ] ) );
		}

		return $default;
	}
}
