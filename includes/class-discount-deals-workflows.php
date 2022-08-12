<?php
/**
 * Class to load discounts and rules
 *
 * @package    Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class to handle installation of the plugin
 */
class Discount_Deals_Workflows {

	/**
	 * Holds all discount types
	 *
	 * @var array $_discounts discount types.
	 */
	protected static $_discounts = array();

	/**
	 * Class constructor
	 */
	public function __construct() {
		$this->load_discounts();
	}

	/**
	 * Function to handle uninstall process
	 *
	 * @return void
	 */
	public function load_discounts() {
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/discounts/class-discount-deals-workflow-discount.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/discounts/class-discount-deals-workflow-simple-discount.php';
	}

	/**
	 * Get all discounts
	 *
	 * @return array
	 */
	public static function get_all_discounts() {
		$valid_discounts = array(
			'simple_discount' => 'Discount_Deals_Workflow_Simple_Discount',
		);
		if ( count( self::$_discounts ) < count( $valid_discounts ) ) {
			foreach ( $valid_discounts as $discount_name => $class_name ) {
				$discount_class = new $class_name();
				/**
				 * Workflow discount
				 * @var Discount_Deals_Workflow_Discount $discount_class Discount.
				 */
				$discount_class->set_name( $discount_name );
				self::$_discounts[ $discount_name ] = new $discount_class();
			}
		}

		return self::$_discounts;
	}

	/**
	 * Function to handle uninstall process
	 *
	 * @return void
	 */
	public function load_rules() {
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule.php';
	}

}//end class
