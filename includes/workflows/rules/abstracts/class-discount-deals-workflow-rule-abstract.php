<?php
/**
 * This class defines all code necessary to workflow rule
 *
 * @package    Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class to handle all the discounts of products and cart
 *
 * @credit Inspired by AutomateWoo
 */
abstract class Discount_Deals_Workflow_Rule_Abstract {
	/**
	 * Name of the rule
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Title of the rule
	 *
	 * @var string
	 */
	public $title;

	/**
	 * Group that rules belongs to
	 *
	 * @var string
	 */
	public $group;

	/**
	 * Type of the rule
	 *
	 * @var string string|number|object|select
	 */
	public $type;

	/**
	 * Define the data type used by the rule.
	 *
	 * @var string
	 */
	public $data_item;

	/**
	 * Comparison type that the rule has used
	 *
	 * @var array
	 */
	public $compare_types = array();
	/**
	 * Is that rule has multiple input value fields?
	 *
	 * @var boolean - e.g meta rules have 2 value fields so their value data is an stored as an array
	 */
	public $has_multiple_value_fields = false;
	/**
	 * Some triggers excluded for particular rule
	 *
	 * @var array
	 */
	public $excluded_triggers = array();
	/**
	 * Has address comparison.
	 *
	 * @var boolean
	 */
	public $has_address_comparison = false;

