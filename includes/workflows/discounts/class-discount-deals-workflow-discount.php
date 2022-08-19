<?php
/**
 * This class defines all code necessary to workflow discount
 *
 * @package    Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class to handle all the discounts of products and cart
 */
abstract class Discount_Deals_Workflow_Discount {
	/**
	 * Title for the discount type
	 *
	 * @var string $title discount title
	 */
	public $title = '';
	/**
	 * Description for the discount type
	 *
	 * @var string $title discount description
	 */
	public $description = '';
	/**
	 * Name for the discount type
	 *
	 * @var string $name discount type slug
	 */
	public $name = '';
	/**
	 * Data items of the discount
	 *
	 * @var array $supplied_data_items valid data items
	 */
	protected $supplied_data_items = array();

	/**
	 * Discount details
	 *
	 * @var array $discount_details Discount details
	 */
	protected $discount_details = array();

	/**
	 * Class constructor
	 */
	public function __construct() {
	}//end __construct()

	/**
	 * Get discount details
	 *
	 * @return array
	 */
	public function get_discount_details() {
		return $this->discount_details;
	}

	/**
	 * Set discount details to the class
	 *
	 * @param array $discount_details Discount details.
	 */
	public function set_discount_details( $discount_details ) {
		$this->discount_details = $discount_details;
	}

	/**
	 * Get tile of the discount
	 *
	 * @return string
	 */
	public function get_title() {
		return $this->title;
	}//end set_title()

	/**
	 * Set title for the discount
	 *
	 * @param string $title Title for the discount type.
	 *
	 * @return void
	 */
	public function set_title( $title ) {
		$this->title = $title;
	}//end get_title()

	/**
	 * Get description of the discount
	 *
	 * @return string
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * Discount description
	 *
	 * @param string $description Description of the discount.
	 */
	public function set_description( $description ) {
		$this->description = $description;
	}

	/**
	 * Get the name of the discount
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}//end get_name()


	/**
	 * Set name for the discount
	 *
	 * @param string $name Name for the discount type.
	 *
	 * @return void
	 */
	public function set_name( $name ) {
		$this->name = $name;
	}//end set_name()


	/**
	 * Valid data items for discount
	 *
	 * @return array
	 */
	public function get_supplied_data_items() {
		return $this->supplied_data_items;
	}//end get_supplied_data_items()

	/**
	 * Set supplied data items for discount
	 *
	 * @return void
	 */
	abstract public function set_supplied_data_items();

	/**
	 * Admin discount fields
	 * @return mixed|void
	 */
	public function load_fields() {
	}

	/**
	 * Calculate discount for given data item
	 *
	 * @param mixed $data_item Calculate for which data item.
	 * @param mixed $subsequent_price Price.
	 *
	 * @return mixed
	 */
	abstract public function calculate_discount( $data_item, $subsequent_price );

}//end class

