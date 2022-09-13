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
	 * @param string $output Output format.
	 *
	 * @return array|object|null
	 */
	public function get_workflow_by_id( $id, $output ) {
		if ( empty( $id ) ) {
			return null;
		}

		$workflows = $this->get_by_conditions( " dd_id = $id", $output );

		if ( ! empty( $workflows ) && 1 == count( $workflows ) ) {
			return $workflows[0];
		}

		return null;
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
	 * @param array $workflow_data Workflow data.
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


}//end class

