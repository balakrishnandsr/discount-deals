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
			'min_quantity'          => '',
			'max_quantity'          => '',
			'free_quantity'         => '',
			'free_product_type'     => '',
			'free_product'          => '',
			'free_category'         => array(),
			'type'                  => 'flat',
			'show_eligible_message' => '',
			'value'                 => '',
			'max_discount'          => '',
		),
	);
}

$discount_types = array(
	'cheapest_in_cart'     => __( 'Cheapest item in the Cart', 'discount-deals' ),
	'biggest_in_cart'      => __( 'Costlier item in the Cart', 'discount-deals' ),
	'products'             => __( 'Specific Product', 'discount-deals' ),
//	'cheapest_in_store'    => __( 'Cheapest item in the Store', 'discount-deals' ),
//	'biggest_in_store'     => __( 'Expensive item in the Store', 'discount-deals' ),
//	'cheapest_in_category' => __( 'Cheapest item in the Category', 'discount-deals' ),
//	'biggest_in_category'  => __( 'Costlier item in the Category', 'discount-deals' ),
);

?>
<table class="cart-discount-details-table discount-deals-fw-table">
    <thead class="discount-deals-text-left">
    <tr>
        <th class="discount-deals-w100"><?php esc_html_e( 'Minimum Quantity', 'discount-deals' ); ?></th>
        <th class="discount-deals-w100"><?php esc_html_e( 'Maximum Quantity', 'discount-deals' ); ?></th>
        <th colspan="4">
            <div class="discount-deals-grid">
                <div class="discount-deals-col-3">
					<?php esc_html_e( 'Discount', 'discount-deals' ); ?>
                </div>
                <div class="discount-deals-col-2">
					<?php esc_html_e( 'Discount Quantity', 'discount-deals' ); ?>
                </div>
                <div class="discount-deals-col-3">
					<?php esc_html_e( 'Discount Type', 'discount-deals' ); ?>
                </div>
                <div class="discount-deals-col-2">
					<?php esc_html_e( 'Discount Value', 'discount-deals' ); ?>
                </div>
                <div class="discount-deals-col-2">
					<?php esc_html_e( 'Maximum Discount ', 'discount-deals' ); ?>
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
                           value="<?php echo esc_attr( discount_deals_get_value_from_array( $discount_detail, 'min_quantity', '' ) ); ?>"
                           required
                           name="discount_deals_workflow[dd_discounts][<?php echo esc_attr( $count ); ?>][min_quantity]"
                           data-name="discount_deals_workflow[dd_discounts][--rule_id--][min_quantity]"
                           placeholder="<?php esc_html_e( 'E.g. 1', 'discount-deals' ); ?>">
                    <span class="input-group-addon "></span>
                </div>
            </td>
            <td>
                <div class="discount-deals-input-group suffix">
                    <input type="number"
                           value="<?php echo esc_attr( discount_deals_get_value_from_array( $discount_detail, 'max_quantity', '' ) ); ?>"
                           required
                           name="discount_deals_workflow[dd_discounts][<?php echo esc_attr( $count ); ?>][max_quantity]"
                           data-name="discount_deals_workflow[dd_discounts][--rule_id--][max_quantity]"
                           placeholder="<?php esc_html_e( 'E.g. 10', 'discount-deals' ); ?>">
                    <span class="input-group-addon "></span>
                </div>
            </td>
            <td colspan="4">
                <div class="discount-deals-grid">
                    <div class="discount-deals-col-3">
                        <select name="discount_deals_workflow[dd_discounts][<?php echo esc_attr( $count ); ?>][free_product_type]"
                                class="discount-deals-w150 discount-deals-free-type"
                                data-default-val="cheapest_in_cart"
                                data-name="discount_deals_workflow[dd_discounts][--rule_id--][free_product_type]">
							<?php
							foreach ( $discount_types as $key => $value ) {
								?>
                                <option value="<?php echo esc_attr( $key ); ?>"
									<?php
									if ( discount_deals_get_value_from_array( $discount_detail, 'free_product_type', '' ) == $key ) {
										echo ' selected';
									}
									?>
                                ><?php echo esc_attr( $value ); ?></option>
								<?php
							}
							?>
                        </select>
                    </div>
                    <div class="discount-deals-col-2">
                        <div class="discount-deals-input-group suffix">
                            <input type="number"
                                   value="<?php echo esc_attr( discount_deals_get_value_from_array( $discount_detail, 'free_quantity', '' ) ); ?>"
                                   required
                                   name="discount_deals_workflow[dd_discounts][<?php echo esc_attr( $count ); ?>][free_quantity]"
                                   data-name="discount_deals_workflow[dd_discounts][--rule_id--][free_quantity]"
                                   placeholder="<?php esc_html_e( 'E.g. 1', 'discount-deals' ); ?>">
                            <span class="input-group-addon "></span>
                        </div>
                    </div>
                    <div class="discount-deals-col-3">
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
                    </div>
                    <div class="discount-deals-col-2">
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
                    </div>
                    <div class="discount-deals-col-2">
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
                    </div>
                </div>
                <div class="bxgy-free-category
				<?php
				if ( discount_deals_get_value_from_array( $discount_detail, 'free_product_type', '' ) != 'biggest_in_category' && discount_deals_get_value_from_array( $discount_detail, 'free_product_type', '' ) != 'cheapest_in_category' ) {
					echo 'discount-deals-hidden';
				}
				?>
				">
                    <p>
                        <b><?php esc_html_e( 'Select category to pick free product', 'discount-deals' ); ?></b>
                    </p>
                    <div class="bxgy-category-select-container">
                        <select name="discount_deals_workflow[dd_discounts][<?php echo esc_attr( $count ); ?>][free_category][]"
                                data-name="discount_deals_workflow[dd_discounts][--rule_id--][free_category][]"
                                class="discount-deals-field discount-deals-rule-value-field wc-category-search discount-deals-bxgy-category-select"
                                data-placeholder="<?php esc_html_e( 'Search category...', 'discount-deals' ); ?>"
                                data-return_id="id" multiple="multiple"
                                data-action="woocommerce_json_search_categories">
							<?php
							$free_categories = discount_deals_get_value_from_array( $discount_detail, 'free_category', array() );
							if ( ! empty( $free_categories ) ) {
								foreach ( $free_categories as $category_id ) {
									$value    = absint( $category_id );
									$category = get_term_by( 'id', $value, 'product_cat' );
									if ( $category ) {
										?>
                                        <option value="<?php echo esc_attr( $value ); ?>"
                                                selected><?php echo esc_attr( $category->name ); ?></option>
										<?php
									}
								}
							}
							?>
                        </select>
                    </div>
                </div>
                <div class="bxgy-free-product
				<?php
				if ( discount_deals_get_value_from_array( $discount_detail, 'free_product_type', '' ) != 'products' ) {
					echo 'discount-deals-hidden';
				}
				?>
				">
                    <p>
                        <b><?php esc_html_e( 'Select free products', 'discount-deals' ); ?></b>
                    </p>
                    <div class="bxgy-product-select-container">
                        <select name="discount_deals_workflow[dd_discounts][<?php echo esc_attr( $count ); ?>][free_product]"
                                data-name="discount_deals_workflow[dd_discounts][--rule_id--][free_product]"
                                class="discount-deals-field discount-deals-rule-value-field wc-product-search discount-deals-bxgy-products-select"
                                data-placeholder="<?php esc_html_e( 'Search products...', 'discount-deals' ); ?>"
                                data-action="woocommerce_json_search_products_and_variations">
							<?php
							$product_id = discount_deals_get_value_from_array( $discount_detail, 'free_product', false );
							if ( ! empty( $product_id ) ) {
								$value   = absint( $product_id );
								$product = wc_get_product( $value );
								if ( $product ) {
									?>
                                    <option value="<?php echo esc_attr( $value ); ?>"
                                            selected><?php echo esc_attr( $product->get_formatted_name() ); ?></option>
									<?php
								}
							}
							?>
                        </select>
                    </div>
                    <p class="bxgy-show-promotion-for-y
					<?php
					if ( discount_deals_get_value_from_array( $discount_detail, 'type', '' ) == 'free' ) {
						echo ' discount-deals-hidden';
					}
					?>
					">
                        <input type="checkbox"
							<?php
							if ( ! empty( discount_deals_get_value_from_array( $discount_detail, 'show_eligible_message', '' ) ) ) {
								echo 'checked';
							}
							?>
                               value="yes"
                               name="discount_deals_workflow[dd_discounts][<?php echo esc_attr( $count ); ?>][show_eligible_message]"
                               data-name="discount_deals_workflow[dd_discounts][--rule_id--][show_eligible_message]"> <?php esc_html_e( 'Would you like to show "Eligible for discount" message, if the above product not found in cart? ', 'discount-deals' ); ?>
                    </p>
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
        <td colspan="7" class="discount-deals-text-right">
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
					<?php esc_html_e( 'As customers add qualifying quantities of the main product (X) to their cart, the Buy X and Get Y Discount feature will automatically apply the BOGO discounts. The plugin will recognize when the conditions for each discount range are met and add the specified discount product (Y) to the customer\'s cart.', 'discount-deals' ); ?>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="6" class="discount-deals-text-left">
            <h3><?php esc_html_e( 'How To Configure?', 'discount-deals' ); ?></h3>
            <ul>
                <li>
                    <b><?php esc_html_e( 'Define Product Quantity Ranges:', 'discount-deals' ); ?></b> <?php esc_html_e( 'Here, you can set up specific product quantity ranges for which you want to apply the BOGO discounts. For example, you can create a discount for customers buying 3 to 5 caps or a discount for customers purchasing 1 laptop.', 'discount-deals' ); ?>
                </li>
                <li>
                    <b><?php esc_html_e( 'Define Discount Product:', 'discount-deals' ); ?></b> <?php esc_html_e( 'For each product quantity range, you\'ll specify the type of discount product (Y) the customer will receive. You have three options:', 'discount-deals' ); ?>
                    <ol>
                        <li><?php esc_html_e( 'Cheapest in the Cart: Offer a discount on the cheapest product in the cart based on the defined quantity range.', 'discount-deals' ); ?></li>
                        <li><?php esc_html_e( 'Costlier in the Cart: Offer a discount on the costlier product in the cart based on the defined quantity range.', 'discount-deals' ); ?></li>
                        <li><?php esc_html_e( 'Specific Product: Specify a particular product that customers will receive as a discount when they meet the required quantity.', 'discount-deals' ); ?></li>
                    </ol>
                </li>
                <li>
                    <b><?php esc_html_e( 'Define Discount Quantity:', 'discount-deals' ); ?></b> <?php esc_html_e( 'For each product quantity range, you\'ll also specify the quantity of the discount product (Y) that the customer will receive. For example, customers need to buy 2 belts to get a $10 discount when they purchase 3 to 5 caps.', 'discount-deals' ); ?>
                </li>
                <li>
                    <b><?php esc_html_e( 'Choose Discount Type', 'discount-deals' ); ?></b> <?php esc_html_e( 'Next, you have three discount types to choose from:', 'discount-deals' ); ?>
                    <ol>
                        <li><?php esc_html_e( 'Free: Provide the discount product (Y) for free when the customer meets the required quantity of the main product (X).', 'discount-deals' ); ?></li>
                        <li><?php esc_html_e( 'Fixed Discount: Set a fixed amount to be deducted from the price of the discount product (Y).', 'discount-deals' ); ?></li>
                        <li><?php esc_html_e( 'Percentage Discount: Apply a percentage reduction based on the price of the discount product (Y).', 'discount-deals' ); ?></li>
                    </ol>
                </li>
                <li>
                    <b><?php esc_html_e( 'Specify Discount Value (for Fixed and Percentage Discounts):', 'discount-deals' ); ?></b> <?php esc_html_e( 'Depending on your chosen discount type, enter the appropriate value. For a fixed discount, enter the amount to be deducted (e.g., $10 off). For a percentage discount, enter the percentage to be discounted (e.g., 10% off).', 'discount-deals' ); ?>
                </li>
                <li>
                    <b><?php esc_html_e( 'Maximum Applicable Discount (for Percentage Discounts):', 'discount-deals' ); ?></b> <?php esc_html_e( 'If you opt for a percentage discount, you can set a maximum limit to control the discount amount. For example, if the calculated percentage discount exceeds a certain value, the discount will be capped at that value for the discount product (Y).', 'discount-deals' ); ?>
                </li>
            </ul>
            <h3><?php esc_html_e( 'Example Scenario:', 'discount-deals' ); ?></h3>
            <div>
                <ul>
                    <li><?php esc_html_e( 'If a customer adds 4 caps to their cart, and you\'ve set up a BOGO discount of $10 for 2 belts when customers purchase 3 to 5 caps, the plugin will automatically add 2 belts to the customer\'s cart, and the price of the belts will be discounted by $10.', 'discount-deals' ); ?></li>
                    <li><?php esc_html_e( 'If another customer adds 1 laptop to their cart, and you\'ve set up a BOGO discount of 1 free laptop bag when customers purchase 1 laptop, the plugin will automatically add 1 laptop bag to the customer\'s cart, and the laptop bag will be provided for free.', 'discount-deals' ); ?></li>
                </ul>
                <p><?php esc_html_e( 'By offering these attractive BOGO discounts, you can encourage customers to purchase more products and increase their cart value while creating a more engaging shopping experience on your WooCommerce store.', 'discount-deals' ); ?></p>
            </div>
        </td>
    </tr>

    </tfoot>
</table>
<script type="text/template" id="temp-free-products-select" class="discount-deals-hidden">
    <select data-name="discount_deals_workflow[dd_discounts][--rule_id--][free_product]"
            class="discount-deals-field discount-deals-rule-value-field wc-product-search discount-deals-bxgy-products-select"
            data-placeholder="<?php esc_html_e( 'Search products...', 'discount-deals' ); ?>"
            data-action="woocommerce_json_search_products_and_variations">
    </select>
</script>
<script type="text/template" id="temp-free-category-select">
    <select data-name="discount_deals_workflow[dd_discounts][--rule_id--][free_category][]"
            class="discount-deals-field discount-deals-rule-value-field wc-category-search discount-deals-bxgy-category-select"
            data-placeholder="<?php esc_html_e( 'Search category...', 'discount-deals' ); ?>"
            data-return_id="id" multiple="multiple"
            data-action="woocommerce_json_search_categories">
    </select>
</script>
