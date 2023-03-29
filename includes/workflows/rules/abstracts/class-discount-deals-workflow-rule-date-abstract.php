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
abstract class Discount_Deals_Workflow_Rule_Date_Abstract extends Discount_Deals_Workflow_Rule_Abstract {
	/**
	 * The type.
	 *
	 * @var string
	 */
	public $type = 'date';

	/**
	 * We use multiple values to assimilate time frame and time measure.
	 *
	 * @var boolean
	 */
	public $has_multiple_value_fields = true;

	/**
	 * Is it future?
	 *
	 * @var boolean
	 */
	public $has_is_future_comparison = false;

	/**
	 * Is it past?
	 *
	 * @var boolean
	 */
	public $has_is_past_comparison = false;

	/**
	 * Is after specific date?
	 *
	 * @var boolean
	 */
	public $has_is_after = true;

	/**
	 * Is before specific date?
	 *
	 * @var boolean
	 */
	public $has_is_before = true;

	/**
	 * Is it on a specific date?
	 *
	 * @var boolean
	 */
	public $has_is_on = true;

	/**
	 * Isn't on a specific date?
	 *
	 * @var boolean
	 */
	public $has_is_not_on = true;

	/**
	 * Only one date per rule.
	 *
	 * @var boolean
	 */
	public $is_multi = false;

	/**
	 * Is day of the week.
	 *
	 * @var boolean
	 */
	public $has_days_of_the_week = true;

	/**
	 * Is between dates.
	 *
	 * @var boolean
	 */
	public $has_is_between_dates = true;

	/**
	 * Is not set?
	 *
	 * @var boolean
	 */
	public $has_is_not_set = true;

	/**
	 * Is set?
	 *
	 * @var boolean
	 */
	public $has_is_set = true;

	/**
	 * Our rule uses datepicker?
	 *
	 * @var boolean
	 */
	public $uses_datepicker = false;

	/**
	 * Our rule uses date time picker?
	 *
	 * @var boolean
	 */
	public $uses_date_time_picker = false;

	/**
	 * Rule select options.
	 *
	 * @var array
	 */
	public $select_choices;

	/**
	 * Abstract_Date constructor.
	 */
	public function __construct() {
		if ( $this->has_is_future_comparison ) {
			$this->compare_types['is_in_the_next']     = __( 'Is in the next', 'discount-deals' );
			$this->compare_types['is_not_in_the_next'] = __( 'Is not in the next', 'discount-deals' );
		}

		if ( $this->has_is_past_comparison ) {
			$this->compare_types['is_in_the_last']     = __( 'Is in the last', 'discount-deals' );
			$this->compare_types['is_not_in_the_last'] = __( 'Is not in the last', 'discount-deals' );
		}

		if ( $this->has_is_after ) {
			$this->uses_date_time_picker     = true;
			$this->compare_types['is_after'] = __( 'Is after', 'discount-deals' );
		}

		if ( $this->has_is_before ) {
			$this->uses_date_time_picker      = true;
			$this->compare_types['is_before'] = __( 'Is before', 'discount-deals' );
		}

		if ( $this->has_is_on ) {
			$this->uses_datepicker        = true;
			$this->compare_types['is_on'] = __( 'Is on', 'discount-deals' );
		}

		if ( $this->has_is_not_on ) {
			$this->uses_datepicker             = true;
			$this->compare_types ['is_not_on'] = __( 'Is not on', 'discount-deals' );
		}

		if ( $this->has_days_of_the_week ) {
			$this->compare_types['days_of_the_week'] = __( 'Is on day/s of the week', 'discount-deals' );
		}

		if ( $this->has_is_between_dates ) {
			$this->compare_types['is_between'] = __( 'Is in the range', 'discount-deals' );
		}

		if ( $this->has_is_not_set ) {
			$this->compare_types['is_not_set'] = __( 'Is not set', 'discount-deals' );
		}

		if ( $this->has_is_set ) {
			$this->compare_types['is_set'] = __( 'Is set', 'discount-deals' );
		}

		$this->select_choices = array(
			'hours' => __( 'Hours', 'discount-deals' ),
			'days'  => __( 'Days', 'discount-deals' ),
		);

		parent::__construct();
	}//end __construct()


