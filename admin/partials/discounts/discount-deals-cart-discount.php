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
		),
	);
}

?>
<table class="cart-discount-details-table discount-deals-fw-table">
	<thead class="discount-deals-text-left">
	<tr>
		<th><?php esc_html_e( 'Minimum Subtotal', 'discount-deals' ); ?></th>
		<th><?php esc_html_e( 'Maximum Subtotal', 'discount-deals' ); ?></th>
		<th><?php esc_html_e( 'Discount Type', 'discount-deals' ); ?></th>
		<th><?php esc_html_e( 'Discount Value', 'discount-deals' ); ?></th>
		<th><?php esc_html_e( 'Maximum Discount ', 'discount-deals' ); ?></th>
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
						   value="<?php echo esc_attr( discount_deals_get_value_from_array( $discount_detail, 'min_subtotal', '' ) ); ?>"
						   required step="0.1"
						   name="discount_deals_workflow[dd_discounts][<?php echo esc_attr( $count ); ?>][min_subtotal]"
						   data-name="discount_deals_workflow[dd_discounts][--rule_id--][min_subtotal]"
						   placeholder="<?php esc_html_e( 'E.g. 5000.00', 'discount-deals' ); ?>">
					<span class="input-group-addon "><?php echo esc_attr( get_woocommerce_currency_symbol() ); ?></span>
				</div>
			</td>
			<td>
				<div class="discount-deals-input-group suffix">
					<input type="number"
						   value="<?php echo esc_attr( discount_deals_get_value_from_array( $discount_detail, 'max_subtotal', '' ) ); ?>"
						   required step="0.1"
						   name="discount_deals_workflow[dd_discounts][<?php echo esc_attr( $count ); ?>][max_subtotal]"
						   data-name="discount_deals_workflow[dd_discounts][--rule_id--][max_subtotal]"
						   placeholder="<?php esc_html_e( 'E.g. 8000.00', 'discount-deals' ); ?>">
					<span class="input-group-addon "><?php echo esc_attr( get_woocommerce_currency_symbol() ); ?></span>
				</div>
			</td>
			<td>
				<select name="discount_deals_workflow[dd_discounts][<?php echo esc_attr( $count ); ?>][type]"
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
						   value="<?php echo esc_attr( discount_deals_get_value_from_array( $discount_detail, 'value', '' ) ); ?>"
						   class="cart-discount-value" required step="0.1"
						   name="discount_deals_workflow[dd_discounts][<?php echo esc_attr( $count ); ?>][value]"
						   data-name="discount_deals_workflow[dd_discounts][--rule_id--][value]"
						   placeholder="<?php esc_html_e( 'E.g. 50', 'discount-deals' ); ?>"
						   class="discount-value-symbol">
					<span class="input-group-addon discount-value-symbol"
						  data-currency="<?php echo esc_attr( get_woocommerce_currency_symbol() ); ?>">
													<?php
													if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'flat' ) {
														echo esc_attr( get_woocommerce_currency_symbol() );
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
						   value="<?php echo esc_attr( discount_deals_get_value_from_array( $discount_detail, 'max_discount', '' ) ); ?>"
						   class="cart-discount-value cart-max-discount" step="0.1"
						   name="discount_deals_workflow[dd_discounts][<?php echo esc_attr( $count ); ?>][max_discount]"
						   data-name="discount_deals_workflow[dd_discounts][--rule_id--][max_discount]"
						   placeholder="<?php esc_html_e( 'E.g. 20.00', 'discount-deals' ); ?>">
					<span class="input-group-addon "><?php echo esc_attr( get_woocommerce_currency_symbol() ); ?></span>
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
					<strong><?php esc_html_e( 'How It Works for Customers:', 'discount-deals' ); ?></strong>
				</div>
				<br/>
				<div>
					<?php esc_html_e( 'As customers shop on your online store and add items to their cart, the Cart Subtotal Based Discount feature will automatically apply discounts based on the cart subtotal. The plugin will recognize the total value of items in the cart and offer the relevant discount based on the configured subtotal ranges and rules.', 'discount-deals' ); ?>
				</div>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="6" class="discount-deals-text-left">
			<h3><?php esc_html_e( 'How To Configure?', 'discount-deals' ); ?></h3>
			<ul>
				<li>
					<b><?php esc_html_e( 'Define Cart Subtotal Ranges:', 'discount-deals' ); ?></b> <?php esc_html_e( 'Here, you can set up specific cart subtotal ranges for which you want to apply discounts. For instance, you can create a discount for carts with a subtotal between $100 and $500, another discount for carts with a subtotal between $501 and $5000, and yet another for carts with a subtotal between $5001 and $10000.', 'discount-deals' ); ?>
				</li>
				<li>
					<b><?php esc_html_e( 'Choose Discount Type:', 'discount-deals' ); ?></b> <?php esc_html_e( 'Next, you have three discount types to choose from:', 'discount-deals' ); ?>
					<ol>
						<li><?php esc_html_e( 'Free Shipping: Provide free shipping for carts falling into the specified subtotal range.', 'discount-deals' ); ?></li>
						<li><?php esc_html_e( 'Fixed Discount: Set a fixed amount to be deducted from the cart subtotal.', 'discount-deals' ); ?></li>
						<li><?php esc_html_e( 'Percentage Discount: Apply a percentage reduction based on the cart subtotal.', 'discount-deals' ); ?></li>
					</ol>
				</li>
				<li><b><?php esc_html_e( 'Specify Discount Value (for Fixed and Percentage Discounts):', 'discount-deals' ); ?></b> <?php esc_html_e( 'Depending on your chosen discount type, enter the appropriate value. For a fixed discount, enter the amount to be deducted (e.g., $100 off). For a percentage discount, enter the percentage to be discounted (e.g., 10% off).', 'discount-deals' ); ?></li>
				<li><b><?php esc_html_e( 'Maximum Applicable Discount (for Percentage Discounts):', 'discount-deals' ); ?></b> <?php esc_html_e( 'If you select a percentage discount, you can set a maximum limit to control the discount amount. For example, if the calculated percentage discount exceeds $5, the discount will be capped at $5 for that specific cart subtotal range.', 'discount-deals' ); ?></li>
			</ul>
			<h3><?php esc_html_e( 'Example Scenario:', 'discount-deals' ); ?></h3>
			<div>
				<p><?php esc_html_e( 'For example, let\'s say you have set up a free shipping discount for carts with a subtotal between $100 and $500, a $100 discount for carts with a subtotal between $501 and $5000, and a 25% discount (with a maximum discount of $5) for carts with a subtotal between $5001 and $10000.', 'discount-deals' ); ?></p>
				<p><?php esc_html_e( 'If a customer\'s cart subtotal is $120, the plugin will automatically apply free shipping to their order. If another customer\'s cart subtotal is $600, the plugin will deduct $100 from the cart subtotal, resulting in a new discounted subtotal. If a third customer\'s cart subtotal is $7000, the plugin will calculate a 25% discount (capped at $5), and the customer will see the updated discounted subtotal.', 'discount-deals' ); ?></p>
				<p><?php esc_html_e( 'By offering these dynamic discounts based on the cart subtotal, you can incentivize customers to increase their order value and create a more engaging shopping experience on your WooCommerce store.', 'discount-deals' ); ?></p>
			</div>
		</td>
	</tr>
	</tfoot>
</table>
