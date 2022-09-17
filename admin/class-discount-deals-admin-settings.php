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
	 * @var string $tab_id tab id
	 */
	protected static $tab_id = "discount-deals-settings";

	/**
	 * Init the settings
	 */
	public static function init() {
		add_filter( 'woocommerce_settings_tabs_array', array( __CLASS__, 'add_settings_tab' ), 50 );
		add_action( 'woocommerce_settings_tabs_' . self::$tab_id, array( __CLASS__, 'add_settings' ) );
		add_filter( 'woocommerce_sections_' . self::$tab_id, array( __CLASS__, 'add_sections' ) );
		add_action( 'woocommerce_update_options_' . self::$tab_id, array( __CLASS__, 'save_settings' ) );
	}//end init()

	/**
	 * Add settings sections
	 */
	public static function add_sections() {
		$sections        = array(
			'general'                             => __( 'General', 'discount-deals' ),
			'product-price-and-quantity-discount' => __( 'Product price and quantity based discount', 'discount-deals' ),
			'cart-discount'                       => __( 'Cart subtotal based discount', 'discount-deals' ),
			'bogo-discount'                       => __( 'Buy one Get one discount', 'discount-deals' ),
		);
		$current_section = discount_deals_get_data( 'section', 'general' );
		echo '<ul class="subsubsub">';
		$array_keys = array_keys( $sections );
		foreach ( $sections as $id => $label ) {
			echo '<li><a href="' . admin_url( 'admin.php?page=wc-settings&tab=' . self::$tab_id . '&section=' . sanitize_title( $id ) ) . '" class="' . ( $current_section == $id ? 'current' : '' ) . '">' . $label . '</a> ' . ( end( $array_keys ) == $id ? '' : '|' ) . ' </li>';
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
		if ( discount_deals_get_value_from_array( $_POST, 'save', false ) ) {
			$actual_Settings = array();
			foreach ( $_POST as $key => $value ) {
				if ( str_starts_with( $key, 'wc_settings_tab_discount_deals_' ) ) {
					$actual_key = str_replace( 'wc_settings_tab_discount_deals_', '', $key );

					$actual_Settings[ $actual_key ] = wc_clean( $value );
				}
			}
			Discount_Deals_Settings::save_settings( $actual_Settings );
		}
	}//end save_settings()

	/**
	 * Add settings fields
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
		$current_section = discount_deals_get_data( 'section', 'product-price-and-quantity-discount' );
		if ( 'general' == $current_section ) {
			return array(
				'general_section_title'          => array(
					'name' => __( 'General', 'discount-deals' ),
					'type' => 'title',
					'desc' => '',
					'id'   => 'wc_settings_tab_discount_deals_general_settings',
				),
				'show_applied_discounts_message' => array(
					'name'    => __( 'Would you like to inform your customers about all applied discounts in their shopping cart?', 'discount-deals' ),
					'type'    => 'radio',
					'options' => array(
						'yes' => __( 'Yes', 'discount-deals' ),
						'no'  => __( 'No', 'discount-deals' ),
					),
					'value'   => Discount_Deals_Settings::get_settings( 'show_applied_discounts_message' ),
					'id'      => 'wc_settings_tab_discount_deals_show_applied_discounts_message',
				),
				'combine_applied_discounts_message' => array(
					'name'    => __( 'Would you like to merge multiple message into single message?', 'discount-deals' ),
					'type'    => 'radio',
					'options' => array(
						'yes' => __( 'Yes', 'discount-deals' ),
						'no'  => __( 'No', 'discount-deals' ),
					),
					'value'   => Discount_Deals_Settings::get_settings( 'combine_applied_discounts_message' ),
					'id'      => 'wc_settings_tab_discount_deals_combine_applied_discounts_message',
				),
				'applied_discount_message'       => array(
					'name'  => __( 'Message to be displayed to your customers on the shopping cart page?', 'discount-deals' ),
					'type'  => 'text',
					'value' => Discount_Deals_Settings::get_settings( 'applied_discount_message' ),
					'id'    => 'wc_settings_tab_discount_deals_applied_discount_message',
				),
				'general_section_end'            => array(
					'type' => 'sectionend',
					'id'   => 'wc_settings_tab_discount_deals_general_settings_end',
				)
			);
		} elseif ( 'product-price-and-quantity-discount' == $current_section ) {
			return array(
				'product_section_title'       => array(
					'name' => __( 'Product discount', 'discount-deals' ),
					'type' => 'title',
					'desc' => '',
					'id'   => 'wc_settings_tab_discount_deals_product_settings',
				),
				'calculate_discount_from'     => array(
					'name'    => __( 'Calculate the discount from ', 'discount-deals' ),
					'type'    => 'select',
					'desc'    => sprintf( "%s <br/> %s", __( 'For what price do you want to calculate the discount for the product?', 'discount-deals' ), __( 'Example: the regular price of product A is $50, and the retail price is $45. Which price should be taken into account when calculating the discount?', 'discount-deals' ) ),
					'options' => array(
						'sale_price'    => __( 'Sale price', 'discount-deals' ),
						'regular_price' => __( 'Regular price', 'discount-deals' ),
					),
					'value'   => Discount_Deals_Settings::get_settings( 'calculate_discount_from' ),
					'id'      => 'wc_settings_tab_discount_deals_calculate_discount_from',
				),
				'apply_product_discount_to'   => array(
					'name'    => __( 'Apply discount to', 'discount-deals' ),
					'type'    => 'select',
					'desc'    => sprintf( "%s <br/> %s", __( 'If one or more workflows are applied to the product, which workflow should be used to apply the discount?', 'discount-deals' ), __( 'Note: If you select "All matching workflows", all discounts will be applied to the product.', 'discount-deals' ) ),
					'options' => array(
						'lowest_matched'  => __( 'Lowest one from matched workflows', 'discount-deals' ),
						'biggest_matched' => __( 'Biggest one from matched workflows', 'discount-deals' ),
						// 'first_matched'   => __( 'First matched Workflow\'s Discount', 'discount-deals' ),
						'all_matched'     => __( 'All matching workflows', 'discount-deals' ),
					),
					'value'   => Discount_Deals_Settings::get_settings( 'apply_product_discount_to' ),
					'id'      => 'wc_settings_tab_discount_deals_apply_product_discount_to',
				),
				'apply_discount_subsequently' => array(
					'name'    => __( 'Calculate discount subsequently?', 'discount-deals' ),
					'type'    => 'radio',
					'options' => array(
						'yes' => __( 'Yes', 'discount-deals' ),
						'no'  => __( 'No', 'discount-deals' ),
					),
					'desc'    => __( 'When calculating multiple discounts, calculate the first discount on the full price and the second discount on the discounted price and so on.', 'discount-deals' ),
					'value'   => Discount_Deals_Settings::get_settings( 'apply_discount_subsequently' ),
					'id'      => 'wc_settings_tab_discount_deals_apply_discount_subsequently',
				),
				'product_section_end'         => array(
					'type' => 'sectionend',
					'id'   => 'wc_settings_tab_discount_deals_product_settings_end',
				)
			);
		} elseif ( 'cart-discount' == $current_section ) {
			return array(
				// Cart discount
				'cart_section_title'               => array(
					'name' => __( 'Cart discount', 'discount-deals' ),
					'type' => 'title',
					'desc' => '',
					'id'   => 'wc_settings_tab_discount_deals_cart_settings',
				),
				'apply_cart_discount_to'           => array(
					'name'    => __( 'Apply discount to', 'discount-deals' ),
					'type'    => 'select',
					'options' => array(
						'biggest_with_free_shipping'    => __( 'Biggest one from matched workflows and free shipping together', 'discount-deals' ),
						'biggest_without_free_shipping' => __( 'Biggest one from matched workflows and ignore free shipping', 'discount-deals' ),
						'lowest_with_free_shipping'     => __( 'Lowest one from matched workflows and free shipping together', 'discount-deals' ),
						'lowest_without_free_shipping'  => __( 'Lowest one from matched workflows and ignore free shipping', 'discount-deals' ),
						'free_shipping_only'            => __( 'Free shipping only', 'discount-deals' ),
						'all_matched'                   => __( 'All matching workflows', 'discount-deals' ),
					),
					'desc'    => sprintf( "%s <br/> %s", __( 'If one or more workflows are applied to the cart, which workflow should be used to apply the discount?', 'discount-deals' ), __( 'Note: If you select "All matching workflows", all discounts will be applied to the cart.', 'discount-deals' ) ),
					'value'   => Discount_Deals_Settings::get_settings( 'apply_cart_discount_to' ),
					'id'      => 'wc_settings_tab_discount_deals_apply_cart_discount_to',
				),
				'apply_cart_discount_subsequently' => array(
					'name'    => __( 'Calculate discount subsequently?', 'discount-deals' ),
					'type'    => 'radio',
					'options' => array(
						'yes' => __( 'Yes', 'discount-deals' ),
						'no'  => __( 'No', 'discount-deals' ),
					),
					'desc'    => __( 'When calculating multiple discounts, calculate the first discount on the full price and the second discount on the discounted price and so on.', 'discount-deals' ),
					'value'   => Discount_Deals_Settings::get_settings( 'apply_cart_discount_subsequently' ),
					'id'      => 'wc_settings_tab_discount_deals_apply_cart_discount_subsequently',
				),
				'apply_cart_discount_as'           => array(
					'name'    => __( 'Apply discount as ', 'discount-deals' ),
					'type'    => 'radio',
					'options' => array(
						'coupon' => __( 'Coupon', 'discount-deals' ),
						'fee'    => __( 'Fee', 'discount-deals' ),
					),
					'desc'    => sprintf( "%s <br/> %s", __( 'In which mode would you like to give a discount on the shopping cart? ', 'discount-deals' ), __( 'Note: Our recommended mode is “Coupon”.', 'discount-deals' ) ),
					'value'   => Discount_Deals_Settings::get_settings( 'apply_cart_discount_as' ),
					'id'      => 'wc_settings_tab_discount_deals_apply_cart_discount_as',
				),
				'apply_coupon_title'               => array(
					'name'        => __( 'Coupon code', 'discount-deals' ),
					'type'        => 'text',
					'desc'        => sprintf( "%s <br/> %s", __( 'Create a coupon with a value of \'0\' in Marketing - > Coupons and then fill the above input field with the coupon code you created.', 'discount-deals' ), __( 'Note: Do not delete or change the discount value of the coupon.', 'discount-deals' ) ),
					'placeholder' => __( 'Enter coupon code here...', 'discount-deals' ),
					'value'       => Discount_Deals_Settings::get_settings( 'apply_coupon_title' ),
					'id'          => 'wc_settings_tab_discount_deals_apply_coupon_title',
				),
				'apply_fee_title'                  => array(
					'name'        => __( 'Fee text', 'discount-deals' ),
					'type'        => 'text',
					'placeholder' => __( 'Enter fee text here...', 'discount-deals' ),
					'desc'        => __( 'The label for the discount that was charged as a fee.', 'discount-deals' ),
					'value'       => Discount_Deals_Settings::get_settings( 'apply_fee_title' ),
					'id'          => 'wc_settings_tab_discount_deals_apply_fee_title',
				),
				'show_strikeout_price_in_cart'     => array(
					'name'    => __( 'Show strikeout on cart?', 'discount-deals' ),
					'desc'    => __( 'Would you like to cross out the prices of each item to highlight the discount?', 'discount-deals' ),
					'type'    => 'radio',
					'options' => array(
						'yes' => __( 'Yes', 'discount-deals' ),
						'no'  => __( 'No', 'discount-deals' ),
					),
					'value'   => Discount_Deals_Settings::get_settings( 'show_strikeout_price_in_cart' ),
					'id'      => 'wc_settings_tab_discount_deals_show_strikeout_price_in_cart',
				),
				'where_display_saving_text'        => array(
					'name'    => __( 'Display you saved text', 'discount-deals' ),
					'desc'    => __( 'Do you want to display the message "How much was saved on a customer\'s purchase?" If yes, where should the message be displayed?', 'discount-deals' ),
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
				'you_saved_text'                   => array(
					'name'  => __( 'You saved text', 'discount-deals' ),
					'desc'  => sprintf( "%s <br/> %s <code>%s</code> %s", __( 'The message to highlight the customer\'s savings.', 'discount-deals' ), __( 'Use the ', 'discount-deals' ), '{{discount}}', __( ' shortcode to display the discount amount.', 'discount-deals' ) ),
					'type'  => 'text',
					'value' => Discount_Deals_Settings::get_settings( 'you_saved_text' ),
					'id'    => 'wc_settings_tab_discount_deals_you_saved_text',
				),
				'cart_section_end'                 => array(
					'type' => 'sectionend',
					'id'   => 'wc_settings_tab_discount_deals_cart_settings_end',
				),
			);
		} elseif ( 'bogo-discount' == $current_section ) {
			return array(
				// BOGO discount
				'bogo_section_title'              => array(
					'name' => __( 'BOGO discount', 'discount-deals' ),
					'type' => 'title',
					'desc' => '',
					'id'   => 'wc_settings_tab_discount_deals_bogo_settings',
				),
				'apply_bogo_discount_to'          => array(
					'name'    => __( 'Apply discount to?', 'discount-deals' ),
					'type'    => 'select',
					'desc'    => __( 'If one or more workflows are applied to the product, which workflow should be used to provide the discount?', 'discount-deals' ),
					'options' => array(
						'lowest_matched'  => __( 'Lowest one from matched workflows', 'discount-deals' ),
						'biggest_matched' => __( 'Biggest one from matched workflows', 'discount-deals' )
					),
					'value'   => Discount_Deals_Settings::get_settings( 'lowest_matched' ),
					'id'      => 'wc_settings_tab_discount_deals_apply_bogo_discount_to',
				),
				'bogo_discount_highlight_message' => array(
					'name'  => __( 'Free item text', 'discount-deals' ),
					'desc'  => sprintf( "%s <br/> %s", __( 'The message to highlight the free items in the shopping cart.', 'discount-deals' ), __( 'Note: This message should clearly indicate that the product is free.', 'discount-deals' ) ),
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
