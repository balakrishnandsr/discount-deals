<?php
/**
 * This class defines all code necessary to workflow.
 *
 * @package Discount_Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class object of each workflow
 */
class Discount_Deals_Workflow {
	/**
	 * Workflow id
	 *
	 * @var integer
	 */
	public $id;

	/**
	 * Workflow title
	 *
	 * @var string
	 */
	public $title;

	/**
	 * Workflow status
	 *
	 * @var integer
	 */
	public $status = 0;

	/**
	 * Workflow type name
	 *
	 * @var string
	 */
	public $type;

	/**
	 * Workflow rules
	 *
	 * @var array
	 */
	public $rules;

	/**
	 * Workflow rules with objects
	 *
	 * @var array
	 */
	public $rules_object;

	/**
	 * Workflow meta data
	 *
	 * @var array
	 */
	public $meta;

	/**
	 * Promotion settings to shown in frontend
	 *
	 * @var array
	 */
	public $promotion;

	/**
	 * Index products that has discounts
	 *
	 * @var array
	 */
	public $index;

	/**
	 * Workflow priority
	 *
	 * @var integer
	 */
	public $exclusive = 0;

	/**
	 * Workflow creation date/time
	 *
	 * @var string
	 */
	public $created_at;

	/**
	 * Workflow last update date/time
	 *
	 * @var string
	 */
	public $updated_at;

	/**
	 * Workflow discounts
	 *
	 * @var Discount_Deals_Workflow_Discount | false
	 */
	private $discount = false;

	/**
	 * Workflow data abstract class object
	 *
	 * @var Discount_Deals_Workflow_Data_Layer
	 */
	private $data_layer;

	/**
	 * Class constructor
	 *
	 * @param mixed $workflow Default null.
	 */
	public function __construct( $workflow = null ) {

		if ( is_numeric( $workflow ) ) {
			$workflow = self::get_instance( $workflow );
		}

		if ( is_object( $workflow ) ) {
			$this->set_id( $workflow->dd_id );
			$this->set_title( $workflow->dd_title );
			$this->set_type( $workflow->dd_type );
			$this->set_promotion( maybe_unserialize( (string) $workflow->dd_promotion ) );
			$this->set_rules( maybe_unserialize( (string) $workflow->dd_rules ) );
			$this->set_discount( maybe_unserialize( (string) $workflow->dd_discounts ) );
			$this->set_meta( maybe_unserialize( (string) $workflow->dd_meta ) );
			$this->set_index( maybe_unserialize( (string) $workflow->dd_index ) );
			$this->set_status( $workflow->dd_status );
			$this->set_exclusive( $workflow->dd_exclusive );
			$this->set_created_at( $workflow->dd_created_at );
			$this->set_updated_at( $workflow->dd_updated_at );
			$this->set_data_layer();
		}
	}//end __construct()


	/**
	 * Retrieve Discount_Deals_Workflow instance.
	 *
	 * @param integer $workflow_id Workflow ID.
	 *
	 * @return Discount_Deals_Workflow|false Workflow object, false otherwise.
	 */
	public static function get_instance( $workflow_id = 0 ) {

		$workflow_id = intval( $workflow_id );
		if ( ! $workflow_id ) {
			return false;
		}
		$workflow_db = new Discount_Deals_Workflow_DB();
		$workflow    = $workflow_db->get_workflow_by_id( $workflow_id );

		if ( ! $workflow ) {
			return false;
		}

		return new Discount_Deals_Workflow( $workflow );
	}//end get_instance()

	/**
	 * Get products having discount
	 *
	 * @return array
	 */
	public function get_index() {
		return $this->index;
	}//end get_index()


	/**
	 * Create index for products having discount
	 *
	 * @param array $index Index details.
	 *
	 * @return void
	 */
	public function set_index( $index ) {
		$this->index = $index;
	}//end set_index()


	/**
	 * Get promotion details
	 *
	 * @return array
	 */
	public function get_promotion() {
		return $this->promotion;
	}//end get_promotion()


	/**
	 * Set promotion details
	 *
	 * @param array $promotion Promotion details.
	 *
	 * @return void
	 */
	public function set_promotion( $promotion ) {
		$this->promotion = $promotion;
	}//end set_promotion()


	/**
	 * Get workflow title
	 *
	 * @return string
	 */
	public function get_title() {
		return $this->title;
	}//end get_title()


	/**
	 * Set workflow title
	 *
	 * @param string $title Title.
	 *
	 * @return void
	 */
	public function set_title( $title = '' ) {
		$this->title = $title;
	}//end set_title()


