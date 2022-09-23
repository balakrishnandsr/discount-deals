<?php
/**
 * This class defines all code necessary to workflow data
 *
 * @package    Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Data for workflow validation and discount
 *
 * @credit Inspired by AutomateWoo
 */
class Discount_Deals_Workflow_Data_Layer {
	/**
	 * Data items
	 *
	 * @var array $data data items.
	 */
	private $data = array();

	/**
	 * Class constructor Discount_Deals_Workflow_Data_Layer
	 *
	 * @param array $data Data items.
	 */
	public function __construct( $data = array() ) {

		if ( is_array( $data ) ) {
			$this->data         = $data;
			$this->data['shop'] = new Discount_Deals_Workflow_Data_Item_Shop();
		}

	}//end __construct()


	/**
	 * Set item.
	 *
	 * @param string $type Data type.
	 * @param mixed  $item Item.
	 *
	 * @return void
	 */
	public function set_item( $type, $item ) {
		$this->data[ $type ] = $item;
	}//end set_item()

	/**
	 * Get item.
	 *
	 * @param string $type Name.
	 *
	 * @return WC_Customer| WC_Cart | Discount_Deals_Workflow_Data_Item_Shop | false
	 */
	public function get_item( $type ) {
		if ( ! isset( $this->data[ $type ] ) ) {
			return false;
		}

		return $this->data[ $type ];
	}//end get_item()

}//end class

