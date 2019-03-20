<?php

/**
 * @link              https://joshuamckendall.github.io/etbi-hubspot
 * @since             1.0.0
 * @package           ETBI_Hubspot
 *
 * @wordpress-plugin
 * Plugin Name:       ETBI_Hubspot
 * Plugin URI:        https://joshuamckendall.github.io/etbi-hubspot
 * Description:       ETBI_Hubspot is a Hubspot client that integrates with edu.t-bio.info.
 * Version:           1.0.0
 * Author:            Joshua McKendall
 * Author URI:        https://joshuamckendall.github.io/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       etbi
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

if( ! class_exists('ETBI_Hubspot') ) {

	final class ETBI_Hubspot { 

		private static $_instance = null;

		public $_session = null;

		/**
		 * Contacter constructor.
		 */
		public function __construct() {

			$this->define_constants();
			$this->includes();
			$this->init_hooks(); 
		}

		public function define_constants() {
			$this->set_define( 'ETBI_HUBSPOT_PATH', plugin_dir_path( __FILE__ ) );
			$this->set_define( 'ETBI_HUBSPOT_URI', plugin_dir_url( __FILE__ ) );
			$this->set_define( 'ETBI_HUBSPOT_INC', ETBI_HUBSPOT_PATH . 'includes/' );
			$this->set_define( 'ETBI_HUBSPOT_INC_URI', ETBI_HUBSPOT_INC . 'includes/' );
			$this->set_define( 'ETBI_HUBSPOT_ASSETS_URI', ETBI_HUBSPOT_URI . 'assets/' );
			$this->set_define( 'ETBI_HUBSPOT_TEMPLATES_URI', ETBI_HUBSPOT_INC . 'templates/' );
			$this->set_define( 'ETBI_HUBSPOT_VER', '1.0.0' );
			$this->set_define( 'ETBI_HUBSPOT_MAIN_FILE', __FILE__ );
		}

		public function set_define( $name = '', $value = '' ) {
			if ( $name && ! defined( $name ) ) { 
				define( $name, $value );
			}
		}

		public function includes() {
			$this->_include( 'vendor/autoload.php' );
			$this->_include( 'includes/functions.php' );
			$this->_include( 'includes/class-etbi-hubspot-activator.php' );
			$this->_include( 'includes/class-etbi-hubspot-deactivator.php' );
			$this->_include( 'includes/class-etbi-hubspot-assets.php' );
			$this->_include( 'includes/class-etbi-hubspot-ajax.php' );
			$this->_include( 'includes/class-etbi-hubspot-settings.php' );
			$this->_include( 'includes/class-etbi-hubspot-session.php' );
			$this->_include( 'includes/class-etbi-hubspot-client.php' );
			$this->settings = ETBI_Hubspot_Settings::instance();

			if ( is_admin() ) {
				$this->_include( 'includes/admin/class-etbi-hubspot-admin.php' ); 
			}
		}

		/**
		 * Include single file
		 *
		 * @param $file
		 */
		public function _include( $file = null ) {
			if ( is_array( $file ) ) {
				foreach ( $file as $key => $f ) {
					if ( file_exists( ETBI_HUBSPOT_PATH . $f ) ) {
						require_once ETBI_HUBSPOT_PATH . $f;
					}
				}
			} else {
				if ( file_exists( ETBI_HUBSPOT_PATH . $file ) ) {
					require_once ETBI_HUBSPOT_PATH . $file;
				} elseif ( file_exists( $file ) ) {
					require_once $file;
				}
			}
		}


		/**
		 * Get the plugin url.
		 *
		 * @param string $sub_dir
		 *
		 * @return string
		 */
		public function plugin_url( $sub_dir = '' ) {

			return ETBI_HUBSPOT_URI . ( $sub_dir ? "{$sub_dir}" : '' );

		}

		/**
		 * Get the plugin path.
		 *
		 * @param string $sub_dir
		 *
		 * @return string
		 */
		public function plugin_path( $sub_dir = '' ) {

			return ETBI_HUBSPOT_PATH . ( $sub_dir ? "{$sub_dir}" : '' );

		}

		/**
		 * load text domain
		 * @return null
		 */
		public function text_domain() {
			// Get mo file
			$text_domain = 'etbi';
			$locale      = apply_filters( 'plugin_locale', get_locale(), $text_domain );
			$mo_file     = $text_domain . '-' . $locale . '.mo';
			// Check mo file global
			$mo_global = WP_LANG_DIR . '/plugins/' . $mo_file;
			// Load translate file
			if ( file_exists( $mo_global ) ) {
				load_textdomain( $text_domain, $mo_global );
			} else {
				load_textdomain( $text_domain, ETBI_HUBSPOT_PATH . '/languages/' . $mo_file );
			}
		}

		public function init_hooks() {
			register_activation_hook( __FILE__, array( 'ETBI_Hubspot', 'activate_etbi_hubspot' ) );
			register_deactivation_hook( __FILE__, array( 'ETBI_Hubspot', 'deactivate_etbi_hubspot' ) );

			//add_action( 'init', array( $this, 'register_blocks' ) );
			add_action( 'plugins_loaded', array( $this, 'loaded' ) );
		}

		static function activate_etbi_hubspot() {
			$etbi_hubspot = new ETBI_Hubspot_Activator;
			$etbi_hubspot::activate($etbi_hubspot);
		}

		static function deactivate_etbi_hubspot() {
			ETBI_Hubspot_Deactivator::deactivate();
		}

		/**
		 * Load components when plugin loaded
		 */
		public function loaded() {
			// load text domain
			$this->text_domain();
			$this->_session = new ETBI_Hubspot_Session();

			do_action( 'etbi_hubspot_loaded', $this );
		}

		/**
		 * get instance class
		 * @return Contacter
		 */
		public static function instance() {
			if ( ! empty( self::$_instance ) ) {
				return self::$_instance;
			}

			return self::$_instance = new self();
		}

	}

	if ( ! function_exists( 'ETBI_Hubspot' ) ) {

		function ETBI_Hubspot() {
			return ETBI_Hubspot::instance();
		}

	}
	add_action( 'init', 'ETBI_Hubspot' );
}

$GLOBALS['ETBI_Hubspot'] = ETBI_Hubspot();