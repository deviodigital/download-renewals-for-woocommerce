<?php

/**
 * The file that defines the core plugin helper functions
 *
 * @link       https://www.deviodigital.com
 * @since      1.0.0
 *
 * @package    Download_Renewals_For_Woocommerce
 * @subpackage Download_Renewals_For_Woocommerce/includes
 */

/**
 * Is download expired?
 * 
 * @since  1.0
 * @param  int  $order_id
 * @return bool true, false or null
 */
function drwc_is_download_expired( $order_id ) {
	// Get order data - '122' test ID.
	$order = wc_get_order( $order_id );

	// Order ID.
	$order_id = $order->get_id();

    // Get the customer ID.
    $customer_id = $order->get_user_id();

    // Get order items = each product in the order
	$items = $order->get_items();

    // Get downloadable items.
	$downloadable_items = $order->get_downloadable_items();

    // Downloadable items exist.
	if ( $downloadable_items ) {
		// Loop through products.
		foreach ( $downloadable_items as $item ) {
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
	} else {
        return null;
    }
}
//add_action( 'init', 'drwc_is_download_expired' );
