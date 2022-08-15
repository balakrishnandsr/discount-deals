<?php
/**
 * Class to load time related stuffs
 *
 * @package    Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class to handle installation of the plugin
 */
class Discount_Deals_Date_Time extends DateTime {

	/**
	 * Same as parent but forces UTC timezone if no timezone is supplied instead of using the PHP default.
	 *
	 * @param string              $time Time.
	 * @param DateTimeZone|string $timezone Time zone.
	 *
	 * @throws Exception Emits Exception in case of an error.
	 */
	public function __construct( $time = 'now', $timezone = null ) {
		if ( ! $timezone ) {
			$timezone = new DateTimeZone( 'UTC' );
		}

		parent::__construct( $time, $timezone instanceof DateTimeZone ? $timezone : null );
	}

	/**
	 * Format date to mySql format
	 *
	 * @return string
	 */
	public function to_mysql_string() {
		return $this->format( 'Y-m-d H:i:s' );
	}

	/**
	 * Set time to the day end in the current timezone.
	 *
	 * @return $this
	 */
	public function set_time_to_day_start() {
		$this->setTime( 0, 0, 0 );

		return $this;
	}

	/**
	 * Convert DateTime from site timezone to UTC.
	 *
	 * Note this doesn't actually set the timezone property, it directly modifies the date.
	 *
	 * @return $this
	 * @throws Exception Throws exception.
	 */
	public function convert_to_utc_time() {
		$this->convert_to_gmt( $this );

		return $this;
	}

	/**
	 * Convert to UTC time
	 *
	 * @param Discount_Deals_Date_Time $datetime Discount deals date time.
	 *
	 * @throws Exception Throws exception.
	 */
	public function convert_to_gmt( $datetime ) {
		$datetime->modify( '-' . $this->get_timezone_offset() * HOUR_IN_SECONDS . ' seconds' );
	}

	/**
	 * Get site timezone offset
	 *
	 * @throws Exception Throws exception.
	 */
	public function get_timezone_offset() {
		$timezone = get_option( 'timezone_string' );
		if ( $timezone ) {
			$timezone_object = new DateTimeZone( $timezone );

			return $timezone_object->getOffset( new DateTime( 'now', new DateTimeZone( 'UTC' ) ) ) / HOUR_IN_SECONDS;
		} else {
			return floatval( get_option( 'gmt_offset', 0 ) );
		}
	}

	/**
	 * Convert DateTime from UTC to the site timezone.
	 *
	 * Note this doesn't actually set the timezone property, it directly modifies the date.
	 *
	 * @return $this
	 * @throws Exception Throws exception.
	 */
	public function convert_to_site_time() {
		$this->convert_from_gmt( $this );

		return $this;
	}

	/**
	 * Convert from UTC time
	 *
	 * @param Discount_Deals_Date_Time $datetime Discount deals date time.
	 *
	 * @throws Exception Throws exception.
	 */
	public function convert_from_gmt( $datetime ) {
		$datetime->modify( '-' . $this->get_timezone_offset() * HOUR_IN_SECONDS . ' seconds' );
	}

	/**
	 * Set time to the day start in the current timezone.
	 *
	 * @return $this
	 */
	public function set_time_to_day_end() {
		$this->setTime( 23, 59, 59 );

		return $this;
	}

	/**
	 * Return a formatted localised date. Wrapper for date_i18n function.
	 *
	 * @param string $format Date format.
	 *
	 * @return string
	 */
	public function format_i18n( $format = 'Y-m-d' ) {
		return date_i18n( $format, $this->getTimestamp() );
	}

	/**
	 * Naturally add months without skipping into the next month.
	 *
	 * @param integer $months_to_add How many months need to add in the given time.
	 *
	 * @throws Exception When months isn't a valid number.
	 */
	public function add_natural_months( $months_to_add ) {
		$original_day = $this->format( 'd' );
		$this->add( new DateInterval( 'P' . intval( $months_to_add ) . 'M' ) );
		$new_day = $this->format( 'd' );

		if ( $original_day !== $new_day ) {
			// Check if the day is changed, if so we skipped to another month.
			// Subtract days to go back to the last day of previous month.
			$this->sub( new DateInterval( 'P' . intval( $new_day ) . 'D' ) );
		}
	}

}//end class
