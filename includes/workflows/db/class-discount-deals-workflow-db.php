<?php
/**
 * This class defines all code necessary to workflow DB
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
			'dd_id'         => '%d',
			'dd_title'      => '%s',
			'dd_rules'      => '%s',
			'dd_discounts'  => '%s',
			'dd_meta'       => '%s',
			'dd_status'     => '%d',
			'dd_type'       => '%d',
			'dd_exclusive'  => '%s',
			'dd_language'   => '%s',
			'dd_created_at' => '%s',
			'dd_updated_at' => '%s',
		);
	}//end get_columns()


	/**
	 * Get default column values
	 */
	public function get_column_defaults() {
		return array(
			'dd_name'       => null,
			'dd_title'      => null,
			'dd_rules'      => '',
			'dd_language'   => '',
			'dd_discounts'  => '',
			'dd_meta'       => '',
			'dd_status'     => 1,
			'dd_type'       => 'product',
			'dd_exclusive'  => 'no',
			'dd_created_at' => gmdate( 'Y-m-d H:i:s' ),
			'dd_updated_at' => gmdate( 'Y-m-d H:i:s' ),
		);
	}//end get_column_defaults()

}//end class

