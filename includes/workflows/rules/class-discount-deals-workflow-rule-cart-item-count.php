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
	public $data_item = "cart";

	/**
	 * Supports float value or not?
	 * @var bool
	 */
	public $support_floats = false;

	/**
	 * Init the rule
	 */
	function init() {
		$this->title = __( 'Cart - Item Count', 'discount-deals' );
	}

	/**
	 * Validate the Cart items count with given value
	 *
	 * @param WC_Cart $data_item data item.
	 * @param string $compare_type compare operator.
	 * @param array int list of values.
	 *
	 * @return bool
	 */
	function validate( $data_item, $compare_type, $value ) {
		return $this->validate_number( count( $data_item->get_cart_contents() ), $compare_type, $value );
	}

}
