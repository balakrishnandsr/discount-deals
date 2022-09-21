<?php
/**
 * Provide interface for adding discounts to workflow
 *
 * @package    Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Variable declaration
 *
 * @var array $discount_details Discount details.
 */

if ( empty( $discount_details ) ) {
	$discount_details = array(
		array(
			'min_subtotal' => '',
			'max_subtotal' => '',
			'type'         => 'free_shipping',
			'value'        => '',
			'max_discount' => '',
		)
	);
}

?>
<table class="cart-discount-details-table discount-deals-fw-table">
	<thead class="discount-deals-text-left">
	<tr>
		<th><?php esc_html_e( 'Min Subtotal', 'discount-deals' ); ?></th>
		<th><?php esc_html_e( 'Max Subtotal', 'discount-deals' ); ?></th>
		<th><?php esc_html_e( 'Discount Type', 'discount-deals' ); ?></th>
		<th><?php esc_html_e( 'Discount Value', 'discount-deals' ); ?></th>
		<th><?php esc_html_e( 'Discount Limit', 'discount-deals' ); ?></th>
		<th></th>
	</tr>
	</thead>
	<tbody>
	<?php
	$count = 1;
	foreach ( $discount_details as $discount_detail ) {
		?>
		<tr>
			<td>
				<div class="discount-deals-input-group suffix">
					<input type="number"
						   value="<?php echo esc_attr(discount_deals_get_value_from_array( $discount_detail, 'min_subtotal', '' )); ?>"
						   required step="0.1"
						   name="discount_deals_workflow[dd_discounts][<?php echo esc_attr($count); ?>][min_subtotal]"
						   data-name="discount_deals_workflow[dd_discounts][--rule_id--][min_subtotal]"
						   placeholder="<?php esc_html_e( 'E.g. 5000.00', 'discount-deals' ); ?>">
					<span class="input-group-addon "><?php echo esc_attr(get_woocommerce_currency_symbol()); ?></span>
				</div>
			</td>
			<td>
				<div class="discount-deals-input-group suffix">
					<input type="number"
						   value="<?php echo esc_attr(discount_deals_get_value_from_array( $discount_detail, 'max_subtotal', '' )); ?>"
						   required step="0.1"
						   name="discount_deals_workflow[dd_discounts][<?php echo esc_attr($count); ?>][max_subtotal]"
						   data-name="discount_deals_workflow[dd_discounts][--rule_id--][max_subtotal]"
						   placeholder="<?php esc_html_e( 'E.g. 8000.00', 'discount-deals' ); ?>">
					<span class="input-group-addon "><?php echo esc_attr(get_woocommerce_currency_symbol()); ?></span>
				</div>
			</td>
			<td>
				<select name="discount_deals_workflow[dd_discounts][<?php echo esc_attr($count); ?>][type]"
						class="discount-deals-w150 cart-discount-type"
						data-default-val="free_shipping"
						data-name="discount_deals_workflow[dd_discounts][--rule_id--][type]">
					<option value="free_shipping" 
					<?php 
					if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'free_shipping' ) {
						echo ' selected';
					} 
					?>
					><?php esc_html_e( 'Free Shipping', 'discount-deals' ); ?></option>
					<option value="flat" 
					<?php 
					if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'flat' ) {
						echo ' selected';
					} 
					?>
					><?php esc_html_e( 'Fixed Discount', 'discount-deals' ); ?></option>
					<option value="percent" 
					<?php 
					if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'percent' ) {
						echo ' selected';
					} 
					?>
					><?php esc_html_e( 'Percentage Discount', 'discount-deals' ); ?></option>
				</select>
			</td>
			<td>
				<div class="discount-deals-input-group suffix">
					<input type="number"
						<?php 
						if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'free_shipping' ) {
							echo ' disabled ';
						} 
						?>
						   value="<?php echo esc_attr(discount_deals_get_value_from_array( $discount_detail, 'value', '' )); ?>"
						   class="cart-discount-value" required step="0.1"
						   name="discount_deals_workflow[dd_discounts][<?php echo esc_attr($count); ?>][value]"
						   data-name="discount_deals_workflow[dd_discounts][--rule_id--][value]"
						   placeholder="<?php esc_html_e( 'E.g. 50', 'discount-deals' ); ?>"
						   class="discount-value-symbol">
					<span class="input-group-addon discount-value-symbol"
						  data-currency="<?php echo esc_attr(get_woocommerce_currency_symbol()); ?>">
													<?php
													if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'flat' ) {
														echo esc_attr(get_woocommerce_currency_symbol());
													} else if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'percent' ) {
														echo '%';
													}
													?>
					</span>
				</div>
			</td>
			<td>
				<div class="discount-deals-input-group suffix">
					<input type="number"
						<?php 
						if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'free_shipping' || discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'flat' ) {
							echo ' disabled ';
						} 
						?>
						   value="<?php echo esc_attr(discount_deals_get_value_from_array( $discount_detail, 'max_discount', '' )); ?>"
						   class="cart-discount-value cart-max-discount" step="0.1"
						   name="discount_deals_workflow[dd_discounts][<?php echo esc_attr($count); ?>][max_discount]"
						   data-name="discount_deals_workflow[dd_discounts][--rule_id--][max_discount]"
						   placeholder="<?php esc_html_e( 'E.g. 20.00', 'discount-deals' ); ?>">
					<span class="input-group-addon "><?php echo esc_attr(get_woocommerce_currency_symbol()); ?></span>
				</div>
			</td>
			<td>
				<button type="button"
						class="discount-deals-remove-cart-discount button discount-deals-cart-discount__remove 
						<?php 
						if ( $count <= 1 ) {
							echo 'discount-deals-hidden';
						} 
						?>
						">
					X
				</button>
			</td>
		</tr>
		<?php
		$count ++;
	}
	?>
	</tbody>
	<tfoot>
	<tr>
		<td colspan="6" class="discount-deals-text-right">
			<button type="button" class="discount-deals-add-cart-discount button button-primary button-large">
				<?php esc_html_e( '+ Add Discount Range', 'discount-deals' ); ?>
			</button>
		</td>
	</tr>
	<tr>
		<td colspan="6">
			<div class="discount-deals-notice">
				<div>
					<strong><?php esc_html_e( 'Important notes:', 'discount-deals' ); ?></strong>
				</div>
				<ol>
					<li>
						<?php esc_html_e( 'When calculating the discount, the subtotal of the shopping cart will be taken into account in order to compare it with the "discount ranges" given above.', 'discount-deals' ); ?>
					</li>
					<li>
						<?php esc_html_e( 'If your discount type is "Percentage Discount", then you can limit the discount for that cart to a certain amount.', 'discount-deals' ); ?>
					</li>
				</ol>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="6" class="discount-deals-text-left">
			<p>
				<b><?php esc_html_e( 'How it Works?', 'discount-deals' ); ?></b><?php esc_html_e( ' Create multiple discount ranges by specifying the minimum cart subtotal, maximum cart subtotal, and discount details. If the cart subtotal matches one of the discount ranges, the discount will be applied to that cart accordingly. In the settings you can specify the mode for applying the discount. By default, the discount is applied as a fee. However, we recommend you to change the mode to Coupon.', 'discount-deals' ); ?>
			</p>
			<b><?php esc_html_e( 'Example: ', 'discount-deals' ); ?></b>
			<ol>
				<li><?php esc_html_e( 'You can give free shipping for the cart whose subtotal is between 100$ and 500$. ', 'discount-deals' ); ?></li>
				<li><?php esc_html_e( 'You can give a fixed discount of 20$ for the shopping cart if the subtotal of the shopping cart is between 500$ and 1000$. ', 'discount-deals' ); ?></li>
				<li><?php esc_html_e( 'You can also apply a 25% discount to the cart if the cart subtotal is over $1500. You can also limit the discount on products by entering the maximum discount value. ', 'discount-deals' ); ?></li>
			</ol>
		</td>
	</tr>
	</tfoot>
</table>
