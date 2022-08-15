<?php
/**
 * Provide interface for adding discounts to workflow
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
<table class="discount-deals-table">
    <tbody>
	<?php
	$all_discount_types       = Discount_Deals_Workflows::get_all_discounts();
	$all_valid_discount_types = array(
		'' => __( '[ Select ]', 'discount-deals' ),
	);
	foreach ( $all_discount_types as $name => $discount_type ) {
		$all_valid_discount_types[ $name ] = $discount_type->get_title();
	}
	discount_deals_select(
		array(
			'id'                => 'discount_deals_workflow_type',
			'name'              => 'discount_deals_workflow[dd_type]',
			'value'             => ( $workflow ) ? $workflow->get_discount()->get_name() : '',
			'label'             => __( 'Discount type', 'discount-deals' ),
			'options'           => $all_valid_discount_types,
			'desc_tip'          => true,
			'required'          => true,
			'value_description' => true,
			'description'       => __( 'What type of discount that you are planning to create for your customers.', 'discount-deals' ),
		)
	)
	?>
    </tbody>
</table>
