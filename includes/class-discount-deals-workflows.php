<?php
/**
 * Class to load discounts and rules
 *
 * @package    Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class to handle installation of the plugin
 */
class Discount_Deals_Workflows {

	/**
	 * Holds all discount types
	 *
	 * @var array $_discounts discount types.
	 */
	protected static $_discounts = array();

	/**
	 * Holds all discount types
	 *
	 * @var Discount_Deals_Workflow $_applied_workflows applied_workflows.
	 */
	protected static $_applied_workflows = array();

	/**
	 * Holds all discount types
	 *
	 * @var mixed $_applied_workflow_discounts applied_workflows.
	 */
	protected static $_applied_workflow_discounts = array();

	/**
	 * Holds all rules
	 *
	 * @var array $_rules workflow rules.
	 */
	protected static $_rules = array();

	/**
	 * Holds all active workflows
	 *
	 * @var array $_active_workflows workflows.
	 */
	protected static $_active_workflows = array();

	/**
	 * Holds all cart discounts
	 *
	 * @var array
	 */
	protected static $_cart_discounts = array();

	/**
	 * Class constructor
	 */
	public function __construct() {
		$this->load_discounts();
		$this->load_rules();
		$this->load_data_items();
	}//end __construct()


	/**
	 * Function to load discounts
	 *
	 * @return void
	 */
	public function load_discounts() {
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/discounts/class-discount-deals-workflow-discount.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/discounts/class-discount-deals-workflow-simple-discount.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/discounts/class-discount-deals-workflow-cart-discount.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/discounts/class-discount-deals-workflow-bulk-discount.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/discounts/class-discount-deals-workflow-bxgx-discount.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/discounts/class-discount-deals-workflow-bxgy-discount.php';
	}//end load_discounts()


	/**
	 * Function to handle load rules
	 *
	 * @return void
	 */
	public function load_rules() {
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/abstracts/class-discount-deals-workflow-rule-abstract.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/abstracts/class-discount-deals-workflow-rule-string-abstract.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/abstracts/class-discount-deals-workflow-rule-bool-abstract.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/abstracts/class-discount-deals-workflow-rule-select-abstract.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/abstracts/class-discount-deals-workflow-rule-searchable-select-abstract.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/abstracts/class-discount-deals-workflow-rule-date-abstract.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/abstracts/class-discount-deals-workflow-rule-preloaded-select-abstract.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/abstracts/class-discount-deals-workflow-rule-product-select-abstract.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/abstracts/class-discount-deals-workflow-rule-number-abstract.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/abstracts/class-discount-deals-workflow-rule-meta-abstract.php';

		// Actual rules.
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-shop-date-time.php';

		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-customer-account-created-date.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-customer-city.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-customer-company.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-customer-country.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-customer-email.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-customer-first-order-date.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-customer-is-guest.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-customer-last-order-date.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-customer-last-review-date.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-customer-meta.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-customer-order-count.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-customer-order-statuses.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-customer-phone.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-customer-postcode.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-customer-purchased-categories.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-customer-purchased-products.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-customer-review-count.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-customer-role.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-customer-state.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-customer-state-text-match.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-customer-tags.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-customer-total-spent.php';

		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-cart-coupons.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-cart-created-date.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-cart-item-categories.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-cart-item-count.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-cart-item-tags.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-cart-items.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-cart-total.php';

		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-product.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule-product-categories.php';
	}//end load_rules()

	/**
	 * Function to load data items
	 *
	 * @return void
	 */
	public function load_data_items() {
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/data_items/class-discount-deals-workflow-data-item-shop.php';
	}//end load_data_items()


	/**
	 * Get discount class by discount name
	 *
	 * @param string $discount_type Discount type name.
	 *
	 * @return Discount_Deals_Workflow_Discount
	 */
	public static function get_discount_type( $discount_type ) {
		$all_discounts   = self::get_all_discounts();
		$discount_class  = $all_discounts[ $discount_type ];
		$discount_object = new $discount_class();
		$discount_object->set_name( $discount_type );

		return $discount_object;
	}//end get_discount_type()


	/**
	 * Get all discounts
	 *
	 * @return array
	 */
	public static function get_all_discounts() {
		return array(
			'simple_discount' => 'Discount_Deals_Workflow_Simple_Discount',
			'bulk_discount'   => 'Discount_Deals_Workflow_Bulk_Discount',
			'cart_discount'   => 'Discount_Deals_Workflow_Cart_Discount',
			'bxgx_discount'   => 'Discount_Deals_Workflow_Bxgx_Discount',
			'bxgy_discount'   => 'Discount_Deals_Workflow_Bxgy_Discount',
		);
	}//end get_all_discounts()

