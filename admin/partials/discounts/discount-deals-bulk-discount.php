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
			'min_quantity' => '',
			'max_quantity' => '',
			'type'         => 'flat',
			'value'        => '',
			'max_discount' => '',
		)
	);
}

?>
<table class="cart-discount-details-table discount-deals-fw-table">
    <thead class="discount-deals-text-left">
    <tr>
        <th><?php echo __( "Min Qty.", "discount-deals" ); ?></th>
        <th><?php echo __( "Max Qty.", "discount-deals" ); ?></th>
        <th><?php echo __( "Discount Type", "discount-deals" ); ?></th>
        <th><?php echo __( "Discount Value", "discount-deals" ); ?></th>
        <th><?php echo __( "Max Discount", "discount-deals" ); ?></th>
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
                           value="<?php echo discount_deals_get_value_from_array( $discount_detail, 'min_quantity', '' ); ?>"
                           required name="discount_deals_workflow[dd_discounts][<?php echo $count; ?>][min_quantity]"
                           data-name="discount_deals_workflow[dd_discounts][--rule_id--][min_quantity]"
                           placeholder="<?php echo __( "E.g. 1", "discount-deals" ) ?>">
                    <span class="input-group-addon "></span>
                </div>
            </td>
            <td>
                <div class="discount-deals-input-group suffix">
                    <input type="number"
                           value="<?php echo discount_deals_get_value_from_array( $discount_detail, 'max_quantity', '' ); ?>"
                           required name="discount_deals_workflow[dd_discounts][<?php echo $count; ?>][max_quantity]"
                           data-name="discount_deals_workflow[dd_discounts][--rule_id--][max_quantity]"
                           placeholder="<?php echo __( "E.g. 10", "discount-deals" ) ?>">
                    <span class="input-group-addon "></span>
                </div>
            </td>
            <td>
                <select name="discount_deals_workflow[dd_discounts][<?php echo $count; ?>][type]"
                        data-default-val="flat"
                        class="discount-deals-w150 cart-discount-type"
                        data-name="discount_deals_workflow[dd_discounts][--rule_id--][type]">
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
                           value="<?php echo discount_deals_get_value_from_array( $discount_detail, 'value', '' ); ?>"
                           class="cart-discount-value" required step="0.1"
                           name="discount_deals_workflow[dd_discounts][<?php echo $count; ?>][value]"
                           data-name="discount_deals_workflow[dd_discounts][--rule_id--][value]"
                           placeholder="<?php echo __( "E.g. 50", "discount-deals" ) ?>">
                    <span class="input-group-addon discount-value-symbol"
                          data-currency="<?php echo get_woocommerce_currency_symbol() ?>"><?php
						if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'flat' ) {
							echo get_woocommerce_currency_symbol();
						} else if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'percent' ) {
							echo '%';
						}
						?></span>
                </div>
            </td>
            <td>
                <div class="discount-deals-input-group suffix">
                    <input type="number"
						<?php if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'flat' ) {
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
                <b><?php echo __( 'How it Works?', 'discount-deals' ) ?></b> <?php echo __( 'If the customers purchase the products in bulk quantity, then you can give discounts accordingly.', 'discount-deals' ) ?>
            </p>
            <b><?php echo __( 'Use cases: ', 'discount-deals' ) ?></b>
            <ol>
                <li><?php echo __( 'You can give a 20% discount for customers who are purchasing 5 to 10 quantities of product.', 'discount-deals' ) ?></li>
                <li><?php echo __( 'You can give  a fixed discount on products to wholesale customers. ', 'discount-deals' ) ?></li>
            </ol>
        </td>
    </tr>
    </tfoot>
</table>