	/**
	 * Get creation date/time of workflow.
	 *
	 * @return string
	 */
	public function get_date_created() {
		return $this->created_at;
	}//end get_date_created()


	/**
	 * Get workflow updated date
	 *
	 * @return string
	 */
	public function get_updated_at() {
		return $this->updated_at;
	}//end get_updated_at()


	/**
	 * Set workflow updated date
	 *
	 * @param string $updated_at Updated at.
	 *
	 * @return void
	 */
	public function set_updated_at( $updated_at = '' ) {
		$this->updated_at = $updated_at;
	}//end set_updated_at()


	/**
	 * Get workflow data layer
	 *
	 * @return Discount_Deals_Workflow_Data_Layer
	 */
	public function get_data_layer() {
		return $this->data_layer;
	}//end get_data_layer()


	/**
	 * Set workflow data layer.
	 *
	 * @param array|Discount_Deals_Workflow_Data_Layer $data_layer          Data layer.
	 * @param boolean                                  $reset_workflow_data Reset workflow data.
	 *
	 * @return void
	 */
	public function set_data_layer( $data_layer = null, $reset_workflow_data = false ) {

		if ( ! is_a( $data_layer, 'Discount_Deals_Workflow_Data_Layer' ) ) {
			$data_layer = new Discount_Deals_Workflow_Data_Layer( $data_layer );
		}

		if ( $reset_workflow_data ) {
			$this->reset_data();
		}

		$this->data_layer = $data_layer;
	}//end set_data_layer()


	/**
	 * Reset the workflow object
	 * Clears any data that is related to the last run
	 * The trigger and actions don't need to be reset because their data flows from the workflow options not the workflow data layer
	 *
	 * @return void
	 */
	public function reset_data() {

	}//end reset_data()


	/**
	 * Get workflow rules
	 *
	 * @return array
	 */
	public function get_rules() {
		return $this->rules;
	}//end get_rules()

	/**
	 * Set workflow rules
	 *
	 * @param array $rules Rules.
	 *
	 * @return void
	 */
	public function set_rules( $rules = array() ) {
		$rule_options = array();
		if ( is_array( $rules ) ) {
			foreach ( $rules as $group_key => $rule_group ) {
				foreach ( $rule_group as $rule ) {
					$rule_object                  = Discount_Deals_Workflows::get_rule_type( $rule['name'] );
					$rule_options[ $group_key ][] = $rule_object;
				}
			}
			$this->rules_object = $rule_options;
			$this->rules        = $rules;
		}
	}//end set_rules()

	/**
	 * Get workflow rules
	 *
	 * @return array
	 */
	public function get_rules_object() {
		return $this->rules_object;
	}//end get_rules_object()

	/**
	 * Get workflow priority
	 *
	 * @return integer
	 */
	public function get_exclusive() {
		return $this->exclusive;
	}//end get_exclusive()


	/**
	 * Set workflow priority
	 *
	 * @param string $exclusive Priority.
	 *
	 * @return void
	 */
	public function set_exclusive( $exclusive = 0 ) {
		$this->exclusive = ( 1 == $exclusive );
	}//end set_exclusive()


	/**
	 * Get workflow created date / time
	 *
	 * @return string
	 */
	public function get_created_at() {
		return $this->created_at;
	}//end get_created_at()


	/**
	 * Set workflow created date / time
	 *
	 * @param string $created_at Created At.
	 *
	 * @return void
	 */
	public function set_created_at( $created_at = '' ) {
		$this->created_at = $created_at;
	}//end set_created_at()

	/**
	 * Validate workflow based on received data from the workflow trigger object.
	 *
	 * @return boolean
	 */
	public function validate_workflow() {
		return true;
	}//end validate_workflow()

	/**
	 * Is workflow active.
	 *
	 * @return boolean
	 */
	public function is_active() {
		return $this->get_status() === 1;
	}//end is_active()

	/**
	 * Get workflow status.
	 *
	 * Possible statuses are active|inactive|trash
	 *
	 * @return integer
	 */
	public function get_status() {
		return $this->status;
	}//end get_status()

	/**
	 * Set the status of the workflow
	 *
	 * @param integer $status Status.
	 *
	 * @return void
	 */
	public function set_status( $status = 0 ) {
		$this->status = $status;
	}//end set_status()

	/**
	 * Get the name of the workflow's trigger.
	 *
	 * @return string
	 */
	public function get_type() {
		return sanitize_text_field( $this->type );
	}//end get_type()

	/**
	 * Set the workflow type
	 *
	 * @param string $type Type.
	 *
	 * @return void
	 */
	public function set_type( $type = '' ) {
		$this->type = $type;
	}//end set_type()

