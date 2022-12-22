<?php
/**
 * Order created date time rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Order created date rule
 *
 * @credit Inspired by AutomateWoo
 */
class Discount_Deals_Workflow_Rule_Order_Created_Date extends Discount_Deals_Workflow_Rule_Date_Abstract {

	/**
	 * Data item type.
	 *
	 * @var string
	 */
	public $data_item = 'order';

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->has_is_past_comparison = true;

		parent::__construct();
	}

	/**
	 * Init the rule.
	 *
	 * @return void
	 */
	public function init() {
		$this->title = __( 'Order - Created Date', 'discount-deals' );
	}

	/**
	 * Validates rule.
	 *
	 * @param WC_Order $data_item The order.
	 * @param string $compare_type What variables we're using to compare.
	 * @param string $value The values we have to compare.
	 *
	 * @return boolean
	 * @throws Exception
	 */
	public function validate( $data_item, $compare_type, $value = null ) {
		return $this->validate_date( $compare_type, $value, aw_normalize_date( $data_item->get_date_created() ) );
	}
}
