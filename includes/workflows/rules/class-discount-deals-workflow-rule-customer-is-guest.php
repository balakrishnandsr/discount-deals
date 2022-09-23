<?php
/**
 * Customer city rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Discount_Deals_Workflow_Rule_Customer_Is_Guest
 *
 * @credit Inspired by AutomateWoo
 */
class Discount_Deals_Workflow_Rule_Customer_Is_Guest extends Discount_Deals_Workflow_Rule_Bool_Abstract {
	/**
	 * Valid data item
	 *
	 * @var string
	 */
	public $data_item = 'customer';

	/**
	 * Init.
	 *
	 * @return void
	 */
	public function init() {
		$this->title = __( 'Customer - Is Guest', 'discount-deals' );
	}//end init()



	/**
	 * Validates rule.
	 *
	 * @param WC_Customer $data_item    The customer.
	 * @param string      $compare_type What variables we're using to compare.
	 * @param array|null  $value        The values we have to compare. Null is only allowed when $compare is is_not_set.
	 *
	 * @return boolean
	 */
	public function validate( $data_item, $compare_type, $value ) {
		$is_guest = ! $data_item->get_id();

		switch ( $value ) {
			case 'yes':
				return $is_guest;
			case 'no':
				return ! $is_guest;
		}

		return false;
	}//end validate()


}//end class

