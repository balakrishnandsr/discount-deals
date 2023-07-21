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
 * @var array $discount_details Configure Customer Discounts.
 */

if ( empty( $discount_details ) ) {
	$discount_details = array(
		array(
			'min_price'    => '',
			'max_price'    => '',
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
        <th><?php esc_html_e( 'Min Price', 'discount-deals' ); ?></th>
        <th><?php esc_html_e( 'Max Price', 'discount-deals' ); ?></th>
        <th><?php esc_html_e( 'Discount Type', 'discount-deals' ); ?></th>
        <th><?php esc_html_e( 'Discount Value', 'discount-deals' ); ?></th>
        <th><?php esc_html_e( 'Maximum Discount', 'discount-deals' ); ?></th>
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
                           value="<?php echo esc_attr( discount_deals_get_value_from_array( $discount_detail, 'min_price', '' ) ); ?>"
                           required step="0.1"
                           name="discount_deals_workflow[dd_discounts][<?php echo esc_attr( $count ); ?>][min_price]"
                           data-name="discount_deals_workflow[dd_discounts][--rule_id--][min_price]"
                           placeholder="<?php esc_html_e( 'E.g. 5000.00', 'discount-deals' ); ?>">
                    <span class="input-group-addon "><?php echo esc_attr( get_woocommerce_currency_symbol() ); ?></span>
                </div>
            </td>
            <td>
                <div class="discount-deals-input-group suffix">
                    <input type="number"
                           value="<?php echo esc_attr( discount_deals_get_value_from_array( $discount_detail, 'max_price', '' ) ); ?>"
                           required step="0.1"
                           name="discount_deals_workflow[dd_discounts][<?php echo esc_attr( $count ); ?>][max_price]"
                           data-name="discount_deals_workflow[dd_discounts][--rule_id--][max_price]"
                           placeholder="<?php esc_html_e( 'E.g. 8000.00', 'discount-deals' ); ?>">
                    <span class="input-group-addon "><?php echo esc_attr( get_woocommerce_currency_symbol() ); ?></span>
                </div>
            </td>
            <td>
                <select name="discount_deals_workflow[dd_discounts][<?php echo esc_attr( $count ); ?>][type]"
                        class="discount-deals-w150 cart-discount-type"
                        data-default-val="flat"
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
						<?php
						if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'free_shipping' ) {
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
					<?php esc_html_e( 'When customers visit your online store and add products to their cart, the Product Price Based Discount feature will automatically kick in. The plugin will identify the product\'s price and apply the corresponding discount based on the price range it falls into and the discount rules you set.', 'discount-deals' ); ?>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="6" class="discount-deals-text-left">
            <h3><?php esc_html_e( 'How To Configure?', 'discount-deals' ); ?></h3>
            <ul>
                <li>
                    <b><?php esc_html_e( 'Define Price Ranges:', 'discount-deals' ); ?></b> <?php esc_html_e( 'Here, you can enter specific price ranges for which you want to offer discounts. For example, you can create a discount for products priced between $10 and $50, another discount for products priced between $51 and $500, and yet another for products priced between $501 and $1000.', 'discount-deals' ); ?>
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
                    <b><?php esc_html_e( 'Maximum Applicable Discount (for Percentage Discounts):', 'discount-deals' ); ?></b> <?php esc_html_e( 'If you select a percentage discount, you can set a maximum limit to control the discount amount. For instance, if the calculated percentage discount exceeds $75, the discount will be capped at $75 for that specific price range.', 'discount-deals' ); ?>
                </li>
            </ul>
            <h3><?php esc_html_e( 'Example Scenario:', 'discount-deals' ); ?></h3>
            <div>
                <p><?php esc_html_e( 'For instance, let\'s say you have set up a discount of 2% for products priced between $10 and $50, $10 off for products priced between $51 and $500, and 10% (with a maximum discount of $75) for products priced between $501 and $1000.', 'discount-deals' ); ?></p>
                <p><?php esc_html_e( 'If a customer adds a product priced at $35 to their cart, the plugin will calculate a 2% discount of $0.70 and display the new discounted price. If another customer adds a product priced at $150 to their cart, the plugin will apply a fixed discount of $10, resulting in a new discounted price.', 'discount-deals' ); ?></p>
                <p><?php esc_html_e( 'This way, you can offer personalized and attractive discounts to your customers based on the price of the products they purchase, enhancing their shopping experience on your WooCommerce store.', 'discount-deals' ); ?></p>
            </div>
        </td>
    </tr>
    </tfoot>
</table>
