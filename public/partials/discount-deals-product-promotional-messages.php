<?php
/**
 * Provide a product promotional messages view
 *
 * @package    Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Variable declaration
 *
 * @var array $all_promotions all promotional messages.
 * @var WC_Product $product product object.
 * @var string $position product object.
 */

if ( ! empty( $all_promotions ) ) {
	foreach ( $all_promotions as $promotion ) {
		if ( ! empty( $promotion ) ) {
			echo wp_kses( $promotion, wp_kses_allowed_html( 'post' ) );
			echo "<br />";
		}
	}
}