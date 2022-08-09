<?php
/**
 * The admin-specific functionality of the plugin.
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
		 * Initialize the class and set its properties.
		 *
		 * @param string $plugin_name The name of this plugin.
		 * @param string $version     The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_slug = $plugin_name;
			$this->version     = $version;

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'admin_menu', array( $this, 'add_ddfw_admin_menu' ), 20 );
			add_action( 'admin_head', array( $this, 'add_ddfw_remove_submenu' ) );
			// Filter to add Settings link on Plugins page.
			add_filter( 'plugin_action_links_' . plugin_basename( DDFW_PLUGIN_FILE ), array( $this, 'plugin_action_links' ) );

		}//end __construct()


		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {

			wp_enqueue_style( $this->plugin_slug, plugin_dir_url( __FILE__ ) . 'css/discount-deals-admin.css', array(), $this->version, 'all' );

		}//end enqueue_styles()


		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {

			wp_enqueue_script( $this->plugin_slug, plugin_dir_url( __FILE__ ) . 'js/discount-deals-admin.js', array( 'jquery' ), $this->version, false );

		}//end enqueue_scripts()

		/**
		 * Admin menus
		 */
		public function add_ddfw_admin_menu() {
			// Translators: A small arrow.
			add_submenu_page( 'woocommerce', __( 'Discount Deals Dashboard', 'discount-deals-for-woocommerce' ), __( 'Discount Deals', 'discount-deals-for-woocommerce' ), 'manage_woocommerce', 'discount-deals-for-woocommerce', array( $this, 'ddfw_dashboard_page' ) );

            $get_page = ( ! empty( $_GET['page'] ) ) ? wc_clean( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore

			if ( empty( $get_page ) ) {
				return;
			}

			if ( 'discount-deals-for-woocommerce-documentation' === $get_page ) {
				add_submenu_page( 'woocommerce', sprintf( __( 'Getting Started', 'discount-deals-for-woocommerce' ), '&rsaquo;' ), __( 'Getting Started', 'discount-deals-for-woocommerce' ), 'manage_woocommerce', 'discount-deals-for-woocommerce-documentation', array( $this, 'ddfw_docs' ) );
			}

		}//end add_ddfw_admin_menu()


		/**
		 * Remove Affiliate For WooCommerce's unnecessary submenus.
		 */
		public function add_ddfw_remove_submenu() {
			remove_submenu_page( 'woocommerce', 'discount-deals-for-woocommerce-documentation' );
		}//end add_ddfw_remove_submenu()


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
					'tab'  => 'discount-deals-for-woocommerce-settings',
				),
				admin_url( 'admin.php' )
			);

			$getting_started_link = add_query_arg( array( 'page' => 'discount-deals-for-woocommerce-documentation' ), admin_url( 'admin.php' ) );

			$action_links = array(
				'getting-started' => '<a href="' . esc_url( $getting_started_link ) . '">' . esc_html( __( 'Getting started', 'discount-deals-for-woocommerce' ) ) . '</a>',
				'settings'        => '<a href="' . esc_url( $settings_link ) . '">' . esc_html( __( 'Settings', 'discount-deals-for-woocommerce' ) ) . '</a>',
				'docs'            => '<a target="_blank" href="' . esc_url( 'https://woocommerce.com/document/discount-deals-for-woocommerce/' ) . '">' . __( 'Docs', 'discount-deals-for-woocommerce' ) . '</a>',
				'support'         => '<a target="_blank" href="' . esc_url( 'https://woocommerce.com/my-account/create-a-ticket/' ) . '">' . __( 'Support', 'discount-deals-for-woocommerce' ) . '</a>',
				'review'          => '<a target="_blank" href="' . esc_url( 'https://woocommerce.com/products/discount-deals-for-woocommerce/#reviews' ) . '">' . __( 'Review', 'discount-deals-for-woocommerce' ) . '</a>',
			);

			return array_merge( $action_links, $links );
		}//end plugin_action_links()



		/**
		 * Function to show admin dashboard.
		 */
		public function ddfw_dashboard_page() {

			$settings_link = add_query_arg(
				array(
					'page' => 'wc-settings',
					'tab'  => 'discount-deals-for-woocommerce-settings',
				),
				admin_url( 'admin.php' )
			);

			$current_tab = $this->getCurrentTab();
			$tabs        = array(
				'dashboard' => 'Dashboard',
				'tab2'      => 'Tab 2',
			);

			?>

			<div class="ddfw-main">
				<h2 class="ddfw_tabs_container nav-tab-wrapper">
					<?php
					foreach ( $tabs as $tab_key => $tab_title ) {
						$params = array(
							'page' => 'discount-deals-for-woocommerce',
							'tab'  => $tab_key,
						);
						$target = '';
						$link   = esc_url( admin_url( 'admin.php?' . http_build_query( $params ) ) );
						?>
						<a class="nav-tab <?php echo esc_attr( ( $tab_key === $current_tab ? 'nav-tab-active' : '' ) ); ?>"
						   href="<?php echo esc_url( $link ); ?>" <?php echo esc_attr( $target ); ?>><?php echo esc_html( $tab_title ); ?></a>
					<?php } ?>
				</h2>
				<div class="clear"></div>
		</div>
			<?php
			if ( 'dashboard' === $current_tab ) {
				?>
				<div id="ddfw-admin-dasboard" class="ddfw-admin-dasboard">
					<h2>hello... Woocommerce</h2>
				</div>
				<?php
			}
			?>

			<?php
		}//end ddfw_dashboard_page()


		/**
		 * Include Admin Doc file
		 */
		public function ddfw_docs() {
			global $wpdb;
			include 'partials/about-discount-deals-for-woocommerce.php';
		}//end ddfw_docs()


		/**
		 * get current active tab
		 *
		 * @return mixed|string
		 */
		private function getCurrentTab() {
			$get_current_tab = ( ! empty( $_REQUEST['tab'] ) ) ? wc_clean( wp_unslash( $_REQUEST['tab'] ) ) : '';
			return ! empty( $get_current_tab ) ? $get_current_tab : 'dashboard';
		}//end getCurrentTab()


	}//end class
}

