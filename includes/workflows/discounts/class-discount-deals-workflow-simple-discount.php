<?php
/**
 * This class defines all code necessary to workflow discount
 *
 * @package    Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class to handle simple product level discount
 */
class Discount_Deals_Workflow_Simple_Discount extends Discount_Deals_Workflow_Discount {

	/**
	 * Class constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->set_supplied_data_items();
		$this->set_title( __( 'Product Price based Discount', 'discount-deals' ) );
		$this->set_description( __( 'Give flat or percentage discount for products you are selling.', 'discount-deals' ) );
	}//end __construct()


	/**
	 * Set valid data items type of the discount
	 */
	public function set_supplied_data_items() {
		$this->supplied_data_items = array( 'customer', 'cart', 'shop', 'product' );
	}//end set_supplied_data_items()

	/**
	 * Load fields for discount settings
	 *
	 * @return false|string
	 */
	public function load_fields() {
		$discount_details = $this->get_discount_details();
		ob_start();
		require_once DISCOUNT_DEALS_ABSPATH . '/admin/partials/discounts/discount-deals-product-discount.php';
		$discount_details_html = ob_get_clean();

		ob_start();
		discount_deals_html(
			array(
				'wrapper_class' => 'discount-options-field-container',
				'id'            => 'discount_deals_workflow_discount_type',
				'value'         => discount_deals_get_value_from_array( $discount_details, 'type', 'flat' ),
				'label'         => __( 'How much discount do you want to give for product(s) ?', 'discount-deals' ),
				'html'          => $discount_details_html,
				'required'      => true,
			)
		);

		return ob_get_clean();
	}//end load_fields()


	/**
	 * Calculate discount for the product
	 *
	 * @param mixed $data_item Calculate discount for which data item.
	 * @param int|float $price Subsequent price.
	 *
	 * @return integer
	 */
	public function calculate_discount( $data_item, $price ) {
		$discount_details = $this->get_discount_details();
		if ( empty( $discount_details ) ) {
			return 0;
		}
		foreach ( $discount_details as $discount_detail ) {
			$type         = discount_deals_get_value_from_array( $discount_detail, 'type', 'flat' );
			$min_subtotal = discount_deals_get_value_from_array( $discount_detail, 'min_price', 0 );
			$max_subtotal = discount_deals_get_value_from_array( $discount_detail, 'max_price', 999999999 );
			$value        = discount_deals_get_value_from_array( $discount_detail, 'value', 0 );
			$max_discount = discount_deals_get_value_from_array( $discount_detail, 'max_discount', 0 );
			if ( ! empty( $type ) && ! empty( $value ) && $price >= $min_subtotal && $price <= $max_subtotal ) {
				$discount = $this->calculate_discount_amount( $type, $price, $value );
				if ( ! empty( $max_discount ) ) {
					$discount = min( $max_discount, $discount );
				}

				return $discount;
			}
		}

		return 0;
	}//end calculate_discount()

}//end class
