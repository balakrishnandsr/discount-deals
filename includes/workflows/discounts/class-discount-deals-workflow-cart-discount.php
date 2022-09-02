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
		$this->set_title( __( 'Cart Discount', 'discount-deals' ) );
		$this->set_description( __( 'Give flat or percentage discount for Cart.', 'discount-deals' ) );
	}

	/**
	 * Set valid data items type of the discount
	 */
	public function set_supplied_data_items() {
		$this->supplied_data_items = array( 'customer', 'cart', 'shop' );
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
	 * @param float $cart_subtotal Cart subtotal.
	 * @param float $subsequent_subtotal Cart subtotal.
	 *
	 * @return array|string
     */
	public function calculate_discount( $cart_subtotal, $subsequent_subtotal) {

        $discount_details = $this->get_discount_details();
        $apply_as         = discount_deals_get_value_from_array( $discount_details, 'apply_as', 'coupon' );
        $type             = discount_deals_get_value_from_array( $discount_details, 'type', 'percent' );
        $max_discount     = floatval( discount_deals_get_value_from_array( $discount_details, 'max_discount', 0 ) );
        $value   = floatval( discount_deals_get_value_from_array( $discount_details, 'value', 0 ) );
        $subtotal_based_discount_values   = floatval( discount_deals_get_value_from_array( $discount_details, 'value', array() ) );

        $subtotal_based_discount_values = array(
            0 => array(
                'min_subtotal' => 10,
                'max_subtotal' => 50,
                'type'         => 'percent',
                'value'        =>  10
            ),
            1 => array(
                'min_subtotal' => 50,
                'max_subtotal' => 100,
                'type'         => 'flat',
                'value'        =>  10
            ),
            2 => array(
                'min_subtotal' => 100,
                'max_subtotal' => 500,
                'type'         => 'flat',
                'value'        =>  15
            ),
        );

        $discount = array();

        if ( empty($apply_as) ) {
            return array();
        }

        //Static value
        $discount_value = 10;
        $max_discount = 100;

        if('free_shipping' === $type){
            return 'discount_deals_free_shipping';
        }else{
            if( empty($subtotal_based_discount_value) ){
                $discount = $this->calculate_discount_amount( $type, $subsequent_subtotal, $discount_value );
            }else{
                foreach ($subtotal_based_discount_values as $subtotal_based_discount_value){
                    $discount = 0;
                    $min_subtotal = !empty( $subtotal_based_discount_value['min_subtotal'] ) ? $subtotal_based_discount_value['min_subtotal'] : 0;
                    $max_subtotal = !empty( $subtotal_based_discount_value['max_subtotal'] ) ? $subtotal_based_discount_value['max_subtotal'] : 0;
                    $type = !empty( $subtotal_based_discount_value['type'] ) ? $subtotal_based_discount_value['type'] : '';
                    $value = !empty( $subtotal_based_discount_value['value'] ) ? $subtotal_based_discount_value['value'] : 0;
                    if( !empty($type) && !empty($value) && $cart_subtotal >= $min_subtotal && $cart_subtotal <= $max_subtotal ){
                        $discount = $this->calculate_discount_amount( $type, $subsequent_subtotal, $value );
                    }
                }
            }
        }

        if ( ! empty( $max_discount ) && is_numeric($discount) ) {
            $discount = min( $max_discount,  $discount );
        }

        return $discount;

	}//end calculate_discount()



}//end class
