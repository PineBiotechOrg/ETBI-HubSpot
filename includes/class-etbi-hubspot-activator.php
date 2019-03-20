<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/JoshuaMcKendall/ETBI-Hubspot-Plugin/includes/
 * @since      1.0.0
 *
 * @package    ETBI_Hubspot
 * @subpackage ETBI_Hubspot/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    ETBI_Hubspot
 * @subpackage ETBI_Hubspot/includes
 * @author     Joshua McKendall <etbi-hubspot@joshuamckendall.com>
 */
class ETBI_Hubspot_Activator {

	/**
	 * Activate ETBI Hubspot
	 *
	 * @since    1.0.0
	 */
	public static function activate( ETBI_Hubspot_Activator $etbi_hubspot ) {
		$etbi_hubspot->create_options();		

		flush_rewrite_rules();
	}
	
	private function create_options() {

	}
	

}