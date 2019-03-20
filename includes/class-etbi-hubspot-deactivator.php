<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://github.com/JoshuaMcKendall/ETBI-Hubspot-Plugin/includes/
 * @since      1.0.0
 *
 * @package    ETBI_Hubspot
 * @subpackage ETBI_Hubspot/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    ETBI_Hubspot
 * @subpackage ETBI_Hubspot/includes
 * @author     Joshua McKendall <etbi-hubspot@joshuamckendall.com>
 */
class ETBI_Hubspot_Deactivator {

	/**
	 * Short Description.
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		flush_rewrite_rules();

	}

}
