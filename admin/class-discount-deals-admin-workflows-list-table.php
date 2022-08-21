<?php
/**
 * The workflows listing functionality in admin area.
 *
 * @package Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Discount_Deals_Admin_Workflows_List_Table
 */
class Discount_Deals_Admin_Workflows_List_Table extends WP_List_Table {
	/**
	 * Class constructor
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => __( 'workflow', 'discount-deals' ),
				'plural'   => __( 'workflows', 'discount-deals' ),
				'ajax'     => false,
			)
		);
	}

	/**
	 * If there is no workflows then print the message to the user
	 *
	 * @return void
	 */
	public function no_items() {
		esc_html_e( 'No workflows found, Please create a new one by clicking here.' );
	}

	/**
	 * Prepare items to display
	 *
	 * @return void
	 */
	public function prepare_items() {
		$columns  = $this->get_columns();
		$hidden   = get_hidden_columns( $this->screen );
		$sortable = $this->get_sortable_columns();

		$this->process_bulk_action();

		$this->_column_headers = array( $columns, $hidden, $sortable );
		$workflows_db          = new Discount_Deals_Workflow_DB();

		$where = ' 1 ';

		// Add search keyword in where query.
		$search_keyword = discount_deals_get_post_data( 's', '' );
		if ( ! empty( $search_keyword ) ) {
			$where .= " AND dd_title like '%{$search_keyword}%' ";
		}

		// Set order by query in where.
		$order_by   = discount_deals_get_data( 'orderby', 'dd_id' );
		$order      = strtolower( discount_deals_get_data( 'order', 'desc' ) );
		$db_columns = $workflows_db->get_columns();
		if ( array_key_exists( $order_by, $db_columns ) && in_array( $order, array( 'asc', 'desc' ) ) ) {
			$where .= " ORDER BY $order_by $order";
		}

		$per_page     = 20;
		$current_page = $this->get_pagenum();
		$offset       = ( $current_page - 1 ) * $per_page;

		$where .= " LIMIT $per_page OFFSET $offset";

		// Get total rows in DB.
		$total_items = $workflows_db->count();

		// Get rows by condition.
		$items = $workflows_db->get_by_conditions( $where );

		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
			)
		);

		$this->items = $items;
	}

	/**
	 * Get columns of the table
	 *
	 * @return string[]
	 */
	public function get_columns() {
		return array(
			'cb'            => '<input type="checkbox" />',
			'dd_title'      => __( 'Title', 'discount-deals' ),
			'dd_type'       => __( 'Discount Type', 'discount-deals' ),
			'dd_exclusive'  => __( 'Is Exclusive', 'discount-deals' ),
			'dd_created_at' => __( 'Created At', 'discount-deals' ),
			'dd_updated_at' => __( 'Updated At', 'discount-deals' ),
			'dd_status'     => __( 'Status', 'discount-deals' ),
		);
	}

	/**
	 * Columns that can be sortable
	 *
	 * @return array[]
	 */
	public function get_sortable_columns() {
		return array(
			'dd_title'      => array( 'dd_title', false ),
			'dd_created_at' => array( 'dd_created_at', false ),
			'dd_updated_at' => array( 'dd_updated_at', false ),
			'dd_status'     => array( 'dd_status', false ),
			'dd_exclusive'  => array( 'dd_exclusive', false ),
		);
	}

	/**
	 * Do bulk option
	 *
	 * @return void
	 */
	public function process_bulk_action() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}
		$current_action = $this->current_action();
		if ( in_array( $current_action, array_keys( $this->get_bulk_actions() ) ) ) {
			$workflow_ids = discount_deals_get_post_data( 'workflow', array(), false );
			if ( ! is_array( $workflow_ids ) ) {
				$workflow_ids = array( $workflow_ids );
			}
			if ( ! empty( $workflow_ids ) ) {
				$workflow_db = new Discount_Deals_Workflow_DB();
				foreach ( $workflow_ids as $workflow_id ) {
					switch ( $current_action ) {
						case "delete":
							$workflow_db->delete( $workflow_id );
							break;
						case "enable":
							$workflow_db->update( $workflow_id, array( 'dd_status' => 1 ) );
							break;
						case "disable":
							$workflow_db->update( $workflow_id, array( 'dd_status' => 0 ) );
							break;
						case "exclusive":
							$workflow_db->update( $workflow_id, array( 'dd_exclusive' => 1 ) );
							break;
						case "not_exclusive":
							$workflow_db->update( $workflow_id, array( 'dd_exclusive' => 0 ) );
							break;
					}
				}
			}
		}
	}

	/**
	 * Bulk actions of the workflows
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		return array(
			'delete'        => __( 'Delete', 'discount-deals' ),
			'enable'        => __( 'Enable', 'discount-deals' ),
			'disable'       => __( 'Disable', 'discount-deals' ),
			'exclusive'     => __( 'Mark as Exclusive', 'discount-deals' ),
			'not_exclusive' => __( 'Mark as Not-Exclusive', 'discount-deals' ),
		);
	}

	/**
	 * Return default value for the workflow
	 *
	 * @param array|object $item Workflow details.
	 * @param string $column_name column name.
	 *
	 * @return bool|mixed|string|void
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'dd_exclusive':
				return ucfirst( $item[ $column_name ] );
			case 'dd_type':
				$discount_type  = $item[ $column_name ];
				$discount_class = Discount_Deals_Workflows::get_discount_type( $discount_type );

				return $discount_class->get_title();
			case 'dd_status':
				if ( 1 == $item[ $column_name ] ) {
					return __( 'Yes', 'discount-deals' );
				}

				return __( 'No', 'discount-deals' );
			default:
				return $item[ $column_name ];
		}
	}

	/**
	 * Display Edit & Delete option for WorkFlow
	 *
	 * @param array $item Workflow details.
	 *
	 * @return string
	 */
	public function column_dd_title( $item ) {
		$actions = array(
			'edit'   => sprintf( '<a href="?page=%s&action=%s&workflow=%s">Edit</a>', discount_deals_get_data( 'page', '' ), 'edit', $item['dd_id'] ),
			'delete' => sprintf( '<a href="?page=%s&action=%s&workflow=%s">Delete</a>', discount_deals_get_data( 'page', '' ), 'delete', $item['dd_id'] ),
		);

		return sprintf( '%1$s %2$s', $item['dd_title'], $this->row_actions( $actions ) );
	}

	/**
	 * Exclusive column details
	 *
	 * @param array $item Workflow details.
	 *
	 * @return void
	 */
	public function column_dd_exclusive( $item ) {
		?>
        <label class="discount-deals-switch">
            <input type="checkbox" data-workflow="<?php echo esc_attr( $item['dd_id'] ) ?>"
                   class="discount-deals-workflow-switch"
                   data-column="exclusive" <?php echo $item['dd_exclusive'] == 1 ? 'checked' : '' ?> />
            <span class="slider round"></span>
        </label>
		<?php
	}

	/**
	 * Status column details
	 *
	 * @param array $item Workflow details.
	 *
	 * @return void
	 */
	public function column_dd_status( $item ) {
		?>
        <label class="discount-deals-switch">
            <input type="checkbox" data-workflow="<?php echo esc_attr( $item['dd_id'] ) ?>"
                   class="discount-deals-workflow-switch"
                   data-column="status" <?php echo $item['dd_status'] == 1 ? 'checked' : '' ?> />
            <span class="slider round"></span>
        </label>
		<?php
	}

	/**
	 * Multi select checkbox
	 *
	 * @param array|object $item Workflow details.
	 *
	 * @return string
	 */
	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="workflow[]" value="%s" />',
			$item['dd_id']
		);
	}
}
