<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @package Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Discount_Deals_Admin_Settings
 */
class Discount_Deals_Admin_Settings {
	/**
	 * Tab id for woocommerce
	 *
	 * @var string $tab_id tab id
	 */
	protected static $tab_id = 'discount-deals-settings';

	/**
	 * Init the settings
	 *
	 * @return void
	 */
	public static function init() {
		add_filter( 'woocommerce_settings_tabs_array', array( __CLASS__, 'add_settings_tab' ), 50 );
		add_action( 'woocommerce_settings_tabs_' . self::$tab_id, array( __CLASS__, 'add_settings' ) );
		add_filter( 'woocommerce_sections_' . self::$tab_id, array( __CLASS__, 'add_sections' ) );
		add_action( 'woocommerce_update_options_' . self::$tab_id, array( __CLASS__, 'save_settings' ) );
	}//end init()

	/**
	 * Add settings sections
	 *
	 * @return void
	 */
	public static function add_sections() {
		$sections        = array(
			'general'                             => __( 'General', 'discount-deals' ),
			'product-price-and-quantity-discount' => __( 'Product discount', 'discount-deals' ),
			'cart-discount'                       => __( 'Cart discount', 'discount-deals' ),
			'bogo-discount'                       => __( 'BOGO discount', 'discount-deals' ),
		);
		$current_section = esc_attr( discount_deals_get_data( 'section', 'general' ) );
		echo '<ul class="subsubsub">';
		$array_keys = array_keys( $sections );
		foreach ( $sections as $id => $label ) {
			echo '<li><a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=' . self::$tab_id . '&section=' . sanitize_title( $id ) ) ) . '" class="' . ( $current_section == $id ? 'current' : '' ) . '">' . esc_html( $label ) . '</a> ' . ( end( $array_keys ) == $id ? '' : '|' ) . ' </li>';
		}
		echo '</ul><br class="clear">';
	}//end add_sections()


	/**
	 * Add Discount deals settings inside Woocommerce settings.
	 *
	 * @param array $settings_tabs Available settings tabs.
	 *
	 * @return array
	 */
	public static function add_settings_tab( $settings_tabs ) {
		$settings_tabs[ self::$tab_id ] = __( 'Discount Deals', 'discount-deals' );

		return $settings_tabs;
	}//end add_settings_tab()

	/**
	 * Save settings into DB.
	 *
	 * @return void
	 */
	public static function save_settings() {
		$nonce = discount_deals_get_request_data( '_wpnonce', '' );
		if ( ! wp_verify_nonce( $nonce, 'woocommerce-settings' ) ) {
			return;
		}
		if ( discount_deals_get_value_from_array( $_POST, 'save', false ) ) {
			$actual_settings = array();
			foreach ( $_POST as $key => $value ) {
				if ( str_starts_with( $key, 'wc_settings_tab_discount_deals_' ) ) {
					$actual_key = str_replace( 'wc_settings_tab_discount_deals_', '', $key );

					$actual_settings[ $actual_key ] = wc_clean( $value );
				}
			}
			Discount_Deals_Settings::save_settings( $actual_settings );
		}
	}//end save_settings()

	/**
	 * Add settings fields
	 *
	 * @return void
	 */
	public static function add_settings() {
		woocommerce_admin_fields( self::get_settings_fields() );
	}//end add_settings()

	/**
	 * Get settings fields to display
	 *
	 * @return array
	 */
	public static function get_settings_fields() {
		$current_section = discount_deals_get_data( 'section', 'general' );
		if ( 'general' == $current_section ) {
			return array(
				'general_section_title'             => array(
					'name' => __( 'General', 'discount-deals' ),
					'type' => 'title',
					'desc' => '',
					'id'   => 'wc_settings_tab_discount_deals_general_settings',
				),
				'show_applied_discounts_message'    => array(
					'name'    => __( 'Notify Customers About Applied Discounts', 'discount-deals' ),
					'desc'    => __( 'Choose whether you want to inform your customers about all applied discounts in their shopping cart.', 'discount-deals' ),
					'type'    => 'radio',
					'options' => array(
						'yes' => __( 'Yes', 'discount-deals' ),
						'no'  => __( 'No', 'discount-deals' ),
					),
					'value'   => Discount_Deals_Settings::get_settings( 'show_applied_discounts_message' ),
					'id'      => 'wc_settings_tab_discount_deals_show_applied_discounts_message',
				),
				'combine_applied_discounts_message' => array(
					'name'    => __( 'Merge Multiple Messages into a Single Message', 'discount-deals' ),
					'desc'    => __( 'Choose whether you want to combine multiple discount messages into a single message for your customers.', 'discount-deals' ),
					'type'    => 'radio',
					'options' => array(
						'yes' => __( 'Yes', 'discount-deals' ),
						'no'  => __( 'No', 'discount-deals' ),
					),
					'value'   => Discount_Deals_Settings::get_settings( 'combine_applied_discounts_message' ),
					'id'      => 'wc_settings_tab_discount_deals_combine_applied_discounts_message',
				),
				'applied_discount_message'          => array(
					'name'  => __( 'Message to be Displayed to Your Customers on the Shopping Cart Page', 'discount-deals' ),
					'type'  => 'text',
					'value' => Discount_Deals_Settings::get_settings( 'applied_discount_message' ),
					'desc'  => sprintf(
							// translators: %s workflow title.
						__( 'Enter the message you want to display to your customers on the shopping cart page. Use the placeholder {{workflow_title}} to dynamically show the title of the applied discount workflow in the default message.', 'discount-deals' ),
						'{{workflow_title}}'
					),
					'id'    => 'wc_settings_tab_discount_deals_applied_discount_message',
				),
				'general_section_end'               => array(
					'type' => 'sectionend',
					'id'   => 'wc_settings_tab_discount_deals_general_settings_end',
				),
			);
		} elseif ( 'product-price-and-quantity-discount' == $current_section ) {
			return array(
				'product_section_title'        => array(
					'name' => __( 'Product discount', 'discount-deals' ),
					'type' => 'title',
					'desc' => '',
					'id'   => 'wc_settings_tab_discount_deals_product_settings',
				),
				'calculate_discount_from'      => array(
					'name'    => __( 'Calculate the Discount from ', 'discount-deals' ),
					'type'    => 'select',
					'desc'    => sprintf( '%s <br/> %s', __( 'Choose whether you want to calculate the discount based on the regular price or the sale price of the product.', 'discount-deals' ), __( 'For example, if a product is on sale, you can decide which price should be considered when calculating the discount.', 'discount-deals' ) ),
					'options' => array(
						'regular_price' => __( 'Regular Price', 'discount-deals' ),
						'sale_price'    => __( 'Sale Price', 'discount-deals' ),
					),
					'value'   => Discount_Deals_Settings::get_settings( 'calculate_discount_from' ),
					'id'      => 'wc_settings_tab_discount_deals_calculate_discount_from',
				),
				'apply_product_discount_to'    => array(
					'name'    => __( 'Apply Discount To', 'discount-deals' ),
					'type'    => 'select',
					'desc'    =>  __( 'Choose how you want to apply discounts to products with multiple matched workflows. Select the lowest or biggest discount from all matching workflows, or apply all matching workflows\' discounts to the product ', 'discount-deals' ),
					'options' => array(
						'lowest_matched'  => __( 'Lowest one from matched workflows', 'discount-deals' ),
						'biggest_matched' => __( 'Biggest one from matched workflows', 'discount-deals' ),
						// 'first_matched'   => __( 'First matched Workflow\'s Discount', 'discount-deals' ),
						'all_matched'     => __( 'All matching workflows', 'discount-deals' ),
					),
					'value'   => Discount_Deals_Settings::get_settings( 'apply_product_discount_to' ),
					'id'      => 'wc_settings_tab_discount_deals_apply_product_discount_to',
				),
				'apply_discount_subsequently'  => array(
					'name'    => __( 'Calculate Discounts Subsequently?', 'discount-deals' ),
					'type'    => 'radio',
					'options' => array(
						'yes' => __( 'Yes', 'discount-deals' ),
						'no'  => __( 'No', 'discount-deals' ),
					),
					'desc'    => __( 'Decide whether you want to calculate discounts sequentially. If enabled, the first discount will be applied to the full price, and subsequent discounts will be applied to the discounted price.', 'discount-deals' ),
					'value'   => Discount_Deals_Settings::get_settings( 'apply_discount_subsequently' ),
					'id'      => 'wc_settings_tab_discount_deals_apply_discount_subsequently',
				),
				'show_strikeout_price_in_cart' => array(
					'name'    => __( 'Show Strikeout on Cart?', 'discount-deals' ),
					'desc'    => __( 'Choose whether you want to display a strikethrough on the prices of each item in the cart to highlight the discount.', 'discount-deals' ),
					'type'    => 'radio',
					'options' => array(
						'yes' => __( 'Yes', 'discount-deals' ),
						'no'  => __( 'No', 'discount-deals' ),
					),
					'value'   => Discount_Deals_Settings::get_settings( 'show_strikeout_price_in_cart' ),
					'id'      => 'wc_settings_tab_discount_deals_show_strikeout_price_in_cart',
				),
				'where_display_saving_text'    => array(
					'name'    => __( 'Display "You Saved" Text', 'discount-deals' ),
					'desc'    => __( 'Decide if you want to display the message "You Saved" on the storefront. If yes, choose where it should be displayed - after each line item\'s total, after the cart total, or both. Alternatively, you can choose not to display the message at all.', 'discount-deals' ),
					'type'    => 'select',
					'options' => array(
						'disabled'                       => __( 'Don\'t show anywhere ', 'discount-deals' ),
						'on_each_line_item'              => __( 'On after each line item\'s total', 'discount-deals' ),
						'after_total'                    => __( 'On after cart total', 'discount-deals' ),
						'both_line_item_and_after_total' => __( 'On both line item\'s total and cart total', 'discount-deals' ),
					),
					'value'   => Discount_Deals_Settings::get_settings( 'where_display_saving_text' ),
					'id'      => 'wc_settings_tab_discount_deals_where_display_saving_text',
				),
				'you_saved_text'               => array(
					'name'  => __( '"You Saved" Text', 'discount-deals' ),
					'desc'  => __( 'Customize the message that highlights the customer\'s savings. Use the {{discount}} shortcode to dynamically display the discount amount.', 'discount-deals' ),
					'type'  => 'text',
					'value' => Discount_Deals_Settings::get_settings( 'you_saved_text' ),
					'id'    => 'wc_settings_tab_discount_deals_you_saved_text',
				),
				'product_section_end'          => array(
					'type' => 'sectionend',
					'id'   => 'wc_settings_tab_discount_deals_product_settings_end',
				),
			);
		} elseif ( 'cart-discount' == $current_section ) {
			return array(
				// Cart discount.
				'cart_section_title'               => array(
					'name' => __( 'Cart discount', 'discount-deals' ),
					'type' => 'title',
					'desc' => '',
					'id'   => 'wc_settings_tab_discount_deals_cart_settings',
				),
				'apply_cart_discount_to'           => array(
					'name'    => __( 'Apply Discount To', 'discount-deals' ),
					'desc'    => __( 'Choose how you want to apply discounts to the cart with multiple matched workflows. Select the biggest discount with or without free shipping, the lowest discount with or without free shipping, matched free shipping only, or apply all matching workflows\' discounts to the cart.', 'discount-deals' ),
					'type'    => 'select',
					'options' => array(
						'biggest_with_free_shipping'    => __( 'Biggest one from matched workflows and matched free shipping together', 'discount-deals' ),
						'biggest_without_free_shipping' => __( 'Biggest one from matched workflows and ignore matched free shipping', 'discount-deals' ),
						'lowest_with_free_shipping'     => __( 'Lowest one from matched workflows and matched free shipping together', 'discount-deals' ),
						'lowest_without_free_shipping'  => __( 'Lowest one from matched workflows and ignore matched free shipping', 'discount-deals' ),
						'free_shipping_only'            => __( 'Matched free shipping only and ignore other matched workflows', 'discount-deals' ),
						'all_matched'                   => __( 'All matched workflows', 'discount-deals' ),
					),
					'value'   => Discount_Deals_Settings::get_settings( 'apply_cart_discount_to' ),
					'id'      => 'wc_settings_tab_discount_deals_apply_cart_discount_to',
				),
				'apply_cart_discount_subsequently' => array(
					'name'    => __( 'Calculate Discounts Subsequently?', 'discount-deals' ),
					'type'    => 'radio',
					'options' => array(
						'yes' => __( 'Yes', 'discount-deals' ),
						'no'  => __( 'No', 'discount-deals' ),
					),
					'desc'    => __( 'Decide whether you want to calculate discounts sequentially. If enabled, the first discount will be applied to the full price, and subsequent discounts will be applied to the discounted price.', 'discount-deals' ),
					'value'   => Discount_Deals_Settings::get_settings( 'apply_cart_discount_subsequently' ),
					'id'      => 'wc_settings_tab_discount_deals_apply_cart_discount_subsequently',
				),
				'apply_cart_discount_as'           => array(
					'name'    => __( 'Apply Discount As', 'discount-deals' ),
					'desc'    => __( 'Choose the mode for applying the discount on the shopping cart. Our recommended mode is "Coupon".', 'discount-deals' ),
					'type'    => 'radio',
					'options' => array(
						'coupon' => __( 'Coupon', 'discount-deals' ),
						'fee'    => __( 'Fee', 'discount-deals' ),
					),
					'value'   => Discount_Deals_Settings::get_settings( 'apply_cart_discount_as' ),
					'id'      => 'wc_settings_tab_discount_deals_apply_cart_discount_as',
				),
				'apply_coupon_title'               => array(
					'name'        => __( 'Coupon Code', 'discount-deals' ),
					'desc'        => __( 'Create a coupon with a value of \'0\' in Marketing -> Coupons and then enter the coupon code you created in this field. Note: Do not delete or change the discount value of the coupon.', 'discount-deals' ),
					'type'        => 'text',
					'placeholder' => __( 'Enter coupon code here...', 'discount-deals' ),
					'value'       => Discount_Deals_Settings::get_settings( 'apply_coupon_title' ),
					'id'          => 'wc_settings_tab_discount_deals_apply_coupon_title',
				),
				'apply_fee_title'                  => array(
					'name'        => __( 'Fee Text', 'discount-deals' ),
					'type'        => 'text',
					'placeholder' => __( 'Enter fee text here...', 'discount-deals' ),
					'desc'        => __( 'Customize the label for the discount that was charged as a fee.', 'discount-deals' ),
					'value'       => Discount_Deals_Settings::get_settings( 'apply_fee_title' ),
					'id'          => 'wc_settings_tab_discount_deals_apply_fee_title',
				),
				'cart_section_end'                 => array(
					'type' => 'sectionend',
					'id'   => 'wc_settings_tab_discount_deals_cart_settings_end',
				),
			);
		} elseif ( 'bogo-discount' == $current_section ) {
			return array(
				// BOGO discount.
				'bogo_section_title'              => array(
					'name' => __( 'BOGO discount', 'discount-deals' ),
					'type' => 'title',
					'desc' => '',
					'id'   => 'wc_settings_tab_discount_deals_bogo_settings',
				),
				'apply_bogo_discount_to'          => array(
					'name'    => __( 'Apply Discount To', 'discount-deals' ),
					'type'    => 'select',
					'desc'    => __( 'Choose whether you want to provide the discount on the product with the lowest or biggest discount among the matched workflows.', 'discount-deals' ),
					'options' => array(
						'lowest_matched'  => __( 'Lowest one from matched workflows', 'discount-deals' ),
						'biggest_matched' => __( 'Biggest one from matched workflows', 'discount-deals' ),
					),
					'value'   => Discount_Deals_Settings::get_settings( 'lowest_matched' ),
					'id'      => 'wc_settings_tab_discount_deals_apply_bogo_discount_to',
				),
				'bogo_discount_highlight_message' => array(
					'name'  => __( 'Free Item Label', 'discount-deals' ),
					'desc'  => __( 'Customize the message to highlight the free items in the shopping cart. Make sure the message clearly indicates that the product is free.', 'discount-deals' ),
					'type'  => 'text',
					'value' => Discount_Deals_Settings::get_settings( 'bogo_discount_highlight_message' ),
					'id'    => 'wc_settings_tab_discount_deals_bogo_discount_highlight_message',
				),
				'bogo_section_end'                => array(
					'type' => 'sectionend',
					'id'   => 'wc_settings_tab_discount_deals_bogo_settings_end',
				),
			);
		}

		return array();
	}//end get_settings_fields()

}//end class