	/**
	 * Get discount class by discount name
	 *
	 * @param string $rule_type Discount type name.
	 *
	 * @return Discount_Deals_Workflow_Rule_Abstract
	 */
	public static function get_rule_type( $rule_type ) {
		$all_discounts = self::get_all_rules();

		return $all_discounts[ $rule_type ];
	}//end get_rule_type()

	/**
	 * Get all rules
	 *
	 * @return Discount_Deals_Workflow_Rule_Abstract[]
	 */
	public static function get_all_rules() {
		$valid_rules = array(
			// Product.
			'product'                       => 'Discount_Deals_Workflow_Rule_Product',
			'product_categories'            => 'Discount_Deals_Workflow_Rule_Product_Categories',

			// Cart.
			'cart_coupons'                  => 'Discount_Deals_Workflow_Rule_Cart_Coupons',
			'cart_created_date'             => 'Discount_Deals_Workflow_Rule_Cart_Created_Date',
			'cart_item_categories'          => 'Discount_Deals_Workflow_Rule_Cart_Item_Categories',
			'cart_item_count'               => 'Discount_Deals_Workflow_Rule_Cart_Item_Count',
			'cart_item_tags'                => 'Discount_Deals_Workflow_Rule_Cart_Item_Tags',
			'cart_items'                    => 'Discount_Deals_Workflow_Rule_Cart_Items',
			'cart_total'                    => 'Discount_Deals_Workflow_Rule_Cart_Total',

			// Shop.
			'shop_date_time'                => 'Discount_Deals_Workflow_Rule_Shop_Date_Time',

			// Customer.
			'customer_is_guest'             => 'Discount_Deals_Workflow_Rule_Customer_Is_Guest',
			'customer_account_created_date' => 'Discount_Deals_Workflow_Rule_Customer_Account_Created_Date',
			'customer_city'                 => 'Discount_Deals_Workflow_Rule_Customer_City',
			'customer_company'              => 'Discount_Deals_Workflow_Rule_Customer_Company',
			'customer_country'              => 'Discount_Deals_Workflow_Rule_Customer_Country',
			'customer_email'                => 'Discount_Deals_Workflow_Rule_Customer_Email',
			// 'customer_first_order_date'     => 'Discount_Deals_Workflow_Rule_Customer_First_Order_Date',
			// 'customer_last_order_date'      => 'Discount_Deals_Workflow_Rule_Customer_Last_Order_Date',
			// 'customer_last_review_date'     => 'Discount_Deals_Workflow_Rule_Customer_Last_Review_Date',
			'customer_meta'                 => 'Discount_Deals_Workflow_Rule_Customer_Meta',
			'customer_order_count'          => 'Discount_Deals_Workflow_Rule_Customer_Order_Count',
			'customer_order_statuses'       => 'Discount_Deals_Workflow_Rule_Customer_Order_Statuses',
			'customer_phone'                => 'Discount_Deals_Workflow_Rule_Customer_Phone',
			'customer_postcode'             => 'Discount_Deals_Workflow_Rule_Customer_Postcode',
			'customer_purchased_categories' => 'Discount_Deals_Workflow_Rule_Customer_Purchased_Categories',
			'customer_purchased_products'   => 'Discount_Deals_Workflow_Rule_Customer_Purchased_Products',
			// 'customer_review_count'         => 'Discount_Deals_Workflow_Rule_Customer_Review_Count',
			'customer_role'                 => 'Discount_Deals_Workflow_Rule_Customer_Role',
			'customer_state'                => 'Discount_Deals_Workflow_Rule_Customer_State',
			'customer_state_text_match'     => 'Discount_Deals_Workflow_Rule_Customer_State_Text_Match',
			'customer_tags'                 => 'Discount_Deals_Workflow_Rule_Customer_Tags',
			'customer_total_spent'          => 'Discount_Deals_Workflow_Rule_Customer_Total_Spent',
		);
		if ( count( self::$_rules ) < count( $valid_rules ) ) {
			foreach ( $valid_rules as $rule_name => $class_name ) {
				$rule_class = new $class_name();

				/*
				 * Workflow discount
				 *
				 * @var Discount_Deals_Workflow_Rule_Abstract $rule_class Rule.
				 */

				$rule_class->set_name( $rule_name );
				self::$_rules[ $rule_name ] = $rule_class;
			}
		}

		return self::$_rules;
	}//end get_all_rules()


