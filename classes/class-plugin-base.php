<?php

/**
 * Main plugin class for Flexx client plugin
 */

namespace Flexx_Client_Plugin;

use Flexx_Client_Plugin\Settings;
use Flexx_Client_Plugin\VC_Elements;

// No direct access
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Plugin_Base {

	/**
	 * Flexx_Client_Plugin constructor
	 */

	public function __construct() {

		// Register custom Visual Composer elements
		$this->custom_vc_elements();

	}


	/**
	 * Function: register custom Visual Composer elements
	 *
	 * @since 1.0.0
	 */

	private function custom_vc_elements() {
		new VC_Elements\Register_Elements();
	}

}
