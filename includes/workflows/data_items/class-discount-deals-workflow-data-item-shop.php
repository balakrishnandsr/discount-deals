<?php
/**
 * This class defines all code necessary to workflow data items.
 *
 * @package Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class object of each workflow
 */
class Discount_Deals_Workflow_Data_Item_Shop {
	/**
	 * Get the shop's current date time in UTC.
	 *
	 * @return DateTime
	 *
	 * @throws Exception In case of error.
	 */
	public function get_current_datetime() {
		$datetime = new Discount_Deals_Date_Time();
		$datetime->setTimestamp( gmdate( 'U' ) );

		return $datetime;
	}
}
