<?php

/**
 * The ETBI Hubspot Frontend Assets class
 *
 * @link       https://github.com/JoshuaMcKendall/ETBI-Hubspot-Plugin/includes/
 * @since      1.0.0
 *
 * @package    ETBI Hubspot
 * @subpackage ETBI Hubspot/includes
 */

/**
 * The ETBI Hubspot Frontend Assets class
 *
 * This class loads the front-end assets for the contact form.
 *
 * @since      1.0.0
 * @package    ETBI Hubspot
 * @subpackage ETBI Hubspot/includes
 * @author     Joshua McKendall <etbi-hubspot@joshuamckendall.com>
 */

defined( 'ABSPATH' ) || exit;

class ETBI_Hubspot_Frontend_Assets {

	/**
	 * Register scripts
	 * @since 1.4.1.4
	 */
	public static function init() {
		add_action( 'etbi_hubspot_before_enqueue_scripts', array( __CLASS__, 'register_scripts' ), 10 );
	}

	/**
	 * Register scripts
	 *
	 * @param type $hook
	 */
	public static function register_scripts( $hook ) {

		ETBI_Hubspot_Assets::register_script( 'etbi-hubspot-gallery-block-js', 	ETBI_HUBSPOT_ASSETS_URI . 'js/public/scripts.js', array( 'jquery' ), ETBI_HUBSPOT_VER );
		ETBI_Hubspot_Assets::register_style(  'etbi-hubspot-gallery-block-style', ETBI_HUBSPOT_ASSETS_URI . 'css/public/style.css', array(), ETBI_HUBSPOT_VER );

	}

}

ETBI_Hubspot_Frontend_Assets::init();
