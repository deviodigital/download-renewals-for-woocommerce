<?php

/**
 * The file that defines the core plugin functions
 *
 * @link       https://www.deviodigital.com
 * @since      1.0.0
 *
 * @package    Download_Renewals_For_Woocommerce
 * @subpackage Download_Renewals_For_Woocommerce/includes
 */

/**
 * Check orders for expired downloads
 * 
 * @since  1.0
 * @return void
 */
function drwc_check_orders_for_expired_downloads() {
	// Get WooCommerce orders.
	$orders = wc_get_orders( array( 'numberposts' => -1 ) );

	// Loop through each WC_Order object
	foreach( $orders as $order ) {
		// Order ID.
		$order_id = $order->get_id();

		// Order status.
		$order_status = $order->get_status();

		// Only run for completed orders.
		if ( 'completed' == $order_status ) {
			// Check order for downloads.
			drwc_check_order_for_downloads( $order_id );
		}
	}
}

/**
 * Check for downloadable products in an order.
 * 
 * @since  1.0
 * @param  int  $order_id
 * @return bool|void
 */
function drwc_check_order_for_downloads( $order_id ) {

	// Get order data - '122' test ID.
	$order = wc_get_order( $order_id );

    // Get the customer ID.
    $customer_id = $order->get_user_id();

    // Get order items = each product in the order
	$items = $order->get_items();

    // Get downloadable items.
	$downloadable_items = $order->get_downloadable_items();

	// Downloadable items do not exist.
	if ( ! $downloadable_items ) {
		return null;
	}

    // Downloadable items exist.
	if ( $downloadable_items ) {
		// Check downloadable items for expired downloads.
		drwc_check_downloadable_items( $order_id, $downloadable_items );
	}
}

/**
 * Check downloadable items for expired downloads.
 * 
 * @since  1.0
 * @param  array $order_id
 * @param  array $downloadable_items
 * @return void
 */
function drwc_check_downloadable_items( $order_id, $downloadable_items ) {
	// Loop through downloadable items.
	foreach ( $downloadable_items as $item ) {
		// Check if download is expired.
		$is_expired = drwc_is_download_expired( $item );

		// Download is expired.
		if ( $is_expired ) {
			// Send WooCommerce email.
			drwc_send_woocommerce_email( $order_id );
			break;
		}
	}
}

/**
 * Is download expired?
 * 
 * @param  array  $item
 * @return bool
 */
function drwc_is_download_expired( $item ) {
	// Access expires.
	$access_expires = $item['access_expires'];
	// Convert access expires to array.
	$access_expires = json_decode( json_encode( $access_expires ), true );
	// Expire date.
	$expire_date = $access_expires['date'];

	// Format the access expires date.
	$dt   = new DateTime( $expire_date );
	$date = $dt->format( 'm/d/Y' );

	// Check if download is expired.
	if ( strtotime( $date ) < strtotime( 'now' ) ) {
		return true;
	}

	return null;
}

/**
 * Send WooCommerce email to customer about expired download
 * 
 * @return void
 */
function drwc_send_woocommerce_email( $order_id ) {
	// WooCommerce mailer.
	$mailer = WC()->mailer();
	// Get WooCommerce emails.
	$mails  = $mailer->get_emails();
	// Check if emails exist.
	if ( ! empty( $mails ) ) {
		// Loop through emails.
		foreach ( $mails as $mail ) {
			// Only run this code on our custom email.
			if ( 'drwc_download_expired' == $mail->id ) {
				// Trigger our email.
				$mail->trigger( $order_id );
			}
		}
	}
}
