<?php

/**
 * Plugin class to handle registration of the custom Flexx VC elements
 *
 * @link        https://flexxmarketing.nl/
 * @since       1.0.0
 *
 * @package     Flexx_Client_Plugin
 * @subpackage  Flexx_Client_Plugin/ContentElements
 */

namespace Flexx_Client_Plugin\VC_Elements;

// No direct access
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Register_Elements {

	/**
	 * Function: Flexx_RegisterElements constructor
	 */

	public function __construct() {
		$this->get_custom_elements();
	}


	/**
	 * Function: get custom elements by scanning the /custom folder for *.php files
	 *
	 * @since 1.0.0
	 */

	public function get_custom_elements() {
		foreach ( glob( FLEXX_CP_PLUGIN_DIR . "/vc-elements/*.php" ) as $file ) {
			if ( ! empty( $file ) ) {
				require_once $file;
			}
		}
	}

}
