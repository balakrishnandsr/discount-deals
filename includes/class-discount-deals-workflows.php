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
	 * Holds all active workflows
	 *
	 * @var array $_active_workflows workflows.
	 */
	protected static $_active_workflows = array();

	/**
	 * Class constructor
	 */
	public function __construct() {
		$this->load_discounts();
	}//end __construct()


	/**
	 * Function to handle uninstall process
	 *
	 * @return void
	 */
	public function load_discounts() {
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/discounts/class-discount-deals-workflow-discount.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/discounts/class-discount-deals-workflow-simple-discount.php';
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/discounts/class-discount-deals-workflow-bulk-discount.php';
	}//end load_discounts()


	/**
	 * Get all discounts
	 *
	 * @return array
	 */
	public static function get_all_discounts() {
		$valid_discounts = array(
			'simple_discount' => 'Discount_Deals_Workflow_Simple_Discount',
			'bulk_discount'   => 'Discount_Deals_Workflow_Bulk_Discount',
		);
		if ( count( self::$_discounts ) < count( $valid_discounts ) ) {
			foreach ( $valid_discounts as $discount_name => $class_name ) {
				$discount_class = new $class_name();

				/*
				 * Workflow discount
				 * @var Discount_Deals_Workflow_Discount $discount_class Discount.
				 */

				$discount_class->set_name( $discount_name );
				self::$_discounts[ $discount_name ] = $discount_class;
			}
		}

		return self::$_discounts;
	}//end get_all_discounts()


	/**
	 * Get_active_workflows.
	 *
	 * @return Discount_Deals_Workflow[]
	 */
	public static function get_active_workflows() {
		if ( ! empty( self::$_active_workflows ) ) {
			return self::$_active_workflows;
		}
		$workflows_db = new Discount_Deals_Workflow_DB();
		$workflows    = $workflows_db->get_by_conditions( 'dd_status = 1', 'object' );
		if ( ! empty( $workflows ) ) {
			foreach ( $workflows as $workflow ) {
                $active_workflows = new Discount_Deals_Workflow( $workflow );
                self::$_active_workflows['all_active'][] = $active_workflows;
                if( !empty( $workflow->dd_exclusive ) && 'yes' === $workflow->dd_exclusive ){
                    self::$_active_workflows['exclusive'][] = $active_workflows;
                }else{
                    self::$_active_workflows['non_exclusive'][] = $active_workflows;
                }
			}
		}
		return self::$_active_workflows;
	}//end get_active_workflows()


	/**
	 * Function to handle uninstall process
	 *
	 * @return void
	 */
	public function load_rules() {
		require_once DISCOUNT_DEALS_ABSPATH . 'includes/workflows/rules/class-discount-deals-workflow-rule.php';
	}//end load_rules()

	/**
	 * Calculate product discount.
	 *
	 * @param object $product Product.
	 * @return integer|void
	 */
	public static function calculate_product_discount( $product ) {

		$active_workflows = self::get_active_workflows();
        $discounts = array();

        //Get from settings
        $apply_as = 'all_matched';
        $cart_items = ( is_object( WC()->cart ) && is_callable( array( WC()->cart, 'get_cart' ) ) ) ? WC()->cart->get_cart() : array();

        //$active_workflows
        if ( empty( $active_workflows ) ) {
            return 0;
        }

        //echo "<pre>"; print_r(); echo "</pre>";

       /* && apply_filters('is_product_eligible_for_dd_exclusive_workflows', true, array(
                'product'  => $product,
                'active_workflows' => $active_workflows,
                'cart_items' => $cart_items
            )
        )*/
        if( ! empty( $active_workflows['exclusive'] ) ){
            $exclusive_apply_as = apply_filters('apply_dd_exclusive_rules_as', 'first_matched', array(
                'product'  => $product,
                'active_workflows' => $active_workflows,
                'cart_items' => $cart_items,
                'workflows_apply_as' => array(
                    'first_matched',
                    'last_matched',
                    'highest_discount',
                    'smallest_discount'
                )

            ) );
            $exclusive_workflows = $active_workflows['exclusive'];

        }else{
            $non_exclusive_workflows = $active_workflows['non_exclusive'];
        }

        $discounts[] = 0;
        if( !empty( $exclusive_workflows ) ){
            foreach ( $exclusive_workflows as $workflow ) {
                $discounts[] = $workflow->may_have_product_discount( $product, array_sum( $discounts ) );
            }
        }
        if( 0 == array_sum($discounts) || empty( $discounts )){
            foreach ( $non_exclusive_workflows as $workflow ) {
                $discounts[] = $workflow->may_have_product_discount( $product, array_sum( $discounts ) );
            }
        }


	}//end calculate_product_discount()


}//end class
