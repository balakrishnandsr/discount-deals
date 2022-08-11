<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @package Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Discount_Deals_Admin' ) ) {
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
		 * @var  Discount_Deals_Admin_Workflows_List_Table $_workflow_listing_page Workflow listing page.
		 */
		private $_workflow_listing_page;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @param string $plugin_name The name of this plugin.
		 * @param string $version The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_slug = $plugin_name;
			$this->version     = $version;

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
			add_action( 'admin_head', array( $this, 'add_remove_submenu' ) );
			// Filter to add Settings link on Plugins page.
			add_filter( 'plugin_action_links_' . plugin_basename( DISCOUNT_DEALS_PLUGIN_FILE ), array( $this, 'plugin_action_links' ) );
			add_action( 'admin_init', array( $this, 'plugin_activation_redirect' ) );

		}//end __construct()


		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @return void
		 */
		public function enqueue_styles() {

			wp_enqueue_style( $this->plugin_slug, plugin_dir_url( __FILE__ ) . 'css/discount-deals-admin.css', array(), $this->version, 'all' );

		}//end enqueue_styles()


		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @return void
		 */
		public function enqueue_scripts() {

			wp_enqueue_script( $this->plugin_slug, plugin_dir_url( __FILE__ ) . 'js/discount-deals-admin.js', array( 'jquery' ), $this->version, false );

		}//end enqueue_scripts()

		/**
		 * Admin menus
		 *
		 * @return void
		 */
		public function add_admin_menu() {
			// Translators: A small arrow.
			$workflow_list_table = add_submenu_page(
				'woocommerce',
				__( 'Discount Deals', 'discount-deals' ),
				__( 'Discount Deals', 'discount-deals' ),
				'manage_woocommerce',
				'discount-deals',
				array(
					$this,
					'discount_deals_plugin_page',
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

			add_action( "load-$workflow_list_table", array( $this, 'workflow_list_table_screen_options' ) );

		}//end add_admin_menu()

		/**
		 * Add screen options for workflow listing page
		 *
		 * @return void
		 */
		public function workflow_list_table_screen_options() {
			require_once DISCOUNT_DEALS_ABSPATH . 'admin/class-discount-deals-admin-workflows-list-table.php';
			$option = 'per_page';
			$args   = array(
				'label'   => __( 'Workflows', 'discount-deals' ),
				'default' => 20,
				'option'  => 'workflows_per_page',
			);
			add_screen_option( $option, $args );
			$this->_workflow_listing_page = new Discount_Deals_Admin_Workflows_List_Table();
		}

		/**
		 * Remove Affiliate For WooCommerce's unnecessary submenus.
		 *
		 * @return void
		 */
		public function add_remove_submenu() {
			remove_submenu_page( 'woocommerce', 'discount-deals-welcome-doc' );
		}//end add_remove_submenu()


		/**
		 * Function to add more action on plugins page
		 *
		 * @param array $links Existing links.
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
		public function discount_deals_plugin_page() {

			$this->_workflow_listing_page->prepare_items();
			?>
			<div class="wrap">
				<h2><?php esc_html_e( 'Workflows', 'discount-deals' ); ?></h2>
				<form method="post">
					<input type="hidden" name="page"
						   value="<?php esc_attr( discount_deals_get_data( 'page', '' ) ); ?>">
					<?php
					$this->_workflow_listing_page->search_box( 'search', 'search_id' );
					$this->_workflow_listing_page->display();
					?>
			</div>
			<?php
		}//end discount_deals_plugin_page()


		/**
		 * Include Admin Doc file
		 *
		 * @return void
		 */
		public function welcome_docs_page() {
			global $wpdb;
			include 'partials/discount-deals-welcome-doc.php';
		}//end welcome_docs_page()


		/**
		 * get current active tab
		 *
		 * @return mixed|string
		 */
		private function get_active_tab() {
			$get_active_tab = discount_deals_get_data( 'tab', '' );
			return ! empty( $get_active_tab ) ? $get_active_tab : 'workflows';
		}//end get_active_tab()


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
}

