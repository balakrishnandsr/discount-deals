<?php
/**
 * Customer role rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * @class Customer_Role
 */
class Discount_Deals_Workflow_Rule_Customer_Role extends Discount_Deals_Workflow_Rule_Preloaded_Select_Abstract {

	public $data_item = 'customer';


	function init() {
		parent::init();

		$this->title = __( 'Customer - User Role', 'discount-deals' );
	}


	/**
	 * @return array
	 */
	function load_select_choices() {
		global $wp_roles;
		$choices = [];

		foreach ( $wp_roles->roles as $key => $role ) {
			$choices[ $key ] = $role['name'];
		}

		$choices['guest'] = __( 'Guest', 'discount-deals' );

		return $choices;
	}


	/**
	 * @param \AutomateWoo\Customer $customer
	 * @param $compare
	 * @param $value
	 *
	 * @return bool
	 */
	function validate( $customer, $compare, $value ) {
		return $this->validate_select( $customer->get_role(), $compare, $value );
	}

}
