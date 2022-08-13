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
class Discount_Deals_Workflow_Simple_Discount extends Discount_Deals_Workflow_Discount {

	/**
	 * Class constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->set_supplied_data_items();
		$this->set_title( __( 'Simple Discount', 'discount-deals' ) );
	}//end __construct()


	/**
	 * Set valid data items type of the discount
	 */
	public function set_supplied_data_items() {
		$this->supplied_data_items = array();
	}//end set_supplied_data_items()


	/**
	 * Calculate discount for the product
	 *
	 * @param mixed $data_item Calculate discount for which data item.
	 *
	 * @return integer
	 */
	public function calculate_discount( $data_item ) {

		// Subsequent discount.
		$discount_array = array(
			'type'         => 'percentage',
			'value'        => 10,
			'max_discount' => 10,
		);
		return 10;
	}//end calculate_discount()

}//end class
