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
			'min_quantity' => '',
			'max_quantity' => '',
			'type'         => 'flat',
			'value'        => '',
			'max_discount' => '',
		),
	);
}

?>
<table class="cart-discount-details-table discount-deals-fw-table">
	<thead class="discount-deals-text-left">
	<tr>
		<th><?php esc_html_e( 'Minimum Quantity', 'discount-deals' ); ?></th>
		<th><?php esc_html_e( 'Maximum Quantity', 'discount-deals' ); ?></th>
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
						   value="<?php echo esc_attr( discount_deals_get_value_from_array( $discount_detail, 'min_quantity', '' ) ); ?>"
						   required name="discount_deals_workflow[dd_discounts][<?php echo esc_attr( $count ); ?>][min_quantity]"
						   data-name="discount_deals_workflow[dd_discounts][--rule_id--][min_quantity]"
						   placeholder="<?php esc_html_e( 'E.g. 1', 'discount-deals' ); ?>">
					<span class="input-group-addon "></span>
				</div>
			</td>
			<td>
				<div class="discount-deals-input-group suffix">
					<input type="number"
						   value="<?php echo esc_attr( discount_deals_get_value_from_array( $discount_detail, 'max_quantity', '' ) ); ?>"
						   required name="discount_deals_workflow[dd_discounts][<?php echo esc_attr( $count ); ?>][max_quantity]"
						   data-name="discount_deals_workflow[dd_discounts][--rule_id--][max_quantity]"
						   placeholder="<?php esc_html_e( 'E.g. 10', 'discount-deals' ); ?>">
					<span class="input-group-addon "></span>
				</div>
			</td>
			<td>
				<select name="discount_deals_workflow[dd_discounts][<?php echo esc_attr( $count ); ?>][type]"
						data-default-val="flat"
						class="discount-deals-w150 cart-discount-type"
						data-name="discount_deals_workflow[dd_discounts][--rule_id--][type]">
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
						   value="<?php echo esc_attr( discount_deals_get_value_from_array( $discount_detail, 'value', '' ) ); ?>"
						   class="cart-discount-value" required step="0.1"
						   name="discount_deals_workflow[dd_discounts][<?php echo esc_attr( $count ); ?>][value]"
						   data-name="discount_deals_workflow[dd_discounts][--rule_id--][value]"
						   placeholder="<?php esc_html_e( 'E.g. 50', 'discount-deals' ); ?>">
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
						if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'flat' ) {
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
					<?php esc_html_e( 'As customers shop on your online store and add products to their cart, the Product Quantity Based Discount feature will automatically take effect. The plugin will recognize the quantity of each product added to the cart and apply the relevant discount based on the quantity ranges and rules you set.', 'discount-deals' ); ?>
				</div>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="6" class="discount-deals-text-left">
			<h3><?php esc_html_e( 'How To Configure?', 'discount-deals' ); ?></h3>
			<ul>
				<li>
					<b><?php esc_html_e( 'Define Quantity Ranges:', 'discount-deals' ); ?></b> <?php esc_html_e( 'Here, you can specify the quantity ranges for which you want to apply discounts. For instance, you can create a discount for products with 1 to 2 quantities, another discount for products with 3 to 5 quantities, and yet another for products with 6 to 20 quantities.', 'discount-deals' ); ?>
				</li>
				<li>
					<b><?php esc_html_e( 'Choose Discount Type:', 'discount-deals' ); ?></b> <?php esc_html_e( 'Next, you can choose the type of discount you want to apply. You have two options:', 'discount-deals' ); ?>
					<ol>
						<li><?php esc_html_e( 'Fixed Discount: Set a fixed amount to be deducted from the original product price.', 'discount-deals' ); ?></li>
						<li><?php esc_html_e( 'Percentage Discount: Apply a percentage reduction based on the original product price.', 'discount-deals' ); ?></li>
					</ol>
				</li>
				<li>
					<b><?php esc_html_e( 'Specify Discount Value:', 'discount-deals' ); ?></b> <?php esc_html_e( 'Based on your chosen discount type, enter the appropriate value. For a fixed discount, enter the amount to be deducted (e.g., $10 off). For a percentage discount, enter the percentage to be discounted (e.g., 5% off).', 'discount-deals' ); ?>
				</li>
				<li>
					<b><?php esc_html_e( 'Maximum Applicable Discount (for Percentage Discounts):', 'discount-deals' ); ?></b> <?php esc_html_e( 'If you opt for a percentage discount, you can set a maximum limit to control the discount amount. For example, if the calculated percentage discount exceeds $5, the discount will be capped at $5 for that specific quantity range.', 'discount-deals' ); ?>
				</li>
			</ul>
			<h3><?php esc_html_e( 'Example Scenario:', 'discount-deals' ); ?></h3>
			<div>
				<p><?php esc_html_e( 'For example, let\'s say you have set up a discount of 2% for products with 1 to 2 quantities, $10 off for products with 3 to 5 quantities, and 10% (with a maximum discount of $5) for products with 6 to 20 quantities.', 'discount-deals' ); ?></p>
				<p><?php esc_html_e( 'If a customer adds two units of a product to their cart, the plugin will calculate a 2% discount based on the product\'s original price and apply it to the cart. If another customer adds four units of a product to their cart, the plugin will apply a fixed discount of $10, resulting in a new discounted price.', 'discount-deals' ); ?></p>
				<p><?php esc_html_e( 'By offering bulk discounts based on the quantity of products purchased, you can encourage customers to buy more, boost sales, and enhance the shopping experience on your WooCommerce store.', 'discount-deals' ); ?></p>
			</div>
		</td>
	</tr>
	</tfoot>
</table>
