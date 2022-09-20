<?php
/**
 * Class Discount_Deals_Free_Shipping file.
 *
 * @package Discount_Deals
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Discount_Deals_Free_Shipping' ) ) {
	/**
	 * Free Shipping Main class.
	 */
	class Discount_Deals_Free_Shipping extends WC_Shipping_Method {

		/**
		 * Constructor for your shipping class
		 */
		public function __construct() {
			parent::__construct();
			$title                    = Discount_Deals_Settings::get_settings( 'free_shipping_title', 'free shipping' );
			$this->id                 = 'discount_deals_free_shipping';
			$this->method_title       = __( 'Free shipping', 'discount-deals' );
			$this->method_description = __( 'Free shipping is a special method which can be triggered with coupons and minimum spends.', 'discount-deals' );
			$this->init();
			$this->enabled = true;
			$this->title   = ( empty( $title ) ) ? __( 'Discount Deals Free Shipping', 'discount-deals' ) : __( $title, 'discount-deals' );
		}//end __construct()


		/**
		 * Initialize Wdr free shipping.
		 *
		 * @return void
		 */
		public function init() {
			// Load the settings.
			$this->init_settings();
			// Save settings in admin if you have any defined.
			add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
		}//end init()


		/**
		 * Called to calculate shipping rates for this method. Rates can be added using the add_rate() method.
		 *
		 * @param array $package Shipping package.
		 *
		 * @uses WC_Shipping_Method::add_rate()
		 *
		 * @return void
		 */
		public function calculate_shipping( $package = array() ) {
			$this->add_rate(
				array(
					'label'   => $this->title,
					'cost'    => 0,
					'taxes'   => false,
					'package' => $package,
				)
			);
		}//end calculate_shipping()

	}//end class

}

