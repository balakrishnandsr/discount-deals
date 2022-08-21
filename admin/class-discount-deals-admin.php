<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @package Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Discount_Deals_Admin
 */
class Discount_Deals_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @var      string $plugin_slug The ID of this plugin.
	 */
	private $plugin_slug;

	/**
	 * The version of this plugin.
	 *
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Workflow listing table of the plugin.
	 *
	 * @var  Discount_Deals_Workflow $_workflow Workflow details.
	 */
	private $_workflow;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_slug = $plugin_name;
		$this->version     = $version;

		$this->include_required_files();

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_head', array( $this, 'add_remove_submenu' ) );
		// Filter to add Settings link on Plugins page.
		add_filter(
			'plugin_action_links_' . plugin_basename( DISCOUNT_DEALS_PLUGIN_FILE ),
			array(
				$this,
				'plugin_action_links',
			)
		);
		add_action( 'admin_init', array( $this, 'plugin_activation_redirect' ) );
		add_action( 'admin_init', array( $this, 'maybe_save_workflow' ) );

		Discount_Deals_Admin_Settings::init();
		Discount_Deals_Admin_Ajax::init();

	}//end __construct()

	/**
	 * Include required files of the admin
	 *
	 * @return void
	 */
	public function include_required_files() {
		require_once DISCOUNT_DEALS_ABSPATH . 'admin/discount-deals-meta-box-functions.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'admin/class-discount-deals-admin-ajax.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'admin/class-discount-deals-admin-settings.php';
	}

	/**
	 * Save the Workflow into DB
	 *
	 * @return array|false|int|string
	 */
	public function maybe_save_workflow() {
		if ( current_user_can( 'manage_woocommerce' ) ) {
			$save_workflow = discount_deals_get_request_data( 'save_discount_deals_workflow' );
			if ( ! $save_workflow ) {
				return false;
			}
			$workflow_nonce = discount_deals_get_request_data( 'discount-deals-workflow-nonce' );
			if ( ! wp_verify_nonce( $workflow_nonce, 'discount-deals-workflow' ) ) {
				return false;
			}
			$posted_data = discount_deals_get_request_data( 'discount_deals_workflow', array(), false );
			$rules       = wc_clean( discount_deals_get_value_from_array( $posted_data, 'rule_options', array() ) );
			$discounts   = wc_clean( discount_deals_get_value_from_array( $posted_data, 'dd_discounts', array() ) );
			$id          = wc_clean( discount_deals_get_value_from_array( $posted_data, 'dd_id', 0 ) );
			$type        = wc_clean( discount_deals_get_value_from_array( $posted_data, 'dd_type', '' ) );
			$title       = wc_clean( discount_deals_get_value_from_array( $posted_data, 'dd_title', '' ) );
			if ( ! empty( $type ) ) {
				$workflow_data = array(
					'dd_title'     => $title,
					'dd_type'      => $type,
					'dd_rules'     => maybe_serialize( $rules ),
					'dd_meta'      => maybe_serialize( array() ),
					'dd_discounts' => maybe_serialize( $discounts ),
					'dd_status'    => wc_clean( discount_deals_get_value_from_array( $posted_data, 'dd_status', '1' ) ),
					'dd_exclusive' => wc_clean( discount_deals_get_value_from_array( $posted_data, 'dd_exclusive', '0' ) ),
					'dd_user_id'   => get_current_user_id(),
				);
				$workflow_db   = new Discount_Deals_Workflow_DB();
				if ( empty( $id ) ) {
					$id = $workflow_db->insert_workflow( $workflow_data );
				} else {
					$workflow = Discount_Deals_Workflow::get_instance( $id );
					if ( $workflow ) {
						$workflow_updated = $workflow_db->update_workflow( $id, $workflow_data );
						if ( ! $workflow_updated ) {
							// Return false if update failed.
							return false;
						}
					}
				}
				$redirect_url = menu_page_url( 'discount-deals', false );
				if ( "save" == $save_workflow ) {
					$redirect_url = add_query_arg(
						array(
							'workflow' => $id,
							'action'   => 'edit',
						),
						$redirect_url
					);
				}
				wp_safe_redirect( $redirect_url );

				return $id;
			}

			return false;
		}

		return false;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @return void
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_slug, plugin_dir_url( __FILE__ ) . 'css/discount-deals-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'jquery-ui-style' );
	}//end enqueue_styles()


	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		$action = discount_deals_get_data( 'action', 'list' );
		if ( 'new' != $action && 'edit' != $action ) {
			wp_enqueue_script( $this->plugin_slug . '-workflows', plugin_dir_url( __FILE__ ) . 'js/discount-deals-admin-workflows.js', array( 'jquery' ), $this->version );
			wp_localize_script( $this->plugin_slug . '-workflows', 'discount_deals_workflows_localize_script', array(
				'nonce' => array(
					'change_column_status' => wp_create_nonce( 'discount_deals_change_workflow_column_status' )
				)
			) );

			// Don't load meta boxes if it is not an add/edit workflow screen.
			return;
		}
		$workflow_id = intval( discount_deals_get_data( 'workflow', 0 ) );
		if ( 'edit' === $action && 0 < $workflow_id ) {
			$this->_workflow = Discount_Deals_Workflow::get_instance( $workflow_id );
		}
		wp_enqueue_script( 'wc-enhanced-select' );
		wp_enqueue_script( 'jquery-tiptip' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-ui-autocomplete' );

		wp_enqueue_script(
			$this->plugin_slug . '-workflow',
			plugin_dir_url( __FILE__ ) . 'js/discount-deals-admin-workflow.js',
			array(
				'jquery',
				'wp-util',
				'backbone',
				'underscore',
			),
			$this->version
		);
		wp_localize_script( $this->plugin_slug . '-workflow', 'discount_deals_workflow_localize_script', $this->get_js_data() );

	}//end enqueue_scripts()

	/**
	 * Data to localize
	 *
	 * @return array
	 */
	public function get_js_data() {
		$rule_options     = array();
		$discount_options = false;
		$workflow         = $this->get_workflow();
		if ( $workflow ) {
			$rule_options     = $workflow->get_rules();
			$discount_options = self::get_discount_data( $workflow->get_discount() );
			foreach ( $rule_options as &$rule_group ) {
				foreach ( $rule_group as &$rule ) {
					$rule_object = Discount_Deals_Workflows::get_rule_type( $rule['name'] );
					if ( ! $rule_object ) {
						continue;
					}
					if ( 'object' == $rule_object->type ) {
						$a = 1;
					} else {
						// Format the rule value.
						$rule['value'] = $rule_object->format_value( $rule['value'] );
					}
					if ( 'select' == $rule_object->type ) {
						$a = 1;
					}
				}
			}
		}

		return array(
			'id'            => 1,
			'is_new'        => ( ! $workflow ),
			'discount_type' => $discount_options,
			'rule_options'  => $rule_options,
			'all_rules'     => self::get_rules_data(),
		);
	}

	/**
	 * Get workflow
	 *
	 * @return Discount_Deals_Workflow
	 */
	public function get_workflow() {
		return $this->_workflow;
	}

	/**
	 * Get discount data
	 *
	 * @param Discount_Deals_Workflow_Discount $discount discount of the workflow.
	 *
	 * @return array|false
	 */
	public static function get_discount_data( $discount ) {
		$data = array();

		if ( ! $discount ) {
			return false;
		}

		$data['title']               = $discount->get_title();
		$data['name']                = $discount->get_name();
		$data['description']         = $discount->get_description();
		$data['supplied_data_items'] = array_values( $discount->get_supplied_data_items() );

		return $data;
	}

	/**
	 * Get all the rule's data for admin
	 *
	 * @return array
	 */
	public static function get_rules_data() {
		$data = array();

		foreach ( Discount_Deals_Workflows::get_all_rules() as $rule ) {
			$rule_data = (array) $rule;
			if ( is_callable( array( $rule, 'get_search_ajax_action' ) ) ) {
				$rule_data['ajax_action'] = $rule->get_search_ajax_action();
			}
			$data[ $rule->get_name() ] = $rule_data;
		}

		return $data;
	}

	/**
	 * Admin menus
	 *
	 * @return void
	 */
	public function add_admin_menu() {
		// Translators: A small arrow.
		$admin_page_hook = add_submenu_page(
			'woocommerce',
			__( 'Discount Deals', 'discount-deals' ),
			__( 'Discount Deals', 'discount-deals' ),
			'manage_woocommerce',
			'discount-deals',
			array(
				$this,
				'discount_deals_main_page',
			)
		);

		$get_page = discount_deals_get_data( 'page', '' );

		if ( 'discount-deals-welcome-doc' === $get_page ) {
			add_submenu_page(
				'woocommerce',
				__( 'Getting Started', 'discount-deals' ),
				__( 'Getting Started', 'discount-deals' ),
				'manage_woocommerce',
				'discount-deals-welcome-doc',
				array(
					$this,
					'welcome_docs_page',
				)
			);
		}

		add_action( "load-$admin_page_hook", array( $this, 'register_meta_boxes' ) );
		add_action( "admin_footer-$admin_page_hook", array( $this, 'print_script_in_footer' ) );
		add_filter( 'woocommerce_screen_ids', array( $this, 'set_wc_screen_ids' ) );

	}//end add_admin_menu()

	/**
	 * Add our screen id to woocommerce screens
	 *
	 * @param array $screen WooCommerce Screens.
	 *
	 * @return array
	 */
	public function set_wc_screen_ids( $screen ) {
		$screen[] = 'admin_page_discount-deals';

		return $screen;
	}

	/**
	 * Print admin meta box init scripts
	 *
	 * @return void
	 */
	public function print_script_in_footer() {
		$action = discount_deals_get_data( 'action', 'list' );

		if ( 'new' != $action && 'edit' != $action ) {
			// Don't load meta boxes if it is not an add/edit workflow screen.
			return;
		}
		?>
        <script>
            jQuery(document).ready(function () {
                postboxes.add_postbox_toggles(pagenow);
            });
        </script>
		<?php
	}

	/**
	 * Add screen options for workflow listing page
	 *
	 * @return void
	 */
	public function register_meta_boxes() {
		$action = discount_deals_get_data( 'action', 'list' );

		if ( 'new' != $action && 'edit' != $action ) {
			// Don't load meta boxes if it is not an add/edit workflow screen.
			return;
		}

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_filter( 'screen_options_show_screen', array( $this, 'remove_screen_options' ) );

		// Trigger the add_meta_boxes hooks to allow meta boxes to be added.
		do_action( 'add_meta_boxes', 'discount_deals_workflows', null );

		// Enqueue WordPress' script for handling the meta boxes.
		wp_enqueue_script( 'postbox' );

		// Add screen option: user can choose between 1 or 2 columns (default 2).
		add_screen_option(
			'layout_columns',
			array(
				'max'     => 2,
				'default' => 2,
			)
		);
	}

	/**
	 * Add meta boxes to workflow
	 *
	 * @return void
	 */
	public function add_meta_boxes() {
		add_meta_box(
			'discount_deals_workflow_discounts_box',
			__( 'Discounts', 'discount-deals' ),
			array(
				$this,
				'discounts_meta_box',
			),
			'admin_page_discount-deals',
			'normal',
			'high'
		);

		add_meta_box(
			'discount_deals_workflow_rules_box',
			__( 'Rules (Optional)', 'discount-deals' ),
			array(
				$this,
				'rules_meta_box',
			),
			'admin_page_discount-deals',
			'normal',
			'core'
		);

		add_meta_box(
			'discount_deals_workflow_save_box',
			__( 'Save', 'discount-deals' ),
			array(
				$this,
				'save_meta_box',
			),
			'admin_page_discount-deals',
			'side'
		);
	}

	/**
	 * Add discount meta box to add/edit workflow page
	 *
	 * @return void
	 */
	public function discounts_meta_box() {
		require_once DISCOUNT_DEALS_ABSPATH . 'admin/partials/meta_boxes/workflow-meta-box-discounts.php';
	}

	/**
	 * Add rules meta box to add/edit workflow page
	 *
	 * @return void
	 */
	public function rules_meta_box() {
		require_once DISCOUNT_DEALS_ABSPATH . 'admin/partials/meta_boxes/workflow-meta-box-rules.php';
	}

	/**
	 * Add save workflow meta box to add/edit workflow page
	 *
	 * @return void
	 */
	public function save_meta_box() {
		require_once DISCOUNT_DEALS_ABSPATH . 'admin/partials/meta_boxes/workflow-meta-box-save.php';
	}

	/**
	 * Method to remove screen options tab on workflow add/edit page.
	 *
	 * @param bool $show_screen_options Show/Hide Screen options.
	 *
	 * @return bool
	 */
	public function remove_screen_options( $show_screen_options ) {
		return false;
	}

	/**
	 * Remove Affiliate For WooCommerce's unnecessary submenus.
	 *
	 * @return void
	 */
	public function add_remove_submenu() {
		remove_submenu_page( 'discount-deals', 'discount-deals-welcome-doc' );
	}//end add_remove_submenu()


	/**
	 * Function to add more action on plugins page
	 *
	 * @param array $links Existing links.
	 *
	 * @return array $links
	 */
	public function plugin_action_links( $links ) {

		$settings_link = add_query_arg(
			array(
				'page' => 'wc-settings',
				'tab'  => 'discount-deals-settings',
			),
			admin_url( 'admin.php' )
		);

		$getting_started_link = add_query_arg( array( 'page' => 'discount-deals-welcome-doc' ), admin_url( 'admin.php' ) );

		$action_links = array(
			'getting-started' => '<a href="' . esc_url( $getting_started_link ) . '">' . esc_html( __( 'Getting started', 'discount-deals' ) ) . '</a>',
			'settings'        => '<a href="' . esc_url( $settings_link ) . '">' . esc_html( __( 'Settings', 'discount-deals' ) ) . '</a>',
			'docs'            => '<a target="_blank" href="' . esc_url( 'https://woocommerce.com/document/discount-deals/' ) . '">' . __( 'Docs', 'discount-deals' ) . '</a>',
			'support'         => '<a target="_blank" href="' . esc_url( 'https://woocommerce.com/my-account/create-a-ticket/' ) . '">' . __( 'Support', 'discount-deals' ) . '</a>',
			'review'          => '<a target="_blank" href="' . esc_url( 'https://woocommerce.com/products/discount-deals/#reviews' ) . '">' . __( 'Review', 'discount-deals' ) . '</a>',
		);

		return array_merge( $action_links, $links );
	}//end plugin_action_links()


	/**
	 * Function to show admin dashboard.
	 *
	 * @return void
	 */
	public function discount_deals_main_page() {
		$action = discount_deals_get_data( 'action', 'list' );
		if ( 'new' == $action || 'edit' == $action ) {
			require_once DISCOUNT_DEALS_ABSPATH . 'admin/partials/discount-deals-admin-workflow-add-or-edit.php';
		} else {
			require_once DISCOUNT_DEALS_ABSPATH . 'admin/partials/discount-deals-admin-workflows-list-table.php';
		}

	}//end discount_deals_main_page()


	/**
	 * Include Admin Doc file
	 *
	 * @return void
	 */
	public function welcome_docs_page() {
		include 'partials/discount-deals-welcome-doc.php';
	}//end welcome_docs_page()


	/**
	 * Handle redirect
	 *
	 * @return void
	 */
	public function plugin_activation_redirect() {
		if ( get_option( 'discount_deals_do_activation_redirect', false ) ) {
			delete_option( 'discount_deals_do_activation_redirect' );
			wp_safe_redirect( admin_url( 'admin.php?page=discount-deals-welcome-doc' ) );
			exit;
		}
	}//end plugin_activation_redirect()


}//end class
