<?php
/**
 * Order created using rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Order created using rule
 *
 * @credit Inspired by AutomateWoo
 */
class Discount_Deals_Workflow_Rule_Order_Created_Via extends Discount_Deals_Workflow_Rule_Preloaded_Select_Abstract {
	/**
	 * What data item should pass in to validate the rule?
	 *
	 * @var string
	 */
	public $data_item = 'order';

	/**
	 * Init the rule.
	 *
	 * @return void
	 */
	public function init() {
		parent::init();

		$this->title = __( 'Order - Created Via', 'discount-deals' );
	}

	/**
	 * Load select choices.
	 *
	 * @return array
	 */
	public function load_select_choices() {
		return [
			'checkout' => __( 'Checkout', 'discount-deals' ),
			'rest-api' => __( 'REST API', 'discount-deals' ),
		];
	}

	/**
	 * Validates rule.
	 *
	 * @param WC_Order $data_item The order.
	 * @param string $compare_type What variables we're using to compare.
	 * @param string $value The values we have to compare.
	 *
	 * @return boolean
	 */
	public function validate( $data_item, $compare_type, $value ) {
		return $this->validate_select( $data_item->get_created_via(), $compare_type, $value );
	}
}
