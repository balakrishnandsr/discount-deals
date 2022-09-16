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
 * @var array $product_discounts product object.
 */
if ( ! empty( $all_promotions ) ) {
	global $product;

	if ( ! empty( $all_promotions['bulk_promotions'] ) ) {
		$bulk_promotions = $all_promotions['bulk_promotions'];
		usort( $bulk_promotions, 'discount_deals_arrange_discounts_by_quantity_range' );
		$calculate_discount_from = Discount_Deals_Settings::get_settings( 'calculate_discount_from', 'sale_price' );
		$product_id              = $product->get_id();
		if ( 'regular_price' === $calculate_discount_from ) {
			$price = $product->get_regular_price();
		} else {
			if ( array_key_exists( $product_id, $product_discounts ) ) {
				$price = $product_discounts[ $product_id ]['price_before_discount'];
			} else {
				$price = $product->get_sale_price();
			}
		}
		?>
        <table>
            <thead>
            <tr>
                <th><?php esc_html_e( 'Qty', 'discount-deals' ) ?></th>
                <th><?php esc_html_e( 'Discount', 'discount-deals' ) ?></th>
                <th><?php esc_html_e( 'Price Per Unit', 'discount-deals' ) ?></th>
            </tr>
            </thead>
            <tbody>
			<?php
			foreach ( $bulk_promotions as $range ) {
				$min_quantity = discount_deals_get_value_from_array( $range, 'min_quantity', 0 );
				$max_quantity = discount_deals_get_value_from_array( $range, 'max_quantity', 999999999 );
				$type         = discount_deals_get_value_from_array( $range, 'type', 'flat' );
				$value        = discount_deals_get_value_from_array( $range, 'value', 0 );
				$max_discount = discount_deals_get_value_from_array( $range, 'max_discount', 0 );
				if ( "flat" == $type ) {
					if ( $value > $price ) {
						$discount_value = wc_price( $price );
					} else {
						$discount_value = wc_price( $value );
					}
					$discount_price = $price - $value;
				} else {
					$discount_value = $value . '%';
					if ( empty( $value ) ) {
						$discount = $price;
					} elseif ( 100 < $value ) {
						$discount = 0;
					} else {
						$discount = $price * ( $value / 100 );
					}
					if ( 0 < floatval( $max_discount ) ) {
						$discount = min( floatval( $max_discount ), $discount );
					}
					$discount_price = $price - $discount;
				}
				?>
                <tr>
                    <td><?php echo $min_quantity . '-' . $max_quantity ?></td>
                    <td><?php echo $discount_value; ?></td>
                    <td><?php echo ( 0 < $discount_price ) ? wc_price( $discount_price ) : wc_price( 0 ) ?></td>
                </tr>
				<?php
			}
			?>
            </tbody>
        </table>
		<?php
	}
	if ( ! empty( $all_promotions['promotion_messages'] ) ) {
		echo implode( '<br />', $all_promotions['promotion_messages'] );
	}
}