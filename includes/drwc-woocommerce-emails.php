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
 * Register action as one that sends emails
 *
 * @since  1.0
 * @return array
 */
function drwc_woocommerce_ajax_email_action( $actions ) {
    $actions[] = 'drwc_download_expired_trigger_email';
    return $actions;
}
add_action( 'woocommerce_email_actions', 'drwc_woocommerce_ajax_email_action' );

/**
 * Filter template path to use our email-downloads.php template
 * 
 * @since  1.0
 * @return string
 */
function drwc_locate_template_email_downloads( $template, $template_name, $template_path ) {
	$basename = basename( $template );
	if ( 'email-downloads.php' == $basename ) {
		$template = trailingslashit( plugin_dir_path( __FILE__ ) ) . 'emails/email-downloads.php';
	}
	return $template;
}
add_filter( 'woocommerce_locate_template', 'drwc_locate_template_email_downloads', 10, 3 );

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
		require_once DRWC_PRO_EMAIL_PATH . '/emails/class-wc-order-download-expired.php';
		// Register emails.
		$emails['WC_Order_Download_Expired']  = new WC_Order_Download_Expired();

		return $emails;
	}

}
// Load the DRWC emails for WooCommerce.
new Download_Renewals_for_WooCommerce_WC_Emails();
