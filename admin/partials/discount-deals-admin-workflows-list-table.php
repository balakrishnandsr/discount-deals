<?php
/**
 * Provide interface for listing the workflows
 *
 * @package    Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Variable declaration
 *
 * @var Discount_Deals_Admin $this Class variable.
 */

require_once DISCOUNT_DEALS_ABSPATH . 'admin/class-discount-deals-admin-workflows-list-table.php';
$workflow_listing_table = new Discount_Deals_Admin_Workflows_List_Table();
$workflow_listing_table->prepare_items();
$current_discount    = discount_deals_get_data( 'tab', 'all' );
$workflows_db        = new Discount_Deals_Workflow_DB();
$all_workflows_count = $workflows_db->count();
?>
	<div class="wrap">
		<div class="discount-deals-fp-loader discount-deals-hidden">
			<div class="discount-deals-lds-ripple">
				<div></div>
				<div></div>
			</div>
		</div>
		<h1 class="wp-heading-inline"><?php esc_html_e( 'Workflows', 'discount-deals' ); ?></h1>
		<a href="?page=<?php echo esc_attr( discount_deals_get_data( 'page', '' ) ); ?>&action=new"
		   class="page-title-action"><?php esc_html_e( 'Add Workflow', 'discount-deals' ); ?></a>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=discount-deals-settings&section=general' ) ); ?>"
		   class="page-title-action" target="_blank"><?php echo esc_html__( 'Settings', 'discount-deals' ); ?></a>
		<hr class="wp-header-end">
		<ul class="subsubsub">
			<li>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=discount-deals&tab=all' ) ); ?>"
				   class="<?php echo( 'all' == $current_discount ? 'current' : '' ); ?>"><?php esc_html_e( 'All', 'discount-deals' ); ?></a><span
						class="count">(<?php echo intval( $all_workflows_count ); ?>)</span>
			</li>&nbsp;|&nbsp;
			<?php
			$all_discount_types = Discount_Deals_Workflows::get_all_discounts();
			$i                  = count( $all_discount_types );
			foreach ( $all_discount_types as $name => $discount_type ) {
				$discount_obj = new $discount_type();
				$count        = $workflows_db->count( $name );
				$workflow_id  = str_replace( '_', '-', $name );
				?>
				<li>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=discount-deals&tab=' . sanitize_title( $workflow_id ) ) ); ?>"
					   class="<?php echo( $current_discount == $workflow_id ? 'current' : '' ); ?>"><?php echo wp_kses_post( $discount_obj->get_title() ); ?></a><span
							class="count">(<?php echo intval( $count ); ?>)</span>
				</li>
				<?php
				if ( 1 < $i ) {
					echo '&nbsp;|&nbsp;';
				}
				$i --;
				$all_valid_discount_types[ $name ] = $discount_obj->get_title();
			}
			?>
		</ul>
		<form method="post">
			<input type="hidden" name="page"
				   value="<?php esc_attr( discount_deals_get_data( 'page', '' ) ); ?>">
			<?php
			$workflow_listing_table->search_box( 'search', 'search_id' );
			$workflow_listing_table->display();
			?>
	</div>
<?php
