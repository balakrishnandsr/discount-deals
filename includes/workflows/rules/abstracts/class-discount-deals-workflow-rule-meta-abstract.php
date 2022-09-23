<?php
/**
 * Meta Abstract rule class.
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Select rule
 *
 * @credit Inspired by AutomateWoo
 */
abstract class Discount_Deals_Workflow_Rule_Meta_Abstract extends Discount_Deals_Workflow_Rule_Abstract {

	/**
	 * Rule type
	 *
	 * @var string
	 */
	public $type = 'meta';

	/**
	 * Has multiple value fields?
	 *
	 * @var boolean
	 */
	public $has_multiple_value_fields = true;

	/**
	 * Abstract_Meta constructor.
	 */
	public function __construct() {
		$this->compare_types = $this->get_string_compare_types() + $this->get_integer_compare_types();
		parent::__construct();
	}//end __construct()



	/**
	 * Validate a meta value.
	 *
	 * @param mixed  $actual_value   Actual value.
	 * @param string $compare_type   Compare type.
	 * @param mixed  $expected_value Expected value.
	 * @return boolean
	 */
	public function validate_meta( $actual_value, $compare_type, $expected_value ) {
		// Meta compares are a mix of string and number comparisons.
		// Validate as a number for numeric comparisons (greater/less/multiples) and for is/is not ONLY with numeric values.
		if ( $this->is_numeric_meta_field( $compare_type, $expected_value ) ) {
			return $this->validate_number( $actual_value, $compare_type, $expected_value );
		} else {
			return $this->validate_string( $actual_value, $compare_type, $expected_value );
		}
	}//end validate_meta()


	/**
	 * Determine whether the meta field can reasonably be evaluated as a number, specifically for
	 * numeric comparisons (greater/less/multiples) and for numeric is/is not.
	 * This can facilitate better comparisons (for example, "5" = "5.0" in numeric comparisons,
	 * but not in string comparisons).
	 *
	 * @param string $compare_type Compare type.
	 * @param mixed  $value        Compare value.
	 *
	 * @return boolean True if the meta field is determined to be numeric.
	 */
	protected function is_numeric_meta_field( $compare_type, $value ) {
		$is_numeric_compare_type = ( $this->is_integer_compare_type( $compare_type ) && ! $this->is_is_or_is_not_compare_type( $compare_type ) );
		$is_numeric_is_is_not    = ( is_numeric( $value ) && $this->is_is_or_is_not_compare_type( $compare_type ) );

		return $is_numeric_compare_type || $is_numeric_is_is_not;
	}//end is_numeric_meta_field()



	/**
	 * Return an associative array with 'key' and 'value' elements.
	 *
	 * @param mixed $value Value.
	 * @return array|false
	 */
	public function prepare_value_data( $value ) {
		if ( ! is_array( $value ) ) {
			return false;
		}

		return array(
			'key'   => trim( $value[0] ),
			'value' => isset( $value[1] ) ? $value[1] : false,
		);
	}//end prepare_value_data()

}//end class

