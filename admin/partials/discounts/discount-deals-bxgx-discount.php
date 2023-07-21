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
			'min_quantity'  => '',
			'max_quantity'  => '',
			'free_quantity' => '',
			'type'          => 'free',
			'value'         => '',
			'max_discount'  => '',
		),
	);
}

?>
<table class="cart-discount-details-table discount-deals-fw-table">
	<thead class="discount-deals-text-left">
	<tr>
		<th><?php esc_html_e( 'Minimum Quantity', 'discount-deals' ); ?></th>
		<th><?php esc_html_e( 'Maximum Quantity', 'discount-deals' ); ?></th>
		<th><?php esc_html_e( 'Discount Quantity', 'discount-deals' ); ?></th>
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
				<div class="discount-deals-input-group suffix">
					<input type="number"
						   value="<?php echo esc_attr( discount_deals_get_value_from_array( $discount_detail, 'free_quantity', '' ) ); ?>"
						   required name="discount_deals_workflow[dd_discounts][<?php echo esc_attr( $count ); ?>][free_quantity]"
						   data-name="discount_deals_workflow[dd_discounts][--rule_id--][free_quantity]"
						   placeholder="<?php esc_html_e( 'E.g. 1', 'discount-deals' ); ?>">
					<span class="input-group-addon "></span>
				</div>
			</td>
			<td>
				<select name="discount_deals_workflow[dd_discounts][<?php echo esc_attr( $count ); ?>][type]"
						class="discount-deals-w150 cart-discount-type"
						data-default-val="free"
						data-name="discount_deals_workflow[dd_discounts][--rule_id--][type]">
					<option value="free"
					<?php
					if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'free' ) {
						echo ' selected';
					}
					?>
					><?php esc_html_e( 'Free', 'discount-deals' ); ?></option>
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
						if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'free' ) {
							echo ' disabled ';
						}
						?>
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
						if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'flat' || discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'free' ) {
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
					<?php esc_html_e( 'As customers add qualifying quantities of a product to their cart, the Buy X and Get X Discount feature will automatically apply the BOGO discounts. The plugin will recognize when the conditions for each discount range are met and offer the corresponding discount to the customer\'s cart.', 'discount-deals' ); ?>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="6" class="discount-deals-text-left">
            <h3><?php esc_html_e( 'How To Configure?', 'discount-deals' ); ?></h3>
            <ul>
                <li>
                    <b><?php esc_html_e( 'Define Product Quantity Ranges:', 'discount-deals' ); ?></b> <?php esc_html_e( 'Here, you can set up specific product quantity ranges for which you want to apply the BOGO discounts. For instance, you can create a discount for customers buying 3 to 5 caps, another discount for customers buying 9 to 10 caps, and yet another for customers buying 20 to 100 caps.', 'discount-deals' ); ?>
                </li>
                <li>
                    <b><?php esc_html_e( 'Define Discount Quantity:', 'discount-deals' ); ?></b> <?php esc_html_e( 'For each product quantity range, you\'ll specify how many units of the product the customer needs to buy to qualify for the discount. For example, customers need to buy 2 caps to get the discount when they purchase 3 to 5 caps.', 'discount-deals' ); ?>
                </li>
                <li>
                    <b><?php esc_html_e( 'Choose Discount Type:', 'discount-deals' ); ?></b> <?php esc_html_e( 'Next, you have three discount types to choose from:', 'discount-deals' ); ?>
                    <ol>
                        <li><?php esc_html_e( 'Free: Provide a certain number of free items based on the purchased quantity.', 'discount-deals' ); ?></li>
                        <li><?php esc_html_e( 'Fixed Discount: Set a fixed amount to be deducted from the product price for each eligible set of items.', 'discount-deals' ); ?></li>
                        <li><?php esc_html_e( 'Percentage Discount: Apply a percentage reduction based on the product price for each eligible set of items.', 'discount-deals' ); ?></li>
                    </ol>
                </li>
                <li><b><?php esc_html_e( 'Specify Discount Value (for Fixed and Percentage Discounts):', 'discount-deals' ); ?></b> <?php esc_html_e( 'Depending on your chosen discount type, enter the appropriate value. For a fixed discount, enter the amount to be deducted (e.g., $10 off). For a percentage discount, enter the percentage to be discounted (e.g., 10% off).', 'discount-deals' ); ?></li>
                <li><b><?php esc_html_e( 'Maximum Applicable Discount (for Percentage Discounts):', 'discount-deals' ); ?></b> <?php esc_html_e( 'If you opt for a percentage discount, you can set a maximum limit to control the discount amount. For instance, if the calculated percentage discount exceeds a certain value, the discount will be capped at that value for the eligible set of items.', 'discount-deals' ); ?></li>
            </ul>
            <h3><?php esc_html_e( 'Example Scenario:', 'discount-deals' ); ?></h3>
            <div>
                <p><?php esc_html_e( 'For example, let\'s say you have set up a $10 discount for customers buying 2 caps when they purchase 3 to 5 caps, a 10% discount for customers buying 5 caps when they purchase 9 to 10 caps, and a free 5 caps for customers buying 20 to 100 caps.', 'discount-deals' ); ?></p>
                <p><?php esc_html_e( 'If a customer adds 4 caps to their cart, the plugin will automatically apply a $10 discount, reducing the total price for the 4 caps. If another customer adds 10 caps to their cart, the plugin will apply a 10% discount to 5 of those caps. If a third customer adds 25 caps to their cart, the plugin will automatically add 5 free caps to the order.', 'discount-deals' ); ?></p>
                <p><?php esc_html_e( 'By offering these enticing BOGO discounts, you can incentivize customers to buy more and increase their cart value while creating a more engaging shopping experience on your WooCommerce store.', 'discount-deals' ); ?></p>
            </div>
        </td>
    </tr>

    </tfoot>
</table>
