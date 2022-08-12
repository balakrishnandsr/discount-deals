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
	}

	/**
	 * Set valid data items type of the discount
	 */
	public function set_supplied_data_items() {
		$this->supplied_data_items = array();
	}

	/**
	 * Calculate discount for the product
	 *
	 * @param mixed $data_item Calculate discount for which data item.
	 *
	 * @return int
	 */
	public function calculate_discount( $data_item ) {
		return 10;
	}
}//end class
