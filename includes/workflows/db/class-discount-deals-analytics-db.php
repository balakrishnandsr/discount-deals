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
 * DB class for dd_analytics
 */
class Discount_Deals_Analytics_DB extends Discount_Deals_DB {

	/**
	 * Discount_Deals_Analytics_DB constructor.
	 */
	public function __construct() {
		parent::__construct();

		$this->set_table_name( 'dd_analytics' );
		$this->set_primary_key( 'dd_analytics_id' );
	}//end __construct()


	/**
	 * Get columns and formats
	 *
	 * @return array
	 */
	public function get_columns() {
		return array(
			'dd_analytics_id'  => '%d',
			'dd_workflow_id'   => '%d',
			'dd_order_id'      => '%d',
			'dd_product_id'    => '%d',
			'dd_regular_price' => '%f',
			'dd_sale_price'    => '%f',
			'dd_quantity'      => '%d',
			'dd_total'         => '%f',
			'dd_discount'      => '%f',
			'dd_created_at'    => '%s',
			'dd_updated_at'    => '%s',
		);
	}//end get_columns()


	/**
	 * Get default column values
	 *
	 * @return array
	 */
	public function get_column_defaults() {
		return array(
			'dd_product_id'    => null,
			'dd_regular_price' => null,
			'dd_sale_price'    => null,
			'dd_quantity'      => null,
			'dd_total'         => 0,
			'dd_discount'      => 0,
			'dd_created_at'    => gmdate( 'Y-m-d H:i:s' ),
			'dd_updated_at'    => gmdate( 'Y-m-d H:i:s' ),
		);
	}//end get_column_defaults()

	/**
	 * Add analytics into database
	 *
	 * @param array $data Workflow data.
	 *
	 * @return integer
	 */
	public function insert_analytics( $data = array() ) {
		if ( empty( $data ) || ! is_array( $data ) ) {
			return 0;
		}
		// Set dd_created_at if not set.
		if ( empty( $data['dd_created_at'] ) ) {
			$data['dd_created_at'] = current_time( 'mysql', true );
		}

		// Set dd_updated_at if not set.
		if ( empty( $data['dd_updated_at'] ) ) {
			$data['dd_updated_at'] = current_time( 'mysql', true );
		}

		return $this->insert( $data );
	}

	/**
	 * Delete analytics details by workflow ID.
	 *
	 * @param int $workflow_id Workflow ID
	 *
	 * @return false
	 */
	public function delete_by_workflow( $workflow_id) {
		global $wpdb;
		$row_id = absint( $workflow_id );
		if ( empty( $row_id ) ) {
			return false;
		}

		return $wpdb->query( $wpdb->prepare( "DELETE FROM `{$wpdb->prefix}dd_analytics` WHERE dd_workflow_id = %d", $row_id ) );
	}

}//end class

