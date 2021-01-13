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
 * Check for downloadable products in an order.
 * 
 * @since  1.0
 * @param  int  $order_id
 * @return bool|void
 */
function drwc_check_order_for_downloads( $order_id = '' ) {

	// Get order data - '122' test ID.
	$order = wc_get_order( 101 );

	// Order ID.
	$order_id = $order->get_id();

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
		drwc_check_downloadable_items( $downloadable_items );
	}
}
//add_action( 'init', 'drwc_check_order_for_downloads' );

/**
 * Check downloadable items for expired downloads.
 * 
 * @since  1.0
 * @param  array  $downloadable_items
 * @return void
 */
function drwc_check_downloadable_items( $downloadable_items ) {
	// Loop through downloadable items.
	foreach ( $downloadable_items as $item ) {
		// Check if download is expired.
		$is_expired = drwc_is_download_expired( $item );

		// Download is expired.
		if ( $is_expired ) {
			// Do something for expired downloads.
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
	$access_expires = $access_expires->date;

	// Format the access expires date.
	$dt   = new DateTime( $access_expires );
	$date = $dt->format( 'm/d/Y' );

	// Check if download is expired.
	if ( strtotime( $date ) < strtotime( 'now' ) ) {
		return true;
	} else {
		return false;
	}
}
