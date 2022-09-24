/**
 * Code contains all stuffs related to admin
 *
 * @package Discount_Deals
 */

(function ($, data) {
	'use strict';
	$(
		function () {
			$( document ).on(
				'submit',
				'form',
				function (e) {
					var $form = $(this);
					var bulk_action = $form.find('select[name="action"]').val();
					if (bulk_action == "delete") {
						if (confirm(discount_deals_workflows_localize_script.i18n.alert_bulk_delete)) {
							return true;
						} else {
							e.preventDefault();
						}
					}
				}
			);
			$( document ).on(
				'click',
				'.row-actions .delete a',
				function (e) {
					if (confirm(discount_deals_workflows_localize_script.i18n.alert_delete)) {
						return true;
					} else {
						e.preventDefault();
					}
				}
			);
			$( document ).on(
				'change',
				'.discount-deals-workflow-switch',
				function () {
					$( ".discount-deals-fp-loader" ).removeClass( 'discount-deals-hidden' );
					let workflow = $( this ).data( 'workflow' );
					let column = $( this ).data( 'column' );
					$.ajax(
						{
							method: 'POST',
							url: ajaxurl,
							data: {
								action: 'discount_deals_update_workflow_column_value',
								workflow: workflow,
								column: column,
								column_value: $( this ).is( ':checked' ) ? 1 : 0,
								security: discount_deals_workflows_localize_script.nonce.change_column_status,
							},
							complete:function () {
								$( ".discount-deals-fp-loader" ).addClass( 'discount-deals-hidden' );
							}
						}
					)
				}
			)
		}
	);
})( jQuery, discount_deals_workflows_localize_script );
