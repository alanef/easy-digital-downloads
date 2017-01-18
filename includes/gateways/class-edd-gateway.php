<?php
/**
 * Payment Gateway Abstract Class
 *
 * @package     EDD
 * @subpackage  Gateways
 * @copyright   Copyright (c) 2017, Sunny Ratilal
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       2.7
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

abstract class EDD_Gateway {
	/**
	 * Gateway ID.
	 *
	 * @access public
	 * @since  2.7
	 * @var    string
	 */
	public $ID;

	/**
	 * Checkout Label.
	 *
	 * @access public
	 * @since  2.7
	 * @var    string
	 */
	public $checkout_label;

	/**
	 * Amdin Label.
	 *
	 * @access public
	 * @since  2.7
	 * @var    string
	 */
	public $admin_label;

	/**
	 * Purchase Data.
	 *
	 * @access public
	 * @since  2.7
	 * @var    array
	 */
	public $purchase_data;

	/**
	 * Customer ID.
	 *
	 * @access public
	 * @since  2.7
	 * @var    int
	 */
	public $customer_id;

	/**
	 * Customer object.
	 *
	 * @access public
	 * @since  2.7
	 * @var    object
	 */
	public $customer;

	/**
	 * Gateway Supports.
	 *
	 * @access public
	 * @since  2.7
	 * @var    array
	 */
	public $supports;

	/**
	 * Is test mode?
	 *
	 * @access public
	 * @since  2.7
	 * @var    bool
	 */
	public $test_mode;

	/**
	 * Empty constructor.
	 *
	 * @access public
	 * @since  2.7
	 *
	 * @return void
	 */
	public function __construct() {
		$this->fill_vars();
		$this->init();
	}

	/**
	 * Fill the object vars based on the ID set.
	 *
	 * @access private
	 * @since  2.7
	 *
	 * @return void
	 */
	private function fill_vars() {
		$gateways = edd_get_payment_gateways();

		$this->test_mode = edd_is_test_mode();

		if ( array_key_exists( $this->ID, $gateways ) ) {
			$this->checkout_label = $gateways[ $this->ID ]['checkout_label'];
			$this->admin_label = $gateways[ $this->ID ]['admin_label'];
			$this->supports = $gateways[ $this->ID ]['supports'];
		}
	}

	/**
	 * Used to initialise the gateway.
	 *
	 * @access public
	 * @since  2.7
	 * @abstract
	 *
	 * @return void
	 */
	public function init() {}

	/**
	 * Process the webhooks (e.g. PayPal IPN).
	 *
	 * @access public
	 * @since  2.7
	 * @abstract
	 *
	 * @return void
	 */
	public function process_webhooks() {}

	/**
	 * Load any scripts required by the gateway.
	 *
	 * @access public
	 * @since  2.7
	 * @abstract
	 *
	 * @return void
	 */
	public function scripts() {}

	/**
	 * Print the fields for the gateway.
	 *
	 * @access public
	 * @since  2.7
	 * @abstract
	 *
	 * @return void
	 */
	public function fields() {}

	/**
	 * Validate fields
	 *
	 * @access public
	 * @since  2.7
	 * @abstract
	 *
	 * @return void
	 */
	public function validate_fields() {}

	/**
	 * Check if a gateway supports a feature.
	 *
	 * @access public
	 * @since  2.7
	 *
	 * @param string $item Item to be checked.
	 * @return bool Whether the item is supported or not.
	 */
	public function supports( $item = '' ) {
		return in_array( $item, $this->supports );
	}

	/**
	 * Add an error that is displayed on the checkout.
	 *
	 * @access public
	 * @since  2.7
	 *
	 * @param string $code    Error code.
	 * @param string $message Error message.
	 * @return void
	 */
	public function add_error( $code = '', $message = '' ) {
		edd_set_error( $code, $message );
	}

	/**
	 * Process the purchase.
	 *
	 * @access public
	 * @since  2.7
	 *
	 * @param array $purchase_data Purchase data from session.
	 * @return void
	 */
	public function process_purchase( $purchase_data = array() ) {}
}