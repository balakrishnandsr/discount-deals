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
		'type'        => 'flat',
		'value'       => '',
		'expires_in'  => '',
		'coupon_code' => ''
	);
}
?>
<table class="cart-discount-details-table discount-deals-fw-table">
    <thead class="discount-deals-text-left">
    <tr>
        <th><?php esc_html_e( 'Discount Type', 'discount-deals' ); ?></th>
        <th><?php esc_html_e( 'Discount Value', 'discount-deals' ); ?></th>
        <th><?php esc_html_e( 'Expires In (days)', 'discount-deals' ); ?></th>
        <th><?php esc_html_e( 'Coupon Code', 'discount-deals' ); ?></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>
            <select name="discount_deals_workflow[dd_discounts][type]"
                    class="discount-deals-w150 noc-discount-type"
                    data-name="discount_deals_workflow[dd_discounts][type]">
                <option value="flat"
					<?php
					if ( discount_deals_get_value_from_array( $discount_details, 'type', '' ) == 'flat' ) {
						echo ' selected';
					}
					?>
                ><?php esc_html_e( 'Fixed Discount', 'discount-deals' ); ?></option>
                <option value="percent"
					<?php
					if ( discount_deals_get_value_from_array( $discount_details, 'type', '' ) == 'percent' ) {
						echo ' selected';
					}
					?>
                ><?php esc_html_e( 'Percentage Discount', 'discount-deals' ); ?></option>
            </select>
        </td>
        <td>
            <div class="discount-deals-input-group suffix">
                <input type="number"
                       value="<?php echo esc_attr( discount_deals_get_value_from_array( $discount_details, 'value', '' ) ); ?>"
                       class="noc-discount-value" required step="0.1"
                       name="discount_deals_workflow[dd_discounts][value]"
                       data-name="discount_deals_workflow[dd_discounts][value]"
                       placeholder="<?php esc_html_e( 'E.g. 50', 'discount-deals' ); ?>">
                <span class="input-group-addon discount-value-symbol"
                      data-currency="<?php echo esc_attr( get_woocommerce_currency_symbol() ); ?>">
                    <?php
                    if ( discount_deals_get_value_from_array( $discount_details, 'type', '' ) == 'flat' ) {
	                    echo esc_attr( get_woocommerce_currency_symbol() );
                    } else if ( discount_deals_get_value_from_array( $discount_details, 'type', '' ) == 'percent' ) {
	                    echo '%';
                    }
                    ?>
                </span>
            </div>
        </td>
        <td>
            <input type="number"
                   value="<?php echo esc_attr( discount_deals_get_value_from_array( $discount_details, 'expires_in', '' ) ); ?>"
                   class="noc-expires-in-days" step="1"
                   name="discount_deals_workflow[dd_discounts][expires_in]"
                   data-name="discount_deals_workflow[dd_discounts][expires_in]"
                   placeholder="<?php esc_html_e( 'E.g. 14', 'discount-deals' ); ?>">
        </td>
        <td>
            <input type="text"
                   value="<?php echo esc_attr( discount_deals_get_value_from_array( $discount_details, 'coupon_code', '' ) ); ?>"
                   class="noc-coupon-code" step="1" required
                   name="discount_deals_workflow[dd_discounts][coupon_code]"
                   data-name="discount_deals_workflow[dd_discounts][coupon_code]">
        </td>
    </tr>
    </tbody>
    <tfoot>
    <tr>
        <td colspan="4">
            <div class="discount-deals-notice">
                <div>
                    <strong><?php esc_html_e( 'Important notes:', 'discount-deals' ); ?></strong>
                </div>
                <ol>
                    <li>
						<?php esc_html_e( "Leave the field 'Expires In' empty, if you don't want to set expire date for the coupons.", 'discount-deals' ); ?>
                    </li>
                    <li>
						<?php esc_html_e( "Create a coupon with a value of '0' in Marketing - > Coupons and then fill the above 'Coupon Code' input field with the coupon code you created. ** Do not delete or change the discount value of the coupon **.", 'discount-deals' ); ?>
                    </li>
                </ol>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="4" class="discount-deals-text-left">
            <p>
                <b><?php esc_html_e( 'How it Works?', 'discount-deals' ); ?></b> <?php esc_html_e( 'Create and send unique one use coupons to your customers for their next purchase.', 'discount-deals' ); ?>
            </p>
            <b><?php esc_html_e( 'Use cases: ', 'discount-deals' ); ?></b>
            <ol>
                <li><?php esc_html_e( 'Send 20% coupon code to your customers for their upcoming purchases whose order total is greater than 1000$.', 'discount-deals' ); ?></li>
                <li><?php esc_html_e( 'Send flat 50$ offer for their next purchase if they purchase 4 products in one order.', 'discount-deals' ); ?></li>
            </ol>
        </td>
    </tr>
    </tfoot>
</table>
