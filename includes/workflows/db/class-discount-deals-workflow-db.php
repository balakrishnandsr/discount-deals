<?php
/**
 * This class defines all code necessary to workflow
 *
 * @package    Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * DB class for dd_workflows
 */
class Discount_Deals_Workflow_DB extends Discount_Deals_DB {

	/**
	 * ES_DB_Workflows constructor.
	 */
	public function __construct() {
		parent::__construct();

		$this->set_table_name( 'dd_workflows' );
		$this->set_primary_key( 'id' );
	}//end __construct()


	/**
	 * Get columns and formats
	 */
	public function get_columns() {
		return array(
			'id'         => '%d',
			'title'      => '%s',
			'rules'      => '%s',
			'discounts'  => '%s',
			'meta'       => '%s',
			'status'     => '%d',
			'type'       => '%d',
			'priority'   => '%d',
			'created_at' => '%s',
			'updated_at' => '%s',
		);
	}//end get_columns()


	/**
	 * Get default column values
	 */
	public function get_column_defaults() {
		return array(
			'name'            => null,
			'title'           => null,
			'trigger_name'    => null,
			'trigger_options' => '',
			'rules'           => '',
			'actions'         => '',
			'meta'            => '',
			'status'          => 1,
			'type'            => 0,
			'priority'        => 0,
			'created_at'      => gmdate( 'Y-m-d H:i:s' ),
			'updated_at'      => gmdate( 'Y-m-d H:i:s' ),
		);
	}//end get_column_defaults()

}//end class