	/**
	 * Get data for discount
	 *
	 * @param Discount_Deals_Workflow_Discount $discount Discount class.
	 *
	 * @return array|false
	 */
	public static function get_discount_data( $discount ) {
		$data = array();

		if ( ! $discount ) {
			return false;
		}

		$data['title']               = $discount->get_title();
		$data['name']                = $discount->get_name();
		$data['description']         = $discount->get_description();
		$data['supplied_data_items'] = array_values( $discount->get_supplied_data_items() );

		return $data;
	}//end get_discount_data()

	/**
	 * Calculate BOGO discount
	 *
	 * @param WC_Product $product  Product object.
	 * @param integer    $quantity Item quantity.
	 *
	 * @return array
	 */
	public static function calculate_bogo_discount( $product, $quantity = 1 ) {
		$active_workflows = self::get_active_workflows();
		if ( empty( $active_workflows ) ) {
			return array();
		}
		$calculate_discount_from = Discount_Deals_Settings::get_settings( 'calculate_discount_from', 'sale_price' );

		if ( 'regular_price' === $calculate_discount_from ) {
			$price = ( is_a( $product, 'WC_Product' ) ) ? $product->get_regular_price() : 0;
		} else {
			$price = ( is_a( $product, 'WC_Product' ) ) ? $product->get_sale_price() : 0;
		}
		$apply_as = Discount_Deals_Settings::get_settings( 'apply_bogo_discount_to', 'lowest_matched' );
		$discount = array();
		if ( ! empty( $active_workflows['exclusive'] ) ) {
			$discount = self::get_bogo_discount( $active_workflows['exclusive'], $product, $price, $quantity, $apply_as );
		}

		if ( empty( $discount ) && ! empty( $active_workflows['non_exclusive'] ) ) {
			$discount = self::get_bogo_discount( $active_workflows['non_exclusive'], $product, $price, $quantity, $apply_as );
		}

		return $discount;
	}//end calculate_bogo_discount()


	/**
	 * Get the bogo discount for product
	 *
	 * @param Discount_Deals_Workflow[] $workflows Workflows to validate against.
	 * @param WC_Product                $product   Product object.
	 * @param float                     $price     Item price.
	 * @param integer                   $quantity  Item quantity.
	 * @param string                    $apply_as  How to apply.
	 *
	 * @return array
	 */
	public static function get_bogo_discount( $workflows, $product, $price, $quantity, $apply_as ) {
		$valid_discounts = array();
		foreach ( $workflows as $workflow ) {
			$workflow_id = $workflow->get_id();
			if ( in_array( $workflow->get_type(), array( 'bxgx_discount', 'bxgy_discount' ) ) ) {
				$workflow->data_layer()->set_item( 'product', $product );
				if ( $workflow->validate_rules() ) {
					$discount_details = $workflow->may_have_bogo_discount( $product, $price, $quantity );
					if ( ! empty( $discount_details ) ) {
						$valid_discounts[ $workflow_id ] = $discount_details;
					}
				}
			}
		}
		$discount = array();
		if ( ! empty( $valid_discounts ) ) {
			$workflow_ids      = array_keys( $valid_discounts );
			$extra_information = array(
				'type'     => 'product',
				'id'       => $product->get_id(),
				'quantity' => $quantity,
				'apply_as' => $apply_as,
				'price'    => $price,
			);
			$valid_discounts_totals = array_column( $valid_discounts, 'total' );
			switch ( $apply_as ) {
				default:
				case 'lowest_matched':
					$value_index = array_search( min( $valid_discounts_totals ), $valid_discounts_totals );
					break;
				case 'biggest_matched':
					$value_index = array_search( max( $valid_discounts_totals ), $valid_discounts_totals );
					break;
			}
			$workflow_id = $workflow_ids[ $value_index ];
			self::set_applied_workflows( $workflow_id, $valid_discounts[ $workflow_id ], $extra_information );
			$discount[ $workflow_id ] = $valid_discounts[ $workflow_id ];
		}

		return $discount;
	}//end get_bogo_discount()


