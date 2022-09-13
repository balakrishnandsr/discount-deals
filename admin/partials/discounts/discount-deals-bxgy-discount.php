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
			'min_quantity'          => '',
			'max_quantity'          => '',
			'free_quantity'         => '',
			'free_product_type'     => '',
			'free_product'          => '',
			'free_category'         => [],
			'type'                  => 'flat',
			'show_eligible_message' => '',
			'value'                 => '',
			'max_discount'          => '',
		)
	);
}

$discount_types = array(
	'cheapest_in_cart' => __( 'Cheapest item in the Cart', 'discount-deals' ),
	'biggest_in_cart'  => __( 'Expensive item in the Cart', 'discount-deals' ),
	'products'         => __( 'Select discount product manually', 'discount-deals' ),
//	'cheapest_in_store' => __( 'Cheapest item in the Store', 'discount-deals' ),
//	'biggest_in_store'  => __( 'Expensive item in the Store', 'discount-deals' ),
//	'cheapest_in_category' => __( 'Cheapest item in the Category', 'discount-deals' ),
//	'biggest_in_category'  => __( 'Expensive item in the Category', 'discount-deals' ),
);

?>
<table class="cart-discount-details-table discount-deals-fw-table">
    <thead class="discount-deals-text-left">
    <tr>
        <th class="discount-deals-w100"><?php echo __( "Min Qty.", "discount-deals" ); ?></th>
        <th class="discount-deals-w100"><?php echo __( "Max Qty.", "discount-deals" ); ?></th>
        <th colspan="4">
            <div class="discount-deals-grid">
                <div class="discount-deals-col-3">
					<?php echo __( "Discount", "discount-deals" ); ?>
                </div>
                <div class="discount-deals-col-2">
					<?php echo __( "Discount Qty.", "discount-deals" ); ?>
                </div>
                <div class="discount-deals-col-3">
					<?php echo __( "Discount Type", "discount-deals" ); ?>
                </div>
                <div class="discount-deals-col-2">
					<?php echo __( "Discount Value", "discount-deals" ); ?>
                </div>
                <div class="discount-deals-col-2">
					<?php echo __( "Discount Limit", "discount-deals" ); ?>
                </div>
            </div>
        </th>
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
            <td colspan="4">
                <div class="discount-deals-grid">
                    <div class="discount-deals-col-3">
                        <select name="discount_deals_workflow[dd_discounts][<?php echo $count; ?>][free_product_type]"
                                class="discount-deals-w150 discount-deals-free-type"
                                data-default-val="cheapest_in_cart"
                                data-name="discount_deals_workflow[dd_discounts][--rule_id--][free_product_type]">
							<?php
							foreach ( $discount_types as $key => $value ) {
								?>
                                <option value="<?php echo $key; ?>" <?php if ( discount_deals_get_value_from_array( $discount_detail, 'free_product_type', '' ) == $key ) {
									echo ' selected';
								} ?>><?php echo $value ?></option>
								<?php
							}
							?>
                        </select>
                    </div>
                    <div class="discount-deals-col-2">
                        <div class="discount-deals-input-group suffix">
                            <input type="number"
                                   value="<?php echo discount_deals_get_value_from_array( $discount_detail, 'free_quantity', '' ); ?>"
                                   required
                                   name="discount_deals_workflow[dd_discounts][<?php echo $count; ?>][free_quantity]"
                                   data-name="discount_deals_workflow[dd_discounts][--rule_id--][free_quantity]"
                                   placeholder="<?php echo __( "E.g. 1", "discount-deals" ) ?>">
                            <span class="input-group-addon "></span>
                        </div>
                    </div>
                    <div class="discount-deals-col-3">
                        <select name="discount_deals_workflow[dd_discounts][<?php echo $count; ?>][type]"
                                class="discount-deals-w150 cart-discount-type"
                                data-default-val="free"
                                data-name="discount_deals_workflow[dd_discounts][--rule_id--][type]">
                            <option value="free" <?php if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'free' ) {
								echo ' selected';
							} ?>><?php echo __( "Free", "discount-deals" ) ?></option>
                            <option value="flat" <?php if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'flat' ) {
								echo ' selected';
							} ?>><?php echo __( "Fixed Discount", "discount-deals" ) ?></option>
                            <option value="percent" <?php if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'percent' ) {
								echo ' selected';
							} ?>><?php echo __( "Percentage Discount", "discount-deals" ) ?></option>
                        </select>
                    </div>
                    <div class="discount-deals-col-2">
                        <div class="discount-deals-input-group suffix">
                            <input type="number"
								<?php if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'free' ) {
									echo ' disabled ';
								} ?>
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
                    </div>
                    <div class="discount-deals-col-2">
                        <div class="discount-deals-input-group suffix">
                            <input type="number"
								<?php if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'flat' || discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'free' ) {
									echo ' disabled ';
								} ?>
                                   value="<?php echo discount_deals_get_value_from_array( $discount_detail, 'max_discount', '' ); ?>"
                                   class="cart-discount-value cart-max-discount" step="0.1"
                                   name="discount_deals_workflow[dd_discounts][<?php echo $count; ?>][max_discount]"
                                   data-name="discount_deals_workflow[dd_discounts][--rule_id--][max_discount]"
                                   placeholder="<?php echo __( "E.g. 20.00", "discount-deals" ) ?>">
                            <span class="input-group-addon "><?php echo get_woocommerce_currency_symbol(); ?></span>
                        </div>
                    </div>
                </div>
                <div class="bxgy-free-category <?php
				if ( discount_deals_get_value_from_array( $discount_detail, 'free_product_type', '' ) != 'biggest_in_category' && discount_deals_get_value_from_array( $discount_detail, 'free_product_type', '' ) != 'cheapest_in_category' ) {
					echo 'discount-deals-hidden';
				}
				?>">
                    <p>
                        <b><?php echo __( 'Select category to pick free product', 'discount-deals' ); ?></b>
                    </p>
                    <div class="bxgy-category-select-container">
                        <select name="discount_deals_workflow[dd_discounts][<?php echo $count; ?>][free_category][]"
                                data-name="discount_deals_workflow[dd_discounts][--rule_id--][free_category][]"
                                class="discount-deals-field discount-deals-rule-value-field wc-category-search discount-deals-bxgy-category-select"
                                data-placeholder="<?php echo __( 'Search category...', 'discount-deals' ); ?>"
                                data-return_id="id" multiple="multiple"
                                data-action="woocommerce_json_search_categories">
							<?php
							$free_categories = discount_deals_get_value_from_array( $discount_detail, 'free_category', [] );
							if ( ! empty( $free_categories ) ) {
								foreach ( $free_categories as $id ) {
									$value    = absint( $id );
									$category = get_term_by( 'id', $value, 'product_cat' );
									if ( $category ) {
										?>
                                        <option value="<?php echo $value; ?>"
                                                selected><?php echo $category->name ?></option>
										<?php
									}
								}
							}
							?>
                        </select>
                    </div>
                </div>
                <div class="bxgy-free-product <?php
				if ( discount_deals_get_value_from_array( $discount_detail, 'free_product_type', '' ) != 'products' ) {
					echo 'discount-deals-hidden';
				}
				?>">
                    <p>
                        <b><?php echo __( 'Select free products', 'discount-deals' ); ?></b>
                    </p>
                    <div class="bxgy-product-select-container">
                        <select name="discount_deals_workflow[dd_discounts][<?php echo $count; ?>][free_product]"
                                data-name="discount_deals_workflow[dd_discounts][--rule_id--][free_product]"
                                class="discount-deals-field discount-deals-rule-value-field wc-product-search discount-deals-bxgy-products-select"
                                data-placeholder="<?php echo __( 'Search products...', 'discount-deals' ); ?>"
                                data-action="woocommerce_json_search_products_and_variations">
							<?php
							$product_id = discount_deals_get_value_from_array( $discount_detail, 'free_product', false );
							if ( ! empty( $product_id ) ) {
								$value   = absint( $product_id );
								$product = wc_get_product( $value );
								if ( $product ) {
									?>
                                    <option value="<?php echo $value; ?>"
                                            selected><?php echo $product->get_formatted_name() ?></option>
									<?php
								}
							}
							?>
                        </select>
                    </div>
                    <p>
                        <input type="checkbox"
							<?php if ( ! empty( discount_deals_get_value_from_array( $discount_detail, 'show_eligible_message', '' ) ) ) {
								echo "checked";
							} ?>
                               value="yes"
                               name="discount_deals_workflow[dd_discounts][<?php echo $count; ?>][show_eligible_message]"
                               data-name="discount_deals_workflow[dd_discounts][--rule_id--][show_eligible_message]"> <?php echo __( 'Would you like to show "Eligible for discount" message, if the above product not found in cart? ', 'discount-deals' ); ?>
                    </p>
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
        <td colspan="7" class="discount-deals-text-right">
            <button type="button" class="discount-deals-add-cart-discount button button-primary button-large">
				<?php echo __( '+ Add Discount Group', 'discount-deals' ) ?>
            </button>
        </td>
    </tr>
    <tr>
        <td colspan="7" class="discount-deals-text-left">
            <p>
                <b><?php echo __( 'How it Works?', 'discount-deals' ) ?></b><?php echo __( ' You can give discount, if the product quantity is between min and max values. then, give discount accordingly. ', 'discount-deals' ) ?>
            </p>
            <b><?php echo __( 'Example: ', 'discount-deals' ) ?></b>
            <ol>
                <li><?php echo __( 'Give flat 2$ as discount for the product who is buying 5 to 10 quantities. ', 'discount-deals' ) ?></li>
                <li><?php echo __( 'Give 15% discount for the product who is buying 15 to 30 quantities. ', 'discount-deals' ) ?></li>
            </ol>
        </td>
    </tr>
    </tfoot>
</table>
<script type="text/template" id="temp-free-products-select">
    <select data-name="discount_deals_workflow[dd_discounts][--rule_id--][free_product]"
            class="discount-deals-field discount-deals-rule-value-field wc-product-search discount-deals-bxgy-products-select"
            data-placeholder="<?php echo __( 'Search products...', 'discount-deals' ); ?>"
            data-action="woocommerce_json_search_products_and_variations">
    </select>
</script>
<script type="text/template" id="temp-free-category-select">
    <select data-name="discount_deals_workflow[dd_discounts][--rule_id--][free_category][]"
            class="discount-deals-field discount-deals-rule-value-field wc-category-search discount-deals-bxgy-category-select"
            data-placeholder="<?php echo __( 'Search category...', 'discount-deals' ); ?>"
            data-return_id="id" multiple="multiple"
            data-action="woocommerce_json_search_categories">
    </select>
</script>