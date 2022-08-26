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
class Discount_Deals_Workflow_Cart_Discount extends Discount_Deals_Workflow_Discount {

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

	public function load_fields() {
		$discount_details = $this->get_discount_details();
		ob_start();
		discount_deals_radio(
			array(
				'wrapper_class' => 'discount-options-field-container',
				'id'            => 'discount_deals_workflow_discount_type',
				'name'          => 'discount_deals_workflow[dd_discounts][type]',
				'value'         => discount_deals_get_value_from_array( $discount_details, 'type', 'flat' ),
				'label'         => __( 'What type of discount do you want to give?', 'discount-deals' ),
				'options'       => array(
					'flat'    => __( 'Flat', 'discount-deals' ),
					'percent' => __( 'Percentage', 'discount-deals' ),
				),
				'required'      => true,
			)
		);
		discount_deals_text_input(
			array(
				'wrapper_class' => 'discount-options-field-container',
				'id'            => 'discount_deals_workflow_type_discount_value',
				'name'          => 'discount_deals_workflow[dd_discounts][value]',
				'value'         => discount_deals_get_value_from_array( $discount_details, 'value', '' ),
				'label'         => __( 'How much discount do you want to give?', 'discount-deals' ),
				'type'          => 'number',
				'placeholder'   => __( 'Enter the discount value here...', 'discount-deals' ),
				'required'      => true,
				'description'   => __( 'NOTE: If your discount type is percentage, then please enter the value less then or equal to 100.', 'discount-deals' ),
			)
		);
		discount_deals_text_input(
			array(
				'wrapper_class' => 'discount-options-field-container',
				'id'            => 'discount_deals_workflow_type_discount_max_value',
				'name'          => 'discount_deals_workflow[dd_discounts][max_discount]',
				'value'         => discount_deals_get_value_from_array( $discount_details, 'max_discount', '' ),
				'label'         => __( 'Maximum discount value for this workflow?', 'discount-deals' ),
				'type'          => 'number',
				'placeholder'   => __( 'Enter the max discount value here...', 'discount-deals' ),
				'data_type'     => 'price',
				'description'   => __( 'If the calculated discount value exceeds the limit then, the max value will be given as a discount. NOTE: Please leave empty if you don\'t want to limit the discount.', 'discount-deals' ),
			)
		);

		return ob_get_clean();
	}

	/**
	 * Calculate discount for the product
	 *
	 * @param mixed $cart_items Calculate discount for which data item.
	 *
	 * @return integer
	 */
	public function calculate_discount( $cart_items , $price = 0) {

        $discount_details = $this->get_discount_details();
        $discount_by      = discount_deals_get_value_from_array( $discount_details, 'discount_by', 'amount' );
        $apply_as         = discount_deals_get_value_from_array( $discount_details, 'apply_as', 'coupon' );
        $type             = discount_deals_get_value_from_array( $discount_details, 'type', 'percentage' );
        $max_discount     = floatval( discount_deals_get_value_from_array( $discount_details, 'max_discount', 0 ) );
        $discount_value   = floatval( discount_deals_get_value_from_array( $discount_details, 'value', 0 ) );

        if ( empty($discount_by) || empty($apply_as) ) {
            return 0;
        }

        if('amount' === $discount_by){
            foreach ($cart_items as $cart_item){
                $product = !empty( $cart_item['data'] ) ? $cart_item['data'] : null;
                $product = !empty( $cart_item['data'] ) ? $cart_item['data'] : null;
                echo "<pre>";
                print_r($cart_item);
                echo "</pre>";
            }
            //$discount = $this->calculate_discount_amount($type, $price, $discount_value);
            if ( ! empty( $max_discount ) ) {
                //$discount = min( $max_discount, $discount );
            }
        }



        return ;
	}//end calculate_discount()



}//end class