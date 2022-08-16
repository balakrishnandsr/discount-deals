<?php
/**
 * This class defines all discount deals settings | options.
 *
 * @package Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


    class Discount_Deals_Settings
    {
        /**
         * settings constant
         * @var string
         */
        const DISCOUNT_DEALS_OPTION_KEY = 'discount-deals-settings';

        public static $instance;

        /**
         * Contains all the configuration details
         * @var array
         */
        private static $config = array();

        private static $default_config = array(

            /**
             * todo:General
             */
            'calculate_discount_from' => 'sale_price',//sale_price,regular_price
            'apply_product_discount_to' => 'biggest_discount',//first,biggest_discount,lowest_discount,all
            'apply_cart_discount_to' => 'biggest_discount',//biggest_discount,lowest_discount,first,all
            'apply_discount_subsequently' => 0, //0,1
            'apply_cart_discount_subsequently' => 0,//1,0

            /**
             * todo:Product
             */
            'show_on_sale_badge' => 'disabled',//when_condition_matches,at_least_has_any_product_simple_rules,disabled
            'show_strikeout_price' => 'no', //| when rule matched | After matched | Dynamic Update
            //if yes above option show below
            'modify_price_at_product_page' => 1,//0,1
            'modify_price_at_category_page' => 1,//0,1
            'modify_price_at_shop_page' => 1,//0,1
            //Bulk Table
            'show_bulk_table' => 'no', //| select  positions of product page | below add to cart etc..



            /**
             * todo:Cart/checkout
             */
            'show_strikeout_on_cart' => 1,//1,0
            'show_applied_rules_message_on_cart' => 0,//1,0
            'applied_rule_message' => 'Discount <strong>{{title}}</strong> has been applied to your cart.',
            'display_saved_text' => 'disabled',//No | Each line item | After total | Both line item and after total
            'you_saved_text' => 'You saved {{total_discount}}',
            'free_shipping_title' => 'free shipping',
            'combine_all_cart_discounts' => 0,//0,1
            'hide_other_shipping_method' => 0, // 0,1

        );

        /**
         * To create instance
         * */
        public static function get_instance()
        {
            if (!self::$instance)
            {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * Save the configuration
         * @param $data
         * @param $key
         * @return bool
         */
        static function save_settings($key = self::DISCOUNT_DEALS_OPTION_KEY, $data = array())
        {
            return update_option($key, $data);
        }

        /**
         * @param $key - what configuration need to get
         * @param string $default - default value if config value not found
         * @return string - configuration value
         */
        static function get_settings($key, $default = '')
        {
            if (empty(self::$config)) {
                self::saved_settings();
            }
            if (isset(self::$config[$key])) {
                return self::$config[$key];
            } elseif (isset(self::$default_config[$key])) {
                return self::$default_config[$key];
            } else {
                return $default;
            }
        }

        /**
         * Set rule configuration to static variable
         */
        static function saved_settings()
        {
            $options = get_option(self::DISCOUNT_DEALS_OPTION_KEY);
            self::$config = wp_parse_args( $options, self::$default_config );
            return self::$config;
        }
    }