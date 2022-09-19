<?php
/**
 * Provide a product promotional messages view
 *
 * @package    Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Variable declaration
 *
 * @var array $all_promotions all promotional messages.
 * @var WC_Product $product product object.
 * @var string $position product object.
 * @var array $product_discounts product object.
 * @var Discount_Deals_Public $this public class.
 */

if ( ! empty( $all_promotions ) ) {
	global $product;

	if ( ! empty( $all_promotions['bulk_promotions'] ) ) {
		/**
		 * Filter to modify show/hide bulk table summary.
		 *
		 * @since 1.0.0
		 */
		$show_table_summary = apply_filters( 'discount_deals_show_bulk_table_summary', true, $product, $this );
		$items_in_cart      = $this->get_quantities_in_cart( $product );
		$bulk_promotions    = $all_promotions['bulk_promotions'];
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
				<th><?php esc_html_e( 'Qty', 'discount-deals' ); ?></th>
				<th><?php esc_html_e( 'Discount', 'discount-deals' ); ?></th>
				<th><?php esc_html_e( 'Price Per Unit', 'discount-deals' ); ?></th>
			</tr>
			</thead>
			<tbody>
			<?php
			foreach ( $bulk_promotions as $range ) {
				$min_quantity = discount_deals_get_value_from_array( $range, 'min_quantity', 0 );
				$max_quantity = discount_deals_get_value_from_array( $range, 'max_quantity', 999999999 );
				$discount_type         = discount_deals_get_value_from_array( $range, 'type', 'flat' );
				$value        = discount_deals_get_value_from_array( $range, 'value', 0 );
				$max_discount = discount_deals_get_value_from_array( $range, 'max_discount', 0 );
				if ( 'flat' == $discount_type ) {
					if ( $value > $price ) {
						$discount_value = wc_price( $price );
					} else {
						$discount_value = wc_price( $value );
					}
				} else {
					$discount_value = $value . '%';
				}
				$discount_price = discount_deals_get_product_discount( $price, $product, $min_quantity, false );
				?>
				<tr>
					<td><?php echo wp_kses_post( $min_quantity . '-' . $max_quantity ); ?></td>
					<td><?php echo wp_kses_post( $discount_value ); ?></td>
					<td><?php echo wp_kses_post( ( 0 < $discount_price ) ? wc_price( $discount_price ) : wc_price( 0 ) ); ?></td>
				</tr>
				<?php
			}
			?>
			</tbody>
			<?php
			if ( $show_table_summary ) {
				?>
				<tfoot>
				<tr>
					<th colspan="3"><?php esc_html_e( 'Summary', 'discount-deals' ); ?></th>
				</tr>
				<tr>
					<th colspan="2">
						<div class="dd-bulk-table-summary-quantity">
							<?php
							$new_quantity       = $items_in_cart + 1;
							$new_discount_price = discount_deals_get_product_discount( $price, $product, $new_quantity, false );
							echo wp_kses_post( $new_quantity . ' &times; ' . wc_price( $new_discount_price ) );
							?>
						</div>
						<small class="dd-bulk-table-summary-quantity-in-cart">
							<?php
							if ( 0 < $items_in_cart ) {
								/**
								 * Filter to modify the bulk table item quantity count in cart.
								 *
								 * @since 1.0.0
								 */
								$items_in_cart_summary = apply_filters( 'discount_deals_bulk_table_summary_items_in_cart_text', sprintf( '%s %d %s %d %s', __( 'Of', 'discount-deals' ), $new_quantity, __( 'quantities, ', 'discount-deals' ), $items_in_cart, __( 'quantities were already in the shopping cart.', 'discount-deals' ) ), $new_quantity, $items_in_cart, $product, $this );
								echo wp_kses_post( $items_in_cart_summary );
							}
							?>
						</small>
					</th>
					<th>
						<div class="dd-bulk-table-summary-total">
							<?php
							echo wp_kses_post( wc_price( $new_quantity * $new_discount_price ) );
							?>
						</div>
					</th>
				</tr>
				</tfoot>
				<?php
			}
			?>
		</table>
		<?php
	}
	if ( ! empty( $all_promotions['promotion_messages'] ) ) {
		echo wp_kses_post( implode( '<br />', $all_promotions['promotion_messages'] ) );
	}
}
