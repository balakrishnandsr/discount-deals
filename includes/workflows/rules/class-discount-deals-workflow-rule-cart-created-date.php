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
	public $data_item = 'cart';

	/**
	 * Discount_Deals_Workflow_Rule_Cart_Created_Date constructor.
	 */
	public function __construct() {
		$this->has_is_past_comparison = true;
		parent::__construct();
	}//end __construct()


	/**
	 * Init the rule.
	 * 
	 * @return void
	 */
	public function init() {
		$this->title = __( 'Cart - Created Date', 'discount-deals' );
	}//end init()


	/**
	 * Validate cart created rule
	 *
	 * @param WC_Cart $data_item    Data item.
	 * @param string  $compare_type Compare operator.
	 * @param array   $value        List of values.
	 *
	 * @return boolean
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
	}//end validate()

}//end class

