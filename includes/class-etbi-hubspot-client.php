<?php

/**
 * The ETBI Hubspot Client class
 *
 * @link       https://github.com/JoshuaMcKendall/ETBI-Hubspot-Plugin/includes/
 * @since      1.0.0
 *
 * @package    ETBI_Hubspot
 * @subpackage ETBI_Hubspot/includes
 */

/**
 * The ETBI Hubspot Client class
 *
 * This class loads the etbi hubspot client.
 *
 * @since      1.0.0
 * @package    ETBI_Hubspot
 * @subpackage ETBI_Hubspot/includes
 * @author     Joshua McKendall <etbi-hubspot@joshuamckendall.com>
 */

defined( 'ABSPATH' ) || exit;

class ETBI_Hubspot_Client {

	private static $_instance = null;

	private $oauth2 = null;

	private $_auth_url = null;

	private $_api_key = null;

	private $_client_id = null;

	private $_client_secret = null;

	private $_auth_code = null;

	private $_redirect_uri = null;

	private $_scopes_array = array();

	private $_access_token = null;

	private $_refresh_token = null;

	private $_expires_in = null;

	public $hubspot = null;

	public function __construct() {

		$this->_oauth2 = true;

		$this->_access_token = get_option( 'etbi_hubspot_options_access_token', false );
		$this->_refresh_token = get_option( 'etbi_hubspot_options_refresh_token', '' );
		$this->_expires_in = get_option( 'etbi_hubspot_options_expires_in', '' );

		$this->_api_key = 'API_KEY';

		if( ! $this->_access_token ) {

			$this->_oauth2 = false;
			$this->_access_token = $this->_api_key;

		}


		$this->hubspot = new SevenShores\Hubspot\Factory([
				'key'      => $this->_api_key,
				'oauth2'    => false, // default
				'base_url' => 'https://api.hubapi.com' // default
			],
			null,
			[
			  'http_errors' => false // pass any Guzzle related option to any request, e.g. throw no exceptions
			],
			false // return Guzzle Response object for any ->request(*) call
		);

		$this->_client_id = get_option( 'etbi_hubspot_options_client_id', '' );
		$this->_client_secret = get_option( 'etbi_hubspot_options_client_secret', '' );
		$this->_auth_code = get_option( 'etbi_hubspot_options_authorization_code', '' );

		$this->_scopes_array = array(

			'contacts',
			'oauth',
			'forms'
		);



		// array(

		// 	'contacts',
		// 	'content',
		// 	'reports',
		// 	'social',
		// 	'automation',
		// 	'actions',
		// 	'timeline',
		// 	'business-intelligence',
		// 	'oauth',
		// 	'forms',
		// 	'files',
		// 	'integration-sync',
		// 	'tickets',
		// 	'e-commerce'
		// )


		add_action( 'user_register', array( $this, 'create_contact' ) );
		add_action( 'publish_lp_course', array( $this, 'add_course_property' ), 10, 2 );
		add_action( 'learn-press/user-enrolled-course', array( $this, 'enroll_contact_in_course' ), 10, 3 );
		add_action( 'etbi_cron_hook', array( $this, 'refresh_tokens' ) );
		add_action( 'post_updated', array( $this, 'update_course_property' ), 10, 3 );

		add_filter( 'cron_schedules', array( $this, 'add_refresh_schedule' ), 10, 1 );

	}

	public function create_contact( $user_id ) {

		$user_data = get_userdata( $user_id );
		$user_email = $user_data->user_email;
		$user_first_name = ( isset( $_POST['first_name'] ) ) ? $_POST['first_name'] : '';
		$user_last_name = ( isset( $_POST['last_name'] ) ) ? $_POST['last_name'] : '';

		$this->hubspot->contacts()->create( array(

			array(

				'property' 	=> 'email',
				'value'		=> $user_email

			),
			array(

				'property'	=> 'firstname',
				'value'		=> $user_first_name
			),
			array(

				'property'	=> 'lastname',
				'value'		=> $user_last_name
			)

		) );

	}

	public function add_course_property( $post_id, $post ) {

		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		$this->hubspot->contactProperties()->create( array(

				'name' 			=> str_replace( '-', '', sanitize_title_with_dashes( $post->post_title, null, 'save' ) ),
				'label'			=> $post->post_title,
				'description'	=> get_the_excerpt( $post_id ),
				'groupName'		=> 'courses',
				'type'			=> 'enumeration',
				'fieldType'		=> 'select',
				'formField' 	=> true,
				'displayOrder'	=> 6,
				'options'		=> array(

					array(

						'label'		=> 'Not Enrolled',
						'value'		=> 'not-enrolled'

					),
					array(

						'label'		=> 'Enrolled',
						'value'		=> 'enrolled'

					),
					array(

						'label'		=> 'Completed',
						'value'		=> 'completed'

					),

				)

		) );

	}


