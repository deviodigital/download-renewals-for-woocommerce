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

    // Get current user data.
    $current_user = wp_get_current_user();

    // Renewal price.
    if ( get_post_meta( $post_id, 'drwc_renewal_price', true ) ) {
        if ( drwc_user_has_bought_items( $current_user->ID, $post_id ) ) {
            $price = get_post_meta( $post_id, 'drwc_renewal_price', true );
        }
    }

    return $price;
}
add_filter( 'woocommerce_get_price', 'drwc_renewal_price_display', 10, 2 );

/**
 * Alter product renewal price in cart
 * 
 * @since  1.0
 * @return void
 */
function drwc_update_renewal_price_in_cart( $cart ) {
    // This is necessary for WC 3.0+
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        return;
    }

    // Avoiding hook repetition (when using price calculations for example).
    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) {
        return;
    }

    // Get user ID.
    $user_id = get_current_user_id();

    // Loop through cart items.
    foreach ( $cart->get_cart() as $item ) {
        if ( get_post_meta( $item['product_id'], 'drwc_renewal_price', true ) && drwc_user_has_bought_items( $user_id, $item['product_id'] ) ) {
            $item['data']->set_price( get_post_meta( $item['product_id'], 'drwc_renewal_price', true ) );
        }
    }
}
add_action( 'woocommerce_before_calculate_totals', 'drwc_update_renewal_price_in_cart', 9999 );

/**
 * Alter product renewal price in mini cart
 * 
 * @since  1.0
 * @return string $output
 */
function drwc_update_renewal_price_in_mini_cart( $output, $cart_item, $cart_item_key ) {
    // Product ID.
    $product_id = $cart_item['product_id'];

    // Get renewal price.
    $renewal_price = get_post_meta( $product_id, 'drwc_renewal_price', true );

    if ( $renewal_price ) {
        return sprintf( '<span class="quantity">%s &times; <span class="woocommerce-Price-amount amount">%s</span></span>', $cart_item['quantity'], wc_price( $renewal_price ) );
    } else {
        return $output;
    }
}
add_filter( 'woocommerce_widget_cart_item_quantity', 'drwc_update_renewal_price_in_mini_cart', 10, 3 );