	/**
	 * Address comparison types.
	 *
	 * @var boolean
	 */
	public $address_comparison_types = array();
	/**
	 * Workflow that the rule belongs to
	 *
	 * @var Discount_Deals_Workflow
	 */
	private $workflow;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->init();
		$this->determine_rule_group();
	}//end __construct()


	/**
	 * Init the rule.
	 *
	 * @return void
	 */
	abstract public function init();

	/**
	 * Determine the rule group based on it's title.
	 *
	 * If the group prop is already set that will be used.
	 *
	 * @return void
	 */
	public function determine_rule_group() {
		if ( isset( $this->group ) ) {
			return;
		}

		// Extract the hyphenated part of the title and use as group.
		if ( isset( $this->title ) && strstr( $this->title, '-' ) ) {
			list( $this->group ) = explode( ' - ', $this->title, 2 );
		}

		if ( empty( $this->group ) ) {
			$this->group = __( 'Other', 'discount-deals' );
		}
	}//end determine_rule_group()


	/**
	 * Validates that a given workflow data item passed the rule validation
	 * based on the supplied $compare_type and $value.
	 *
	 * @param WC_Customer| WC_Cart | Discount_Deals_Workflow_Data_Item_Shop $data_item A valid workflow data item e.g. an instance of `\WC_Order` for an order based rule.
	 * @param string $compare_type The user selected compare type for the rule.
	 * @param mixed $value The user entered value for the rule. This value is validated by the validate_value() method beforehand.
	 * @param array $rule Admin configured rule.
	 *
	 * @return boolean
	 */
	abstract public function validate( $data_item, $compare_type, $value, $rule );

	/**
	 * Validate the rule's user entered value.
	 *
	 * @param mixed $value Value.
	 *
	 * @return void
	 * @throws UnexpectedValueException When the value is not valid.
	 *
	 */
	public function validate_value( $value ) {
		// Override this method in child classes.
	}//end validate_value()


	/**
	 * Get rule group
	 *
	 * @return string
	 */
	public function get_group() {
		return $this->group;
	}//end get_group()


	/**
	 * Get rule name
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}//end get_name()


	/**
	 * Set name of the rule
	 *
	 * @param string $name Name for the rule.
	 *
	 * @return void;
	 */
	public function set_name( $name ) {
		$this->name = $name;
	}//end set_name()


	/**
	 * Get rule title
	 *
	 * @return string
	 */
	public function get_title() {
		return $this->title;
	}//end get_title()


	/**
	 * Set workflow
	 *
	 * @param Discount_Deals_Workflow $workflow Workflow.
	 *
	 * @return void
	 */
	public function set_workflow( $workflow ) {
		$this->workflow = $workflow;
	}//end set_workflow()


	/**
	 * Get the multiple string comparison types
	 *
	 * @return array
	 */
	public function get_multi_string_compare_types() {
		return array(
			'contains'    => __( 'any contains', 'discount-deals' ),
			'is'          => __( 'any matches exactly', 'discount-deals' ),
			'starts_with' => __( 'any starts with', 'discount-deals' ),
			'ends_with'   => __( 'any ends with', 'discount-deals' ),
		);
	}//end get_multi_string_compare_types()


	/**
	 * Get multi-select match compare types.
	 *
	 * @return array
	 */
	public function get_multi_select_compare_types() {
		return array(
			'matches_all'  => __( 'matches all', 'discount-deals' ),
			'matches_any'  => __( 'matches any', 'discount-deals' ),
			'matches_none' => __( 'matches none', 'discount-deals' ),
		);
	}//end get_multi_select_compare_types()


	/**
	 * Get includes or not includes compare types.
	 *
	 * @return array
	 */
	public function get_includes_or_not_compare_types() {
		return array(
			'includes'     => __( 'includes in list', 'discount-deals' ),
			'not_includes' => __( 'does not include in list', 'discount-deals' ),
		);
	}//end get_includes_or_not_compare_types()


	/**
	 * Check the comparison type
	 *
	 * @param string $compare_type Compare type.
	 *
	 * @return boolean
	 */
	public function is_string_compare_type( $compare_type ) {
		return array_key_exists( $compare_type, $this->get_string_compare_types() );
	}//end is_string_compare_type()


	/**
	 * Get the string comparison types
	 *
	 * @return array
	 */
	public function get_string_compare_types() {
		return array(
			'contains'     => __( 'contains', 'discount-deals' ),
			'not_contains' => __( 'does not contain', 'discount-deals' ),
			'is'           => __( 'is', 'discount-deals' ),
			'is_not'       => __( 'is not', 'discount-deals' ),
			'starts_with'  => __( 'starts with', 'discount-deals' ),
			'ends_with'    => __( 'ends with', 'discount-deals' ),
			'blank'        => __( 'is blank', 'discount-deals' ),
			'not_blank'    => __( 'is not blank', 'discount-deals' ),
			'regex'        => __( 'matches regex', 'discount-deals' ),
		);
	}//end get_string_compare_types()


	/**
	 * Check the comparison type
	 *
	 * @param string $compare_type Compare type.
	 *
	 * @return boolean
	 */
	public function is_integer_compare_type( $compare_type ) {
		return array_key_exists( $compare_type, $this->get_integer_compare_types() );
	}//end is_integer_compare_type()


	/**
	 * Get the integer comparison types
	 *
	 * @return array
	 */
	public function get_integer_compare_types() {
		return $this->get_float_compare_types() + array(
				'multiple_of'     => __( 'is a multiple of', 'discount-deals' ),
				'not_multiple_of' => __( 'is not a multiple of', 'discount-deals' ),
			);
	}//end get_integer_compare_types()


	/**
	 * Get the Float value comparison types
	 *
	 * @return array
	 */
	public function get_float_compare_types() {
		return $this->get_is_or_not_compare_types() + array(
				'greater_than' => __( 'is greater than', 'discount-deals' ),
				'less_than'    => __( 'is less than', 'discount-deals' ),
			);
	}//end get_float_compare_types()


	/**
	 * Get is/is not compare types.
	 *
	 * @return array
	 */
	public function get_is_or_not_compare_types() {
		return array(
			'is'     => __( 'is', 'discount-deals' ),
			'is_not' => __( 'is not', 'discount-deals' ),
		);
	}//end get_is_or_not_compare_types()

	/**
	 * Get address compare types.
	 *
	 * @return array
	 */
	public function get_address_compare_types() {
		return array(
			'billing'  => __( 'billing address', 'discount-deals' ),
			'shipping' => __( 'shipping address', 'discount-deals' ),
		);
	}//end get_is_or_not_compare_types()


	/**
	 * Check the comparison type
	 *
	 * @param string $compare_type Compare type.
	 *
	 * @return boolean
	 */
	public function is_float_compare_type( $compare_type ) {
		return array_key_exists( $compare_type, $this->get_float_compare_types() );
	}//end is_float_compare_type()


	/**
	 * Get the is/is not comparison type
	 *
	 * @param string $compare_type Compare type.
	 *
	 * @return boolean
	 */
	public function is_is_or_is_not_compare_type( $compare_type ) {
		return array_key_exists( $compare_type, $this->get_is_or_not_compare_types() );
	}//end is_is_or_is_not_compare_type()


	/**
	 * Only supports 'contains', 'is', 'starts_with', 'ends_with'
	 *
	 * @param array $actual_values Actual values for check.
	 * @param string $compare_type Compare type.
	 * @param string $expected_value Expected value.
	 *
	 * @return boolean
	 */
	public function validate_string_multi( $actual_values, $compare_type, $expected_value ) {

		if ( empty( $expected_value ) ) {
			return false;
		}

		// Look for at least one item that validates the text match.
		foreach ( $actual_values as $coupon_code ) {
			if ( $this->validate_string( $coupon_code, $compare_type, $expected_value ) ) {
				return true;
			}
		}

		return false;
	}//end validate_string_multi()

