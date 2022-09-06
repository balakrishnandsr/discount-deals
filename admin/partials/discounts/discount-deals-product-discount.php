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
			'min_price'    => '',
			'max_price'    => '',
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
        <th><?php echo __( "Minimum Price", "discount-deals" ) . ' (' . get_woocommerce_currency_symbol() . ')'; ?></th>
        <th><?php echo __( "Maximum Price", "discount-deals" ) . ' (' . get_woocommerce_currency_symbol() . ')'; ?></th>
        <th><?php echo __( "Discount Type", "discount-deals" ); ?></th>
        <th><?php echo __( "Discount Value", "discount-deals" ); ?></th>
        <th><?php echo __( "Maximum Discount", "discount-deals" ) . ' (' . get_woocommerce_currency_symbol() . ')'; ?></th>
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
                <input type="number"
                       value="<?php echo discount_deals_get_value_from_array( $discount_detail, 'min_price', '' ); ?>"
                       class="discount-deals-w100" required
                       name="discount_deals_workflow[dd_discounts][<?php echo $count; ?>][min_price]"
                       data-name="discount_deals_workflow[dd_discounts][--rule_id--][min_price]"
                       placeholder="<?php echo __( "E.g. 5000.00", "discount-deals" ) ?>">
            </td>
            <td>
                <input type="number"
                       value="<?php echo discount_deals_get_value_from_array( $discount_detail, 'max_price', '' ); ?>"
                       class="discount-deals-w100" required
                       name="discount_deals_workflow[dd_discounts][<?php echo $count; ?>][max_price]"
                       data-name="discount_deals_workflow[dd_discounts][--rule_id--][max_price]"
                       placeholder="<?php echo __( "E.g. 8000.00", "discount-deals" ) ?>">
            </td>
            <td>
                <select name="discount_deals_workflow[dd_discounts][<?php echo $count; ?>][type]"
                        class="discount-deals-w100 cart-discount-type"
                        data-name="discount_deals_workflow[dd_discounts][--rule_id--][type]">
                    <option value="flat" <?php if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'flat' ) {
						echo ' selected';
					} ?>><?php echo __( "Flat", "discount-deals" ) ?></option>
                    <option value="percent" <?php if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'percent' ) {
						echo ' selected';
					} ?>><?php echo __( "Percent", "discount-deals" ) ?></option>
                </select>
            </td>
            <td>
                <input type="number"
					<?php if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'free_shipping' ) {
						echo ' disabled ';
					} ?>
                       value="<?php echo discount_deals_get_value_from_array( $discount_detail, 'value', '' ); ?>"
                       class="discount-deals-w100 cart-discount-value" required
                       name="discount_deals_workflow[dd_discounts][<?php echo $count; ?>][value]"
                       data-name="discount_deals_workflow[dd_discounts][--rule_id--][value]"
                       placeholder="<?php echo __( "E.g. 50", "discount-deals" ) ?>">&nbsp;<span
                        class="discount-value-symbol" data-currency="<?php echo get_woocommerce_currency_symbol() ?>">
				<?php
				if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'flat' ) {
					echo get_woocommerce_currency_symbol();
				} else if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'percent' ) {
					echo '%';
				}
				?></span>
            </td>
            <td>
                <input type="number"
					<?php if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'free_shipping' || discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'flat' ) {
						echo ' disabled ';
					} ?>
                       value="<?php echo discount_deals_get_value_from_array( $discount_detail, 'max_discount', '' ); ?>"
                       class="discount-deals-w100 cart-discount-value cart-max-discount" required
                       name="discount_deals_workflow[dd_discounts][<?php echo $count; ?>][max_discount]"
                       data-name="discount_deals_workflow[dd_discounts][--rule_id--][max_discount]"
                       placeholder="<?php echo __( "E.g. 100.00", "discount-deals" ) ?>">
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
        <td colspan="4" class="discount-deals-text-left">
            <p>
                <b><?php echo __( 'How it Works?', 'discount-deals' ) ?></b><?php echo __( ' You can give discount, if the product price is between min and max values. then, give discount accordingly. ', 'discount-deals' ) ?>
            </p>
            <b><?php echo __( 'Example: ', 'discount-deals' ) ?></b>
            <ol>
                <li><?php echo __( 'Give flat 2$ as discount for the product whose price is between 10$ and 50$. ', 'discount-deals' ) ?></li>
                <li><?php echo __( 'Give 15% discount for the product whose price is greater than 200$ and lesser than 1000$. ', 'discount-deals' ) ?></li>
            </ol>
        </td>
        <td colspan="2" class="discount-deals-text-right">
            <button type="button" class="discount-deals-add-cart-discount button button-primary button-large">
				<?php echo __( '+ Add Discount Group', 'discount-deals' ) ?>
            </button>
        </td>
    </tr>
    </tfoot>
</table>