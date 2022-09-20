<?php
/**
 * Product rule
 *
 * @package     Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Discount_Deals_Workflow_Rule_Product
 */
class Discount_Deals_Workflow_Rule_Product extends Discount_Deals_Workflow_Rule_Product_Select_Abstract {

	/**
	 * What data item should pass in to validate the rule?
	 *
	 * @var string
	 */
	public $data_item = 'product';

	/**
	 * Init the rule
	 */
	public function init() {
		parent::init();

		$this->title         = __( 'Product - Product', 'discount-deals' );
		$this->compare_types = $this->get_includes_or_not_compare_types();
	}//end init()



	/**
	 * Validate the product rule
	 *
	 * @param WC_Product|WC_Product_Variation $data_item    Data item.
	 * @param string                          $compare_type Compare operator.
	 * @param array                           $value        List of values.
	 *
	 * @return boolean
	 */
	public function validate( $data_item, $compare_type, $value ) {
		if ( ! is_array( $value ) ) {
			return false;
		}
		$is_variation = $data_item->is_type( 'variation' );
		if ( $is_variation ) {
			$includes = ( in_array( $data_item->get_id(), $value ) || in_array( $data_item->get_parent_id(), $value ) );
		} else {
			$includes = in_array( $data_item->get_id(), $value );
		}
		switch ( $compare_type ) {
			default:
			case 'includes':
				return $includes;
			case 'not_includes':
				return ! $includes;
		}
	}//end validate()


}//end class

