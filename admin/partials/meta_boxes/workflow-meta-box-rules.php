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
    <p class="discount-deals-rules-empty-message"><?php printf( esc_attr__( 'Rules can be used to add conditional logic to workflows. Click the %1$s+ Add Rule Group%2$s button to create a rule.', 'discount-deals' ), '<strong>', '</strong>' ); ?></p>
</script>

<! -- formatter:off -->
<script type="text/template" id="tmpl-discount-deals-rule">

    <div class="discount-deals-rule discount-deals-rule--type-{{ data.rule.object.type ? data.rule.object.type : 'new' }} discount-deals-rule--compare-{{ data.rule.compare }}">
        <div class="discount-deals-rule__fields">
            <div class="discount-deals-rule-select-container discount-deals-rule__field-container">
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
            <div class="discount-deals-rule-field-compare discount-deals-rule__field-container">
                <select name="{{ data.field_name_base }}[compare]" class="discount-deals-rule-compare-field discount-deals-field" <#
                    if ( _.isEmpty( data.rule.object.compare_types ) ) { #>disabled<# } #>>
                    <# _.each( data.rule.object.compare_types, function( option, key ) { #>
                        <option value="{{ key }}">{{ option }}</option>
                    <# }) #>
                </select>
            </div>
            <div class="discount-deals-rule-field-value discount-deals-rule__field-container <# if ( data.rule.is_value_loading ) { #>discount-deals-loading<# } #>">
                <# if ( data.rule.isValueLoading ) { #>
                    <div class="aw-loader"></div>
                <# } else { #>

                    <# if ( data.rule.object.type === 'number' ) { #>
                        <input name="{{ data.fieldNameBase }}[value]" class="discount-deals-field discount-deals-rule-value-field" type="text" required>
                    <# } else { #>
                    <# } #>

                <# } #>
            </div>
        </div>
        <div class="discount-deals-rule__buttons">
            <button type="button" class="discount-deals-add-rule discount-deals-rule__add button">
				<?php esc_html_e( 'and', 'discount-deals' ); ?>
            </button>
            <button type="button" class="discount-deals-remove-rule discount-deals-rule__remove">
				<?php esc_html_e( 'Remove', 'discount-deals' ); ?>
            </button>
        </div>
    </div>
</script>
<! -- formatter:on -->

<script type="text/template" id="tmpl-discount-deals-rule-group">
    <div class="rules"></div>
    <div class="discount-deals-rule-group__or"><span><?php esc_attr_e( 'or', 'discount-deals' ); ?></span></div>
</script>
