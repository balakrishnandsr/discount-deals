<?php
/**
 * Provide interface for saving the workflow
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
$workflow = $this->_workflow;
?>
<div class="submitbox" id="submitpost">
	<table class="ig-es-table">
		<tr class="ig-es-table__row">
			<td class="ig-es-table__col">
				<div class="ig-es-input-group__input">
					<?php
					if ( $workflow ) {
						$workflow_status = $workflow->is_active() ? 1 : 0;
					} else {
						$workflow_status = 1;
					}

					$workflow_status_field = new ES_Select( false );
					$workflow_status_field->set_name( 'discount_Deals_workflow_data[dd_status]' );
					$workflow_status_field->set_options(
						array(
							0 => __( 'Inactive', 'discount-deals' ),
							1 => __( 'Active', 'discount-deals' ),
						)
					);
					$workflow_status_field->render( $workflow_status );
					?>
				</div>
			</td>
		</tr>
	</table>
	<div id="major-publishing-actions">
		<?php
		if ( $workflow ) :
			$workflow_id = $workflow->get_id();
			$nonce = wp_create_nonce( 'discount_deals_post_workflow' );
			?>
			<div id="delete-action">
				<?php
				echo sprintf( '<a class="submitdelete deletion" href="?page=%s&action=%s&id=%s&_wpnonce=%s" onclick="return checkDelete()">%s</a>', esc_attr( discount_deals_get_data( 'page', '' ) ), 'delete', esc_attr( $workflow_id ), esc_attr( $nonce ), esc_html__( 'Delete', 'discount-deals' ) );
				?>
			</div>
			<?php
		endif;
		?>
		<div id="publishing-action">
			<button type="submit" id="publish" name="save_workflow" value="save"
					class="button button-primary button-large"><?php echo esc_html__( 'Save', 'discount-deals' ); ?></button>
		</div>
		<div class="clear"></div>
	</div>
</div>
