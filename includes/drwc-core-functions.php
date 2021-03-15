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

		// Order statuses to check against.
		$order_statuses = apply_filters( 'drwc_check_order_for_downloads_order_statuses', 'completed' );

		// Check orders for downloads.
		if ( in_array( $order_status, $order_statuses ) || 'yes' === get_option( 'woocommerce_downloads_grant_access_after_payment' ) ) {
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

	// Get order data.
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

		// Check if email has already been sent.
		$email_sent = get_post_meta( $order_id, 'drwc_download_expired_email_sent', true );

		// Download is expired.
		if ( $is_expired && ! $email_sent ) {
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
				// Set metadata for email already being sent.
				update_post_meta( $order_id, 'drwc_download_expired_email_sent', true );
			}
		}
	}
}

/**
 * Check if user has bought items
 * 
 * @since  1.0
 * @return bool true|false
 */
function drwc_user_has_bought_items( $user_var = 0,  $product_ids = 0 ) {
    global $wpdb;

    // Based on user ID (registered users).
    if ( is_numeric( $user_var ) ) {
        $meta_key   = '_customer_user';
        $meta_value = $user_var == 0 ? (int) get_current_user_id() : (int) $user_var;
    }

    // Based on billing email (Guest users).
    else { 
        $meta_key   = '_billing_email';
        $meta_value = sanitize_email( $user_var );
    }
    
    $paid_statuses    = array_map( 'esc_sql', wc_get_is_paid_statuses() );
    $product_ids      = is_array( $product_ids ) ? implode(',', $product_ids) : $product_ids;
    $line_meta_value  = $product_ids !=  ( 0 || '' ) ? 'AND woim.meta_value IN ('.$product_ids.')' : 'AND woim.meta_value != 0';

    // Count the number of products.
    $count = $wpdb->get_var( "
        SELECT COUNT(p.ID) FROM {$wpdb->prefix}posts AS p
        INNER JOIN {$wpdb->prefix}postmeta AS pm ON p.ID = pm.post_id
        INNER JOIN {$wpdb->prefix}woocommerce_order_items AS woi ON p.ID = woi.order_id
        INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS woim ON woi.order_item_id = woim.order_item_id
        WHERE p.post_status IN ( 'wc-" . implode( "','wc-", $paid_statuses ) . "' )
        AND pm.meta_key = '$meta_key'
        AND pm.meta_value = '$meta_value'
        AND woim.meta_key IN ( '_product_id', '_variation_id' ) $line_meta_value 
    " );

    // Return true if count is higher than 0 (or false).
    return $count > 0 ? true : false;
}