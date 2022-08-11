<?php
/**
 * This class defines all code necessary to workflow
 *
 * @package    Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Data for workflow validation and discount
 */
class Discount_Deals_Workflow_Data_Layer {
	/**
	 * Set item.
	 *
	 * @param string $name Name.
	 * @param string $item Item.
	 * @return void
	 */
	public function set_item( $name, $item ) {

	}//end set_item()

	/**
	 * Get item.
	 *
	 * @param string $name Name.
	 * @return boolean false
	 */
	public function get_item( $name ) {
		return false;
	}//end get_item()

}//end class

