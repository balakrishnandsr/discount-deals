<?php
/**
 * Discount deals For WooCommerce About/Landing page
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$plugin_data = Discount_Deals::get_plugin_data();
?>
	<style type="text/css">
		.wrap.about-wrap,
		.afw-faq .has-3-columns.feature-section.col.three-col {
			max-width: unset !important;
		}
		.about-wrap h1 {
			margin: 0.2em 0;
		}
	</style>
	<script type="text/javascript">
		jQuery( function(){
			jQuery('#toplevel_page_woocommerce').find('a[href$="admin.php?page=discount-deals-for-woocommerce"]').addClass('current');
			jQuery('#toplevel_page_woocommerce').find('a[href$="admin.php?page=discount-deals-for-woocommerce"]').parent().addClass('current');
		});
	</script>
	<div class="wrap about-wrap">
		<h1><?php echo esc_html__( 'Thank you for installing Discount Deals for WooCommerce', 'discount-deals-for-woocommerce' ) . ' ' . esc_html( $plugin_data['Version'] ) . '!'; ?></h1>
		<p class="about-text"><?php echo esc_html__( 'Glad to have you onboard. We hope the plugin adds to your success 🏆', 'discount-deals-for-woocommerce' ); ?></p>

		<div class="changelog">
			<div class="about-text">
				<span style="font-size: 22px;"><?php echo esc_html__( 'To get started:', 'discount-deals-for-woocommerce' ); ?></span>
				<br>
				<?php
				echo sprintf(
				// Translators: Link to the Affiliate For WooCommerce Settings.
					esc_html__( 'Review and update your Discount Deals For WooCommerce %s', 'discount-deals-for-woocommerce' ),
					'<a class="button-primary" target="_blank" href="' . esc_url(
						add_query_arg(
							array(
								'page' => 'wc-settings',
								'tab'  => 'discount-deals-for-woocommerce-settings',
							),
							admin_url( 'admin.php' )
						)
					) . '">' . esc_html__( 'Settings &rarr;', 'discount-deals-for-woocommerce' ) . '</a>'
				);
				?>
				<br>
				<?php
				echo sprintf(
				// Translators: Link to the Affiliate For WooCommerce Dashboard in admin.
					esc_html__( 'Access Discount Deals %s.', 'discount-deals-for-woocommerce' ),
					'<a target="_blank" href="' . esc_url(
						add_query_arg(
							array(
								'page' => 'discount-deals-for-woocommerce',
							),
							admin_url( 'admin.php' )
						)
					) . '">' . esc_html__( 'dashboard', 'discount-deals-for-woocommerce' ) . '</a>'
				);
				?>
				<br>
				<?php
				echo sprintf(
				// Translators: Link to the Affiliate For WooCommerce Plans Dashboard in admin.
					esc_html__( 'Setup default commission rate for Storewide Default Commission in %s.', 'discount-deals-for-woocommerce' ),
					'<a target="_blank" href="' . esc_url(
						add_query_arg(
							array(
								'page' => 'discount-deals-for-woocommerce#!/plans',
							),
							admin_url( 'admin.php' )
						)
					) . '">' . esc_html__( 'plans', 'discount-deals-for-woocommerce' ) . '</a>'
				);
				?>
			</div>
			<hr>
			<div class="afw-faq">
				<h3><?php echo esc_html__( 'Quick links', 'discount-deals-for-woocommerce' ); ?></h3>
				<div class="has-3-columns feature-section col three-col">
					<div class="column col">
						<h4><?php echo esc_html__( 'How do I add an affiliate?', 'discount-deals-for-woocommerce' ); ?></h4>
						<p>
						<?php
						// Translators: Link to the Affiliate For WooCommerce Doc.
							echo sprintf( esc_html__( 'Check %s.', 'discount-deals-for-woocommerce' ), '<a target="_blank" href="https://woocommerce.com/document/discount-deals-for-woocommerce/#section-5">' . esc_html__( 'here', 'discount-deals-for-woocommerce' ) . '</a>' );
						?>
							</p>
					</div>
					<div class="column col">
						<h4><?php echo esc_html__( 'Where do affiliates login / get their stats from?', 'discount-deals-for-woocommerce' ); ?></h4>
						<p>
						<?php
						// Translators: Link to the Affiliate For WooCommerce Doc.
							echo sprintf( esc_html__( 'Check %s.', 'discount-deals-for-woocommerce' ), '<a target="_blank" href="https://woocommerce.com/document/discount-deals-for-woocommerce/#section-9">' . esc_html__( 'here', 'discount-deals-for-woocommerce' ) . '</a>' );
						?>
							</p>
					</div>
					<div class="column col last-feature">
						<h4><?php echo esc_html__( "Where's the link an affiliate will use to refer to my site?", 'discount-deals-for-woocommerce' ); ?></h4>
						<p>
						<?php
						// Translators: Link to the Affiliate For WooCommerce Doc.
							echo sprintf( esc_html__( 'Check %s.', 'discount-deals-for-woocommerce' ), '<a target="_blank" href="https://woocommerce.com/document/discount-deals-for-woocommerce/#section-11">' . esc_html__( 'here', 'discount-deals-for-woocommerce' ) . '</a>' );
						?>
							</p>
					</div>
				</div>
				<div class="has-3-columns feature-section col three-col">
					<div class="column col">
						<h4><?php echo esc_html__( 'How to customize referral link for an affiliate?', 'discount-deals-for-woocommerce' ); ?></h4>
						<p>
						<?php
						// Translators: Link to the Affiliate For WooCommerce Doc.
							echo sprintf( esc_html__( 'Check %s.', 'discount-deals-for-woocommerce' ), '<a target="_blank" href="https://woocommerce.com/document/discount-deals-for-woocommerce/how-to-customize-affiliate-referral-link/">' . esc_html__( 'here', 'discount-deals-for-woocommerce' ) . '</a>' );
						?>
							</p>
					</div>
					<div class="column col">
						<h4><?php echo esc_html__( 'How to give coupons to affiliates instead of link for referral?', 'discount-deals-for-woocommerce' ); ?></h4>
						<p>
						<?php
						// Translators: Link to the Affiliate For WooCommerce Doc.
							echo sprintf( esc_html__( 'Check %s.', 'discount-deals-for-woocommerce' ), '<a target="_blank" href="https://woocommerce.com/document/discount-deals-for-woocommerce/how-to-create-and-assign-coupons-to-affiliates/">' . esc_html__( 'here', 'discount-deals-for-woocommerce' ) . '</a>' );
						?>
							</p>
					</div>
					<div class="column col last-feature">
						<h4><?php echo esc_html__( 'How to manually assign / unassign an order to an affiliate?', 'discount-deals-for-woocommerce' ); ?></h4>
						<p>
						<?php
						// Translators: Link to the Affiliate For WooCommerce Doc.
							echo sprintf( esc_html__( 'Check %s.', 'discount-deals-for-woocommerce' ), '<a target="_blank" href="https://woocommerce.com/document/discount-deals-for-woocommerce/how-to-assign-unassign-an-order-to-an-affiliate/">' . esc_html__( 'here', 'discount-deals-for-woocommerce' ) . '</a>' );
						?>
							</p>
					</div>
				</div>
				<div class="has-3-columns feature-section col three-col">
					<div class="column col">
						<h4><?php echo esc_html__( 'Set different commission rates for affiliates', 'discount-deals-for-woocommerce' ); ?></h4>
						<p>
						<?php
						// Translators: Link to the Affiliate For WooCommerce Doc.
							echo sprintf( esc_html__( 'Check %s.', 'discount-deals-for-woocommerce' ), '<a target="_blank" href="https://woocommerce.com/document/discount-deals-for-woocommerce/how-to-set-different-affiliate-commission-rates-for-affiliates/">' . esc_html__( 'here', 'discount-deals-for-woocommerce' ) . '</a>' );
						?>
							</p>
					</div>
					<div class="column col">
						<h4><?php echo esc_html__( 'Set different affiliate commission rates for product or product category', 'discount-deals-for-woocommerce' ); ?></h4>
						<p>
						<?php
						// Translators: Link to the Affiliate For WooCommerce Doc.
							echo sprintf( esc_html__( 'Check %s.', 'discount-deals-for-woocommerce' ), '<a target="_blank" href="https://woocommerce.com/document/discount-deals-for-woocommerce/how-to-set-different-affiliate-commission-rates-for-product-or-product-category/">' . esc_html__( 'here', 'discount-deals-for-woocommerce' ) . '</a>' );
						?>
							</p>
					</div>
					<div class="column col last-feature">
						<h4><?php echo esc_html__( 'FAQ\'s', 'discount-deals-for-woocommerce' ); ?></h4>
						<p>
						<?php
						// Translators: Link to the Affiliate For WooCommerce Doc FAQ.
							echo sprintf( esc_html__( 'Check %s.', 'discount-deals-for-woocommerce' ), '<a target="_blank" href="https://woocommerce.com/document/discount-deals-for-woocommerce/#section-25">' . esc_html__( 'here', 'discount-deals-for-woocommerce' ) . '</a>' );
						?>
							</p>
					</div>
				</div>
				<br>
				<div>
					<?php
					echo sprintf(
					// Translators: Link to the Affiliate For WooCommerce documentation.
						esc_html__( 'View detailed documentation from %s.', 'discount-deals-for-woocommerce' ),
						'<a target="_blank" href="https://woocommerce.com/document/discount-deals-for-woocommerce/">' . esc_html__( 'here', 'discount-deals-for-woocommerce' ) . '</a>'
					);
					?>
				</div>
			</div>
		</div>
	</div>
<?php
