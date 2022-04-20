<?php

/**
 * The plugin bootstrap file
 * 
 * @link              https://www.deviodigital.com
 * @since             1.0.0
 * @package           Download_Renewals_For_WooCommerce
 *
 * @wordpress-plugin
 * Plugin Name:          Download Renewals for WooCommerce
 * Plugin URI:           https://www.deviodigital.com/download-renewals-for-woocommerce-release-notes
 * Description:          Sell digital products and remind your customers when their downloads expire
 * Version:              1.0.2
 * Author:               Devio Digital
 * Author URI:           https://www.deviodigital.com
 * License:              GPL-2.0+
 * License URI:          http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:          download-renewals-for-woocommerce
 * Domain Path:          /languages
 * WC requires at least: 3.5.0
 * WC tested up to:      6.3.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	wp_die();
}

// Current plugin version.
define( 'DRWC_VERSION', '1.0.2' );

// Define DRWC plugin name.
$plugin_name = plugin_basename( __FILE__ );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-download-renewals-for-woocommerce-activator.php
 */
function activate_download_renewals_for_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-download-renewals-for-woocommerce-activator.php';
	Download_Renewals_For_WooCommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-download-renewals-for-woocommerce-deactivator.php
 */
function deactivate_download_renewals_for_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-download-renewals-for-woocommerce-deactivator.php';
	Download_Renewals_For_WooCommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_download_renewals_for_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_download_renewals_for_woocommerce' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-download-renewals-for-woocommerce.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_download_renewals_for_woocommerce() {

	$plugin = new Download_Renewals_For_WooCommerce();
	$plugin->run();

}
run_download_renewals_for_woocommerce();
