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
 * Add renewal price to product option group
 * 
 * @since  1.0
 * @return void
 */
function drwc_downloadable_product_option_group() {
	echo '<div class="options_group show_if_downloadable hidden">';
 
    $args = array(
        'label'             => __( 'Renewal Price ', 'drwc' ) . '(' . get_woocommerce_currency_symbol() . ')', // Text in Label
        'placeholder'       => '',
        'class'             => 'short wc_input_price',
        'style'             => '',
        'wrapper_class'     => '',
        'value'             => get_post_meta( get_the_ID(), 'drwc_renewal_price', true ),
        'id'                => 'drwc_renewal_price',
        'name'              => 'drwc_renewal_price',
        'type'              => 'number',
        'desc_tip'          => '',
        'data_type'         => '',
        'description'       => '',
        'custom_attributes' => array(
            'step' => 'any',
            'min'  => '0'
        ),

    );
    
    woocommerce_wp_text_input( $args );
 
	echo '</div>';
}
add_action( 'woocommerce_product_options_pricing', 'drwc_downloadable_product_option_group', 1 );

/**
 * Save product fields
 * 
 * @since  1.0
 * @return void
 */
function drwc_downloadable_product_save_fields( $post_id ) {
    // Get product.
    $product = wc_get_product( $post_id );
    // Get renewal price (if it's set).
    $drwc_renewal_price = isset($_POST['drwc_renewal_price']) ? $_POST['drwc_renewal_price'] : '';
    // Update product meta.
    $product->update_meta_data( 'drwc_renewal_price', sanitize_text_field( $drwc_renewal_price ) );
    // Save product.
    $product->save();
}
add_action( 'woocommerce_process_product_meta', 'drwc_downloadable_product_save_fields', 9999 );
