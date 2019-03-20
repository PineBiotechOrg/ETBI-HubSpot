<?php


/**
 * Get the Illustrator plugin path.
 *
 * @param string $sub_dir
 *
 * @return string
 */
function etbi_hubspot_plugin_path( $sub_dir = '' ) {

	return ETBI_Hubspot()->plugin_path( $sub_dir );

}

if( ! function_exists('etbi_get_auth_code') ) {

	function etbi_get_auth_code() {

		$auth_code = '';

		if( isset( $_GET['code'] ) && empty( get_option( 'etbi_hubspot_options_authorization_code', $auth_code ) ) ) {

			$auth_code = $_GET['code'];

			update_option( 'etbi_hubspot_options_authorization_code', $auth_code );

		} else if ( ! empty( get_option( 'etbi_hubspot_options_authorization_code', $auth_code ) ) && get_option( 'etbi_hubspot_options_authorization_code' ) == $_GET['code'] ) {

			$auth_code = get_option( 'etbi_hubspot_options_authorization_code', '' );

		} else {

			$auth_code = $_GET['code'];

			update_option( 'etbi_hubspot_options_authorization_code', $auth_code );

		}

		return apply_filters( 'etbi_hubspot_authorization_code', $auth_code );

	}

}

if( ! function_exists('etbi_get_auth_url') ) {

	function etbi_get_auth_url() {

		$hubspot_client = ETBI_Hubspot_Client::instance();

		return $hubspot_client->get_auth_url();

	}

}

if( ! function_exists('etbi_get_auth_tokens') ) {

	function etbi_get_auth_tokens( $context = 'code' ) {

		$hubspot_client = ETBI_Hubspot_Client::instance();

		return $hubspot_client->get_tokens( $context );

	}

}

if( ! function_exists('etbi_set_tokens_and_expiry_time') ) {

	function etbi_set_tokens_and_expiry_time( $json_response ) {

		$response = (string) $json_response->getBody();

		$response = json_decode( $response, true );

		print_r( $response );

		update_option( 'etbi_hubspot_options_access_token', $response['access_token'] );
		update_option( 'etbi_hubspot_options_refresh_token', $response['refresh_token'] );
		update_option( 'etbi_hubspot_options_expires_in', $response['expires_in'] );

		wp_schedule_event( $response['expires_in'], 'six_hours', 'etbi_cron_hook' );

	}

}

if( ! function_exists('reset_auth_code') ) {

	function reset_auth_code() {



	}

}