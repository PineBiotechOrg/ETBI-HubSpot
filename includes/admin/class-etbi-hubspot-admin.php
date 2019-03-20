<?php
/**
 * ETBI Hubspot Admin class
 *
 * @author        Joshua McKendall
 * @package       ETBI_Hubspot/Class
 * @version       1.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit;

class ETBI_Hubspot_Admin {

	public function __construct() {

		$this->_includes();
		
	}

	private function _includes() {

		include( ETBI_HUBSPOT_PATH . 'includes/admin/class-etbi-hubspot-admin-settings.php' );
	}

}

new ETBI_Hubspot_Admin();