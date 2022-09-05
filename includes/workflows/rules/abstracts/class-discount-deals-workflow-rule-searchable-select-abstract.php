<?php
/**
 * Select Abstract rule class.
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Searchable select rule
 */
abstract class Discount_Deals_Workflow_Rule_Searchable_Select_Abstract extends Discount_Deals_Workflow_Rule_Select_Abstract {
	/**
	 * The rule type.
	 *
	 * @var string
	 */
	public $type = 'object';

	/**
	 * The CSS class to use on the search field.
	 *
	 * @var string
	 */
	public $class = 'wc-json-search';

	/**
	 * The field placeholder.
	 *
	 * @var string
	 */
	public $placeholder;

	/**
	 * Get the ajax action to use for the AJAX search.
	 *
	 * @return string
	 */
	abstract public function get_search_ajax_action();

	/**
	 * Init.
	 */
	public function init() {
		parent::init();

		$this->placeholder = __( 'Search...', 'discount-deals' );

		if ( ! $this->is_multi ) {
			$this->compare_types = $this->get_includes_or_not_compare_types();
		}
	}//end init()


	/**
	 * Override this method to alter how saved values are displayed.
	 *
	 * @param string $value Value.
	 *
	 * @return string
	 */
	public function get_object_display_value( $value ) {
		return $value;
	}//end get_object_display_value()

}//end class

