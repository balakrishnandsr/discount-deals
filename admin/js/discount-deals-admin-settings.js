/**
 * Code contains all stuffs related to admin settings
 *
 * @package Discount_Deals
 */

(function ($, data) {
    'use strict';

    function product_apply_discount_to(value) {
        if (value == "all_matched") {
            $('input[name=wc_settings_tab_discount_deals_apply_discount_subsequently]').closest('tr').show();
        } else {
            $('input[name=wc_settings_tab_discount_deals_apply_discount_subsequently]').closest('tr').hide();
        }
    }

    $(document).on('change', '#wc_settings_tab_discount_deals_apply_product_discount_to', function () {
        product_apply_discount_to($(this).val())
    })

    function cart_apply_discount_to(value) {
        if (value == "all_matched") {
            $('input[name=wc_settings_tab_discount_deals_apply_cart_discount_subsequently]').closest('tr').show();
        } else {
            $('input[name=wc_settings_tab_discount_deals_apply_cart_discount_subsequently]').closest('tr').hide();
        }
    }

    $(document).on('change', '#wc_settings_tab_discount_deals_apply_cart_discount_to', function () {
        cart_apply_discount_to($(this).val())
    })

    function cart_apply_mode(value) {
        if (value == "coupon") {
            $('#wc_settings_tab_discount_deals_apply_coupon_title').closest('tr').show();
            $('#wc_settings_tab_discount_deals_apply_fee_title').closest('tr').hide();
        } else {
            $('#wc_settings_tab_discount_deals_apply_coupon_title').closest('tr').hide();
            $('#wc_settings_tab_discount_deals_apply_fee_title').closest('tr').show();
        }
    }

    $(document).on('change', 'input[name=wc_settings_tab_discount_deals_apply_cart_discount_as]', function () {
        cart_apply_mode($('input[type="radio"][name="wc_settings_tab_discount_deals_apply_cart_discount_as"]:checked').val())
    })

    function you_saved_text(value) {
        if (value == "disabled") {
            $('#wc_settings_tab_discount_deals_you_saved_text').closest('tr').hide();
        } else {
            $('#wc_settings_tab_discount_deals_you_saved_text').closest('tr').show();
        }
    }

    $(document).on('change', '#wc_settings_tab_discount_deals_where_display_saving_text', function () {
        you_saved_text($(this).val())
    })

    function general_show_promotion_message(value) {
        console.log(value)
        if (value == "yes") {
            $('#wc_settings_tab_discount_deals_applied_discount_message').closest('tr').show();
            $('input[type="radio"][name="wc_settings_tab_discount_deals_combine_applied_discounts_message"]').closest('tr').show();
        } else {
            $('#wc_settings_tab_discount_deals_applied_discount_message').closest('tr').hide();
            $('input[type="radio"][name="wc_settings_tab_discount_deals_combine_applied_discounts_message"]').closest('tr').hide();
        }
    }

    $(document).on('change', 'input[name=wc_settings_tab_discount_deals_show_applied_discounts_message]', function () {
        general_show_promotion_message($('input[type="radio"][name="wc_settings_tab_discount_deals_show_applied_discounts_message"]:checked').val())
    })

    $(function () {
        product_apply_discount_to($('#wc_settings_tab_discount_deals_apply_product_discount_to').val())
        cart_apply_discount_to($('#wc_settings_tab_discount_deals_apply_cart_discount_to').val())
        cart_apply_mode($('input[type="radio"][name="wc_settings_tab_discount_deals_apply_cart_discount_as"]:checked').val())
        you_saved_text($('#wc_settings_tab_discount_deals_where_display_saving_text').val())
        general_show_promotion_message($('input[type="radio"][name="wc_settings_tab_discount_deals_show_applied_discounts_message"]:checked').val());
    });
})(jQuery);
