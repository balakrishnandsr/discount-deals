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
 * Preloaded select rule
 */
abstract class Discount_Deals_Workflow_Rule_Preloaded_Select_Abstract extends Discount_Deals_Workflow_Rule_Select_Abstract {
	/**
	 * Cached select options. Leave public for JSON.
	 *
	 * @var array
	 */
	public $select_choices;

	/**
	 * Get the select choices for the rule.
	 *
	 * Choices are cached in memory.
	 *
	 * @return array
	 */
	public function get_select_choices() {
		if ( ! isset( $this->select_choices ) ) {
			/**
			 * discount_deals_rules_preloaded_select_choices
			 *
			 * @since 1.0.0
			 */
			$this->select_choices = apply_filters( 'discount_deals_rules_preloaded_select_choices', $this->load_select_choices(), $this );
		}

		return $this->select_choices;
	}

	/**
	 * Load select choices for rule.
	 *
	 * @return array
	 */
	public function load_select_choices() {
		return array();
	}
}
