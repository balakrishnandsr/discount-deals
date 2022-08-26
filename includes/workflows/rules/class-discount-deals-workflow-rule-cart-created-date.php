<?php
/**
 * Cart created date time rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Cart created date rule.
 */
class Discount_Deals_Workflow_Rule_Cart_Created_Date extends Discount_Deals_Workflow_Rule_Date_Abstract {

	/**
	 * Data item type.
	 *
	 * @var string
	 */
	public $data_item = "cart";

	/**
	 * Cart_Created_Date constructor.
	 */
	public function __construct() {
		$this->has_is_past_comparison = true;
		parent::__construct();
	}

	/**
	 * Init.
	 */
	public function init() {
		$this->title = __( 'Cart - Created Date', 'discount-deals' );
	}

	/**
	 * Validates rule.
	 *
	 * @param \AutomateWoo\Cart $data_item    The cart.
	 * @param string            $compare_type What variables we're using to compare.
	 * @param array|null        $value   The values we have to compare. Null is only allowed when $compare is is_not_set.
	 *
	 * @return bool
	 */
	public function validate( $data_item, $compare_type, $value = null ) {
		return $this->validate_date( $compare_type, $value, $data_item->get_date_created() );
	}
}
