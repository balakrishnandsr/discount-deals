<?php
/**
 * Discount deals For WooCommerce About/Landing page
 *
 * @package Discount_Deals
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$plugin_data = Discount_Deals::get_plugin_data();
?>
	<style>
		.about-wrap h1 {
			margin: 0.2em 0;
		}
		.discount-deals-faq .has-3-columns.feature-section.col.three-col {
			max-width: unset !important;
		}
	</style>
	<script type="text/javascript">
		jQuery(function () {
			let top_level_page = jQuery('#toplevel_page_woocommerce');
			top_level_page.find('a[href$="admin.php?page=discount-deals"]').addClass('current');
			top_level_page.find('a[href$="admin.php?page=discount-deals"]').parent().addClass('current');
		});
	</script>
	<div class="wrap about-wrap">
		<h1><?php echo esc_html__( 'Thank you for installing Discount Deals', 'discount-deals' ) . ' ' . esc_html( $plugin_data['Version'] ) . '!'; ?></h1>
		<p class="about-text"><?php echo esc_html__( 'Glad to have you onboard. We hope the plugin adds to your success 🏆', 'discount-deals' ); ?></p>

		<div class="changelog">
			<div class="about-text">
				<h3><?php echo esc_html__( 'To get started', 'discount-deals' ); ?></h3>
				<?php
				echo sprintf(
					// translators: %s item name.
					esc_html__( 'Review and update your Discount Deals %s', 'discount-deals' ),
					'<a target="_blank" href="' . esc_url(
						add_query_arg(
							array(
								'page' => 'wc-settings',
								'tab'  => 'discount-deals-settings',
							),
							admin_url( 'admin.php' )
						)
					) . '">' . esc_html__( 'Settings &rarr;', 'discount-deals' ) . '</a>'
				);
				?>
				<br>
				<?php
				echo sprintf(
					// translators: %s item name.
					esc_html__( 'Access Discount Deals %s.', 'discount-deals' ),
					'<a target="_blank" href="' . esc_url(
						add_query_arg(
							array(
								'page' => 'discount-deals',
							),
							admin_url( 'admin.php' )
						)
					) . '">' . esc_html__( 'dashboard', 'discount-deals' ) . '</a>'
				);
				?>
			</div>
			<hr>
			<div class="discount-deals-faq">
				<h3><?php echo esc_html__( 'Frequently Asked Questions', 'discount-deals' ); ?></h3>
				<div class="has-3-columns feature-section col three-col">
					<div class="column col">
						<h4><?php esc_html_e( 'Is it possible to give discounts based on user roles?', 'discount-deals' ); ?></h4>
						<p><?php esc_html_e( 'Yes, you can apply discounts for different user roles. Example: Premium customers receive a 20% discount, while first-time customers receive a 10% discount.', 'discount-deals' ); ?></p>
					</div>
					<div class="column col">
						<h4><?php esc_html_e( 'Are the discounts visible on the product page?', 'discount-deals' ); ?></h4>
						<p><?php esc_html_e( 'Yes, the discount is displayed on the product page, on the product details page and in the shopping cart, but the original price is not taken into account.', 'discount-deals' ); ?></p>
					</div>
					<div class="column col">
						<h4><?php esc_html_e( 'Can I offer a discount based on the customer\'s purchase history?', 'discount-deals' ); ?></h4>
						<p><?php esc_html_e( 'Yes. You can offer a discount based on the purchase history, the total amount spent by the customer on their previous orders or based on the total number of orders placed by a customer.', 'discount-deals' ); ?></p>
					</div>
				</div>
				<div class="has-3-columns feature-section col three-col">
					<div class="column col">
						<h4><?php esc_html_e( 'Is it possible to offer discounts to wholesale customers?', 'discount-deals' ); ?></h4>
						<p><?php esc_html_e( 'Yes. The plugin has a user role specific discount rule. You can set up a discount for specific user roles such as wholesale customers.', 'discount-deals' ); ?></p>
					</div>
					<div class="column col">
						<h4><?php esc_html_e( 'How can I offer a quantity discount in WooCommerce?', 'discount-deals' ); ?></h4>
						<p><?php esc_html_e( 'Simple. Simply install the plugin and create a product quantity based discount workflow. You can configure quantity ranges for bulk purchases with discount percentages. Example: 3 to 5 quantities at 10%, 6 to 10 quantities at 20%.', 'discount-deals' ); ?></p>
					</div>
					<div class="column col">
						<h4><?php esc_html_e( 'Is it possible to offer bogo offers?', 'discount-deals' ); ?></h4>
						<p><?php esc_html_e( 'Yes, you can offer "Buy 1 get 1" offers in your shop.', 'discount-deals' ); ?></p>
					</div>
				</div>
				<div class="has-3-columns feature-section col three-col">
					<div class="column col">
						<h4><?php esc_html_e( 'Can I set discounts based on billing country and city?', 'discount-deals' ); ?></h4>
						<p><?php esc_html_e( 'Yes, it is possible to set a discount rule based on the customer\'s city. Example: Customers from Texas will receive a 15% discount. The discount is applied at the shopping cart level.', 'discount-deals' ); ?></p>
					</div>
					<div class="column col">
						<h4><?php esc_html_e( 'Is the discount displayed on the invoice?', 'discount-deals' ); ?></h4>
						<p><?php esc_html_e( 'Yes, the discount will be shown separately on the invoice.', 'discount-deals' ); ?></p>
					</div>
					<div class="column col">
						<h4><?php esc_html_e( 'How can I set up BOGO ( buy one and get one )?', 'discount-deals' ); ?></h4>
						<p><?php esc_html_e( 'Navigate to WooCommerce - > Discount Deals - > Add New Workflow. Select Buy X and Get X as the discount type. Then set rules and discount groups.', 'discount-deals' ); ?></p>
					</div>
				</div>
				<br>
				<div>
					<?php
					echo sprintf(
					// Translators: Link to the Affiliate For WooCommerce documentation.
						esc_html__( 'View detailed documentation from %s.', 'discount-deals' ),
						'<a target="_blank" href="https://woocommerce.com/document/discount-deals/">' . esc_html__( 'here', 'discount-deals' ) . '</a>'
					);
					?>
				</div>
			</div>
		</div>
	</div>
<?php
