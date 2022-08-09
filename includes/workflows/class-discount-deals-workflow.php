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
	 * Workflow meta data
	 *
	 * @var array
	 */
	public $meta;

	/**
	 * Workflow priority
	 *
	 * @var integer
	 */
	public $priority = 0;

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
	 * @var Discount_Deals_Workflow_Discount[]
	 */
	private $discounts;

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
			$this->set_id( $workflow->id );
			$this->set_title( $workflow->title );
			$this->set_type( $workflow->type );
			$this->set_rules( maybe_unserialize( (string) $workflow->rules ) );
			$this->set_discounts( maybe_unserialize( (string) $workflow->discounts ) );
			$this->set_meta( maybe_unserialize( (string) $workflow->meta ) );
			$this->set_status( $workflow->status );
			$this->set_priority( $workflow->priority );
			$this->set_created_at( $workflow->created_at );
			$this->set_updated_at( $workflow->updated_at );
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
		$workflow = ES()->workflows_db->get_workflow( $workflow_id, 'object' );
		if ( ! $workflow ) {
			return false;
		}

		return new Discount_Deals_Workflow( $workflow );
	}//end get_instance()


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
		$this->rules = $rules;
	}//end set_rules()


	/**
	 * Get workflow priority
	 *
	 * @return integer
	 */
	public function get_priority() {
		return $this->priority;
	}//end get_priority()


	/**
	 * Set workflow priority
	 *
	 * @param integer $priority Priority.
	 *
	 * @return void
	 */
	public function set_priority( $priority = 0 ) {
		$this->priority = $priority;
	}//end set_priority()


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
	 * Returns the saved actions with their data
	 *
	 * @param float $number Number.
	 *
	 * @return Discount_Deals_Workflow_Discount|false
	 */
	public function get_discount( $number = 0 ) {

		$discounts = $this->get_discounts();

		if ( ! isset( $discounts[ $number ] ) ) {
			return false;
		}

		return $discounts[ $number ];
	}//end get_discount()


	/**
	 * Get all actions in current workflow.
	 *
	 * @return Discount_Deals_Workflow_Discount[]
	 */
	public function get_discounts() {
		return array();
	}//end get_discounts()


	/**
	 * Set discounts
	 *
	 * @param Discount_Deals_Workflow_Discount[] $discounts Discounts.
	 *
	 * @return void
	 */
	public function set_discounts( $discounts = array() ) {
		$this->discounts = $discounts;
	}//end set_discounts()


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

		// No rules found.
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

		$rule_name    = isset( $rule['name'] ) ? $rule['name'] : false;
		$rule_compare = isset( $rule['compare'] ) ? $rule['compare'] : false;
		$rule_value   = isset( $rule['value'] ) ? $rule['value'] : false;

		// It's ok for compare to be false for boolean type rules.
		if ( ! $rule_name ) {
			return true;
		}

		return false;
	}//end validate_rule()


	/**
	 * Retrieve and validate a data item
	 *
	 * @param string $name Name.
	 *
	 * @return mixed
	 */
	public function get_data_item( $name = '' ) {
		return $this->data_layer()->get_item( $name );
	}//end get_data_item()


	/**
	 * Get workflow data layer object.
	 *
	 * @return Discount_Deals_Workflow_Data_Layer
	 */
	public function data_layer() {
		if ( ! isset( $this->data_layer ) ) {
			$this->data_layer = new Discount_Deals_Workflow_Data_Layer();
		}

		return $this->data_layer;
	}//end data_layer()


	/**
	 * Set data item in workflow data layer.
	 *
	 * @param string $name Name.
	 * @param array  $item Item.
	 * @return void
	 */
	public function set_data_item( $name = '', $item = array() ) {
		$this->data_layer()->set_item( $name, $item );
	}//end set_data_item()


	/**
	 * Get workflow meta data from meta key.
	 *
	 * @param array $key Key.
	 *
	 * @return mixed
	 */
	public function get_meta( $key = array() ) {
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
	 * Check if workflow has given action or not.
	 *
	 * @param string $discount_name Action name.
	 *
	 * @return boolean Whether workflow has given action or not.
	 */
	public function has_discount( $discount_name = '' ) {
		$has_action = false;
		$discounts  = $this->get_discounts();

		if ( ! empty( $discounts ) ) {
			foreach ( $discounts as $discount ) {
				$current_action_name = $discount->get_name();
				if ( $current_action_name === $discount_name ) {
					$has_action = true;
					break;
				}
			}
		}

		return $has_action;
	}//end has_discount()


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
	 * @return void
	 */
	public function set_id( $id = 0 ) {
		$this->id = $id;
	}//end set_id()


}//end class
