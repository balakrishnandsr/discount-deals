<?php
/**
 * Order shipping method rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Order shipping method rule
 *
 * @credit Inspired by AutomateWoo
 */
class Discount_Deals_Workflow_Rule_Order_Shipping_Method extends Discount_Deals_Workflow_Rule_Preloaded_Select_Abstract {

	/**
	 * What data item should pass in to validate the rule?
	 *
	 * @var string
	 */
	public $data_item = 'order';

	/**
	 * Supports multiple values or not?
	 *
	 * @var boolean
	 */
	public $is_multi = true;

	/**
	 * Init the rule.
	 *
	 * @return void
	 */
	public function init() {
		parent::init();

		$this->title = __( 'Order - Shipping Method', 'discount-deals' );
	}

	/**
	 * Load choices to select
	 *
	 * @return array
	 */
	public function load_select_choices() {
		$choices = [];

		foreach ( WC()->shipping()->get_shipping_methods() as $method_id => $method ) {
			$choices[ $method_id ] = $method->get_method_title();
		}

		return $choices;
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

		$methods = [];

		foreach ( $data_item->get_shipping_methods() as $shipping_line_item ) {
			$methods[] = '';//TODO
		}

		return $this->validate_select( $methods, $compare_type, $value );
	}

}