	/**
	 * Validates that we're passing a correct number of days, and we're checking more than 0 days.
	 *
	 * @param string                         $compare What variables we're using to compare.
	 * @param array|integer                  $value   The value to compare.
	 * @param Discount_Deals_Date_Time|false $date    The date used for the comparison. Must be UTC.
	 *
	 * @return boolean
	 * @throws Exception Throws exception.
	 */
	public function validate_date( $compare, $value, $date ) {
		// Make sure that our rule still can run this compare type.
		if ( ! array_key_exists( $compare, $this->compare_types ) ) {
			return false;
		}

		// If we have no date, pass to separate validation method.
		if ( empty( $date ) ) {
			return $this->validate_logical_empty_date( $compare );
		}

		// Normalize date even though it should already be a Discount_Deals_Date_Time instance.
		$date = discount_deals_normalize_date( $date );

		// Validate && sanitize values.
		if ( is_array( $value ) ) {
			$rule_timeframe    = ( ! empty( $value['timeframe'] ) ) ? absint( $value['timeframe'] ) : 0;
			$rule_measure      = ( ! empty( $value['measure'] ) && 'days' === $value['measure'] ) ? $value['measure'] : 'hours';
			$rule_date         = ( ! empty( $value['date'] ) ) ? $value['date'] : '';
			$rule_days_of_week = ( ! empty( $value['dow'] ) ) ? $value['dow'] : array();
			$rule_from         = ( ! empty( $value['from'] ) ) ? $value['from'] : '';
			$rule_to           = ( ! empty( $value['to'] ) ) ? $value['to'] : '';
		} else {
			$rule_timeframe    = absint( $value );
			$rule_measure      = 'hours';
			$rule_date         = '';
			$rule_days_of_week = array();
			$rule_from         = '';
			$rule_to           = '';
		}

		// Verify that the date is set.
		if ( 'is_set' === $compare ) {
			return false !== $date;
		}

		// Date diff. past/future.
		if ( $this->is_past_future_validation( $compare ) ) {
			if ( ! $rule_timeframe ) {
				return false;
			}

			return $this->validate_date_diff( $date, $compare, $rule_timeframe, $rule_measure );
		}

		// Before/After date.
		if ( $this->is_before_after_validation( $compare ) ) {
			if ( ! $rule_date ) {
				return false;
			}

			$rule_date   = new Discount_Deals_Date_Time( $rule_date );
			$comparative = 'before';

			if ( 'is_after' === $compare ) {
				$comparative = 'after';
				// Exclude the current day from after comparisons.
				// Comment $rule_date->set_time_to_day_end();
			}

			// Because this date value is set in the admin it is logically in site's timezone
			// Therefore we must convert it to UTC for the comparison.
			$rule_date->convert_to_utc_time();

			return $this->validate_before_after_date( $date, $rule_date, $comparative );
		}

		// Is/Is Not on same date.
		if ( $this->is_same_date_validation( $compare ) ) {
			if ( ! $rule_date ) {
				return false;
			}
			$rule_date = new Discount_Deals_Date_Time( $rule_date );

			// We must consider that the dates are from the user perspective in this case
			// So do the comparison in the site's timezone.
			$date->convert_to_site_time();

			if ( 'is_on' === $compare ) {
				return $this->validate_same_date( $date, $rule_date );
			}

			if ( 'is_not_on' === $compare ) {
				return ! $this->validate_same_date( $date, $rule_date );
			}
		}

		// Handle day of the week.
		if ( $this->is_days_of_week_validation( $compare ) ) {
			return $this->validate_is_day_of_week( $date, $rule_days_of_week );
		}

		// Handle between validation.
		// This validation is inclusive meaning it starts at 00:00:00 on the 'from date' and ends at 23:59:59 on the 'to date'.
		if ( $this->is_between_dates_validation( $compare ) ) {
			// Require at least a from or a to date, if one isn't set it can default to now.
			if ( ! $rule_from && ! $rule_to ) {
				return false;
			}
			$from = new Discount_Deals_Date_Time( $rule_from );
			$to   = new Discount_Deals_Date_Time( $rule_to );
			// Include the full 'to' day in the date range.
			// Comment $to->set_time_to_day_end();.
			if ( $from > $to ) {
				return false;
			}

			// Because the date values are set in the admin it is logically in site's timezone
			// Convert the validation date to site time also.
			$date->convert_to_site_time();

			return $this->validate_is_between_dates( $date, $from, $to );
		}

		return false;
	}//end validate_date()


