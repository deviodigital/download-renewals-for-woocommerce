<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.deviodigital.com
 * @since      1.0.0
 *
 * @package    DownloadRenewalsForWooCommerce
 * @subpackage DownloadRenewalsForWooCommerce/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    DownloadRenewalsForWooCommerce
 * @subpackage DownloadRenewalsForWooCommerce/includes
 * @author     Devio Digital <contact@deviodigital.com>
 */
class DownloadRenewalsForWooCommercei18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'download-renewals-for-woocommerce',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}
}