	public function update_course_property( $post_id, $post_after, $post_before ) {

		$old_property_name = str_replace( '-', '', sanitize_title_with_dashes( $post_before->post_title, null, 'save' ) );
		$new_property_name = str_replace( '-', '', sanitize_title_with_dashes( $post_after->post_title, null, 'save' ) );

		$response = $this->hubspot->contactProperties()->get( $old_property_name );

		if( $response->getStatusCode() == 400 ) {

			$this->add_course_property( $post_id, $post_after );

		} else {

			$json_responsse = (string) $response->getBody();
			$json_response = json_decode( $json_response, true );

			$this->hubspot->contactProperties()->update( $json_response['name'], array(

					'name' 			=> $new_property_name,
					'label'			=> $post_after->post_title,
					'description'	=> get_the_excerpt( $post_id ),
					'groupName'		=> 'courses',
					'type'			=> 'enumeration',
					'fieldType'		=> 'select',
					'formField' 	=> true,
					'displayOrder'	=> 6,
					'options'		=> array(

						array(

							'label'		=> 'Not Enrolled',
							'value'		=> 'not-enrolled'

						),
						array(

							'label'		=> 'Enrolled',
							'value'		=> 'enrolled'

						),
						array(

							'label'		=> 'Completed',
							'value'		=> 'completed'

						),

					)

			)  );
			
		}


	}

	public function enroll_contact_in_course( $course_id, $user_id, $return ) {

		if ( ! $course = learn_press_get_course( $course_id ) ) {
			return;
		}

		$user_data = get_userdata( $user_id );
		$user_email = $user_data->user_email;
		$post = get_post( $course_id );

		$properties = array(

			array(

				'property'	=> str_replace( '-', '', sanitize_title_with_dashes( $post->post_title, null, 'save' ) ),
				'value'		=> 'enrolled'

			)

		);


		$response = $this->hubspot->contacts()->updateByEmail( $user_email, $properties );



	}

	public function get_auth_url( $redirect_uri ) {

		$client_id = $this->_client_id;
		$client_secret = $this->_client_secret;
		$this->_redirect_uri = $redirect_uri;
		$scopes_array = $this->_scopes_array;

		error_log(menu_page_url( 'etbi_hubspot_client' ));

		return $this->hubspot->oauth2()->getAuthUrl( $client_id, $redirect_uri, $scopes_array );

	}

	public function get_tokens( $context = 'code' ) {

		$response = '';

		switch ( $context ) {

			case 'code':
				$response = $this->hubspot->oauth2()->getTokensByCode( $this->_client_id, $this->_client_secret, $this->_redirect_uri, $this->_auth_code );
				break;

			case 'refresh':
				$response = $this->hubspot->oauth2()->getTokensByRefresh( $this->_client_id, $this->_client_secret, $this->_refresh_token );
				break;
			
			default:
				$response = $this->hubspot->oauth2()->getTokensByCode( $this->_client_id, $this->_client_secret, $this->_redirect_uri, $this->_auth_code );
				break;
		}

		return $response;

	}

	public function refresh_tokens() {

		if( ! empty( $this->_refresh_token ) ) {

		    $response = $this->get_tokens( 'refresh' );

		    etbi_set_tokens_and_expiry_time( $response );

		    $this->_access_token = get_option( 'etbi_hubspot_options_access_token', false );
		    $this->_refresh_token = get_option( 'etbi_hubspot_options_access_token', '' );
		    $this->_expires_in = get_option( 'etbi_hubspot_options_expires_in', '' );

			if ( ! wp_next_scheduled( 'etbi_cron_hook' ) ) {

			    wp_schedule_event( $this->_expires_in, 'six_hours', 'etbi_cron_hook' );

			}

		}

	}

	public function add_refresh_schedule( $schedules ) {	

		$schedules['six_hours'] = array(
	        'interval' => 21600,
	        'display'  => esc_html__( 'Every Six Hours' ),
	    );
	 
	    return $schedules;

	}

	public static function instance() {
		if ( ! empty( self::$_instance ) ) {
			return self::$_instance;
		}

		return self::$_instance = new self();		
	}

}

add_action( 'init', array( 'ETBI_Hubspot_Client', 'instance' ) );