	/**
	 * All messages for that position
	 *
	 * @param WC_Product $product  Product object.
	 * @param string     $position Where to show the promotional message.
	 *
	 * @return array
	 */
	public static function get_product_promotional_messages( $product, $position = '' ) {
		if ( ! is_a( $product, 'WC_Product' ) ) {
			return array();
		}
		if ( is_null( $position ) ) {
			return array();
		}
		$active_workflows = self::get_active_workflows();
		$all_messages     = array(
			'all_promotions'     => array(),
			'bulk_promotions'    => array(),
			'promotion_messages' => array(),
			'bxgx_promotions'    => array(),
			'bxgy_promotions'    => array(),
		);
		if ( ! empty( $active_workflows['all_active'] ) ) {
			foreach ( $active_workflows['all_active'] as $workflow ) {
				$promotion_details = $workflow->get_promotional_message( $position );
				if ( is_null( $promotion_details ) ) {
					continue;
				}
				if ( empty( $promotion_details['bulk_promotion'] ) && empty( $promotion_details['promotion_message'] ) ) {
					continue;
				}
				$workflow->data_layer()->set_item( 'product', $product );
				if ( ! $workflow->validate_index() ) {
					continue;
				}
				$when_to_show   = $workflow->get_when_to_show_promotional_message();
				$show_promotion = false;
				if ( 'all_time' == $when_to_show ) {
					$show_promotion = true;
				} else {
					$is_rules_passed = $workflow->validate_rules();
					if ( 'after_rule' == $when_to_show && $is_rules_passed ) {
						$show_promotion = true;
					}

					if ( 'before_rule' == $when_to_show && ! $is_rules_passed ) {
						$show_promotion = true;
					}
				}
				if ( $show_promotion ) {
					if ( ! empty( $promotion_details['bulk_promotion'] ) && is_array( $promotion_details['bulk_promotion'] ) ) {
						$all_messages['bulk_promotions'] = array_merge( $all_messages['bulk_promotions'], $promotion_details['bulk_promotion'] );
						$all_messages['all_promotions']  = array_merge( $all_messages['all_promotions'], $promotion_details['bulk_promotion'] );
					}
					if ( ! empty( $promotion_details['bxgy_promotion'] ) && is_array( $promotion_details['bxgy_promotion'] ) ) {
						$all_messages['bxgy_promotions'] = array_merge( $all_messages['bxgy_promotions'], $promotion_details['bxgy_promotion'] );
						$all_messages['all_promotions']  = array_merge( $all_messages['all_promotions'], $promotion_details['bxgy_promotion'] );
					}
					if ( ! empty( $promotion_details['bxgx_promotion'] ) && is_array( $promotion_details['bxgx_promotion'] ) ) {
						$all_messages['bxgx_promotions'] = array_merge( $all_messages['bxgx_promotions'], $promotion_details['bxgx_promotion'] );
						$all_messages['all_promotions']  = array_merge( $all_messages['all_promotions'], $promotion_details['bxgx_promotion'] );
					}
					if ( ! empty( $promotion_details['promotion_message'] ) ) {
						$all_messages['promotion_messages'][] = $promotion_details['promotion_message'];
					}
				}
			}
		}

		return $all_messages;
	}//end get_product_promotional_messages()


	/**
	 * Get_active_workflows.
	 *
	 * @return array
	 */
	public static function get_active_workflows() {
		if ( ! empty( self::$_active_workflows ) ) {
			return self::$_active_workflows;
		}
		self::$_active_workflows = array();
		$workflows_db            = new Discount_Deals_Workflow_DB();
		$workflows               = $workflows_db->get_by_conditions( 'dd_status = 1', 'object' );
		if ( ! empty( $workflows ) ) {
			$data_items = array(
				'customer' => WC()->customer,
				'cart'     => WC()->cart,
			);

			foreach ( $workflows as $workflow ) {
				$workflow_object = new Discount_Deals_Workflow( $workflow );
				$workflow_object->set_data_layer( $data_items );
				self::$_active_workflows['all_active'][] = $workflow_object;
				if ( $workflow_object->get_exclusive() ) {
					self::$_active_workflows['exclusive'][] = $workflow_object;
				} else {
					self::$_active_workflows['non_exclusive'][] = $workflow_object;
				}
			}
		}

		return self::$_active_workflows;
	}//end get_active_workflows()

