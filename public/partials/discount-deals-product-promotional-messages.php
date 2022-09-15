<?php
/**
 * Provide a product promotional messages view
 *
 * @package    Discount_Deals
 */

if (!defined('ABSPATH')) {
    exit;
}

/*
 * Variable declaration
 *
 * @var array $all_promotions all promotional messages.
 * @var WC_Product $product product object.
 * @var string $position product object.
 */

if (!empty($all_promotions)) {
    global $product;

    $calculate_discount_from = Discount_Deals_Settings::get_settings('calculate_discount_from', 'sale_price');

    if ('regular_price' === $calculate_discount_from) {
        $price = $product->get_regular_price();
    } else {
        $price = $product->get_sale_price();
    }
    $promotional_messages = array();
    $is_header_printed = false;
    foreach ($all_promotions as $promotion) {
        if (!empty($promotion['bulk_promotion']) && is_array($promotion['bulk_promotion'])) {
            if (!$is_header_printed) {
                $is_header_printed = true;
                ?>
                <table>
                <thead>
                <tr>
                    <th><?php esc_html_e('Qty', 'discount-deals') ?></th>
                    <th><?php esc_html_e('Discount', 'discount-deals') ?></th>
                    <th><?php esc_html_e('Price Per Unit', 'discount-deals') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
            }
            foreach ($promotion['bulk_promotion'] as $range) {
                $type = discount_deals_get_value_from_array($range, 'type', 'flat');
                $min_quantity = discount_deals_get_value_from_array($range, 'min_quantity', 0);
                $max_quantity = discount_deals_get_value_from_array($range, 'max_quantity', 999999999);
                $max_discount = discount_deals_get_value_from_array($range, 'max_discount', 0);
                $value = discount_deals_get_value_from_array($range, 'value', 0);
                if ("flat" == $type) {
                    $discount_value = wc_price($value);
                    $discount_price = $price - $value;
                } else {
                    $discount_value = $value . '%';
                    if(empty($value)){
                        $discount_price = $price;
                    } elseif ( 100 < $value ) {
                        $discount_price = 0;
                    }else{
                        $discount_price = $price * ( $value / 100 );
                    }
                }
                ?>
                <tr>
                    <td><?php echo $min_quantity . '-' . $max_quantity ?></td>
                    <td><?php echo $discount_value; ?></td>
                    <td><?php echo (0 < $discount_price) ? wc_price($discount_price) : wc_price(0) ?></td>
                </tr>
                <?php
            }
            ?>


            <?php
        }
        if (!empty($promotion['promotion_message'])) {
            $promotional_messages[] = wp_kses($promotion['promotion_message'], wp_kses_allowed_html('post'));

        }
    }
    if ($is_header_printed) {
        ?>
        </tbody>
        </table><?php
    }
    echo implode('<br />', $promotional_messages);
}