	/**
	 * Attempts to do a more logical validation for empty dates.
	 *
	 * This method will ALWAYS validate true when using the comparative 'is_not_in_the_last' or 'is_not_in_the_next'.
	 * This is because if you have the following rule 'workflow has not run for the customer in the last hour',
	 * and in fact the workflow has NEVER run for the customer, it's more logical for validation to be true.
	 *
	 * Logically VALID comparatives for empty dates are:
	 * - is_not_set Date is empty so 'is not set' is logically true
	 * - is_not_in_the_next|is_not_in_the_last Date is empty so it has not happened AT ALL
	 * - is_not_on Date is empty so it's 'not on' all dates, therefore validates true
	 *
	 * Logically INVALID comparatives for empty dates are:
	 * - is_after|is_before It's not before or after any date
	 * - is_on It's not 'on' any date
	 * - is_in_the_next|is_in_the_last Date has not ever happened
	 * - days_of_the_week Date didn't run on any day
	 * - is_between Date can't be between any dates.
	 *
	 * @param string $comparative The type of comparison.
	 *
	 * @return boolean
	 */
	public function validate_logical_empty_date( $comparative ) {
		$valid_comparatives = array(
			'is_not_set',
			'is_not_in_the_next',
			'is_not_in_the_last',
			'is_not_on',
		);

		return in_array( $comparative, $valid_comparatives, true );
	}//end validate_logical_empty_date()


	/**
	 * Are we running a in/not in the next/last validation?
	 *
	 * @param string $compare Compare we want to run.
	 *
	 * @return boolean
	 */
	private function is_past_future_validation( $compare ) {
		return in_array(
			$compare,
			array(
				'is_in_the_next',
				'is_not_in_the_next',
				'is_in_the_last',
				'is_not_in_the_last',
			),
			true
		);
	}//end is_past_future_validation()


	/**
	 * Validates a timeframe between dates and that the timeframe is the expected.
	 *
	 * @param Discount_Deals_Date_Time $date      The date to validate.
	 * @param string                   $compare   Date compare type: is_in_the_next, is_not_in_the_next, is_in_the_last, is_not_in_the_last.
	 * @param integer                  $timeframe The timeframe we want to validate.
	 * @param string                   $measure   Days or Hours.
	 *
	 * @return boolean
	 */
	protected function validate_date_diff( $date, $compare, $timeframe, $measure ) {
		if ( 'days' === $measure ) {
			$interval_spec = 'P' . $timeframe . 'D';
		} else {
			$interval_spec = 'PT' . $timeframe . 'H';
		}

		try {
			$interval = new DateInterval( $interval_spec );
		} catch ( Exception $e ) {
			return false;
		}

		$now             = new Discount_Deals_Date_Time( 'now' );
		$comparison_date = clone $now;

		switch ( $compare ) {
			case 'is_in_the_next':
				$comparison_date->add( $interval );

				return $this->validate_is_between_dates( $date, $now, $comparison_date );
			case 'is_not_in_the_next':
				$comparison_date->add( $interval );

				return ! $this->validate_is_between_dates( $date, $now, $comparison_date );
			case 'is_in_the_last':
				$comparison_date->sub( $interval );

				return $this->validate_is_between_dates( $date, $comparison_date, $now );
			case 'is_not_in_the_last':
				$comparison_date->sub( $interval );

				return ! $this->validate_is_between_dates( $date, $comparison_date, $now );
		}

		return false;
	}//end validate_date_diff()


