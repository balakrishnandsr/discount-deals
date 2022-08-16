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
		$this->set_title( __( 'Simple Discount', 'discount-deals' ) );
		$this->set_description( __( 'Give flat or percentage discount for products you are selling.', 'discount-deals' ) );
	}

	/**
	 * Set valid data items type of the discount
	 */
	public function set_supplied_data_items() {
		$this->supplied_data_items = array( 'customer', 'cart', 'shop', 'product' );
	}

	/**
	 * Calculate discount for the product
	 *
	 * @param mixed $data_item Calculate discount for which data item.
	 * @param float $subsequent_price Subsequent price.
	 *
	 * @return integer
	 */
	public function calculate_discount( $data_item, $subsequent_price ) {

		// Subsequent discount.
		$discount_array = array(
			'type'         => 'percentage',
			'value'        => 10,
			'max_discount' => 10,
		);
		$calculate_subsequent_price = 'no';
		$calculate_discount_from = 'regular_price';
		if ( 'regular_price' == $calculate_discount_from ) {
			$price = ( is_object( $data_item ) && is_callable( array( $data_item, 'get_regular_price' ) ) ) ? $data_item->get_regular_price() : 0;
		} else {
			$price = ( is_object( $data_item ) && is_callable( array( $data_item, 'get_price' ) ) ) ? $data_item->get_price() : 0;
		}
		if ( 'yes' == $calculate_subsequent_price ) {
			$price = $price - $subsequent_price;
		}
		$type = 'fixed_price';
		$max_discount = 10;
		$discount_value = 10;
		switch ( $type ) {
			case 'fixed_price':
				$discount = min( $price, $discount_value );
				break;
			case 'percentage':
				$discount_value = $price * ( $discount_value / 100 );
				$discount = $price - $discount_value;
				break;
			default:
			case 'flat':
				$discount = $price - $discount_value;
				break;
		}
		if ( ! empty( $max_discount ) ) {
			$discount = min( $max_discount, $discount );
		}
		return $discount;
	}//end calculate_discount()

}//end class
