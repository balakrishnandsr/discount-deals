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
	 * @param bool   $clean Need to clean the output.
	 *
	 * @return mixed|string
	 */
	function discount_deals_get_data( $key, $default = null, $clean = true ) {
		if ( isset( $_GET[ $key ] ) ) {
			$data = wp_unslash( $_GET[ $key ] );
			if ( $clean ) {
				return sanitize_text_field( $data );
			}

			return $data;
		}

		return $default;
	}//end discount_deals_get_data()
}


if ( ! function_exists( 'discount_deals_get_request_data' ) ) {
	/**
	 * Get data from the REQUEST
	 *
	 * @param string $key Key of the array.
	 * @param mixed  $default If there is no data then return default value.
	 * @param bool   $clean Need to clean the output.
	 *
	 * @return mixed|string
	 */
	function discount_deals_get_request_data( $key, $default = null, $clean = true ) {
		if ( isset( $_REQUEST[ $key ] ) ) {
			$data = wp_unslash( $_REQUEST[ $key ] );
			if ( $clean ) {
				return sanitize_text_field( $data );
			}

			return $data;
		}

		return $default;
	}//end discount_deals_get_request_data()
}

if ( ! function_exists( 'discount_deals_get_post_data' ) ) {
	/**
	 * Get data from the POST request
	 *
	 * @param string $key Key of the array.
	 * @param mixed  $default If there is no data then return default value.
	 * @param bool   $clean Need to clean the output.
	 *
	 * @return mixed|string
	 */
	function discount_deals_get_post_data( $key, $default = null, $clean = true ) {
		if ( isset( $_REQUEST[ $key ] ) ) {
			$data = wp_unslash( $_REQUEST[ $key ] );
			if ( $clean ) {
				return sanitize_text_field( $data );
			}

			return $data;
		}

		return $default;
	}//end discount_deals_get_post_data()
}

if ( ! function_exists( 'discount_deals_get_weekday' ) ) {
	/**
	 * Get weekday oof the site
	 *
	 * @param integer $day Weekday number 1-7.
	 *
	 * @return string
	 */
	function discount_deals_get_weekday( $day ) {
		global $wp_locale;

		$days = array(
			1 => $wp_locale->get_weekday( 1 ),
			2 => $wp_locale->get_weekday( 2 ),
			3 => $wp_locale->get_weekday( 3 ),
			4 => $wp_locale->get_weekday( 4 ),
			5 => $wp_locale->get_weekday( 5 ),
			6 => $wp_locale->get_weekday( 6 ),
			7 => $wp_locale->get_weekday( 0 ),
		);

		if ( ! isset( $days[ $day ] ) ) {
			return false;
		}

		return $days[ $day ];
	}//end discount_deals_get_weekday()
}
if ( ! function_exists( 'discount_deals_normalize_date' ) ) {
	/**
	 * Convert a date object to an instance of AutomateWoo\DateTime.
	 *
	 * WC_Datetime objects are converted to UTC timezone.
	 *
	 * @param WC_DateTime|DateTime|Discount_Deals_Date_Time|string $input Date to normalize.
	 *
	 * @return Discount_Deals_Date_Time|false
	 *
	 */
	function discount_deals_normalize_date( $input ) {
		if ( ! $input ) {
			return false;
		}

		try {
			if ( is_numeric( $input ) ) {
				$new = new Discount_Deals_Date_Time();
				$new->setTimestamp( $input );

				return $new;
			}

			if ( is_string( $input ) ) {
				return new Discount_Deals_Date_Time( $input );
			}

			if ( is_a( $input, 'Discount_Deals_Date_Time' ) ) {
				return $input;
			}

			if ( is_a( $input, 'WC_DateTime' ) || is_a( $input, 'DateTime' ) ) {
				$new = new Discount_Deals_Date_Time();
				$new->setTimestamp( $input->getTimestamp() );

				return $new;
			}
		} catch ( Exception $e ) {
			return false;
		}

		return false;
	}
}

if ( ! function_exists( 'discount_deals_get_product_discount' ) ) {
	/**
	 * Calculate the discount for the product.
	 *
	 * @param WC_Product $product Product object.
	 *
	 * @return mixed|string
	 */
	function discount_deals_get_product_discount( $price, $product ) {
        return Discount_Deals_Workflows::calculate_product_discount( $price, $product );
	}//end discount_deals_get_product_discount()
}

