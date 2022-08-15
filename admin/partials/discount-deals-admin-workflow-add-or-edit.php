<?php
/**
 * Provide interface for Add / Edit workflow
 *
 * @package    Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Variable declaration
 *
 * @var Discount_Deals_Admin $this Class variable.
 */
$workflow           = $this->get_workflow();
$workflow_id        = discount_deals_get_data( 'workflow', 0 );
$workflows_page_url = menu_page_url( 'discount-deals', false );

$workflow_action = discount_deals_get_data( 'action', '' );
if ( 'new' === $workflow_action ) {
	$workflow_title = __( ' Add New Workflow', 'discount-deals' );
} else {
	$workflow_title = __( ' Edit Workflow', 'discount-deals' );
}
?>
	<div class="wrap">
		<h1 class="wp-heading-inline">
			<?php echo esc_html( $workflow_title ); ?>
		</h1>
		<form class="mt-5" method="post" action="#">
			<input type="hidden" id="discount_deals_workflow_dd_id" name="discount_deals_workflow[dd_id]"
				   value="<?php echo ! empty( $workflow_id ) ? esc_attr( $workflow_id ) : ''; ?>">
			<?php
			// Workflow nonce.
			wp_nonce_field( 'discount-deals-workflow', 'discount-deals-workflow-nonce', false );

			// Used to save closed meta boxes and their order.
			wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
			wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
			?>
			<div id="poststuff">
				<div id="post-body" class="meta-box-holder columns-2">
					<div id="post-body-content">
						<div id="titlediv">
							<div id="titlewrap">
								<label for="title">
									<input type="text" name="discount_deals_workflow[dd_title]" size="30"
										   value="<?php echo esc_attr( $workflow ? $workflow->get_title() : '' ); ?>"
										   id="title"
										   spellcheck="true"
										   autocomplete="off"
										   placeholder="<?php echo esc_attr__( 'Add title', 'discount-deals' ); ?>"
										   required>
								</label>
							</div>
						</div>
					</div>
					<div id="postbox-container-1" class="postbox-container">
						<?php
						do_meta_boxes( 'admin_page_discount-deals', 'side', null );
						?>
					</div>
					<div id="postbox-container-2" class="postbox-container">
						<?php
						do_meta_boxes( 'admin_page_discount-deals', 'normal', null );
						do_meta_boxes( 'admin_page_discount-deals', 'advanced', null );
						?>
					</div>
				</div>
			</div>
		</form>
	</div>
<?php
