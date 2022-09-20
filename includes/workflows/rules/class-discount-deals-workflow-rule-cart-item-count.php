<?php
/**
 * Cart item count rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Discount_Deals_Workflow_Rule_Cart_Item_count.
 */
class Discount_Deals_Workflow_Rule_Cart_Item_Count extends Discount_Deals_Workflow_Rule_Number_Abstract {

	/**
	 * What data item should pass in to validate the rule?
	 *
	 * @var string
	 */
	public $data_item = 'cart';

	/**
	 * Supports float value or not?
	 *
	 * @var boolean
	 */
	public $support_floats = false;

	/**
	 * Init the rule
	 */
	public function init() {
		$this->title = __( 'Cart - Item Count', 'discount-deals' );
	}//end init()


	/**
	 * Validate the Cart items count with given value
	 *
	 * @param WC_Cart $data_item    Data item.
	 * @param string  $compare_type Compare operator.
	 * @param array   $value        int list of values.
	 *
	 * @return boolean
	 */
	public function validate( $data_item, $compare_type, $value ) {
		return $this->validate_number( count( $data_item->get_cart_contents() ), $compare_type, $value );
	}//end validate()


}//end class

