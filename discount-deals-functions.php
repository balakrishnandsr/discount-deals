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
	function discount_deals_get_data( $key, $default = null ) {
		if ( empty( $key ) ) {
			wc_clean( wp_unslash( $_GET ) );
		}
		if ( isset( $_GET[ $key ] ) ) {
			return wc_clean( wp_unslash( $_GET[ $key ] ) );
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
	function discount_deals_get_request_data( $key, $default = null ) {
		if ( empty( $key ) ) {
			wc_clean( wp_unslash( $_REQUEST ) );
		}
		if ( isset( $_REQUEST[ $key ] ) ) {
			return wc_clean( wp_unslash( $_REQUEST[ $key ] ) );
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
	function discount_deals_get_post_data( $key, $default = null ) {
		return wc_get_post_data_by_key( $key, $default );
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
	 * Convert a date object to an instance of Discount_Deals_Date_Time.
	 *
	 * WC_Datetime objects are converted to UTC timezone.
	 *
	 * @param WC_DateTime|DateTime|Discount_Deals_Date_Time|string $input Date to normalize.
	 *
	 * @return Discount_Deals_Date_Time|false
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
	}//end discount_deals_normalize_date()
}

if ( ! function_exists( 'discount_deals_get_product_discount' ) ) {
	/**
	 * Calculate the discount for the product.
	 *
	 * @param float      $price                  Product price.
	 * @param WC_Product $product                Product object.
	 * @param integer    $quantity               Product quantity.
	 * @param boolean    $validate_against_rules Need to validate against rules.
	 *
	 * @return float|integer
	 */
	function discount_deals_get_product_discount( $price, $product, $quantity = 1, $validate_against_rules = true ) {
		$discounted_price = Discount_Deals_Workflows::calculate_product_discount( $price, $product, $quantity, $validate_against_rules );
		if ( 0 >= $discounted_price ) {
			$discounted_price = 0;
		}

		return $discounted_price;
	}//end discount_deals_get_product_discount()
}

if ( ! function_exists( 'discount_deals_get_bogo_discount' ) ) {
	/**
	 * Calculate the discount for the product.
	 *
	 * @param WC_Product $product  Product object.
	 * @param integer    $quantity Product quantity.
	 *
	 * @return array
	 */
	function discount_deals_get_bogo_discount( $product, $quantity = 1 ) {
		return Discount_Deals_Workflows::calculate_bogo_discount( $product, $quantity );
	}//end discount_deals_get_bogo_discount()
}


if ( ! function_exists( 'discount_deals_get_value_from_array' ) ) {
	/**
	 * Get value from array
	 *
	 * @param array   $array         Array.
	 * @param string  $key           Array key.
	 * @param mixed   $default_value What value should return when the key is not found.
	 * @param boolean $clean         Do we need to clean the output.
	 *
	 * @return mixed
	 */
	function discount_deals_get_value_from_array( $array, $key, $default_value = null, $clean = true ) {
		if ( is_array( $array ) && array_key_exists( $key, $array ) ) {
			if ( $clean ) {
				return wc_clean( wp_unslash( $array[ $key ] ) );
			}

			return $array[ $key ];
		}

		return $default_value;
	}//end discount_deals_get_value_from_array()
}

if ( ! function_exists( 'discount_deals_get_cart_discount' ) ) {
	/**
	 * Calculate discount for cart.
	 *
	 * @return array
	 */
	function discount_deals_get_cart_discount() {
		return Discount_Deals_Workflows::calculate_cart_discount();
	}//end discount_deals_get_cart_discount()
}

if ( ! function_exists( 'discount_deals_get_applied_workflows' ) ) {
	/**
	 * Get applied workflows
	 *
	 * @return Discount_Deals_Workflow[]
	 */
	function discount_deals_get_applied_workflows() {
		return Discount_Deals_Workflows::get_applied_workflows();
	}//end discount_deals_get_applied_workflows()
}

if ( ! function_exists( 'discount_deals_get_applied_workflow_discounts' ) ) {
	/**
	 * Get applied workflows
	 *
	 * @return array
	 */
	function discount_deals_get_applied_workflow_discounts() {
		return Discount_Deals_Workflows::get_applied_workflow_discounts();
	}//end discount_deals_get_applied_workflow_discounts()
}

if ( ! function_exists( 'discount_deals_get_all_categories' ) ) {
	/**
	 * Get all categories of the shop
	 *
	 * @return array
	 */
	function discount_deals_get_all_categories() {
		$list = array();

		$categories = get_terms(
			'product_cat',
			array(
				'orderby'    => 'name',
				'hide_empty' => false,
			)
		);

		foreach ( $categories as $category ) {
			$list[ $category->term_id ] = $category->name;
		}

		return $list;
	}//end discount_deals_get_all_categories()
}

if ( ! function_exists( 'discount_deals_get_all_tags' ) ) {
	/**
	 * Get all tags of the shop
	 *
	 * @return array
	 */
	function discount_deals_get_all_tags() {
		$list = array();

		$terms = get_terms(
			'product_tag',
			array(
				'orderby'    => 'name',
				'hide_empty' => false,
			)
		);

		foreach ( $terms as $term ) {
			$list[ $term->term_id ] = $term->name;
		}

		return $list;
	}//end discount_deals_get_all_tags()
}

if ( ! function_exists( 'discount_deals_search_coupons' ) ) {
	/**
	 * Search coupons
	 *
	 * @param string  $term                 Term.
	 * @param boolean $exclude_personalized Exclude personalized.
	 *
	 * @return array
	 */
	function discount_deals_search_coupons( $term, $exclude_personalized ) {
		$args = array(
			'post_type'      => 'shop_coupon',
			'posts_per_page' => 50,
			'no_found_rows'  => true,
			'meta_query'     => array(),
			's'              => $term,
		);

		if ( $exclude_personalized ) {
			$args['meta_query'][] = array(
				'key'     => '_is_discount_deals_coupon',
				'compare' => 'NOT EXISTS',
			);
		}

		$query   = new \WP_Query( $args );
		$results = array();

		foreach ( $query->posts as $coupon ) {
			$code             = wc_format_coupon_code( $coupon->post_title );
			$results[ $code ] = $code;
		}

		return $results;
	}//end discount_deals_search_coupons()
}
if ( ! function_exists( 'discount_deals_get_counted_order_statuses' ) ) {
	/**
	 * Get counted order statuses
	 *
	 * @param boolean $include_prefix Include prefix in status.
	 *
	 * @return array|mixed|string[]|void
	 */
	function discount_deals_get_counted_order_statuses( $include_prefix = true ) {
		$default_statuses = array_merge( wc_get_is_paid_statuses(), array( 'on-hold' ) );

		/**
		 * Filter to modify counted order statuses.
		 *
		 * @since 1.0.0
		 */
		$statuses         = array_filter( apply_filters( 'discount_deals_counted_order_statuses', $default_statuses ) );

		if ( ! $statuses ) {
			$statuses = $default_statuses;
		}

		if ( $include_prefix ) {
			$statuses = array_map( 'discount_deals_add_order_status_prefix', $statuses );
		}

		return $statuses;
	}//end discount_deals_get_counted_order_statuses()
}
if ( ! function_exists( 'discount_deals_add_order_status_prefix' ) ) {
	/**
	 * Add prifix to order status
	 *
	 * @param string $status Order status.
	 *
	 * @return string
	 */
	function discount_deals_add_order_status_prefix( $status ) {
		return 'wc-' . $status;
	}//end discount_deals_add_order_status_prefix()
}

if ( ! function_exists( 'discount_deals_search_coupons' ) ) {
	/**
	 * Get the country name for country code
	 *
	 * @param string $country_code Country code.
	 *
	 * @return false|mixed
	 */
	function discount_deals_get_country_name( $country_code ) {
		$countries = WC()->countries->get_countries();

		return isset( $countries[ $country_code ] ) ? $countries[ $country_code ] : false;
	}//end discount_deals_get_country_name()
}
if ( ! function_exists( 'discount_deals_search_coupons' ) ) {
	/**
	 * Get product and variation ids of all the customers purchased products
	 *
	 * @param WC_Customer $customer Customer object.
	 *
	 * @return array
	 */
	function discount_deals_get_customer_purchased_products( $customer ) {
		global $wpdb;

		if ( ! is_a( $customer, 'WC_Customer' ) ) {
			return array();
		}
		if ( 0 >= $customer->get_id() ) {
			return array();
		}
		$transient_name = 'discount_deals_cpp_' . $customer->get_id();
		$products       = get_transient( $transient_name );
		if ( false === $products ) {
			$customer_data = array( $customer->get_email(), $customer->get_id() );
			$customer_data = array_map( 'esc_sql', array_filter( $customer_data ) );
			$statuses      = array_map( 'esc_sql', discount_deals_get_counted_order_statuses( true ) );
			$result = $wpdb->get_col(
				$wpdb->prepare(
					"SELECT im.meta_value FROM {$wpdb->posts} AS p
				INNER JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id
				INNER JOIN {$wpdb->prefix}woocommerce_order_items AS i ON p.ID = i.order_id
				INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS im ON i.order_item_id = im.order_item_id
				WHERE p.post_status IN ( %s )
				AND pm.meta_key IN ( '_billing_email', '_customer_user' )
				AND im.meta_key IN ( '_product_id', '_variation_id' )
				AND im.meta_value != 0
				AND pm.meta_value IN ( %s )",
					array( implode( "','", $statuses ), implode( "','", $customer_data ) )
				)
			);
			$products = array_unique( array_map( 'absint', $result ) );

			set_transient( $transient_name, $result, DAY_IN_SECONDS * 7 );
		}

		return $products;
	}//end discount_deals_get_customer_purchased_products()
}

if ( ! function_exists( 'discount_deals_get_state_name' ) ) {
	/**
	 * Get the state name for the text
	 *
	 * @param string $country_code Country code.
	 * @param string $state_code   State code.
	 *
	 * @return false|mixed
	 */
	function discount_deals_get_state_name( $country_code, $state_code ) {
		$states = WC()->countries->get_states( $country_code );

		return isset( $states[ $state_code ] ) ? $states[ $state_code ] : false;
	}//end discount_deals_get_state_name()
}
if ( ! function_exists( 'discount_deals_get_user_tags' ) ) {
	/**
	 * Get the user tags
	 *
	 * @return array
	 */
	function discount_deals_get_user_tags() {
		$list = array();

		$tags = get_terms(
			array(
				'taxonomy'   => 'user_tag',
				'hide_empty' => false,
			)
		);

		foreach ( $tags as $tag ) {
			$list[ $tag->term_id ] = $tag->name;
		}

		return $list;
	}//end discount_deals_get_user_tags()
}
if ( ! function_exists( 'discount_deals_arrange_discounts_by_quantity_range' ) ) {
	/**
	 * Re-order discount ranges by quantities
	 *
	 * @param array $range_1 Discount range 1.
	 * @param array $range_2 Discount range 2.
	 *
	 * @return integer
	 */
	function discount_deals_arrange_discounts_by_quantity_range( $range_1, $range_2 ) {
		if ( empty( $range_1 ) || empty( $range_2 ) || ! isset( $range_1['min_quantity'] ) || ! isset( $range_2['min_quantity'] ) ) {
			return 0;
		}
		$min_quantity_1 = intval( $range_1['min_quantity'] );
		$min_quantity_2 = intval( $range_2['min_quantity'] );
		if ( $min_quantity_1 > $min_quantity_2 ) {
			return 1;
		} else if ( $min_quantity_1 < $min_quantity_2 ) {
			return - 1;
		} else {
			return 0;
		}
	}//end discount_deals_arrange_discounts_by_quantity_range()
}

if ( ! function_exists( 'discount_deals_get_cart_subtotal' ) ) {
	/**
	 * Get subtotal of the cart.
	 *
	 * @return float
	 */
	function discount_deals_get_cart_subtotal() {
		$subtotal = WC()->cart->get_subtotal();
		$subtotal_tax = WC()->cart->get_subtotal_tax();
		if ( get_option( 'woocommerce_tax_display_cart' ) != 'excl' ) {
			$subtotal += $subtotal_tax;
		}

		/**
		 * Filter to modify cart subtotal.
		 *
		 * @since 1.0.0
		 */
		return apply_filters( 'discount_deals_cart_subtotal', $subtotal, $subtotal, $subtotal_tax );
	}//end discount_deals_get_cart_subtotal()
}