	/**
	 * Calculate product discount.
	 *
	 * @param float      $price                  Product price.
	 * @param WC_Product $product                Product.
	 * @param integer    $quantity               Quantity.
	 * @param boolean    $validate_against_rules Need to validate against rules.
	 *
	 * @return integer|void
	 */
	public static function calculate_product_discount( $price, $product, $quantity = 1, $validate_against_rules = true ) {
		$active_workflows = self::get_active_workflows();
		if ( empty( $active_workflows ) ) {
			return $price;
		}
		$calculate_discount_from = Discount_Deals_Settings::get_settings( 'calculate_discount_from', 'sale_price' );

		if ( 'regular_price' === $calculate_discount_from ) {
			$price = ( is_object( $product ) && is_callable(
				array(
					$product,
					'get_regular_price',
				)
			) ) ? $product->get_regular_price() : 0;
		}

		$apply_as         = Discount_Deals_Settings::get_settings( 'apply_product_discount_to', 'lowest_matched' );
		$discounted_price = false;
		if ( ! empty( $active_workflows['exclusive'] ) ) {
			$discounted_price = self::get_product_discount( $active_workflows['exclusive'], $product, $price, $quantity, $apply_as, $validate_against_rules );
		}

		if ( false === $discounted_price && ! empty( $active_workflows['non_exclusive'] ) ) {
			$discounted_price = self::get_product_discount( $active_workflows['non_exclusive'], $product, $price, $quantity, $apply_as, $validate_against_rules );
		}

		if ( false === $discounted_price ) {
			return $price;
		}

		return $discounted_price;

	}//end calculate_product_discount()

	/**
	 * Get discount details by workflows.
	 *
	 * @param Discount_Deals_Workflow[] $workflows              Array of objects.
	 * @param WC_Product                $product                Product object.
	 * @param float                     $price                  Product price.
	 * @param integer                   $quantity               Product quantity.
	 * @param string                    $apply_as               Apply Discount as.
	 * @param boolean                   $validate_against_rules Need to validate against rules.
	 *
	 * @return array|mixed
	 */
	public static function get_product_discount( $workflows, $product, $price, $quantity, $apply_as, $validate_against_rules = false ) {
		if ( empty( $workflows ) ) {
			return false;
		}
		if ( ! is_numeric( $price ) ) {
			return false;
		}
		$valid_discounts  = array();
		$subsequent_price = $price;
		foreach ( $workflows as $workflow ) {
			$workflow_id = $workflow->get_id();

			if ( in_array( $workflow->get_type(), array( 'cart_discount', 'bxgx_discount', 'bxgy_discount' ) ) ) {
				continue;
			}
			$workflow->data_layer()->set_item( 'product', $product );
			$apply_subsequently = Discount_Deals_Settings::get_settings( 'apply_discount_subsequently', 'no' );

			if ( 'yes' == $apply_subsequently && 'all_matched' === $apply_as ) {
				$discounts        = array_sum( $valid_discounts );
				$subsequent_price = $subsequent_price - $discounts;
			}
			if ( ! $validate_against_rules ) {
				$is_passed = $workflow->validate_index();
			} else {
				$is_passed = $workflow->validate_rules();
			}
			if ( $is_passed ) {
				$discount = $workflow->may_have_product_discount( $product, $subsequent_price, $quantity );
				if ( 0 < $discount ) {
					$valid_discounts[ $workflow_id ] = $discount;
				}
			}
		}
		$discount_price = self::get_matched_product_discount( $valid_discounts, $product, $price, $quantity, $apply_as );
		if ( 0 >= $discount_price ) {
			return false;
		}

		return $price - $discount_price;

	}//end get_product_discount()


	/**
	 * Get matched Discount
	 *
	 * @param array      $valid_discounts Discounts.
	 * @param WC_Product $product         Product object.
	 * @param float      $price           Product price.
	 * @param integer    $quantity        Product quantity.
	 * @param string     $apply_as        Apply Discount as.
	 *
	 * @return float|integer|mixed
	 */
	public static function get_matched_product_discount( $valid_discounts, $product, $price, $quantity, $apply_as ) {
		$extra_information = array(
			'type'     => 'product',
			'id'       => $product->get_id(),
			'quantity' => $quantity,
			'apply_as' => $apply_as,
			'price'    => $price,
		);
		if ( ! empty( $valid_discounts ) ) {
			switch ( $apply_as ) {
				case 'biggest_matched':
					$discount    = max( $valid_discounts );
					$workflow_id = array_search( $discount, $valid_discounts );
					self::set_applied_workflows( $workflow_id, $discount, $extra_information );

					return $discount;
				case 'lowest_matched':
					$discount    = min( $valid_discounts );
					$workflow_id = array_search( $discount, $valid_discounts );
					self::set_applied_workflows( $workflow_id, $discount, $extra_information );

					return $discount;
				default:
				case 'all_matched':
					$discount_total = 0;
					foreach ( $valid_discounts as $workflow_id => $discount ) {
						$discount_total += $discount;
						self::set_applied_workflows( $workflow_id, $discount, $extra_information );
					}

					return $discount_total;
			}
		}

		return 0;
	}//end get_matched_product_discount()

