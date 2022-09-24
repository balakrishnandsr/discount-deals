<?php
/**
 * Provide interface for adding discounts to workflow
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
	<tbody>
	<?php
	$all_discount_types       = Discount_Deals_Workflows::get_all_discounts();
	$all_valid_discount_types = array(
		'' => __( '[ Select ]', 'discount-deals' ),
	);
	foreach ( $all_discount_types as $name => $discount_type ) {
		$discount_obj          = new $discount_type();
		$short_title           = $discount_obj->get_short_title();
		$discount_title        = $discount_obj->get_title();
		$group                 = $discount_obj->get_category();
		if ( ! empty( $short_title ) ) {
			$discount_title .= " ( $short_title )";
		}
		$all_valid_discount_types[ $group ][ $name ] = $discount_title;
	}
	discount_deals_select(
		array(
			'id'                    => 'discount_deals_workflow_type',
			'name'                  => 'discount_deals_workflow[dd_type]',
			'value'                 => ( $workflow ) ? $workflow->get_discount()->get_name() : '',
			'label'                 => __( 'Workflow type', 'discount-deals' ),
			'options'               => $all_valid_discount_types,
			'desc_tip'              => true,
			'required'              => true,
			'has_value_description' => true,
			'value_description'     => ( $workflow ) ? $workflow->get_discount()->get_description() : '',
			'description'           => __( 'What type of discount that you are planning to create for your customers.', 'discount-deals' ),
		)
	);
	if ( $workflow ) {
		echo wp_kses( $workflow->get_discount()->load_fields(), wp_kses_allowed_html( 'discount_deals' ) );
	}
	?>
	</tbody>
</table>
