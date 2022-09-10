<?php
/**
 * Provide interface for adding discounts to workflow
 *
 * @package    Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
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
        <th><?php echo __( "Minimum Subtotal", "discount-deals" ); ?></th>
        <th><?php echo __( "Maximum Subtotal", "discount-deals" ); ?></th>
        <th><?php echo __( "Discount Type", "discount-deals" ); ?></th>
        <th><?php echo __( "Discount Value", "discount-deals" ); ?></th>
        <th><?php echo __( "Maximum Discount", "discount-deals" ); ?></th>
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
                           value="<?php echo discount_deals_get_value_from_array( $discount_detail, 'min_subtotal', '' ); ?>"
                           required step="0.1"
                           name="discount_deals_workflow[dd_discounts][<?php echo $count; ?>][min_subtotal]"
                           data-name="discount_deals_workflow[dd_discounts][--rule_id--][min_subtotal]"
                           placeholder="<?php echo __( "E.g. 5000.00", "discount-deals" ) ?>">
                    <span class="input-group-addon "><?php echo get_woocommerce_currency_symbol(); ?></span>
                </div>
            </td>
            <td>
                <div class="discount-deals-input-group suffix">
                    <input type="number"
                           value="<?php echo discount_deals_get_value_from_array( $discount_detail, 'max_subtotal', '' ); ?>"
                           required step="0.1"
                           name="discount_deals_workflow[dd_discounts][<?php echo $count; ?>][max_subtotal]"
                           data-name="discount_deals_workflow[dd_discounts][--rule_id--][max_subtotal]"
                           placeholder="<?php echo __( "E.g. 8000.00", "discount-deals" ) ?>">
                    <span class="input-group-addon "><?php echo get_woocommerce_currency_symbol(); ?></span>
                </div>
            </td>
            <td>
                <select name="discount_deals_workflow[dd_discounts][<?php echo $count; ?>][type]"
                        class="discount-deals-w150 cart-discount-type"
                        data-name="discount_deals_workflow[dd_discounts][--rule_id--][type]">
                    <option value="free_shipping" <?php if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'free_shipping' ) {
						echo ' selected';
					} ?>><?php echo __( "Free Shipping", "discount-deals" ) ?></option>
                    <option value="flat" <?php if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'flat' ) {
						echo ' selected';
					} ?>><?php echo __( "Fixed Discount", "discount-deals" ) ?></option>
                    <option value="percent" <?php if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'percent' ) {
						echo ' selected';
					} ?>><?php echo __( "Percentage Discount", "discount-deals" ) ?></option>
                </select>
            </td>
            <td>
                <div class="discount-deals-input-group suffix">
                    <input type="number"
						<?php if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'free_shipping' ) {
							echo ' disabled ';
						} ?>
                           value="<?php echo discount_deals_get_value_from_array( $discount_detail, 'value', '' ); ?>"
                           class="cart-discount-value" required step="0.1"
                           name="discount_deals_workflow[dd_discounts][<?php echo $count; ?>][value]"
                           data-name="discount_deals_workflow[dd_discounts][--rule_id--][value]"
                           placeholder="<?php echo __( "E.g. 50", "discount-deals" ) ?>"
                           class="discount-value-symbol">
                    <span class="input-group-addon discount-value-symbol"
                          data-currency="<?php echo get_woocommerce_currency_symbol() ?>"><?php
						if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'flat' ) {
							echo get_woocommerce_currency_symbol();
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
						<?php if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'free_shipping' || discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'flat' ) {
							echo ' disabled ';
						} ?>
                           value="<?php echo discount_deals_get_value_from_array( $discount_detail, 'max_discount', '' ); ?>"
                           class="cart-discount-value cart-max-discount" step="0.1"
                           name="discount_deals_workflow[dd_discounts][<?php echo $count; ?>][max_discount]"
                           data-name="discount_deals_workflow[dd_discounts][--rule_id--][max_discount]"
                           placeholder="<?php echo __( "E.g. 20.00", "discount-deals" ) ?>">
                    <span class="input-group-addon "><?php echo get_woocommerce_currency_symbol(); ?></span>
                </div>
            </td>
            <td>
                <button type="button"
                        class="discount-deals-remove-cart-discount button discount-deals-cart-discount__remove <?php if ( $count <= 1 ) {
					        echo 'discount-deals-hidden';
				        } ?>">
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
				<?php echo __( '+ Add Discount Group', 'discount-deals' ) ?>
            </button>
        </td>
    </tr>
    <tr>
        <td colspan="6" class="discount-deals-text-left">
            <p>
                <b><?php echo __( 'How it Works?', 'discount-deals' ) ?></b><?php echo __( ' You can give discount, if the cart subtotal is between min and max values. ', 'discount-deals' ) ?>
            </p>
            <b><?php echo __( 'Example: ', 'discount-deals' ) ?></b>
            <ol>
                <li><?php echo __( 'Give free shipping for the cart whose subtotal is greater than 500 and lesser than 1000. ', 'discount-deals' ) ?></li>
                <li><?php echo __( 'Give 10% discount on cart whose subtotal is greater than 1500 and lesser than 3000. ', 'discount-deals' ) ?></li>
            </ol>
        </td>
    </tr>
    </tfoot>
</table>