	/**
	 * Set all applied discounts in store.
	 *
	 * @param integer $workflow_id Workflow ID.
	 * @param float   $discount    Discount for particular workflow.
	 * @param array   $extra       Save some extra information.
	 *
	 * @return void
	 */
	public static function set_applied_workflows( $workflow_id, $discount, $extra = array() ) {
		self::$_applied_workflows[]          = $workflow_id;
		self::$_applied_workflow_discounts[] = array(
			'workflow_id'          => $workflow_id,
			'discount_information' => $discount,
			'extra_information'    => $extra,
		);
	}//end set_applied_workflows()


	/**
	 * Get all applied discounts in store.
	 *
	 * @return Discount_Deals_Workflow[]
	 */
	public static function get_applied_workflows() {
		$applied_workflow_ids = array_unique( self::$_applied_workflows );
		$applied_workflows    = array();
		if ( ! empty( $applied_workflow_ids ) ) {
			$active_workflows = self::get_active_workflows();
			if ( ! empty( $active_workflows['all_active'] ) ) {
				foreach ( $active_workflows['all_active'] as $workflow ) {
					/*
					 * Variable declaration.
					 * @var Discount_Deals_Workflow $workflow workflow object.
					 */

					$workflow_id = $workflow->get_id();
					if ( in_array( $workflow_id, $applied_workflow_ids ) ) {
						$applied_workflows[] = $workflow;
					}
				}
			}
		}

		return $applied_workflows;
	}//end get_applied_workflows()


	/**
	 * Get all applied discounts in store.
	 *
	 * @return Discount_Deals_Workflow[]
	 */
	public static function get_applied_workflow_discounts() {
		return self::$_applied_workflow_discounts;
	}//end get_applied_workflow_discounts()



	/**
	 * Calculate Cart Discount
	 *
	 * @return array
	 */
	public static function calculate_cart_discount() {
		if ( ! empty( self::$_cart_discounts['is_discount_calculated'] ) ) {
			return self::$_cart_discounts['discount_details'];
		}
		$active_workflows = self::get_active_workflows();
		if ( empty( $active_workflows ) ) {
			return array();
		}

		$apply_as = Discount_Deals_Settings::get_settings( 'apply_product_discount_to', 'lowest_matched' );

		$valid_workflows = array();
		if ( ! empty( $active_workflows['exclusive'] ) ) {
			$valid_workflows = self::get_cart_discount( $active_workflows['exclusive'], $apply_as );
		}

		if ( empty( $valid_workflows ) && ! empty( $active_workflows['non_exclusive'] ) ) {
			$valid_workflows = self::get_cart_discount( $active_workflows['non_exclusive'], $apply_as );
		}
		self::$_cart_discounts['is_discount_calculated'] = 'yes';
		self::$_cart_discounts['discount_details']       = $valid_workflows;

		return $valid_workflows;

	}//end calculate_cart_discount()

