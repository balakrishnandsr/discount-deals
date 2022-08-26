<?php
/**
 * Cart total rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Discount_Deals_Workflow_Rule_Cart_Total.
 */
class Discount_Deals_Workflow_Rule_Cart_Total extends Discount_Deals_Workflow_Rule_Number_Abstract {

	/** @var string */
	public $data_item = "cart";

	public $support_floats = true;


	function init() {
		$this->title = __( 'Cart - Total', 'discount-deals' );
	}


	/**
	 * @param Cart $data_item
	 * @param $compare_type
	 * @param $value
	 *
	 * @return bool
	 */
	function validate( $data_item, $compare_type, $value ) {
		return $this->validate_number( $data_item->get_total(), $compare_type, $value );
	}

}
