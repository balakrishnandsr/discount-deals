<?php
/**
 * Provide interface for saving the workflow
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

$workflow = $this->get_workflow();
?>
<div class="submitbox" id="submitpost">
	<div class="wide">
		<table class="discount-deals-table">
			<tbody>
			<?php
			discount_deals_select(
				array(
					'id'                    => 'discount_deals_workflow_status',
					'name'                  => 'discount_deals_workflow[dd_status]',
					'value'                 => ( $workflow ) ? $workflow->get_status() : '1',
					'label'                 => __( 'Status', 'discount-deals' ),
					'options'               => array(
						'1' => __( 'Publish', 'discount_deals' ),
						'0' => __( 'Draft', 'discount_deals' ),
					),
					'desc_tip'              => false,
					'has_value_description' => false,
				)
			);
			discount_deals_radio(
				array(
					'id'                    => 'discount_deals_workflow_exclusive',
					'name'                  => 'discount_deals_workflow[dd_exclusive]',
					'value'                 => ( $workflow ) ? $workflow->get_exclusive() ? 1 : 0 : '0',
					'label'                 => __( 'Is Exclusive', 'discount-deals' ),
					'options'               => array(
						'1' => __( 'Yes', 'discount_deals' ),
						'0' => __( 'No', 'discount_deals' ),
					),
					'desc_tip'              => false,
					'has_value_description' => false,
				)
			);
			?>
			</tbody>
		</table>
		<div class="discount-deals-meta-box-footer">
			<?php
			if ( $workflow ) {
				$workflow_id = $workflow->get_id();
				$nonce       = wp_create_nonce( 'discount_deals_post_workflow' );
				?>
				<div id="delete-action">
					<?php
					echo sprintf( '<a class="submitdelete deletion" href="?page=%s&action=%s&workflow=%s&_wpnonce=%s" onclick="return checkDelete()">%s</a>', esc_attr( discount_deals_get_data( 'page', '' ) ), 'delete', esc_attr( $workflow_id ), esc_attr( $nonce ), esc_html__( 'Delete', 'discount-deals' ) );
					?>
				</div>
				<?php
			}
			?>
			<button type="submit" id="publish_and_close" name="save_discount_deals_workflow" value="save_and_close"
					class="button">
				<?php echo esc_html__( 'Save & Close', 'discount-deals' ); ?>
			</button>
			<button type="submit" id="publish" name="save_discount_deals_workflow" value="save"
					class="button button-primary">
				<?php echo esc_html__( 'Save', 'discount-deals' ); ?>
			</button>
		</div>
	</div>
</div>
