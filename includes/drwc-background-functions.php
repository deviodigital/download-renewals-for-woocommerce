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

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Create daily cron schedule
 * 
 * @since  1.0
 * @return array $schedules
 */
function drwc_cron_schedules( $schedules ) {
    // Daily cron check
    if ( ! isset($schedules['drwcDaily'] ) ) {
        $schedules['drwcDaily'] = array(
            'interval' => 86400,
            'display'  => __( 'Once per day', 'download-renewals-for-woocommerce' )
        );
    }
    return $schedules;
}
add_filter( 'cron_schedules', 'drwc_cron_schedules' );

/**
 * Schedule our daily cron event
 * 
 * @since  1.0
 * @return void
 */
function drwc_schedule_cron_event() {
    // Schedule cron event if it's not already scheduled.
    if ( ! wp_next_scheduled( 'drwc_check_orders_for_expired_downloads' ) ) {
        wp_schedule_event( strtotime( '00:01:00' ), 'drwcDaily', 'drwc_check_orders_for_expired_downloads' );
    }
    // Hook into that action that'll fire every day.
    add_action( 'drwc_check_orders_for_expired_downloads', 'drwc_check_orders_for_expired_downloads' );
}
add_action( 'init', 'drwc_schedule_cron_event' );
