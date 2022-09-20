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
 * Class to handle buy x get x discount
 */
class Discount_Deals_Workflow_Bxgx_Discount extends Discount_Deals_Workflow_Discount {

	/**
	 * Class constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->set_supplied_data_items();
		$this->set_title( __( 'Buy X and Get X discount', 'discount-deals' ) );
		$this->set_description( __( 'If the customer buys product X, then give some quantities as discounts on the same product.', 'discount-deals' ) );
	}//end __construct()

	/**
	 * Set valid data items type of the discount
	 *
	 * @return void
	 */
	public function set_supplied_data_items() {
		$this->supplied_data_items = array( 'customer', 'cart', 'shop', 'product' );
	}//end set_supplied_data_items()


	/**
	 * Load fields to get discount details.
	 *
	 * @return string
	 */
	public function load_fields() {
		$discount_details = $this->get_discount_details();
		ob_start();
		require_once DISCOUNT_DEALS_ABSPATH . '/admin/partials/discounts/discount-deals-bxgx-discount.php';
		$discount_details_html = ob_get_clean();

		ob_start();
		discount_deals_html(
			array(
				'wrapper_class' => 'discount-options-field-container',
				'id'            => 'discount_deals_workflow_discount_type',
				'label'         => __( 'Configure the discount you want to give to your customers', 'discount-deals' ),
				'html'          => $discount_details_html,
				'required'      => true,
			)
		);

		return ob_get_clean();
	}//end load_fields()

	/**
	 * Calculate discount for the product
	 *
	 * @param WC_Product $data_item Calculate discount for which data item.
	 * @param float      $price     Calculate discount subsequently.
	 * @param array      $extra     Extra details for calculate discount.
	 *
	 * @return array
	 */
	public function calculate_discount( $data_item, $price, $extra = array() ) {
		$discount_details = $this->get_discount_details();
		if ( empty( $discount_details ) ) {
			return array();
		}
		$product_quantity = ! empty( $extra['quantity'] ) ? intval( $extra['quantity'] ) : 1;
		if ( $product_quantity <= 0 ) {
			$product_quantity = 1;
		}
		foreach ( $discount_details as $discount_detail ) {
			$free_quantity = discount_deals_get_value_from_array( $discount_detail, 'free_quantity', 1 );
			if ( 0 >= $free_quantity ) {
				continue;
			}
			$type         = discount_deals_get_value_from_array( $discount_detail, 'type', 'free' );
			$min_quantity = discount_deals_get_value_from_array( $discount_detail, 'min_quantity', 0 );
			$max_quantity = discount_deals_get_value_from_array( $discount_detail, 'max_quantity', 999999999 );
			$max_discount = discount_deals_get_value_from_array( $discount_detail, 'max_discount', 0 );
			$value        = discount_deals_get_value_from_array( $discount_detail, 'value', 0 );
			if ( ! empty( $type ) && $product_quantity >= $min_quantity && $product_quantity <= $max_quantity ) {
				$discount       = $this->calculate_discount_amount( $type, $price, $value );
				$total_discount = $free_quantity * $discount;
				if ( 0 < floatval( $max_discount ) && 'percent' == $type ) {
					$total_discount = min( $max_discount, $total_discount );
					$discount = $total_discount / $free_quantity;
				}
				if ( 0 >= $discount ) {
					return array();
				}

				return array(
					'discount_quantity' => $free_quantity,
					'discount'          => $discount,
					'total'             => $total_discount,
					'is_free'           => 'free' == $type,
					'discount_on_same'  => true
				);
			}
		}

		return array();
	}//end calculate_discount()

}//end class
