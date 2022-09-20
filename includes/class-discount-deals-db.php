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
	 * @param array   $data            Data.
	 * @param array   $column_formats  Column Formats.
	 * @param array   $column_defaults Column_defaults.
	 * @param boolean $insert          Insert.
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
	 * Bulk insert data into given table.
	 *
	 * @param array  $fields        Fields.
	 * @param string $place_holders Place_holders.
	 * @param array  $values        Values.
	 *
	 * @return boolean
	 */
	public function do_insert( $fields = array(), $place_holders = '', $values = array() ) {
		global $wpdb;

		$fields_str = '`' . implode( '`, `', $fields ) . '`';

		$query  = "INSERT INTO {$this->get_table_name()} ({$fields_str}) VALUES ";
		$query .= implode( ', ', $place_holders );
		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$sql    = $wpdb->prepare( $query, $values );
		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		if ( $wpdb->query( $sql ) ) {
			return true;
		} else {
			return false;
		}
	}//end do_insert()


	/**
	 * Get table name
	 *
	 * @return mixed
	 */
	public function get_table_name() {
		global $wpdb;

		return $wpdb->prefix . $this->table_name;
	}//end get_table_name()


	/**
	 * Set table name.
	 *
	 * @param string $table_name Table_name.
	 * @return void
	 */
	public function set_table_name( $table_name = '' ) {

		$this->table_name = $table_name;
	}//end set_table_name()


	/**
	 * Retrieve a row by the primary key
	 *
	 * @param integer $row_id    Row_id.
	 * @param array   $output    Output.
	 * @param boolean $use_cache Use_cache.
	 *
	 * @return array|object|stdClass|null
	 */
	public function get( $row_id = 0, $output = ARRAY_A, $use_cache = false ) {
		global $wpdb;
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$query = $wpdb->prepare( "SELECT * FROM {$this->get_table_name()} WHERE {$this->get_primary_key()} = %s LIMIT 1;", $row_id );

		return $wpdb->get_row( $query, $output );
	}//end get()


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
	 * Retrieve a row by a specific column / value
	 *
	 * @param array   $column    Column.
	 * @param integer $row_id    Row_id.
	 * @param string  $output    Output.
	 * @param boolean $use_cache Use_cache.
	 *
	 * @return false|mixed
	 */
	public function get_by( $column = array(), $row_id = 0, $output = ARRAY_A, $use_cache = false ) {
		global $wpdb;
		$column = esc_sql( $column );

		$query = $wpdb->prepare( "SELECT * FROM {$this->get_table_name()} WHERE $column = %s LIMIT 1;", $row_id );

		return $wpdb->get_row( $query, $output );
	}//end get_by()


	/**
	 * Get all data from table without any condition
	 *
	 * @return array|object|null
	 */
	public function get_all() {
		return $this->get_by_conditions();
	}//end get_all()


	/**
	 * Get rows by conditions
	 *
	 * @param string  $where     Where.
	 * @param string  $output    Output.
	 * @param boolean $use_cache Use_cache.
	 *
	 * @return false|mixed
	 */
	public function get_by_conditions( $where = '', $output = ARRAY_A, $use_cache = false ) {
		global $wpdb;

		$query = "SELECT * FROM {$this->get_table_name()}";

		if ( ! empty( $where ) ) {
			$query .= " WHERE $where";
		}
		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		return $wpdb->get_results( $query, $output );
	}//end get_by_conditions()


	/**
	 * Retrieve a specific column's value by the primary key.
	 *
	 * @param string  $column    Column.
	 * @param integer $row_id    Row_id.
	 * @param boolean $use_cache Use_cache.
	 *
	 * @return null|string|array
	 */
	public function get_column( $column = '', $row_id = 0, $use_cache = false ) {
		global $wpdb;

		$column = esc_sql( $column );

		if ( $row_id ) {
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$query = $wpdb->prepare( "SELECT $column FROM {$this->get_table_name()} WHERE {$this->get_primary_key()} = %s LIMIT 1;", $row_id );
		} else {
			$query = "SELECT $column FROM {$this->get_table_name()}";
		}

		if ( $row_id ) {
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$result = $wpdb->get_var( $query );
		} else {
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$result = $wpdb->get_col( $query );
		}

		return $result;
	}//end get_column()


	/**
	 * Retrieve a specific column's value by the the specified column / value
	 *
	 * @param string  $column       Column.
	 * @param string  $column_where Column_where.
	 * @param string  $column_value Column_value.
	 * @param boolean $only_one     Only_one.
	 * @param boolean $use_cache    Use_cache.
	 *
	 * @return array|string|null
	 */
	public function get_column_by( $column = '', $column_where = '', $column_value = '', $only_one = true, $use_cache = false ) {
		global $wpdb;

		$column_where = esc_sql( $column_where );

		$column = esc_sql( $column );

		if ( $only_one ) {
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$query = $wpdb->prepare( "SELECT $column FROM {$this->get_table_name()} WHERE $column_where = %s LIMIT 1;", $column_value );
		} else {
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$query = $wpdb->prepare( "SELECT $column FROM {$this->get_table_name()} WHERE $column_where = %s;", $column_value );
		}

		if ( $only_one ) {
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$result = $wpdb->get_var( $query );
		} else {
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$result = $wpdb->get_col( $query );
		}

		return $result;
	}//end get_column_by()


	/**
	 * Get column based on where condition.
	 *
	 * @param string  $column    Column.
	 * @param string  $where     Where.
	 * @param boolean $use_cache Use_cache.
	 *
	 * @return array
	 */
	public function get_column_by_condition( $column = '', $where = '', $use_cache = false ) {
		global $wpdb;

		$column = esc_sql( $column );

		$query = "SELECT $column FROM {$this->get_table_name()}";
		if ( ! empty( $where ) ) {
			$query .= " WHERE $where";
		}
		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		return $wpdb->get_col( $query );
	}//end get_column_by_condition()


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
	 * @param array   $data   Data.
	 * @param string  $where  Where.
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
	 * Delete a row by primary key.
	 *
	 * @param integer $row_id Row_id.
	 *
	 * @return boolean
	 */
	public function delete( $row_id = 0 ) {

		global $wpdb;

		// Row ID must be positive integer.
		$row_id = absint( $row_id );

		if ( empty( $row_id ) ) {
			return false;
		}
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$where = $wpdb->prepare( "{$this->get_primary_key()} = %d", $row_id );

		if ( false === $this->delete_by_condition( $where ) ) {
			return false;
		}

		return true;
	}//end delete()


	/**
	 * Delete records based on $where.
	 *
	 * @param string $where Where.
	 *
	 * @return boolean
	 */
	public function delete_by_condition( $where = '' ) {
		global $wpdb;

		if ( empty( $where ) ) {
			return false;
		}
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		if ( false === $wpdb->query( "DELETE FROM {$this->get_table_name()} WHERE $where" ) ) {// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			return false;
		}

		return true;
	}//end delete_by_condition()


	/**
	 * Delete rows by primary key.
	 *
	 * @param array $row_ids Row_ids.
	 *
	 * @return boolean
	 */
	public function bulk_delete( $row_ids = array() ) {

		if ( ! is_array( $row_ids ) && empty( $row_ids ) ) {
			return false;
		}

		$row_ids_str = $this->prepare_for_in_query( $row_ids );

		$where = "{$this->get_primary_key()} IN( $row_ids_str )";

		if ( false === $this->delete_by_condition( $where ) ) {
			return false;
		}

		return true;
	}//end bulk_delete()


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


	/**
	 * Get total count.
	 *
	 * @param string  $where     Where.
	 * @param boolean $use_cache Use_cache.
	 *
	 * @return string|null
	 */
	public function count( $where = '', $use_cache = false ) {
		global $wpdb;

		$query = "SELECT count(*) FROM {$this->get_table_name()}";

		if ( ! empty( $where ) ) {
			$query .= " WHERE $where";
		}

		return $wpdb->get_var( $query );// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	}//end count()


	/**
	 * Insert data into bulk.
	 *
	 * @param array   $values            Values.
	 * @param integer $length            Length.
	 * @param boolean $return_insert_ids Return_insert_ids.
	 *
	 * @return boolean
	 */
	public function bulk_insert( $values = array(), $length = 100, $return_insert_ids = false ) {
		global $wpdb;

		if ( ! is_array( $values ) ) {
			return false;
		}

		// Get the first value from an array to check data structure.
		$first_value = array_slice( $values, 0, 1 );

		$data = array_shift( $first_value );

		// Set default values.
		$data = wp_parse_args( $data, $this->get_column_defaults() );

		// Initialise column format array.
		$column_formats = $this->get_columns();

		// Remove primary key as we don't require while inserting data.
		unset( $column_formats[ $this->get_primary_key() ] );

		// Force fields to lower case.
		$data = array_change_key_case( $data );

		// White list columns.
		$data = array_intersect_key( $data, $column_formats );

		// Reorder $column_formats to match the order of columns given in $data.
		$data = wp_parse_args( $data, $this->get_column_defaults() );

		$data_keys = array_keys( $data );

		$fields = array_keys( array_merge( array_flip( $data_keys ), $column_formats ) );

		// Convert Batches into smaller chunk.
		$batches = array_chunk( $values, $length );

		$error_flag = false;

		// Holds first and last row ids of each batch insert.
		$bulk_rows_start_end_ids = array();

		foreach ( $batches as $key => $batch ) {

			$place_holders = array();
			$final_values  = array();
			$fields_str    = '';

			foreach ( $batch as $value ) {

				$formats = array();
				foreach ( $column_formats as $column => $format ) {
					$final_values[] = isset( $value[ $column ] ) ? $value[ $column ] : $data[ $column ]; // Set default if we don't have.
					$formats[]      = $format;
				}

				$place_holders[] = '( ' . implode( ', ', $formats ) . ' )';
				$fields_str      = '`' . implode( '`, `', $fields ) . '`';
			}

			$query  = "INSERT INTO {$this->get_table_name()} ({$fields_str}) VALUES ";
			$query .= implode( ', ', $place_holders );
			$sql    = $wpdb->prepare( $query, $final_values ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

			if ( ! $wpdb->query( $sql ) ) { // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
				$error_flag = true;
			} else {
				$start_id = $wpdb->insert_id;
				$end_id   = ( $start_id - 1 ) + count( $batch );
				$bulk_rows_start_end_ids[] = $start_id;
				$bulk_rows_start_end_ids[] = $end_id;
			}
		}

		if ( $return_insert_ids && count( $bulk_rows_start_end_ids ) > 0 ) {
			return array( min( $bulk_rows_start_end_ids ), max( $bulk_rows_start_end_ids ) );
		}

		// Check if error occured during executing the query.
		if ( $error_flag ) {
			return false;
		}

		return true;
	}//end bulk_insert()


	/**
	 * Get ID, Name Map.
	 *
	 * @param string $where Where.
	 *
	 * @return array
	 */
	public function get_id_name_map( $where = '' ) {
		return $this->get_columns_map( $this->get_primary_key(), 'name', $where );
	}//end get_id_name_map()


	/**
	 * Get map of two columns.
	 *
	 * E.g array($column_1 => $column_2).
	 *
	 * @param string $column_1 Column_1.
	 * @param string $column_2 Column_2.
	 * @param string $where    Where.
	 *
	 * @return array
	 */
	public function get_columns_map( $column_1 = '', $column_2 = '', $where = '' ) {
		if ( empty( $column_1 ) || empty( $column_2 ) ) {
			return array();
		}

		$columns = array( $column_1, $column_2 );

		$results = $this->get_columns_by_condition( $columns, $where );

		$map = array();
		if ( count( $results ) > 0 ) {
			foreach ( $results as $result ) {
				$map[ $result[ $column_1 ] ] = $result[ $column_2 ];
			}
		}

		return $map;
	}//end get_columns_map()


	/**
	 * Select few columns based on condition.
	 *
	 * @param array   $columns   Columns.
	 * @param string  $where     Where.
	 * @param string  $output    Output.
	 * @param boolean $use_cache Use_cache.
	 *
	 * @return array|object|null
	 */
	public function get_columns_by_condition( $columns = array(), $where = '', $output = ARRAY_A, $use_cache = false ) {
		global $wpdb;

		if ( ! is_array( $columns ) ) {
			return array();
		}

		$columns = esc_sql( $columns );

		$columns = implode( ', ', $columns );

		$query = "SELECT $columns FROM {$this->get_table_name()}";
		if ( ! empty( $where ) ) {
			$query .= " WHERE $where";
		}

		return $wpdb->get_results( $query, $output ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	}//end get_columns_by_condition()

}//end class