	/**
	 * Validates that a date is between two other dates.
	 *
	 * All dates must be in the same timezone.
	 *
	 * @param Discount_Deals_Date_Time $date Date that we are checking is between $from and $to.
	 * @param Discount_Deals_Date_Time $from Date we are checking from.
	 * @param Discount_Deals_Date_Time $to   Date we are checking up to.
	 *
	 * @return boolean
	 */
	private function validate_is_between_dates( $date, $from, $to ) {
		if ( $date < $from ) {
			return false;
		}

		if ( $date > $to ) {
			return false;
		}

		return true;
	}//end validate_is_between_dates()


	/**
	 * Are we running a before/after validation?
	 *
	 * @param string $compare Compare we want to run.
	 *
	 * @return boolean
	 */
	private function is_before_after_validation( $compare ) {
		return in_array(
			$compare,
			array(
				'is_after',
				'is_before',
			),
			true
		);
	}//end is_before_after_validation()


	/**
	 * Validates if a date is after/before a second date.
	 *
	 * @param Discount_Deals_Date_Time $date1      Is date1 before/after.
	 * @param Discount_Deals_Date_Time $date2      Date2?.
	 * @param string                   $comparison After/before.
	 *
	 * @return boolean
	 */
	private function validate_before_after_date( $date1, $date2, $comparison ) {
		if ( 'after' === $comparison ) {
			return ( $date1 > $date2 );
		}

		return ( $date1 < $date2 );
	}//end validate_before_after_date()


	/**
	 * Are we running a same/not same date validation?
	 *
	 * @param string $compare Compare we want to run.
	 *
	 * @return boolean
	 */
	private function is_same_date_validation( $compare ) {
		return in_array(
			$compare,
			array(
				'is_on',
				'is_not_on',
			),
			true
		);
	}//end is_same_date_validation()


	/**
	 * Validates if a date is the same, based on Y-m-d format.
	 *
	 * @param Discount_Deals_Date_Time $date1 First date.
	 * @param Discount_Deals_Date_Time $date2 Second date for comparison.
	 *
	 * @return boolean
	 */
	private function validate_same_date( $date1, $date2 ) {
		$format = 'Y-m-d';

		return ( $date1->format( $format ) === $date2->format( $format ) );
	}//end validate_same_date()


	/**
	 * Are we running a day_of_week validation?
	 *
	 * @param string $compare Compare we want to run.
	 *
	 * @return boolean
	 */
	private function is_days_of_week_validation( $compare ) {
		return ( 'days_of_the_week' === $compare );
	}//end is_days_of_week_validation()


	/**
	 * Validates that our day is of the days in the array.
	 *
	 * @param Discount_Deals_Date_Time $date         Must be UTC.
	 * @param array                    $days_of_week Which days of the week we want to search against.
	 *
	 * @return boolean
	 * @throws Exception Throws exception.
	 */
	private function validate_is_day_of_week( $date, $days_of_week ) {
		// Days of the week must be compared in the site's timezone.
		$date->convert_to_site_time();

		$days_of_week = array_map( 'absint', $days_of_week );

		return in_array( absint( $date->format( 'N' ) ), $days_of_week, true );
	}//end validate_is_day_of_week()


	/**
	 * Are we running a between_dates validation?
	 *
	 * @param string $compare Compare we want to run.
	 *
	 * @return boolean
	 */
	private function is_between_dates_validation( $compare ) {
		return ( 'is_between' === $compare );
	}//end is_between_dates_validation()


}//end class
