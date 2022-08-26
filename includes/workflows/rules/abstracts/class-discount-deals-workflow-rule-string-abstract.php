<?php
/**
 * String Abstract rule class.
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * String rule abstract
 */
abstract class Discount_Deals_Workflow_Rule_String_Abstract extends Discount_Deals_Workflow_Rule_Abstract {
	/**
	 * Rule type
	 *
	 * @var string
	 */
	public $type = 'string';

	/**
	 * Discount_Deals_Workflow_Rule_String_Abstract constructor
	 */
	public function __construct() {
		$this->compare_types = $this->get_string_compare_types();
		parent::__construct();
	}//end __construct()

}//end class

