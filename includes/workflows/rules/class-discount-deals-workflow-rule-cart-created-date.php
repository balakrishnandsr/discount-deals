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
	 * @param \AutomateWoo\Cart $cart    The cart.
	 * @param string            $compare What variables we're using to compare.
	 * @param array|null        $value   The values we have to compare. Null is only allowed when $compare is is_not_set.
	 *
	 * @return bool
	 */
	public function validate( $cart, $compare, $value = null ) {
		return $this->validate_date( $compare, $value, $cart->get_date_created() );
	}
}
