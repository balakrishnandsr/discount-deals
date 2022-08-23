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
 * @class Customer_Tags
 */
class Discount_Deals_Workflow_Rule_Customer_Tags extends Discount_Deals_Workflow_Rule_Preloaded_Select_Abstract {

	public $data_item = 'customer';

	public $is_multi = true;


	function init() {
		parent::init();

		$this->title = __( 'Customer - User Tags', 'discount-deals' );
	}


	/**
	 * @return array
	 */
	function load_select_choices() {
		return Fields_Helper::get_user_tags_list();
	}


	/**
	 * @param $customer \AutomateWoo\Customer
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $customer, $compare, $value ) {

		if ( $customer->is_registered() ) {
			$tags = wp_get_object_terms( $customer->get_user_id(), 'user_tag', [
				'fields' => 'ids'
			]);
		}
		else {
			$tags = [];
		}

		return $this->validate_select( $tags, $compare, $value );
	}

}
