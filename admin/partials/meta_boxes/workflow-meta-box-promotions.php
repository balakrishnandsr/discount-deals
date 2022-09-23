<?php
/**
 * Provide interface for adding promotion settings to workflow
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
<table class="discount-deals-table">
	<tbody id="discount-deals-promotion-details-container">
	<?php
	if ( $workflow ) {
		echo wp_kses( $workflow->get_discount()->load_promotion_fields(), wp_kses_allowed_html('discount_deals') );
		// Uncomment echo $workflow->get_discount()->load_promotion_fields();.
	}
	?>
	</tbody>
	<tbody>
    <?php
        $discount_details = array();
        if ( $workflow ) {
	        $discount_details = $workflow->get_discount()->get_promotion_details();
        }
        discount_deals_editor(
	        array(
		        'id'       => 'discount_deals_workflow_promotion_message',
		        'name'     => 'discount_deals_workflow[dd_promotion][message]',
		        'value'    => discount_deals_get_value_from_array( $discount_details, 'message', '<p><b>Special Price</b> Purchase above 500$ and get extra 5% off. </p>', false ),
		        'label'    => __( 'Enter the promotional message that will be displayed to the customer', 'discount-deals' ),
		        'required' => true,
                'style'    => 'display:none;'
	        )
        );
    ?>
	</tbody>
	<tfoot class="
	<?php
	if ( $workflow ) {
		echo 'discount-deals-hidden';
	}
	?>
	">
	<tr>
		<td colspan="2">
			<p class="discount-deals-ph10"><?php esc_html_e( 'Promotions can be used to entice customers to buy from your store with more discounts.', 'discount-deals' ); ?></p>
		</td>
	</tr>
	</tfoot>
</table>