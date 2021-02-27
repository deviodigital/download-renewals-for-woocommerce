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

function drwc_downloadable_product_option_group() {
	echo '<div class="options_group show_if_downloadable hidden">';
 
    $args = array(
        'label'             => __( 'Renewal Price ', 'drwc' ) . '(' . get_woocommerce_currency_symbol() . ')', // Text in Label
        'placeholder'       => '',
        'class'             => 'short wc_input_price',
        'style'             => '',
        'wrapper_class'     => '',
        'value'             => '', // if empty, retrieved from post meta where id is the meta_key
        'id'                => 'drwc_renewal_price', // required
        'name'              => 'drwc_renewal_price', //name will set from id if empty
        'type'              => 'text',
        'desc_tip'          => '',
        'data_type'         => '',
        'custom_attributes' => '', // array of attributes 
        'description'       => ''
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
function drwc_downloadable_product_save_fields( $id, $post ){
 
    if ( ! empty( $_POST['super_product'] ) ) {
        update_post_meta( $id, 'drwc_renewal_price', $_POST['drwc_renewal_price'] );
    } else {
        delete_post_meta( $id, 'super_product' );
    }

}
add_action( 'woocommerce_process_product_meta', 'drwc_downloadable_product_save_fields', 10, 2 );
