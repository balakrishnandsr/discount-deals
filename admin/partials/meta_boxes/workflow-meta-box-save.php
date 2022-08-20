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
$workflow = $this->get_workflow();
?>
<div class="submitbox" id="submitpost">
	<div class="wide">
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
		<div class="discount-deals-meta-box-footer">
			<button type="submit" id="publish" name="save_discount_deals_workflow" value="save"
					class="button button-primary">
				<?php echo esc_html__( 'Save', 'discount-deals' ); ?>
			</button>
		</div>
	</div>
</div>
