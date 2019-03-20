<?php
/**
 * ETBI Hubspot Settings class
 *
 * @link       https://github.com/JoshuaMcKendall/ETBI-Hubspot-Plugin/includes/
 * @since      1.0.0
 *
 * @package    ETBI_Hubspot
 * @subpackage ETBI_Hubspot/includes
 */

/**
 * This class handles all the plugin settings.
 *
 * @since      1.0.0
 * @package    ETBI_Hubspot
 * @subpackage ETBI_Hubspot/includes
 * @author     Joshua McKendall <etbi-hubspot@joshuamckendall.com>
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit;

class ETBI_Hubspot_Settings { 

	/**
	 * $_options
	 * @var null
	 */
	public $_options = null;

	/**
	 * prefix option name
	 * @var string
	 */
	public $_prefix = 'etbi_hubspot';

	/**
	 * _instance
	 * @var null
	 */
	static $_instance = null;

	public function __construct( $prefix = null ) {
		if ( $prefix )
			$this->_prefix = $prefix;

		// load options
		if ( !$this->_options )
			$this->_options = $this->options();
	}

	public function __get( $id = null ) {
		$settings = apply_filters( 'etbi_hubspot_settings_field', array() );

		if ( isset( $settings[$id] ) ) {
			return $settings[$id];
		}
	}

	/**
	 * options load options
	 * @return array || null
	 */
	protected function options() {

		if ( is_array( get_option( $this->_prefix, null ) ) ) {
			return call_user_func_array( 'array_merge', get_option( $this->_prefix, null ) );
		}

		return get_option( $this->_prefix, null );
	}

	/**
	 * get_name_field
	 *
	 * @param  $name of field option
	 *
	 * @return string name field
	 */
	public function get_field_name( $name = null ) {
		if ( !$this->_prefix || !$name )
			return;

		return $this->_prefix . '[' . $name . ']';
	}

	/**
	 * get_name_field
	 *
	 * @param  $name of field option
	 *
	 * @return string name field
	 */
	public function get_field_id( $name = null, $default = null ) {
		if ( !$this->_prefix || !$name )
			return;

		return $this->_prefix . '_' . $name;
	}

	/**
	 * get option value
	 *
	 * @param  $name
	 *
	 * @return option value. array, string, boolean
	 */
	public function get( $name = null, $default = null ) {
		if ( !$this->_options )
			$this->_options = $this->options();

		if ( $name && isset( $this->_options[$name] ) )
			return $this->_options[$name];

		return $default;
	}

	/**
	 * get option value
	 *
	 * @param  $name
	 *
	 * @return option value. array, string, boolean
	 */
	public function set( $name = null, $default = null ) {
		if ( !$this->_options )
			$this->_options = $this->options();

		if ( $name && isset( $this->_options[$name] ) )
			return $this->_options[$name];

		return $default;
	}

	/**
	 * instance
	 *
	 * @param  $prefix
	 *
	 * @return object class
	 */
	public static function instance( $prefix = null ) {

		if ( !empty( self::$_instance[$prefix] ) ) {

			return $GLOBALS['etbi_hubspot_settings'] = self::$_instance[$prefix];
		}

		return $GLOBALS['etbi_hubspot_settings'] = self::$_instance[$prefix] = new self( $prefix );
	}

}