	/**
	 * Validate rules against user input
	 *
	 * @return boolean
	 */
	public function validate_rules() {
		$rules = self::get_rule_data();
		if ( empty( $rules ) ) {
			return true;
		}
		foreach ( $rules as $rule_group ) {
			$is_group_valid = true;
			foreach ( $rule_group as $rule ) {
				// Rules have AND relationship so all must return true.
				if ( ! $this->validate_rule( $rule ) ) {
					$is_group_valid = false;
					break;
				}
			}
			// Groups have an OR relationship so if one is valid we can break the loop and return true.
			if ( $is_group_valid ) {
				return true;
			}
		}

		// No groups were valid.
		return false;
	}//end validate_rules()

	/**
	 * Get rule data
	 *
	 * @return array
	 */
	public function get_rule_data() {
		return is_array( $this->rules ) ? $this->rules : array();
	}//end get_rule_data()

	/**
	 * Returns true if rule is missing data so that the rule is skipped
	 *
	 * @param array $rule Rule.
	 *
	 * @return boolean
	 */
	public function validate_rule( $rule = array() ) {
		if ( ! is_array( $rule ) ) {
			return true;
		}
		$rule_name    = discount_deals_get_value_from_array( $rule, 'name', false );
		$rule_compare = discount_deals_get_value_from_array( $rule, 'compare', false );
		$rule_value   = discount_deals_get_value_from_array( $rule, 'value', false );
		if ( ! $rule_name ) {
			return true;
		}
		$rule_object = Discount_Deals_Workflows::get_rule_type( $rule_name );
		if ( ! $rule_object ) {
			return false;
		}
		$data_item = $this->data_layer()->get_item( $rule_object->data_item );
		if ( ! $data_item ) {
			return false;
		}
		$rule_object->set_workflow( $this );
		try {
			$rule_object->validate_value( $rule_value );
		} catch ( Exception $e ) {
			// Always return false if the rule value is invalid.
			return false;
		}

		return $rule_object->validate( $data_item, $rule_compare, $rule_value );
	}//end validate_rule()

	/**
	 * Get workflow data layer object.
	 *
	 * @return Discount_Deals_Workflow_Data_Layer
	 */
	public function data_layer() {
		return $this->data_layer;
	}//end data_layer()

	/**
	 * Retrieve and validate a data item
	 *
	 * @param string $name Name.
	 *
	 * @return boolean
	 */
	public function get_data_item( $name = '' ) {
		return $this->data_layer()->get_item( $name );
	}//end get_data_item()

	/**
	 * Get workflow meta data from meta key.
	 *
	 * @param string $key Key.
	 *
	 * @return mixed
	 */
	public function get_meta( $key ) {
		return isset( $this->meta[ $key ] ) ? $this->meta[ $key ] : '';
	}//end get_meta()

	/**
	 * Set workflow meta
	 *
	 * @param array $meta Meta.
	 *
	 * @return void
	 */
	public function set_meta( $meta = array() ) {
		$this->meta = $meta;
	}//end set_meta()

	/**
	 * Method to get edit url of a workflow
	 *
	 * @return string  $edit_url Workflow edit URL
	 */
	public function get_edit_url() {
		$id       = $this->get_id();
		$edit_url = admin_url( 'admin.php?page=discount-deals-workflows' );

		return add_query_arg(
			array(
				'id'     => $id,
				'action' => 'edit',
			),
			$edit_url
		);
	}//end get_edit_url()

	/**
	 * Get workflow id
	 *
	 * @return integer
	 */
	public function get_id() {
		return $this->id;
	}//end get_id()

	/**
	 * Set If of the workflow
	 *
	 * @param integer $id Int.
	 *
	 * @return void
	 */
	public function set_id( $id = 0 ) {
		$this->id = $id;
	}//end set_id()

	/**
	 * May have product discount.
	 *
	 * @param object  $product  Product.
	 * @param float   $price    Price used for when enable subsequent.
	 * @param integer $quantity Product quantity.
	 *
	 * @return integer
	 */
	public function may_have_product_discount( $product, $price, $quantity ) {
		$discount = $this->get_discount();
		if ( is_a( $discount, 'Discount_Deals_Workflow_Discount' ) ) {
			return $discount->calculate_discount( $product, $price, array( 'quantity' => $quantity ) );
		}

		return 0;
	}//end may_have_product_discount()


	/**
	 * Get all actions in current workflow.
	 *
	 * @return Discount_Deals_Workflow_Discount | false
	 */
	public function get_discount() {
		return $this->discount;
	}//end get_discount()

