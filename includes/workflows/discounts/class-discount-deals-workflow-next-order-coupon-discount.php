<?php
/**
 * This class defines all code necessary to workflow discount
 *
 * @package    Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class to handle simple product level discount
 */
class Discount_Deals_Workflow_Next_Order_Coupon_Discount extends Discount_Deals_Workflow_Discount {

	/**
	 * Class constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->set_supplied_data_items();
		$this->set_title( __( 'Next order coupon discount', 'discount-deals' ) );
		$this->set_short_title( __( 'NOC discount', 'discount-deals' ) );
		$this->set_category( __( 'Order discount', 'discount-deals' ) );
		$this->set_description( __( 'Send one use coupon to the customers based on the conditions.', 'discount-deals' ) );
	}//end __construct()


	/**
	 * Set valid data items type of the discount
	 *
	 * @return void
	 */
	public function set_supplied_data_items() {
		$this->supplied_data_items = array( 'customer', 'order' );
	}//end set_supplied_data_items()

	/**
	 * Load fields for discount settings
	 *
	 * @return false|string
	 */
	public function load_fields() {
		$discount_details = $this->get_discount_details();

		ob_start();
		discount_deals_html(
			array(
				'wrapper_class' => 'discount-options-field-container',
				'id'            => 'discount_deals_workflow_discount_type',
				'label'         => __( 'Configure the discount you want to give to your customers', 'discount-deals' ),
				'html'          => "hai",
				'required'      => true,
			)
		);

		return ob_get_clean();
	}//end load_fields()


	/**
	 * Calculate discount for the product
	 *
	 * @param mixed         $data_item Calculate discount for which data item.
	 * @param integer|float $price     Subsequent price.
	 * @param array         $extra     Extra details for calculate discount.
	 *
	 * @return integer
	 */
	public function calculate_discount( $data_item, $price, $extra = array() ) {
		return 0;
	}//end calculate_discount()

}//end class
