<?php
/**
 * The file responsible for the WooCommerce email functionality.
 *
 * @link       https://www.deviodigital.com
 * @since      1.0.0
 *
 * @package    Download_Renewals_for_WooCommerce
 * @subpackage Download_Renewals_for_WooCommerce/inc
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Download_Renewals_for_WooCommerce_WC_Emails
 *
 * @since 1.0
 */
class Download_Renewals_for_WooCommerce_WC_Emails {
	/**
	 * Download_Renewals_for_WooCommerce_WC_Emails constructor.
	 */
	public function __construct() {
		// Filtering the emails and adding our own email.
		add_filter( 'woocommerce_email_classes', array( $this, 'register_emails' ), 15, 1 );
		// Absolute path to the plugin folder.
		define( 'DRWC_PRO_EMAIL_PATH', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Register emails
	 *
	 * @param array $emails
	 *
	 * @return array
	 */
	public function register_emails( $emails ) {
		// Email classes.
		require_once 'emails/class-wc-order-download-expired.php';
		// Register emails.
		$emails['WC_Order_Download_Expired']  = new WC_Order_Download_Expired();

		return $emails;
	}

}

/**
 * Load the WooCommerce emails.
 * 
 * @since  1.0
 * @return void
 */
function drwc_load_woocommerce_emails() {
    // Load the DRWC emails for WooCommerce.
    new Download_Renewals_for_WooCommerce_WC_Emails();
}
add_action( 'woocommerce_init', 'drwc_load_woocommerce_emails' );
