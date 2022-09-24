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
	 * Discount_Deals_Workflow_DB constructor.
	 */
	public function __construct() {
		parent::__construct();

		$this->set_table_name( 'dd_workflows' );
		$this->set_primary_key( 'dd_id' );
	}//end __construct()


	/**
	 * Get columns and formats
	 *
	 * @return array
	 */
	public function get_columns() {
		return array(
			'dd_id'         => '%d',
			'dd_status'     => '%d',
			'dd_user_id'    => '%d',
			'dd_exclusive'  => '%d',
			'dd_title'      => '%s',
			'dd_rules'      => '%s',
			'dd_discounts'  => '%s',
			'dd_index'      => '%s',
			'dd_promotion'  => '%s',
			'dd_meta'       => '%s',
			'dd_type'       => '%s',
			'dd_language'   => '%s',
			'dd_created_at' => '%s',
			'dd_updated_at' => '%s',
		);
	}//end get_columns()


	/**
	 * Get default column values
	 *
	 * @return array
	 */
	public function get_column_defaults() {
		return array(
			'dd_name'       => null,
			'dd_title'      => null,
			'dd_language'   => null,
			'dd_status'     => 1,
			'dd_user_id'    => 0,
			'dd_rules'      => '',
			'dd_discounts'  => '',
			'dd_index'      => '',
			'dd_promotion'  => '',
			'dd_meta'       => '',
			'dd_type'       => 'simple_discount',
			'dd_exclusive'  => 0,
			'dd_created_at' => gmdate( 'Y-m-d H:i:s' ),
			'dd_updated_at' => gmdate( 'Y-m-d H:i:s' ),
		);
	}//end get_column_defaults()

	/**
	 * Get workflows by id
	 *
	 * @param integer $id Workflow.
	 *
	 * @return array|object|null
	 */
	public function get_workflow_by_id( $id ) {
		global $wpdb;
		if ( empty( $id ) ) {
			return null;
		}

		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}dd_workflows` WHERE dd_id = %d", $id ) );
	}//end get_workflow_by_id()


	/**
	 * Add workflow into database
	 *
	 * @param array $workflow_data Workflow data.
	 *
	 * @return integer
	 */
	public function insert_workflow( $workflow_data = array() ) {
		if ( empty( $workflow_data ) || ! is_array( $workflow_data ) ) {
			return 0;
		}
		// Set dd_created_at if not set.
		if ( empty( $workflow_data['dd_created_at'] ) ) {
			$workflow_data['dd_created_at'] = current_time( 'mysql', true );
		}

		// Set dd_updated_at if not set.
		if ( empty( $workflow_data['dd_updated_at'] ) ) {
			$workflow_data['dd_updated_at'] = current_time( 'mysql', true );
		}

		return $this->insert( $workflow_data );
	}//end insert_workflow()


	/**
	 * Update Workflow
	 *
	 * @param integer $workflow_id Workflow ID.
	 * @param array   $workflow_data Workflow data.
	 *
	 * @return boolean|void
	 */
	public function update_workflow( $workflow_id = 0, $workflow_data = array() ) {

		if ( empty( $workflow_id ) || empty( $workflow_data ) || ! is_array( $workflow_data ) ) {
			return;
		}
		// Set updated_at if not set.
		if ( empty( $workflow_data['dd_updated_at'] ) ) {
			$workflow_data['dd_updated_at'] = current_time( 'mysql', true );
		}

		return $this->update( $workflow_id, $workflow_data );
	}//end update_workflow()

	/**
	 * Get total count.
	 *
	 * @param string $type Type.
	 *
	 * @return string|null
	 */
	public function count( $type = '' ) {
		global $wpdb;
		if ( empty( $type ) ) {
			return $wpdb->get_var( "SELECT count(*) FROM `{$wpdb->prefix}dd_workflows`" );
		}

		return $wpdb->get_var( $wpdb->prepare( "SELECT count(*) FROM `{$wpdb->prefix}dd_workflows` WHERE dd_type=%s", $type ) );
	}

	/**
	 * Delete a row by primary key.
	 *
	 * @param integer $row_id Row_id.
	 *
	 * @return boolean
	 */
	public function delete( $row_id = 0 ) {
		global $wpdb;
		$row_id = absint( $row_id );
		if ( empty( $row_id ) ) {
			return false;
		}

		return $wpdb->query( $wpdb->prepare( "DELETE FROM `{$wpdb->prefix}dd_workflows` WHERE dd_id = %d", $row_id ) );
	}


	/**
	 * Get all active workflows.
	 *
	 * @return array
	 */
	public function get_active_workflows() {
		global $wpdb;

		return $wpdb->get_results( "SELECT * FROM `{$wpdb->prefix}dd_workflows` WHERE dd_status = 1" );
	}

	/**
	 * Get the table of workflows by condition.
	 *
	 * @param string  $order_by Order by.
	 * @param integer $limit Limit.
	 * @param integer $offset Offset value.
	 * @param string  $type Discount type.
	 * @param string  $search Search keyword.
	 *
	 * @return array|object|stdClass[]|null
	 */
	public function get_by_conditions( $order_by, $order, $limit, $offset, $type = '', $search = '' ) {
		$order = strtolower( $order );
		$order_type = "{$order_by}_$order";
		global $wpdb;
		$search = "%{$wpdb->esc_like($search)}%";
		if ( ! empty( $type ) ) {
			switch ( $order_type ) {
				case 'dd_status_asc':
					return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}dd_workflows` WHERE 1 = 1 AND dd_title like %s AND dd_type = %s ORDER BY dd_status ASC LIMIT %d OFFSET %d", $search, $type, $limit, $offset ), ARRAY_A );
				case 'dd_status_desc':
					return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}dd_workflows` WHERE 1 = 1 AND dd_title like %s AND dd_type = %s ORDER BY dd_status DESC LIMIT %d OFFSET %d", $search, $type, $limit, $offset ), ARRAY_A );
				case 'dd_updated_at_asc':
					return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}dd_workflows` WHERE 1 = 1 AND dd_title like %s AND dd_type = %s ORDER BY dd_updated_at ASC LIMIT %d OFFSET %d", $search, $type, $limit, $offset ), ARRAY_A );
				case 'dd_updated_at_desc':
					return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}dd_workflows` WHERE 1 = 1 AND dd_title like %s AND dd_type = %s ORDER BY dd_updated_at DESC LIMIT %d OFFSET %d", $search, $type, $limit, $offset ), ARRAY_A );
				case 'dd_created_at_asc':
					return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}dd_workflows` WHERE 1 = 1 AND dd_title like %s AND dd_type = %s ORDER BY dd_created_at ASC LIMIT %d OFFSET %d", $search, $type, $limit, $offset ), ARRAY_A );
				case 'dd_created_at_desc':
					return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}dd_workflows` WHERE 1 = 1 AND dd_title like %s AND dd_type = %s ORDER BY dd_created_at DESC LIMIT %d OFFSET %d", $search, $type, $limit, $offset ), ARRAY_A );
				case 'dd_title_asc':
					return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}dd_workflows` WHERE 1 = 1 AND dd_title like %s AND dd_type = %s ORDER BY dd_title ASC LIMIT %d OFFSET %d", $search, $type, $limit, $offset ), ARRAY_A );
				case 'dd_title_desc':
					return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}dd_workflows` WHERE 1 = 1 AND dd_title like %s AND dd_type = %s ORDER BY dd_title DESC LIMIT %d OFFSET %d", $search, $type, $limit, $offset ), ARRAY_A );
				default:
					return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}dd_workflows` WHERE 1 = 1 AND dd_title like %s AND dd_type = %s ORDER BY dd_id DESC LIMIT %d OFFSET %d", $search, $type, $limit, $offset ), ARRAY_A );
			}
		} else {
			switch ( $order_type ) {
				case 'dd_status_asc':
					return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}dd_workflows` WHERE 1 = 1 AND dd_title like %s  ORDER BY dd_status ASC LIMIT %d OFFSET %d", $search, $limit, $offset ), ARRAY_A );
				case 'dd_status_desc':
					return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}dd_workflows` WHERE 1 = 1 AND dd_title like %s  ORDER BY dd_status DESC LIMIT %d OFFSET %d", $search, $limit, $offset ), ARRAY_A );
				case 'dd_updated_at_asc':
					return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}dd_workflows` WHERE 1 = 1 AND dd_title like %s  ORDER BY dd_updated_at ASC LIMIT %d OFFSET %d", $search, $limit, $offset ), ARRAY_A );
				case 'dd_updated_at_desc':
					return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}dd_workflows` WHERE 1 = 1 AND dd_title like %s  ORDER BY dd_updated_at DESC LIMIT %d OFFSET %d", $search, $limit, $offset ), ARRAY_A );
				case 'dd_created_at_asc':
					return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}dd_workflows` WHERE 1 = 1 AND dd_title like %s  ORDER BY dd_created_at ASC LIMIT %d OFFSET %d", $search, $limit, $offset ), ARRAY_A );
				case 'dd_created_at_desc':
					return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}dd_workflows` WHERE 1 = 1 AND dd_title like %s  ORDER BY dd_created_at DESC LIMIT %d OFFSET %d", $search, $limit, $offset ), ARRAY_A );
				case 'dd_title_asc':
					return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}dd_workflows` WHERE 1 = 1 AND dd_title like %s  ORDER BY dd_title ASC LIMIT %d OFFSET %d", $search, $limit, $offset ), ARRAY_A );
				case 'dd_title_desc':
					return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}dd_workflows` WHERE 1 = 1 AND dd_title like %s  ORDER BY dd_title DESC LIMIT %d OFFSET %d", $search, $limit, $offset ), ARRAY_A );
				default:
					return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}dd_workflows` WHERE 1 = 1 AND dd_title like %s  ORDER BY dd_id DESC LIMIT %d OFFSET %d", $search, $limit, $offset ), ARRAY_A );
			}
		}
	}//end get_by_conditions()


}//end class

