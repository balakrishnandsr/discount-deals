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
		$this->set_description( __( 'Give dynamic discounts on products when customers buy the products in large quantities.', 'discount-deals' ) );
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
				'label'         => __( 'Configure the discount you want to give to your customers', 'discount-deals' ),
				'html'          => $discount_details_html,
				'required'      => true,
			)
		);

		return ob_get_clean();
	}//end load_fields()

	/**
	 * Load promotional message fields
	 *
	 * @return string
	 */
	public function load_promotion_fields() {
		$discount_details = $this->get_promotion_details();
		ob_start();
		discount_deals_radio(
			array(
				'wrapper_class' => 'discount-options-field-container',
				'id'            => 'discount_deals_workflow_toggle_promotion',
				'name'          => 'discount_deals_workflow[dd_promotion][enable]',
				'value'         => discount_deals_get_value_from_array( $discount_details, 'enable', 'no' ),
				'label'         => __( 'Would you like to display promotional message in the storefront?', 'discount-deals' ),
				'options'       => array(
					'yes' => __( 'Yes', 'discount-deals' ),
					'no'  => __( 'No', 'discount-deals' ),
				),
				'required'      => true,
			)
		);
		discount_deals_select(
			array(
				'id'       => 'discount_deals_workflow_promotion_when',
				'name'     => 'discount_deals_workflow[dd_promotion][when_to_show]',
				'value'    => discount_deals_get_value_from_array( $discount_details, 'when_to_show', 'all_time' ),
				'label'    => __( 'When to show this promotional message?', 'discount-deals' ),
				'options'  => array(
					'before_rule' => __( 'Before all workflow rules are passed', 'discount-deals' ),
					'after_rule'  => __( 'After all workflow rules are passed', 'discount-deals' ),
					'all_time'    => __( 'All time (Not checked against all rules)', 'discount-deals' ),
				),
				'required' => true,
			)
		);

		discount_deals_select(
			array(
				'id'       => 'discount_deals_workflow_promotion_where',
				'name'     => 'discount_deals_workflow[dd_promotion][where_to_show]',
				'value'    => discount_deals_get_value_from_array( $discount_details, 'where_to_show', 'all_time' ),
				'label'    => __( 'Where to show this promotional message?', 'discount-deals' ),
				'options'  => array(
					'before_add_to_cart_button'    => __( 'Before "Add to cart" form', 'discount-deals' ),
					'after_add_to_cart_button'     => __( 'After "Add to cart" form', 'discount-deals' ),
					'after_single_product_summary' => __( 'Before product additional information', 'discount-deals' ),
				),
				'required' => true,
			)
		);

		discount_deals_radio(
			array(
				'wrapper_class' => 'discount-options-field-container',
				'id'            => 'discount_deals_workflow_toggle_show_bulk_table',
				'name'          => 'discount_deals_workflow[dd_promotion][show_bulk_table]',
				'value'         => discount_deals_get_value_from_array( $discount_details, 'show_bulk_table', 'yes' ),
				'label'         => __( 'Do you want to show bulk table in product detail page?', 'discount-deals' ),
				'options'       => array(
					'yes' => __( 'Yes', 'discount-deals' ),
					'no'  => __( 'No', 'discount-deals' ),
				),
				'required'      => true,
			)
		);

		discount_deals_editor(
			array(
				'id'       => 'discount_deals_workflow_promotion_message',
				'name'     => 'discount_deals_workflow[dd_promotion][message]',
				'value'    => discount_deals_get_value_from_array( $discount_details, 'message', '<p><b>Special Price</b> Purchase above 500$ and get extra 5% off. </p>', false ),
				'label'    => __( 'Enter the promotional message that will be displayed to the customer', 'discount-deals' ),
				'required' => true,
			)
		);

		return ob_get_clean();
	}//end load_promotion_fields()


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
