<?php
/**
 * This class defines all abstract class for database
 *
 * @package Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * It is an abstract DB class handles most of the operation (CURD)
 */
abstract class Discount_Deals_DB {

	/**
	 * Table name
	 *
	 * @var $table_name
	 */
	public $table_name;

	/**
	 * Table primary key column name
	 *
	 * @var $primary_key
	 */
	public $primary_key;

	/**
	 * Discount_Deals_DB constructor.
	 */
	public function __construct() {
	}//end __construct()


	/**
	 * Prepare data for operation.
	 *
	 * @param array   $data Data.
	 * @param array   $column_formats Column Formats.
	 * @param array   $column_defaults Column_defaults.
	 * @param boolean $insert Insert.
	 *
	 * @return array
	 */
	public static function prepare_data( $data = array(), $column_formats = array(), $column_defaults = array(), $insert = true ) {

		// Set default values.
		if ( $insert ) {
			$data = wp_parse_args( $data, $column_defaults );
		}

		// Force fields to lower case.
		$data = array_change_key_case( $data );

		// White list columns.
		$data = array_intersect_key( $data, $column_formats );

		// Reorder $column_formats to match the order of columns given in $data.
		$data_keys      = array_keys( $data );
		$column_formats = array_merge( array_flip( $data_keys ), $column_formats );

		return array(
			'data'           => $data,
			'column_formats' => $column_formats,
		);
	}//end prepare_data()

	/**
	 * Get table name
	 *
	 * @return string
	 */
	public function get_table_name() {
		global $wpdb;

		return $wpdb->prefix . $this->table_name;
	}//end get_table_name()


	/**
	 * Set table name.
	 *
	 * @param string $table_name Table_name.
	 *
	 * @return void
	 */
	public function set_table_name( $table_name = '' ) {

		$this->table_name = $table_name;
	}//end set_table_name()

	/**
	 * Get primary key of the table
	 *
	 * @return mixed
	 */
	public function get_primary_key() {
		return $this->primary_key;
	}//end get_primary_key()


	/**
	 * Set primary key of the table
	 *
	 * @param mixed $primary_key Primary_key.
	 *
	 * @return void
	 */
	public function set_primary_key( $primary_key = '' ) {
		$this->primary_key = $primary_key;
	}//end set_primary_key()

	/**
	 * Insert a new row.
	 *
	 * @param array  $data Data.
	 * @param string $type Type.
	 *
	 * @return integer
	 */
	public function insert( $data = array(), $type = '' ) {
		global $wpdb;

		// Set default values.
		$data = wp_parse_args( $data, $this->get_column_defaults() );

		// Initialise column format array.
		$column_formats = $this->get_columns();

		// Force fields to lower case.
		$data = array_change_key_case( $data );

		// White list columns.
		$data = array_intersect_key( $data, $column_formats );

		// Reorder $column_formats to match the order of columns given in $data.
		$data_keys      = array_keys( $data );
		$column_formats = array_merge( array_flip( $data_keys ), $column_formats );

		$wpdb->insert( $this->get_table_name(), $data, $column_formats );

		return $wpdb->insert_id;
	}//end insert()


	/**
	 * Get columns default values.
	 *
	 * @return array
	 */
	abstract public function get_column_defaults();

	/**
	 * Get default columns.
	 *
	 * @return array
	 */
	abstract public function get_columns();

	/**
	 * Update a specific row.
	 *
	 * @param integer $row_id Row_id.
	 * @param array   $data Data.
	 * @param string  $where Where.
	 *
	 * @return boolean
	 */
	public function update( $row_id = 0, $data = array(), $where = '' ) {

		global $wpdb;

		// Row ID must be positive integer.
		$row_id = absint( $row_id );

		if ( empty( $row_id ) ) {
			return false;
		}

		if ( empty( $where ) ) {
			$where = $this->get_primary_key();
		}

		// Initialise column format array.
		$column_formats = $this->get_columns();

		// Force fields to lower case.
		$data = array_change_key_case( $data );

		// White list columns.
		$data = array_intersect_key( $data, $column_formats );

		// Reorder $column_formats to match the order of columns given in $data.
		$data_keys       = array_keys( $data );
		$column_formats  = array_merge( array_flip( $data_keys ), $column_formats );
		$update_response = $wpdb->update( $this->get_table_name(), $data, array( $where => $row_id ), $column_formats );

		if ( false === $update_response ) {
			return false;
		}

		return true;
	}//end update()

	/**
	 * Prepare string for SQL IN query.
	 *
	 * @param array $array Array.
	 *
	 * @return string
	 */
	public function prepare_for_in_query( $array = array() ) {

		$array = esc_sql( $array );

		if ( is_array( $array ) && count( $array ) > 0 ) {
			return "'" . implode( "', '", $array ) . "'";
		}

		return '';
	}//end prepare_for_in_query()


	/**
	 * Check whether table installed.
	 *
	 * @return boolean
	 */
	public function installed() {
		return $this->table_exists( $this->get_table_name() );
	}//end installed()


	/**
	 * Check whether table exists or not.
	 *
	 * @param string $table Table.
	 *
	 * @return boolean
	 */
	public function table_exists( $table ) {
		global $wpdb;
		$table = sanitize_text_field( $table );

		return $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) ) === $table;
	}//end table_exists()

}//end class


