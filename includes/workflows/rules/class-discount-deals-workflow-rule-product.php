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

	public $data_item = 'product';


	function init() {
		parent::init();

		$this->title         = __( 'Product - Product', 'discount-deals' );
		$this->compare_types = $this->get_includes_or_not_compare_types();
	}


	/**
	 * @param \WC_Product|\WC_Product_Variation $product
	 * @param $compare
	 * @param $expected
	 *
	 * @return bool
	 */
	function validate( $product, $compare, $expected ) {
		$expected_product = wc_get_product( absint( $expected ) );

		if ( ! $expected_product ) {
			return false;
		}

		$match = false;

		switch ( $compare ) {
			case 'is':
				return $match;
			case 'is_not':
				return ! $match;
		}
	}

}
