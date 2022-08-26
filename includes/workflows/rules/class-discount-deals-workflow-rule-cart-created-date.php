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
 * Class Discount_Deals_Workflow_Rule_Cart_Created_Date.
 */
class Discount_Deals_Workflow_Rule_Cart_Created_Date extends Discount_Deals_Workflow_Rule_Date_Abstract {

	/**
	 * What data item should pass in to validate the rule?
	 *
	 * @var string
	 */
	public $data_item = "cart";

	/**
	 * Discount_Deals_Workflow_Rule_Cart_Created_Date constructor.
	 */
	public function __construct() {
		$this->has_is_past_comparison = true;
		parent::__construct();
	}

	/**
	 * Init the rule.
	 */
	public function init() {
		$this->title = __( 'Cart - Created Date', 'discount-deals' );
	}

	/**
	 * Validate cart created rule
	 *
	 * @param WC_Cart $data_item data item.
	 * @param string $compare_type compare operator.
	 * @param array $value list of values.
	 *
	 * @return bool
	 */
	public function validate( $data_item, $compare_type, $value = null ) {
		$created_time = WC()->session->get( 'discount_deals_cart_created_time', false );
		if ( $created_time ) {
			try {
				$date = new Discount_Deals_Date_Time();
				$date->setTimestamp( $created_time );

				return $this->validate_date( $compare_type, $value, $date );
			} catch ( Exception $e ) {

				return false;
			}
		}

		return false;
	}
}
