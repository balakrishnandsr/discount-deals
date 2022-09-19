<?php
/**
 * Provide interface for adding rules to workflow
 *
 * @package    Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div id="discount-deals-rules-meta-box-container"></div>

<script type="text/template" id="tmpl-discount-deals-rules-container">
    <div class="discount-deals-rules-container">
        <div class="discount-deals-rule-groups"></div>
    </div>
    <div class="discount-deals-meta-box-footer">
        <button type="button" class="discount-deals-add-rule-group button button-primary button-large">
			<?php esc_attr_e( '+ Add Rule Group', 'discount-deals' ); ?>
        </button>
    </div>
</script>


<script type="text/template" id="tmpl-discount-deals-empty-rule-groups">
    <p class="discount-deals-rules-empty-message">
		<?php
		printf(
		// Translators: Strong Open Tag.
			esc_attr__( 'Rules can be used to add conditional logic to workflows. Click the %1$s+ Add Rule Group%2$s button to create a rule.', 'discount-deals' ),
			'<strong>',
			'</strong>'
		);
		?>
    </p>
</script>

<! -- formatter:off -->
<script type="text/template" id="tmpl-discount-deals-rule">

	<div class="discount-deals-rule discount-deals-rule--type-{{ data.rule.object.type ? data.rule.object.type : 'new' }} discount-deals-rule--compare-{{ data.rule.compare }}">
        <table>
            <tr>
                <td>
                    <div class="discount-deals-rule__fields discount-deals-grid">
                        <div class="discount-deals-rule-select-container discount-deals-rule__field-container discount-deals-col-3">
                            <select name="{{ data.field_name_base }}[name]" class="discount-deals-rule-select discount-deals-field" required>
                                <option value=""><?php esc_attr_e( '[Select Rule]', 'discount-deals' ); ?></option>
                                <# _.each( data.grouped_rules, function( rules, group_name ) { #>
                                    <optgroup label="{{ group_name }}">
                                        <# _.each( rules, function( rule ) { #>
                                            <option value="{{ rule.name }}">{{ rule.title }}</option>
                                        <# }) #>
                                    </optgroup>
                                <# }) #>
                            </select>
                        </div>
                        <div class="discount-deals-rule-field-compare discount-deals-rule__field-container discount-deals-col-2">
                            <select name="{{ data.field_name_base }}[compare]" class="discount-deals-rule-compare-field discount-deals-field" <#
                                if ( _.isEmpty( data.rule.object.compare_types ) ) { #>disabled<# } #>>
                                <# _.each( data.rule.object.compare_types, function( option, key ) { #>
                                    <option value="{{ key }}">{{ option }}</option>
                                <# }) #>
                            </select>
                        </div>
                        <div class="discount-deals-rule-field-value discount-deals-rule__field-container discount-deals-col-7 <# if ( data.rule.is_value_loading ) { #>discount-deals-loading<# } #>">
                            <# if ( data.rule.isValueLoading ) { #>
                                <div class="discount-deals-loader"></div>
                            <# } else { #>

                                <# if ( data.rule.object.type === 'number' ) { #>
                                    <input name="{{ data.field_name_base }}[value]" placeholder="<?php esc_attr_e( 'Enter value here...', 'discount-deals' ); ?>" class="discount-deals-field discount-deals-rule-value-field" type="text" required>
                                <# } else if ( data.rule.object.type === 'object' ) { #>
                                    <select name="{{ data.field_name_base }}[value]{{ data.rule.object.is_multi ? '[]' : '' }}"
                                            class="{{ data.rule.object.class }} discount-deals-field discount-deals-rule-value-field"
                                            data-placeholder="{{ data.rule.object.placeholder }}"
                                            data-action="{{ data.rule.object.ajax_action }}"
                                            {{ data.rule.object.is_multi ? 'multiple="multiple"' : '' }} ></select>
                                <# } else if ( data.rule.object.type === 'select' ) { #>
                                    <# if ( data.rule.object.is_single_select ) { #>
                                        <select name="{{ data.field_name_base }}[value]" class="discount-deals-field wc-enhanced-select discount-deals-rule-value-field" data-placeholder="{{{ data.rule.object.placeholder }}}">
                                            <# if ( data.rule.object.placeholder ) { #>
                                            <option></option>
                                            <# } #>
                                    <# } else { #>
                                        <select name="{{ data.field_name_base }}[value][]" multiple="multiple" class="discount-deals-field wc-enhanced-select discount-deals-rule-value-field" data-placeholder="{{ data.rule.object.placeholder }}">
                                    <# } #>
                                            <# _.each( data.rule.object.select_choices, function( option, key ) { #>
                                            <option value="{{ key }}">{{{ option }}}</option>
                                            <# }) #>
                                        </select>
                                <# } else if ( data.rule.object.type === 'string' && ( data.rule.compare != 'blank' && data.rule.compare != 'not_blank' ) )  { #>
                                    <input name="{{ data.field_name_base }}[value]" class="discount-deals-field discount-deals-rule-value-field" type="text" placeholder="<?php esc_attr_e( 'Enter value here...', 'discount-deals' ); ?>" required>
                                <# } else if ( data.rule.object.type === 'meta' )  { #>
                                    <input name="{{ data.field_name_base }}[value][]" class="discount-deals-field discount-deals-rule-value-field" type="text" placeholder="<?php esc_attr_e( 'key', 'discount-deals' ); ?>">
                                    <input name="{{ data.field_name_base }}[value][]" class="discount-deals-field discount-deals-rule-value-field" type="text" placeholder="<?php esc_attr_e( 'value', 'discount-deals' ); ?>">
                                <# } else if ( data.rule.object.type === 'bool' )  { #>
                                    <select name="{{ data.field_name_base }}[value]" class="discount-deals-field discount-deals-rule-value-field">
                                        <# _.each( data.rule.object.select_choices, function( option, key ) { #>
                                        <option value="{{ key }}">{{{ option }}}</option>
                                        <# }); #>
                                    </select>
                                <# } else if ( data.rule.object.type === 'date' ) { #>
                                    <# if ( data.rule.object.uses_datepicker === true ) { #>
                                        <input type="text" name="{{ data.field_name_base }}[value][date]" class="discount-deals-field discount-deals-rule-value-field discount-deals-rule-value-date discount-deals-date-picker date-picker discount-deals-hidden" placeholder="<?php esc_attr_e( 'Select date...', 'discount-deals' ); ?>" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" data-discount-deals-compare="is_after is_before is_on is_not_on" autocomplete="off"/>
                                    <# } #>
                                    <# if ( data.rule.object.uses_datetime_picker === true ) { #>
                                        <input type="text" name="{{ data.field_name_base }}[value][date]" placeholder="<?php esc_attr_e( 'Select date...', 'discount-deals' ); ?>" class="discount-deals-field discount-deals-rule-value-field discount-deals-rule-value-date discount-deals-date-time-picker date-time-picker discount-deals-hidden" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}" data-discount-deals-compare="is_after is_before is_on is_not_on" autocomplete="off"/>
                                    <# } #>
                                    <# if ( data.rule.object.has_is_between_dates === true ) { #>
                                        <div class="discount-deals-grid discount-deals-hidden" data-discount-deals-compare="is_between">
                                            <div class="discount-deals-col-6">
                                                <input type="text" name="{{ data.field_name_base }}[value][from]" class="discount-deals-field discount-deals-rule-value-field discount-deals-rule-value-from date-time-picker discount-deals-date-time-picker" placeholder="<?php esc_attr_e( 'Select start date...', 'discount-deals' ); ?>" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}" autocomplete="off"/>
                                            </div>
                                            <div class="discount-deals-col-6">
                                                <input type="text" name="{{ data.field_name_base }}[value][to]" class="discount-deals-field discount-deals-rule-value-field discount-deals-rule-value-to date-time-picker discount-deals-date-time-picker" placeholder="<?php esc_attr_e( 'Select end date...', 'discount-deals' ); ?>" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}" autocomplete="off"/>
                                            </div>
                                        </div>
                                    <# } #>
                                    <# if ( data.rule.object.has_days_of_the_week === true ) { #>
                                        <div class="discount-deals-hidden" data-discount-deals-compare="days_of_the_week">
                                            <select name="{{ data.field_name_base }}[value][dow][]" multiple required class="discount-deals-field discount-deals-rule-value-field discount-deals-rule-value-dow wc-enhanced-select" data-placeholder="<?php echo esc_attr( __('Select days...','discount-deals') ); ?>">
                                                <?php for ( $day = 1; $day <= 7; $day++ ) : ?>
                                                    <option value="<?php echo esc_attr( $day ); ?>"><?php echo esc_attr( discount_deals_get_weekday( $day ) ); ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                    <# } #>
                                    <# if( data.rule.object.has_is_future_comparison === true || data.rule.object.has_is_past_comparison === true ) { #>
                                        <div class="discount-deals-grid discount-deals-hidden" data-discount-deals-compare="is_in_the_next is_not_in_the_next is_in_the_last is_not_in_the_last">
                                            <div class="discount-deals-col-6">
                                                <input type="number" step="1" min="1" name="{{ data.field_name_base }}[value][timeframe]" class="discount-deals-field discount-deals-rule-value-field discount-deals-rule-value-timeframe" placeholder="<?php esc_attr_e( 'Enter value here...', 'discount-deals' ); ?>" required/>
                                            </div>
                                            <div class="discount-deals-col-6">
                                                <select name="{{ data.field_name_base }}[value][measure]" class="discount-deals-field discount-deals-rule-value-field discount-deals-rule-value-measure" required>
                                                    <# _.each( data.rule.object.select_choices, function( option, key ) { #>
                                                        <option value="{{ key }}">{{{ option }}}</option>
                                                    <# }); #>
                                                </select>
                                            </div>
                                        </div>
                                    <# } #>
                                <# } else { #>
                                    <input class="discount-deals-field" type="text" disabled>
                                <# } #>

                            <# } #>
                        </div>
                    </div>
                </td>
                <td class="discount-deals-w100">
                    <div class="discount-deals-rule__buttons discount-deals-center">
                        <button type="button" class="discount-deals-add-rule discount-deals-rule__add button">
                            <?php esc_html_e( 'and', 'discount-deals' ); ?>
                        </button>
                        <button type="button" class="button discount-deals-remove-rule discount-deals-rule__remove">
                            X
                        </button>
                    </div>
                </td>
            </tr>
        </table>
	</div>
</script>
<! -- formatter:on -->

<script type="text/template" id="tmpl-discount-deals-rule-group">
    <div class="rules"></div>
    <div class="discount-deals-rule-group__or"><span><?php esc_attr_e( 'or', 'discount-deals' ); ?></span></div>
</script>
