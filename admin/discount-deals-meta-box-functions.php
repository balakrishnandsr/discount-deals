<?php
/**
 * Discount deals Meta Box Functions
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Output a select input box.
 *
 * @param array $field Data about the field to render.
 */
function discount_deals_select( $field ) {
	$field = wp_parse_args(
		$field,
		array(
			'class'             => 'discount-deals-field ',
			'style'             => '',
			'value'             => '',
			'name'              => $field['id'],
			'desc_tip'          => false,
			'required'          => false,
			'custom_attributes' => array(),
			'value_description' => false,
		)
	);

	$wrapper_attributes = array(
		'class'         => 'discount-deals-table__row',
		'data-name'     => $field['id'],
		'data-type'     => 'select',
		'data-required' => $field['required'] ? 1 : 0,
	);

	$label_attributes = array(
		'for' => $field['id'],
	);

	$field_attributes          = (array) $field['custom_attributes'];
	$field_attributes['style'] = $field['style'];
	$field_attributes['id']    = $field['id'];
	$field_attributes['name']  = $field['name'];
	$field_attributes['class'] = $field['class'];
	if ( $field['required'] ) {
		$field_attributes['required'] = 'required';
	}

	$tooltip     = ! empty( $field['description'] ) && false !== $field['desc_tip'] ? $field['description'] : '';
	?>
	<tr <?php echo wc_implode_html_attributes( $wrapper_attributes ); // WPCS: XSS ok. ?>>
		<td class="discount-deals-table__col discount-deals-table__col--label">
			<label <?php echo wc_implode_html_attributes( $label_attributes ); // WPCS: XSS ok. ?>><?php echo wp_kses_post( $field['label'] ); ?></label>
			<?php if ( $field['required'] ) : ?>
				<span class="required">*</span>
			<?php endif; ?>
			<?php if ( $tooltip ) : ?>
				<?php echo wc_help_tip( $tooltip ); // WPCS: XSS ok. ?>
			<?php endif; ?>
		</td>
		<td class="discount-deals-table__col discount-deals-table__col--field">
			<select <?php echo wc_implode_html_attributes( $field_attributes ); // WPCS: XSS ok. ?>>
				<?php
				foreach ( $field['options'] as $key => $value ) {
					echo '<option value="' . esc_attr( $key ) . '"' . wc_selected( $key, $field['value'] ) . '>' . esc_html( $value ) . '</option>';
				}
				?>
			</select>
			<?php if ( $field['value_description'] ) : ?>
				<div class="<?php echo esc_attr( $field['id'] ); ?>_description"></div>
			<?php endif; ?>
		</td>
	</tr>
	<?php
}
