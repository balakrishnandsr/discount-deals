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
 * Customer role rule
 *
 * @class Discount_Deals_Workflow_Rule_Customer_Role
 */
class Discount_Deals_Workflow_Rule_Customer_Role extends Discount_Deals_Workflow_Rule_Preloaded_Select_Abstract {
	/**
	 * What data item should pass in to validate the rule?
	 *
	 * @var string
	 */
	public $data_item = 'customer';

	/**
	 * Init the rule.
	 */
	function init() {
		parent::init();

		$this->title = __( 'Customer - User Role', 'discount-deals' );
	}//end init()

	/**
	 * Load choices for admin to choose from
	 *
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
	}//end load_select_choices()

	/**
	 * Validates rule.
	 *
	 * @param WC_Customer $data_item    The customer.
	 * @param string      $compare_type What variables we're using to compare.
	 * @param array       $value        The values we have to compare. Null is only allowed when $compare is is_not_set.
	 *
	 * @return boolean
	 */
	function validate( $data_item, $compare_type, $value ) {
		return $this->validate_select( $data_item->get_role(), $compare_type, $value );
	}//end validate()


}//end class

