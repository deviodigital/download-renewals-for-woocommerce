<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.deviodigital.com
 * @since      1.0.0
 *
 * @package    DownloadRenewalsForWooCommerce
 * @subpackage DownloadRenewalsForWooCommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    DownloadRenewalsForWooCommerce
 * @subpackage DownloadRenewalsForWooCommerce/admin
 * @author     Devio Digital <contact@deviodigital.com>
 */
class DownloadRenewalsForWooCommerceAdmin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string    $plugin_name    The name of this plugin.
	 * @param    string    $version        The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		// Plugin admin CSS.
		// wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/download-renewals-for-woocommerce-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		// Plugin admin JS.
		// wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/download-renewals-for-woocommerce-admin.js', array( 'jquery' ), $this->version, false );
	}

}