	/**
	 * Set discounts
	 *
	 * @param array $discounts Discounts.
	 *
	 * @return void
	 */
	public function set_discount( $discounts = array() ) {
		$discount_type   = $this->get_type();
		$discount_object = Discount_Deals_Workflows::get_discount_type( $discount_type );
		$discount_object->set_discount_details( $discounts );
		$discount_object->set_promotion_details( $this->get_promotion() );
		$this->discount = $discount_object;
	}//end set_discount()


	/**
	 * May have BOGO discount.
	 *
	 * @param WC_Product $product  Product.
	 * @param float      $price    Product price.
	 * @param integer    $quantity Product quantity.
	 *
	 * @return array
	 */
	public function may_have_bogo_discount( $product, $price, $quantity ) {
		$discount = $this->get_discount();
		if ( is_a( $discount, 'Discount_Deals_Workflow_Discount' ) ) {
			return $discount->calculate_discount( $product, $price, array( 'quantity' => $quantity ) );
		}

		return array();
	}//end may_have_bogo_discount()

	/**
	 * Check cart has discount.
	 *
	 * @param WC_Cart $cart     Actual cart object.
	 * @param float   $subtotal Calculated cart subtotal.
	 *
	 * @return string | number
	 */
	public function may_have_cart_discount( $cart, $subtotal ) {
		$discount = $this->get_discount();
		if ( is_a( $discount, 'Discount_Deals_Workflow_Discount' ) ) {
			return $discount->calculate_discount( $cart, $subtotal );
		}

		return 0;
	}//end may_have_cart_discount()

	/**
	 * Check that the product in the index or not
	 *
	 * @param string $validate_for Product object.
	 *
	 * @return boolean
	 */
	public function validate_index( $validate_for = 'product' ) {
		$data_item = $this->data_layer()->get_item( $validate_for );
		if ( ! $data_item ) {
			return false;
		}
		if ( 'product' == $validate_for ) {

			/*
			 * Variable declaration
			 *
			 * @var WC_Product $data_item product object
			 */

			$index = $this->get_index();
			if ( empty( $index ) || ! is_array( $index ) ) {
				return true;
			}
			foreach ( $index as $rule_group ) {
				$is_group_valid = true;
				foreach ( $rule_group as $rule ) {
					// Rules have AND relationship so all must return true.
					if ( ! $this->validate_rule( $rule ) ) {
						$is_group_valid = false;
						break;
					}
				}
				// Groups have an OR relationship so if one is valid we can break the loop and return true.
				if ( $is_group_valid ) {
					return true;
				}
			}

			// No groups were valid.
			return false;
		}

		return false;
	}//end validate_index()


	/**
	 * Get the promotional to show on product message
	 *
	 * @param string $position Position to show the promotional message.
	 *
	 * @return array|null
	 */
	public function get_promotional_message( $position ) {
		$promotion_details = $this->get_promotion();
		if ( empty( $promotion_details ) || ! is_array( $promotion_details ) ) {
			return null;
		}

		if ( 'no' == discount_deals_get_value_from_array( $promotion_details, 'enable', 'no' ) ) {
			return null;
		}

		if ( discount_deals_get_value_from_array( $promotion_details, 'where_to_show', '' ) != $position ) {
			return null;
		}
		$promotion = array(
			'bulk_promotion' => array(),
			'promotion_message' => null,
		);
		if ( 'bulk_discount' == $this->get_type() ) {
			if ( 'yes' == discount_deals_get_value_from_array( $promotion_details, 'show_bulk_table', 'yes' ) ) {
				$promotion['bulk_promotion'] = $this->get_discount()->get_discount_details();
			}
		}
		if ( 'bxgx_discount' == $this->get_type() ) {
			$promotion['bxgx_promotion'] = $this->get_discount()->get_discount_details();
		}
		if ( 'bxgy_discount' == $this->get_type() ) {
			$promotion['bxgy_promotion'] = $this->get_discount()->get_discount_details();
		}

		$promotion_message = discount_deals_get_value_from_array( $promotion_details, 'message', null, false );
		if ( empty( $promotion_message ) ) {
			return $promotion;
		}

		$promotion['promotion_message'] = $promotion_message;

		return $promotion;
	}//end get_promotional_message()


	/**
	 * Get when to show the promotional message
	 *
	 * @return string
	 */
	public function get_when_to_show_promotional_message() {
		$promotion_details = $this->get_promotion();

		return discount_deals_get_value_from_array( $promotion_details, 'when_to_show', 'all_time' );
	}//end get_when_to_show_promotional_message()


}//end class
