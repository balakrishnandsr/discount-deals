/**
 * JS file contains all code necessary for frontend
 *
 * @package Discount_Deals
 */

(function ($) {
    'use strict';
    var timeout = false;

    function refresh_cart() {
        $('body').trigger('update_checkout');
    }

    $(document).on('change', 'input[name="payment_method"],input[name="billing_city"],input[name="billing_company"],input[name="billing_postcode"],input[name="billing_phone"]', function () {
        refresh_cart();
    });

    /**
     * refresh cart when Email changed
     */
    $(document).on('blur', 'input[name="billing_email"], select#billing_state', function () {
        refresh_cart();
    });
    $(document).on('change', '[name="quantity"]', function () {
        if (timeout) {
            clearTimeout(timeout);
            timeout = false;
        }
        var $quantity_input_field = $(this);
        var quantity = $quantity_input_field.val();
        var product_id = 0;
        var $price_place = null;
        var $form = $quantity_input_field.closest("form");
        if ($form.find('button[name="add-to-cart"]').length) {
            product_id = $form.find('button[name="add-to-cart"]').val();
            var target = 'div.product p.price';
            $price_place = $(target).first();
        } else if ($form.find('input[name="variation_id"]').length) {
            product_id = $form.find('input[name="variation_id"]').val();
            var target = 'div.product .woocommerce-variation-price';
            $price_place = $(target);
            if (!$(target + ' .price').length) {
                $price_place.html("<div class='price'></div>");
            }
            $price_place = $(target + ' .price')
        }

        if (!product_id || !$price_place || product_id == 0) {
            return;
        }

        timeout = setTimeout(function () {
            var data = {
                action: 'discount_deals_get_product_discount_price',
                product_id: product_id,
                quantity: quantity,
                nonce: discount_deals_params.product_price_nonce,
            };
            $.ajax({
                url: discount_deals_params.ajax_url,
                data: data,
                type: 'POST',
                success: function (response) {
                    if (response.price_html) {
                        $price_place.html(response.price_html)
                    } else {
                        if (response.original_price_html != undefined) {
                            $price_place.html(response.original_price_html)
                        }
                    }
                    $('.dd-bulk-table-summary-quantity').html(response.quantity_price_summary);
                    $('.dd-bulk-table-summary-quantity-in-cart').html(response.existing_quantity_summary);
                    $('.dd-bulk-table-summary-total').html(response.total_price_summary);
                },
                error: function (response) {
                    $price_place.html("")
                }
            });
        }, 1000)
    });

})(jQuery);
