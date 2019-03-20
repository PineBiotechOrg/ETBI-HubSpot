<?php
/**
 * ETBI Hubspot Admin Settings class
 *
 * @author        Joshua McKendall
 * @package       ETBI_Hubspot/Class
 * @version       1.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit;

class ETBI_Hubspot_Admin_Settings {

	private static $messages = array();

	public static function init() {
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
		add_action( 'admin_menu', array( __CLASS__, 'hubspot_settings_page' ) );
	}

	public static function register_settings() {

		register_setting( 'etbi_hubspot', 'etbi_hubspot_options_client_id' );

		register_setting( 'etbi_hubspot', 'etbi_hubspot_options_client_secret' );

		register_setting( 'etbi_hubspot', 'etbi_hubspot_options_authorization_code' );

		register_setting( 'etbi_hubspot', 'etbi_hubspot_options_access_token' );

		register_setting( 'etbi_hubspot', 'etbi_hubspot_options_refresh_token' );

		register_setting( 'etbi_hubspot', 'etbi_hubspot_options_expires_in' );

		add_settings_section(

			'hubspot_client_credentials', //id
			__( 'Hubspot Client Credentials', 'etbi' ), //title
			'hubspot_client_credentials_callback', //callback
			'etbi_hubspot' //page

		);

		 // register a new field in the "wporg_section_developers" section, inside the "wporg" page
		 // add_settings_field(

			//  'etbi_hubspot_client_id', // as of WP 4.6 this value is used only internally
			//  // use $args' label_for to populate the id inside the callback
			//  __( 'Client ID', 'etbi' ),
			//  'etbi_hubspot_client_id_callback',
			//  'etbi_hubspot',
			//  'hubspot_client_credentials',
			//  [
			//  'label_for' => 'etbi_hubspot_client_id',
			//  'class' => 'etbi_hubspot_client_id',
			//  'wporg_custom_data' => 'custom',
			//  ]

		 // );

	}

	public static function hubspot_settings_page() {


		add_options_page( 'Hubspot API Settings', 'Hubspot Client', 'manage_options', 'etbi_hubspot_client', array( __CLASS__, 'render_hubspot_settings_page' ) );

	}

	public static function render_hubspot_settings_page() {

		require_once ETBI_HUBSPOT_INC . 'admin/views/settings/hubspot-client-settings.php';

	}

}

ETBI_Hubspot_Admin_Settings::init();
