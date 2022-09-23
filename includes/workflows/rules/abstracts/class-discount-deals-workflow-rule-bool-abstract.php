<?php
/**
 * Bool Abstract rule class.
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Bool rule abstract
 *
 * @credit Inspired by AutomateWoo
 */
abstract class Discount_Deals_Workflow_Rule_Bool_Abstract extends Discount_Deals_Workflow_Rule_Abstract {
	/**
	 * Rule type
	 *
	 * @var string
	 */
	public $type = 'bool';

	/**
	 * Options to select
	 *
	 * @var array
	 */
	public $select_choices;

	/**
	 * Discount_Deals_Workflow_Rule_Bool_Abstract constructor
	 */
	public function __construct() {
		$this->select_choices = array(
			'yes' => __( 'Yes', 'discount-deals' ),
			'no'  => __( 'No', 'discount-deals' ),
		);

		parent::__construct();
	}//end __construct()

}//end class

