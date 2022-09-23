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
	'cheapest_in_cart' => __( 'Cheapest item in the Cart', 'discount-deals' ),
	'biggest_in_cart'  => __( 'Expensive item in the Cart', 'discount-deals' ),
	'products'         => __( 'Select discount product manually', 'discount-deals' ),
// 'cheapest_in_store' => __( 'Cheapest item in the Store', 'discount-deals' ),
// 'biggest_in_store'  => __( 'Expensive item in the Store', 'discount-deals' ),
// 'cheapest_in_category' => __( 'Cheapest item in the Category', 'discount-deals' ),
// 'biggest_in_category'  => __( 'Expensive item in the Category', 'discount-deals' ),
);

?>
<table class="cart-discount-details-table discount-deals-fw-table">
	<thead class="discount-deals-text-left">
	<tr>
		<th class="discount-deals-w100"><?php esc_html_e( 'Min Qty.', 'discount-deals' ); ?></th>
		<th class="discount-deals-w100"><?php esc_html_e( 'Max Qty.', 'discount-deals' ); ?></th>
		<th colspan="4">
			<div class="discount-deals-grid">
				<div class="discount-deals-col-3">
					<?php esc_html_e( 'Discount', 'discount-deals' ); ?>
				</div>
				<div class="discount-deals-col-2">
					<?php esc_html_e( 'Discount Qty.', 'discount-deals' ); ?>
				</div>
				<div class="discount-deals-col-3">
					<?php esc_html_e( 'Discount Type', 'discount-deals' ); ?>
				</div>
				<div class="discount-deals-col-2">
					<?php esc_html_e( 'Discount Value', 'discount-deals' ); ?>
				</div>
				<div class="discount-deals-col-2">
					<?php esc_html_e( 'Discount Limit', 'discount-deals' ); ?>
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
		<td colspan="7">
			<div class="discount-deals-notice">
				<div>
					<strong><?php esc_html_e( 'Important notes:', 'discount-deals' ); ?></strong>
				</div>
				<ol>
					<li>
						<?php esc_html_e( 'When calculating the discount, the quantity details of the respective item in the shopping cart are taken into account and compared with the "discount ranges" you have created.', 'discount-deals' ); ?>
					</li>
					<li>
						<?php esc_html_e( 'If your discount type is "Percentage Discount", then you can limit the discount for that product to a certain amount. Here, the "discount quantity" of the range will be taken into account to limit the discount.', 'discount-deals' ); ?>
					</li>
				</ol>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="7" class="discount-deals-text-left">
			<p>
				<b><?php esc_html_e( 'How it Works?', 'discount-deals' ); ?></b><?php esc_html_e( ' Create multiple discount ranges by specifying the minimum and maximum product quantity and discount details. If the product quantity matches one of the discount ranges, the discount will be applied to that product accordingly. ', 'discount-deals' ); ?>
			</p>
			<b><?php esc_html_e( 'Use cases: ', 'discount-deals' ); ?></b>
			<ol>
				<li><?php esc_html_e( 'Buy two or more t-shirts and get one cap for free as a discount.', 'discount-deals' ); ?></li>
				<li><?php esc_html_e( 'Give a 50% discount on a quantity of product Y if the customer buys product X in five or more quantities. ', 'discount-deals' ); ?></li>
			</ol>
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
