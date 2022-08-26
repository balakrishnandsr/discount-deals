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
 * Select rule
 */
abstract class Discount_Deals_Workflow_Rule_Select_Abstract extends Discount_Deals_Workflow_Rule_Abstract {
	/**
	 * The rule type.
	 *
	 * @var string
	 */
	public $type = 'select';

	/**
	 * Allow multiple selections?
	 *
	 * @var boolean
	 */
	public $is_multi = false;

	/**
	 * Init rule.
	 */
	public function init() {
		if ( $this->is_multi ) {
			$this->compare_types = $this->get_multi_select_compare_types();
		} else {
			$this->compare_types = $this->get_is_or_not_compare_types();
		}
	}//end init()


	/**
	 * Validate select rule, but case insensitive.
	 *
	 * @param array|string $actual       Will be an array when is_multi prop is true.
	 * @param string       $compare_type Compare type.
	 * @param array|string $expected     Expected value.
	 *
	 * @return boolean
	 */
	public function validate_select_case_insensitive( $actual, $compare_type, $expected ) {
		if ( is_array( $actual ) ) {
			$actual = array_map( 'wc_strtolower', $actual );
		} else {
			$actual = strtolower( (string) $actual );
		}
		$expected = array_map( 'wc_strtolower', (array) $expected );

		return $this->validate_select( $actual, $compare_type, $expected );
	}//end validate_select_case_insensitive()


	/**
	 * Validate a select rule.
	 *
	 * @param string|array $actual       Will be an array when is_multi prop is true.
	 * @param string       $compare_type Compare type.
	 * @param array|string $expected     Expected type.
	 *
	 * @return boolean
	 */
	public function validate_select( $actual, $compare_type, $expected ) {
		if ( $this->is_multi ) {
			// actual can be empty.
			if ( ! $actual ) {
				$actual = array();
			}
			// expected must have a value.
			if ( ! $expected ) {
				return false;
			}
			$actual   = (array) $actual;
			$expected = (array) $expected;
			switch ( $compare_type ) {
				case 'matches_all':
					return count( array_intersect( $expected, $actual ) ) === count( $expected );
				case 'matches_none':
					return count( array_intersect( $expected, $actual ) ) === 0;
				case 'matches_any':
					return count( array_intersect( $expected, $actual ) ) >= 1;
			}
		} else {
			// actual must be scalar, but expected could be multiple values.
			if ( ! is_scalar( $actual ) ) {
				return false;
			}
			// TODO review above exclusions.
			if ( is_array( $expected ) ) {
				$is_equal = in_array( $actual, $expected );
			} else {
				$is_equal = $expected == $actual;
			}
			switch ( $compare_type ) {
				case 'is':
					return $is_equal;
				case 'is_not':
					return ! $is_equal;
			}
		}

		return false;
	}//end validate_select()

}//end class

