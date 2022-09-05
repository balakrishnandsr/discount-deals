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
?>
	<div class="wrap">
        <div class="discount-deals-fp-loader discount-deals-hidden">
            <div class="discount-deals-lds-ripple"><div></div><div></div></div>
        </div>
		<h1 class="wp-heading-inline"><?php esc_html_e( 'Workflows', 'discount-deals' ); ?></h1>
		<a href="?page=<?php echo esc_attr( discount_deals_get_data( 'page', '' ) ); ?>&action=new"
		   class="page-title-action"><?php esc_html_e( 'Add Workflow', 'discount-deals' ); ?></a>
		<hr class="wp-header-end">
		<form method="post">
			<input type="hidden" name="page"
				   value="<?php esc_attr( discount_deals_get_data( 'page', '' ) ); ?>">
			<?php
			$workflow_listing_table->search_box( 'search', 'search_id' );
			$workflow_listing_table->display();
			?>
	</div>
<?php
