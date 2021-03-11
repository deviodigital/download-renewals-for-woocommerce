<?php
/**
 * The file responsible for the WooCommerce renewal price change functionality.
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
 * Alter product price for renewals
 * 
 * @since  1.0
 * @return string $price_html
 */ 
function drwc_renewal_price_display( $price, $product ) {	
    global $post;

    // Post ID.
    $post_id = $post->ID;

    // Product data.
    $product = wc_get_product( $post_id );

    $current_user = wp_get_current_user();

    // Renewal price.
    if ( get_post_meta( $post_id, 'drwc_renewal_price', true ) ) {
        if ( sitecare_user_has_bought_items( $current_user->ID, $post_id ) ) {
            $price = get_post_meta( $post_id, 'drwc_renewal_price', true );
        }
    }

    return $price;
}
//add_filter( 'woocommerce_get_price', 'drwc_renewal_price_display', 10, 2 );

/**
 * Check if user has bought items
 * 
 * @return bool true|false
 */
function sitecare_user_has_bought_items( $user_var = 0,  $product_ids = 0 ) {
    global $wpdb;
    
    // Based on user ID (registered users)
    if ( is_numeric( $user_var) ) { 
        $meta_key     = '_customer_user';
        $meta_value   = $user_var == 0 ? (int) get_current_user_id() : (int) $user_var;
    } 
    // Based on billing email (Guest users)
    else { 
        $meta_key     = '_billing_email';
        $meta_value   = sanitize_email( $user_var );
    }
    
    $paid_statuses    = array_map( 'esc_sql', wc_get_is_paid_statuses() );
    $product_ids      = is_array( $product_ids ) ? implode(',', $product_ids) : $product_ids;

    $line_meta_value  = $product_ids !=  ( 0 || '' ) ? 'AND woim.meta_value IN ('.$product_ids.')' : 'AND woim.meta_value != 0';

    // Count the number of products
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

    // Return true if count is higher than 0 (or false)
    return $count > 0 ? true : false;
}

/**
 * Alter product renewal price in cart
 * 
 * @since  1.0
 * @return void
 */ 
function drwc_renewal_price_in_cart( $cart ) {
 
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;

    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) return;

    // IF CUSTOMER NOT LOGGED IN, DONT APPLY DISCOUNT.
    if ( ! wc_current_user_has_role( 'customer' ) ) return;

    // LOOP THROUGH CART ITEMS & APPLY 20% DISCOUNT.
    foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
        $product = $cart_item['data'];
        $price   = $product->get_price();
        $cart_item['data']->set_price( $price * 0.80 );
    }
}

/**
 * User purchase check
 * 
 * Display the renewal price if user previously purchased the product
 * 
 * @return string
 */
function drwc_renewal_price_user_purchase_check() {

    if ( ! is_user_logged_in() ) return;

    $current_user = wp_get_current_user();
    
    // Display renewal price if customer previously bought the product.
	if ( sitecare_user_has_bought_items( '', get_the_ID() ) ) {
        if ( get_post_meta( get_the_ID(), 'drwc_renewal_price', true ) ) {
            add_action( 'woocommerce_before_calculate_totals', 'drwc_renewal_price_in_cart', 9999 );
            add_filter( 'woocommerce_get_price', 'drwc_renewal_price_display', 9999, 2 );
        }
	}
}
add_action( 'plugins_loaded', 'drwc_renewal_price_user_purchase_check' );