	/**
	 * Get cart discount
	 *
	 * @param Discount_Deals_Workflow[] $workflows Workflows to validate.
	 * @param string                    $apply_as  How to apply discount.
	 *
	 * @return array
	 */
	public static function get_cart_discount( $workflows, $apply_as ) {

		if ( empty( $workflows ) ) {
			return array();
		}

		if ( ! is_a( WC()->cart, 'WC_Cart' ) ) {
			return array();
		}

		$valid_discounts  = array();
		$applied_discount = array();
		$free_shipping    = array();

		$subtotal = WC()->cart->get_subtotal();
		$subtotal_tax = WC()->cart->get_subtotal_tax();
		/**
		 * Filter to modify cart subtotal.
		 *
		 * @since 1.0.0
		 */
		$subsequent_subtotal = apply_filters( 'discount_deals_cart_subtotal', ( $subtotal + $subtotal_tax ), $subtotal, $subtotal_tax );
		foreach ( $workflows as $workflow ) {
			$workflow_id = $workflow->get_id();
			if ( 'cart_discount' == $workflow->get_type() ) {
				$apply_subsequently = Discount_Deals_Settings::get_settings( 'apply_cart_discount_subsequently', 'no' );

				if ( 'yes' == $apply_subsequently && 'all_matched' === $apply_as ) {
					$discounts           = array_sum( $valid_discounts );
					$subsequent_subtotal = $subsequent_subtotal - $discounts;
				}

				if ( $workflow->validate_rules() ) {
					$processed_discount = $workflow->may_have_cart_discount( WC()->cart, $subsequent_subtotal );
					if ( 0 > $processed_discount ) {
						$free_shipping[ $workflow_id ] = $processed_discount;
					} elseif ( 0 < $processed_discount ) {
						$applied_discount[ $workflow_id ] = $processed_discount;
					}
				}
			}
		}

		$valid_discounts['discounts']     = self::get_matched_cart_discount( $applied_discount );
		$valid_discounts['free_shipping'] = self::get_matched_cart_discount( $free_shipping, 'shipping' );

		return $valid_discounts;
	}//end get_cart_discount()


	/**
	 * Get matched Discount
	 *
	 * @param array  $valid_discounts All valid discounts.
	 * @param string $type            Type to check.
	 *
	 * @return array
	 */
	public static function get_matched_cart_discount( $valid_discounts, $type = 'amount' ) {
		$apply_as             = Discount_Deals_Settings::get_settings( 'apply_cart_discount_to', 'lowest_matched' );
		$calculated_discounts = 0;
		$extra_information    = array(
			'type'       => 'cart',
			'apply_as'   => $apply_as,
			'apply_type' => $type,
		);
		if ( ! empty( $valid_discounts ) ) {
			switch ( $apply_as ) {
				case 'biggest_with_free_shipping':
					if ( 'amount' === $type ) {
						$discount             = max( $valid_discounts );
						$calculated_discounts = array( $discount );
						$workflow_id          = array_search( $discount, $valid_discounts );
						self::set_applied_workflows( $workflow_id, $discount, $extra_information );
					} else {
						$calculated_discounts = $valid_discounts;
						foreach ( $valid_discounts as $workflow_id => $discount ) {
							self::set_applied_workflows( $workflow_id, $discount, $extra_information );
						}
					}
					break;
				case 'biggest_without_free_shipping':
					if ( 'amount' === $type ) {
						$discount             = max( $valid_discounts );
						$calculated_discounts = array( $discount );
						$workflow_id          = array_search( $discount, $valid_discounts );
						self::set_applied_workflows( $workflow_id, $discount, $extra_information );
					} else {
						$calculated_discounts = array();
					}
					break;
				case 'lowest_with_free_shipping':
					if ( 'amount' === $type ) {
						$discount             = min( $valid_discounts );
						$calculated_discounts = array( $discount );
						$workflow_id          = array_search( $discount, $valid_discounts );
						self::set_applied_workflows( $workflow_id, $discount, $extra_information );
					} else {
						$calculated_discounts = $valid_discounts;
						foreach ( $valid_discounts as $workflow_id => $discount ) {
							self::set_applied_workflows( $workflow_id, $discount, $extra_information );
						}
					}
					break;
				case 'lowest_without_free_shipping':
					if ( 'amount' === $type ) {
						$discount             = min( $valid_discounts );
						$calculated_discounts = array( $discount );
						$workflow_id          = array_search( $discount, $valid_discounts );
						self::set_applied_workflows( $workflow_id, $discount, $extra_information );
					} else {
						$calculated_discounts = array();
					}
					break;
				case 'free_shipping_only':
					if ( 'amount' === $type ) {
						$calculated_discounts = array();
					} else {
						$calculated_discounts = $valid_discounts;
						foreach ( $valid_discounts as $workflow_id => $discount ) {
							self::set_applied_workflows( $workflow_id, $discount, $extra_information );
						}
					}
					break;
				default:
				case 'all_matched':
					$calculated_discounts = $valid_discounts;
					foreach ( $valid_discounts as $workflow_id => $discount ) {
						self::set_applied_workflows( $workflow_id, $discount, $extra_information );
					}
					break;
			}
		}

		return $calculated_discounts;
	}//end get_matched_cart_discount()


}//end class