/**
	 * Validate a string based rule value.
	 *
	 * @param string $actual_value Actual value.
	 * @param string $compare_type Compare type.
	 * @param string $expected_value Expected value.
	 *
	 * @return boolean
	 */
	public function validate_string( $actual_value, $compare_type, $expected_value ) {

		$actual_value   = (string) $actual_value;
		$expected_value = (string) $expected_value;

		// Most comparisons are case in-sensitive.
		$actual_value_lowercase   = strtolower( $actual_value );
		$expected_value_lowercase = strtolower( $expected_value );

		switch ( $compare_type ) {

			case 'is':
				return $actual_value_lowercase == $expected_value_lowercase;

			case 'is_not':
				return $actual_value_lowercase != $expected_value_lowercase;

			case 'contains':
				return strstr( $actual_value_lowercase, $expected_value_lowercase ) !== false;

			case 'not_contains':
				return strstr( $actual_value_lowercase, $expected_value_lowercase ) === false;

			case 'starts_with':
				return str_starts_with( $actual_value_lowercase, $expected_value_lowercase );

			case 'ends_with':
				return str_ends_with( $actual_value_lowercase, $expected_value_lowercase );

			case 'blank':
				return empty( $actual_value );

			case 'not_blank':
				return ! empty( $actual_value );

			case 'regex':
				// Regex validation must not use case insensitive values.
				return $this->validate_string_regex( $actual_value, $expected_value );
		}

		return false;
	}

/**
	 * Validates string regex rule.
	 *
	 * @param string $string Input.
	 * @param string $regex Regular expression to check.
	 *
	 * @return boolean
	 */
	protected function validate_string_regex( $string, $regex ) {
		$regex = $this->remove_global_regex_modifier( trim( $regex ) );

		// Add '/' delimiters if none are provided in the regex.
		if ( ! preg_match( '#^/(.+)/[gi]*$#', $regex ) ) {

			// Escape any unescaped delimiters in the regex first.
			if ( preg_match( '#[^\\\\]/#', $regex ) ) {
				$regex = str_replace( '/', '\\/', $regex );
			}

			$regex = '/' . $regex . '/';
		}

		return (bool) @preg_match( $regex, $string );
	}//end validate_string()

/**
	 * Remove the global regex modifier as it is not supported by PHP.
	 *
	 * @param string $regex Regular expression to check.
	 *
	 * @return string
	 */
	protected function remove_global_regex_modifier( $regex ) {
		return preg_replace_callback(
			'/(\/[a-z]+)$/',
			function ( $modifiers ) {
				return str_replace( 'g', '', $modifiers[0] );
			},
			$regex
		);
	}//end validate_string_regex()

	/**
	 * Get the value by sub comparison field.
	 *
	 * @param string $compare Compare operator.
	 * @param string $field Field name.
	 * @param WC_Cart | WC_Customer | WC_Order $data_item Data item.
	 * @param mixed $default Any.
	 *
	 * @return mixed
	 */
	public function get_value_to_validate_by_sub_compare( $compare, $field, $data_item, $default = "" ) {
		switch ( $compare ) {
			case "shipping":
				$method_name = "get_shipping_$field";
				if ( is_callable( array( $data_item, $method_name ) ) ) {
					return $data_item->$method_name();
				}
				break;
			default:
			case "billing":
				$method_name = "get_billing_$field";
				if ( is_callable( array( $data_item, $method_name ) ) ) {
					return $data_item->$method_name();
				}
				break;
		}

		return $default;
	}//end remove_global_regex_modifier()

/**
	 * Check the given two numbers against the operator
	 *
	 * @param string $actual_value Actual values for check.
	 * @param string $compare_type Compare type.
	 * @param string $expected_value Expected value.
	 *
	 * @return boolean
	 */
	public function validate_number( $actual_value, $compare_type, $expected_value ) {

		$actual_value   = (float) $actual_value;
		$expected_value = (float) $expected_value;

		switch ( $compare_type ) {
			case 'is':
				return $actual_value == $expected_value;
			case 'is_not':
				return $actual_value != $expected_value;
			case 'greater_than':
				return $actual_value > $expected_value;
			case 'less_than':
				return $actual_value < $expected_value;
		}

		// Validate 'multiple of' compares, only accept integers.
		if ( ! $this->is_whole_number( $actual_value ) || ! $this->is_whole_number( $expected_value ) ) {
			return false;
		}

		$actual_value   = (int) $actual_value;
		$expected_value = (int) $expected_value;

		switch ( $compare_type ) {
			case 'multiple_of':
				return 0 == $actual_value % $expected_value;
			case 'not_multiple_of':
				return 0 != $actual_value % $expected_value;
		}

		return false;
	}//end validate_number()


	/**
	 * Check the given input is whole number or not
	 *
	 * @param integer $number Input number.
	 *
	 * @return boolean
	 */
	public function is_whole_number( $number ) {
		$number = (float) $number;

		return floor( $number ) == $number;
	}//end is_whole_number()


	/**
	 * Format the given value
	 *
	 * @param mixed $value Value to format.
	 *
	 * @return mixed
	 */
	public function format_value( $value ) {
		return $value;
	}//end format_value()


}//end class
