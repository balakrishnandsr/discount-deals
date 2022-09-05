<?php
/**
 * Customer tags rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Customer tags rules
 *
 * @class Discount_Deals_Workflow_Rule_Customer_Tags
 */
class Discount_Deals_Workflow_Rule_Customer_Tags extends Discount_Deals_Workflow_Rule_Preloaded_Select_Abstract {
	/**
	 * What data item should pass in to validate the rule?
	 *
	 * @var string
	 */
	public $data_item = 'customer';

	/**
	 * Supports multiple values or not?
	 *
	 * @var boolean
	 */
	public $is_multi = true;

	/**
	 * Init the rule.
	 */
	function init() {
		parent::init();

		$this->title = __( 'Customer - User Tags', 'discount-deals' );
	}//end init()

	/**
	 * Load choices for admin to choose from
	 *
	 * @return array
	 */
	function load_select_choices() {
		return discount_deals_get_user_tags();
	}//end load_select_choices()

	/**
	 * Validates rule.
	 *
	 * @param WC_Customer $data_item    The customer.
	 * @param string      $compare_type What variables we're using to compare.
	 * @param string      $value        The values we have to compare. Null is only allowed when $compare is is_not_set.
	 *
	 * @return boolean
	 */
	function validate( $data_item, $compare_type, $value ) {

		if ( $data_item->get_id() > 0 ) {
			$tags = wp_get_object_terms( $data_item->get_id(), 'user_tag', [
				'fields' => 'ids'
			] );
		} else {
			$tags = [];
		}

		return $this->validate_select( $tags, $compare_type, $value );
	}//end validate()


}//end class

