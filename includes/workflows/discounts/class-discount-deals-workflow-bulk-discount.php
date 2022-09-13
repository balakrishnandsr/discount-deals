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
class Discount_Deals_Workflow_Bulk_Discount extends Discount_Deals_Workflow_Discount {

	/**
	 * Class constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->set_supplied_data_items();
		$this->set_title( __( 'Product quantity based Discount', 'discount-deals' ) );
		$this->set_description( __( 'Purchase in bulk and get a discount in bulk.', 'discount-deals' ) );
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
		require_once DISCOUNT_DEALS_ABSPATH . '/admin/partials/discounts/discount-deals-bulk-discount.php';
		$discount_details_html = ob_get_clean();

		ob_start();
		discount_deals_html(
			array(
				'wrapper_class' => 'discount-options-field-container',
				'id'            => 'discount_deals_workflow_discount_type',
				'label'         => __( 'Discount details', 'discount-deals' ),
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
	 * @param float $price     Calculate discount subsequently.
	 * @param array $extra     Extra details for calculate discount.
	 *
	 * @return integer
	 */
	public function calculate_discount( $data_item, $price, $extra = array() ) {
		$discount_details = $this->get_discount_details();
		if ( empty( $discount_details ) ) {
			return 0;
		}
		$product_quantity = ! empty( $extra['quantity'] ) ? intval( $extra['quantity'] ) : 1;
		if ( $product_quantity <= 0 ) {
			$product_quantity = 1;
		}
		foreach ( $discount_details as $discount_detail ) {
			$type         = discount_deals_get_value_from_array( $discount_detail, 'type', 'flat' );
			$min_quantity = discount_deals_get_value_from_array( $discount_detail, 'min_quantity', 0 );
			$max_quantity = discount_deals_get_value_from_array( $discount_detail, 'max_quantity', 999999999 );
			$max_discount = discount_deals_get_value_from_array( $discount_detail, 'max_discount', 0 );
			$value        = discount_deals_get_value_from_array( $discount_detail, 'value', 0 );
			if ( ! empty( $type ) && ! empty( $value ) && $product_quantity >= $min_quantity && $product_quantity <= $max_quantity ) {
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
