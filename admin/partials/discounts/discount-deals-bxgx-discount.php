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
		)
	);
}

?>
<table class="cart-discount-details-table discount-deals-fw-table">
    <thead class="discount-deals-text-left">
    <tr>
        <th><?php esc_html_e( "Min Qty.", "discount-deals" ); ?></th>
        <th><?php esc_html_e( "Max Qty.", "discount-deals" ); ?></th>
        <th><?php esc_html_e( "Discount Qty.", "discount-deals" ); ?></th>
        <th><?php esc_html_e( "Discount Type", "discount-deals" ); ?></th>
        <th><?php esc_html_e( "Discount Value", "discount-deals" ); ?></th>
        <th><?php esc_html_e( "Max Discount", "discount-deals" ); ?></th>
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
                           value="<?php echo esc_attr(discount_deals_get_value_from_array( $discount_detail, 'min_quantity', '' )); ?>"
                           required name="discount_deals_workflow[dd_discounts][<?php echo esc_attr($count); ?>][min_quantity]"
                           data-name="discount_deals_workflow[dd_discounts][--rule_id--][min_quantity]"
                           placeholder="<?php esc_html_e( "E.g. 1", "discount-deals" ) ?>">
                    <span class="input-group-addon "></span>
                </div>
            </td>
            <td>
                <div class="discount-deals-input-group suffix">
                    <input type="number"
                           value="<?php echo esc_attr(discount_deals_get_value_from_array( $discount_detail, 'max_quantity', '' )); ?>"
                           required name="discount_deals_workflow[dd_discounts][<?php echo esc_attr($count); ?>][max_quantity]"
                           data-name="discount_deals_workflow[dd_discounts][--rule_id--][max_quantity]"
                           placeholder="<?php esc_html_e( "E.g. 10", "discount-deals" ) ?>">
                    <span class="input-group-addon "></span>
                </div>
            </td>
            <td>
                <div class="discount-deals-input-group suffix">
                    <input type="number"
                           value="<?php echo esc_attr(discount_deals_get_value_from_array( $discount_detail, 'free_quantity', '' )); ?>"
                           required name="discount_deals_workflow[dd_discounts][<?php echo esc_attr($count); ?>][free_quantity]"
                           data-name="discount_deals_workflow[dd_discounts][--rule_id--][free_quantity]"
                           placeholder="<?php esc_html_e( "E.g. 1", "discount-deals" ) ?>">
                    <span class="input-group-addon "></span>
                </div>
            </td>
            <td>
                <select name="discount_deals_workflow[dd_discounts][<?php echo esc_attr($count); ?>][type]"
                        class="discount-deals-w150 cart-discount-type"
                        data-default-val="free"
                        data-name="discount_deals_workflow[dd_discounts][--rule_id--][type]">
                    <option value="free" <?php if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'free' ) {
						echo ' selected';
					} ?>><?php esc_html_e( "Free", "discount-deals" ) ?></option>
                    <option value="flat" <?php if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'flat' ) {
						echo ' selected';
					} ?>><?php esc_html_e( "Fixed Discount", "discount-deals" ) ?></option>
                    <option value="percent" <?php if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'percent' ) {
						echo ' selected';
					} ?>><?php esc_html_e( "Percentage Discount", "discount-deals" ) ?></option>
                </select>
            </td>
            <td>
                <div class="discount-deals-input-group suffix">
                    <input type="number"
						<?php if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'free' ) {
							echo ' disabled ';
						} ?>
                           value="<?php echo esc_attr(discount_deals_get_value_from_array( $discount_detail, 'value', '' )); ?>"
                           class="cart-discount-value" required step="0.1"
                           name="discount_deals_workflow[dd_discounts][<?php echo esc_attr($count); ?>][value]"
                           data-name="discount_deals_workflow[dd_discounts][--rule_id--][value]"
                           placeholder="<?php esc_html_e( "E.g. 50", "discount-deals" ) ?>">
                    <span class="input-group-addon discount-value-symbol"
                          data-currency="<?php echo esc_attr(get_woocommerce_currency_symbol()) ?>"><?php
						if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'flat' ) {
							echo esc_attr(get_woocommerce_currency_symbol());
						} else if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'percent' ) {
							echo '%';
						}
						?></span>
                </div>
            </td>
            <td>
                <div class="discount-deals-input-group suffix">
                    <input type="number"
						<?php if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'flat' || discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'free' ) {
							echo ' disabled ';
						} ?>
                           value="<?php echo esc_attr(discount_deals_get_value_from_array( $discount_detail, 'max_discount', '' )); ?>"
                           class="cart-discount-value cart-max-discount" step="0.1"
                           name="discount_deals_workflow[dd_discounts][<?php echo esc_attr($count); ?>][max_discount]"
                           data-name="discount_deals_workflow[dd_discounts][--rule_id--][max_discount]"
                           placeholder="<?php esc_html_e( "E.g. 20.00", "discount-deals" ) ?>">
                    <span class="input-group-addon "><?php echo esc_attr(get_woocommerce_currency_symbol()); ?></span>
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
				<?php esc_html_e( '+ Add Discount Group', 'discount-deals' ) ?>
            </button>
        </td>
    </tr>
    <tr>
        <td colspan="6" class="discount-deals-text-left">
            <p>
                <b><?php esc_html_e( 'How it Works?', 'discount-deals' ) ?></b><?php esc_html_e( ' Create multiple discount groups by specifying the minimum and maximum product quantity and discount details. If the product quantity matches one of the discount groups, the discount will be applied to that product accordingly. ', 'discount-deals' ) ?>
            </p>
            <b><?php esc_html_e( 'Example: ', 'discount-deals' ) ?></b>
            <ol>
                <li><?php esc_html_e( 'Buy two or more t-shirts and get one t-shirt for free as a discount.', 'discount-deals' ) ?></li>
                <li><?php esc_html_e( 'Give a 50% discount on a quantity of product X if the customer buys product X in five or more quantities. ', 'discount-deals' ) ?></li>
            </ol>
        </td>
    </tr>
    </tfoot>
</table>