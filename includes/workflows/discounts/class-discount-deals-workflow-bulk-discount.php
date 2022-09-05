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
class Discount_Deals_Workflow_Bulk_Discount extends Discount_Deals_Workflow_Discount {

	/**
	 * Class constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->set_supplied_data_items();
		$this->set_title( __( 'Bulk Discount', 'discount-deals' ) );
		$this->set_description( __( 'Give flat or percentage discount for products with high quantity.', 'discount-deals' ) );
	}//end __construct()


	/**
	 * Set valid data items type of the discount
	 *
	 * @return void
	 */
	public function set_supplied_data_items() {
		$this->supplied_data_items = array( 'customer', 'cart', 'shop', 'product' );
	}//end set_supplied_data_items()


	/**
	 * Calculate discount for the product
	 *
	 * @param mixed $data_item Calculate discount for which data item.
	 * @param float $price     Calculate discount subsequently.
	 *
	 * @return integer
	 */
	public function calculate_discount( $data_item, $price ) {
		return 10;
	}//end calculate_discount()

}//end class
