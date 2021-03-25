<?php

/**
 * The file responsible for creating the DRWC Settings page
 *
 * @link       https://www.deviodigital.com
 * @since      1.0.0
 *
 * @package    Download_Renewals_For_WooCommerce
 * @subpackage Download_Renewals_For_WooCommerce/admin
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define global constants.
 *
 * @since 1.0.0
 */
// Plugin version.
if ( ! defined( 'WPOSA_VERSION' ) ) {
	define( 'WPOSA_VERSION', '1.0.0' );
}

if ( ! defined( 'WPOSA_NAME' ) ) {
	define( 'WPOSA_NAME', trim( dirname( plugin_basename( __FILE__ ) ), '/' ) );
}

if ( ! defined( 'WPOSA_DIR' ) ) {
	define( 'WPOSA_DIR', WP_PLUGIN_DIR . '/' . WPOSA_NAME );
}

if ( ! defined( 'WPOSA_URL' ) ) {
	define( 'WPOSA_URL', WP_PLUGIN_URL . '/' . WPOSA_NAME );
}

/**
 * Actions/Filters
 *
 * Related to all settings API.
 *
 * @since  1.0.0
 */
if ( class_exists( 'WP_OSA' ) ) {
	/**
	 * Object Instantiation.
	 *
	 * Object for the class `WP_OSA`.
	 */
	$wposa_obj = new WP_OSA();


	// Section: Basic Settings.
	$wposa_obj->add_section(
		array(
			'id'    => 'wposa_basic',
			'title' => __( 'Basic Settings', 'download-renewals-for-woocommerce' ),
		)
	);

	// Section: Other Settings.
	$wposa_obj->add_section(
		array(
			'id'    => 'wposa_other',
			'title' => __( 'Other Settings', 'download-renewals-for-woocommerce' ),
		)
	);

	// Field: Text.
	$wposa_obj->add_field(
		'wposa_basic',
		array(
			'id'      => 'text',
			'type'    => 'text',
			'name'    => __( 'Text Input', 'download-renewals-for-woocommerce' ),
			'desc'    => __( 'Text input description', 'download-renewals-for-woocommerce' ),
			'default' => 'Default Text',
		)
	);

	// Field: Number.
	$wposa_obj->add_field(
		'wposa_basic',
		array(
			'id'                => 'text_no',
			'type'              => 'number',
			'name'              => __( 'Number Input', 'download-renewals-for-woocommerce' ),
			'desc'              => __( 'Number field with validation callback `intval`', 'download-renewals-for-woocommerce' ),
			'default'           => 1,
			'sanitize_callback' => 'intval',
		)
	);

	// Field: Password.
	$wposa_obj->add_field(
		'wposa_basic',
		array(
			'id'   => 'password',
			'type' => 'password',
			'name' => __( 'Password Input', 'download-renewals-for-woocommerce' ),
			'desc' => __( 'Password field description', 'download-renewals-for-woocommerce' ),
		)
	);

	// Field: Textarea.
	$wposa_obj->add_field(
		'wposa_basic',
		array(
			'id'   => 'textarea',
			'type' => 'textarea',
			'name' => __( 'Textarea Input', 'download-renewals-for-woocommerce' ),
			'desc' => __( 'Textarea description', 'download-renewals-for-woocommerce' ),
		)
	);

	// Field: Separator.
	$wposa_obj->add_field(
		'wposa_basic',
		array(
			'id'   => 'separator',
			'type' => 'separator',
		)
	);

	// Field: Title.
	$wposa_obj->add_field(
		'wposa_basic',
		array(
			'id'   => 'title',
			'type' => 'title',
			'name' => '<h1>Title</h1>',
		)
	);

	// Field: Checkbox.
	$wposa_obj->add_field(
		'wposa_basic',
		array(
			'id'   => 'checkbox',
			'type' => 'checkbox',
			'name' => __( 'Checkbox', 'download-renewals-for-woocommerce' ),
			'desc' => __( 'Checkbox Label', 'download-renewals-for-woocommerce' ),
		)
	);

	// Field: Radio.
	$wposa_obj->add_field(
		'wposa_basic',
		array(
			'id'      => 'radio',
			'type'    => 'radio',
			'name'    => __( 'Radio', 'download-renewals-for-woocommerce' ),
			'desc'    => __( 'Radio Button', 'download-renewals-for-woocommerce' ),
			'options' => array(
				'yes' => 'Yes',
				'no'  => 'No',
			),
		)
	);

	// Field: Multicheck.
	$wposa_obj->add_field(
		'wposa_basic',
		array(
			'id'      => 'multicheck',
			'type'    => 'multicheck',
			'name'    => __( 'Multile checkbox', 'download-renewals-for-woocommerce' ),
			'desc'    => __( 'Multile checkbox description', 'download-renewals-for-woocommerce' ),
			'options' => array(
				'yes' => 'Yes',
				'no'  => 'No',
			),
		)
	);

	// Field: Select.
	$wposa_obj->add_field(
		'wposa_basic',
		array(
			'id'      => 'select',
			'type'    => 'select',
			'name'    => __( 'A Dropdown', 'download-renewals-for-woocommerce' ),
			'desc'    => __( 'A Dropdown description', 'download-renewals-for-woocommerce' ),
			'options' => array(
				'yes' => 'Yes',
				'no'  => 'No',
			),
		)
	);

	// Field: Image.
	$wposa_obj->add_field(
		'wposa_other',
		array(
			'id'      => 'image',
			'type'    => 'image',
			'name'    => __( 'Image', 'download-renewals-for-woocommerce' ),
			'desc'    => __( 'Image description', 'download-renewals-for-woocommerce' ),
			'options' => array(
				'button_label' => 'Choose Image',
			),
		)
	);

	// Field: File.
	$wposa_obj->add_field(
		'wposa_other',
		array(
			'id'      => 'file',
			'type'    => 'file',
			'name'    => __( 'File', 'download-renewals-for-woocommerce' ),
			'desc'    => __( 'File description', 'download-renewals-for-woocommerce' ),
			'options' => array(
				'button_label' => 'Choose file',
			),
		)
	);

	// Field: Color.
	$wposa_obj->add_field(
		'wposa_other',
		array(
			'id'          => 'color',
			'type'        => 'color',
			'name'        => __( 'Color', 'download-renewals-for-woocommerce' ),
			'desc'        => __( 'Color description', 'download-renewals-for-woocommerce' ),
			'placeholder' => __( '#5F4B8B', 'download-renewals-for-woocommerce' ),
		)
	);

	// Field: WYSIWYG.
	$wposa_obj->add_field(
		'wposa_other',
		array(
			'id'   => 'wysiwyg',
			'type' => 'wysiwyg',
			'name' => __( 'WP_Editor', 'download-renewals-for-woocommerce' ),
			'desc' => __( 'WP_Editor description', 'download-renewals-for-woocommerce' ),
		)
	);
}