<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines Download Expired email Class.
 *
 * @link       https://www.deviodigital.com
 * @since      1.0
 *
 * @package    Download_Renewals_for_WooCommerce
 * @subpackage Download_Renewals_for_WooCommerce/emails
 * @author     Devio Digital <contact@deviodigital.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WC_Email' ) ) {
	return; // Exit if WooCommerce isn't active.
}

/**
 * Class WC_Order_Driver_Assigned
 *
 * @since 1.1
 */
class WC_Order_Download_Expired extends WC_Email {
	/**
	 * Create an instance of the class.
	 *
	 * @access public
	 * @return void
	 */
	function __construct() {
		// Email slug we can use to filter other data.
		$this->id          = 'drwc_driver_expired';
		$this->title       = __( 'DRWC Download Expired', 'download-renewals-for-woocommerce' );
		$this->description = __( 'An email sent to the customer when a downloadable item they\'ve purchased is expired', 'download-renewals-for-woocommerce' );

		// For admin area to let the admin know we are sending this email to customers.
		$this->customer_email = true;
		$this->heading        = __( 'Download Expired', 'download-renewals-for-woocommerce' );

		// translators: placeholder is {blogname}, a variable that will be substituted when email is sent out
		$this->subject = sprintf( _x( '[%s] A download you purchased has expired', 'default email subject for customers when a purchased download is expired', 'download-renewals-for-woocommerce' ), '{blogname}' );

		// Template paths.
		$this->template_html  = 'emails/wc-order-download-expired.php';
		$this->template_plain = 'emails/plain/wc-order-download-expired.php';
		$this->template_base  = DRWC_PRO_EMAIL_PATH . 'emails/templates/';

		// Action to which we hook onto to send the email.
		add_action( 'drwc_download_expired_trigger_email', array( $this, 'trigger' ), 9 );
		parent::__construct();

		if ( ! $this->recipient ) {
			$this->recipient = get_option( 'admin_email' );
		}
	}

	/**
	 * Trigger Function that will send this email to the customer.
	 *
	 * @access public
	 * @return void
	 */
	function trigger( $order_id ) {
		// Get order details.
		$this->object = wc_get_order( $order_id );

		// Get customer email.
		if ( version_compare( '3.0.0', WC()->version, '>' ) ) {
			$order_email = $this->object->billing_email;
		} else {
			$order_email = $this->object->get_billing_email();
		}

		// Customer email.
		$this->recipient = $order_email;

		if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
			return;
		}

		// Send email.
		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
	}

	/**
	 * Get content html.
	 *
	 * @access public
	 * @return string
	 */
	public function get_content_html() {
		return wc_get_template_html( $this->template_html, array(
			'order'         => $this->object,
			'email_heading' => $this->get_heading(),
			'sent_to_admin' => false,
			'plain_text'    => false,
			'email'			=> $this
		), '', $this->template_base );
	}

	/**
	 * Get content plain.
	 *
	 * @return string
	 */
	public function get_content_plain() {
		return wc_get_template_html( $this->template_plain, array(
			'order'         => $this->object,
			'email_heading' => $this->get_heading(),
			'sent_to_admin' => false,
			'plain_text'    => true,
			'email'         => $this
		), '', $this->template_base );
  }

}
