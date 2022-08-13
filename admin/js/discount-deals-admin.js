/**
 * Code contains all stuffs related to admin
 *
 * @package Discount_Deals
 */

(function( $ ) {
	'use strict';
	const init_discounts = function () {
		$( document ).on(
			"change",
			"#discount_deals_workflow_type",
			function () {
				let discount_type = $( this ).val();
				fetch_discount_details( discount_type ).done(
					function (response) {
						console.log( response )
					}
				)
			}
		);

		const fetch_discount_details = function (discount_type) {
			return $.ajax(
				{
					method: 'GET',
					url: ajaxurl,
					data: {
						action: 'discount_deals_fill_discount_fields',
						discount_type: discount_type
					}
				}
			);
		}
	}

	$(
		function () {
			init_discounts();
		}
	);
})( jQuery );
