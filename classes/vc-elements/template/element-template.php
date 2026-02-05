<?php
/**
 * Abstract plugin class to handle base setup for custom Visual Composer elements
 *
 * @link        https://flexxmarketing.nl/
 * @since       1.0.0
 *
 * @package     Flexx_Client_Plugin
 * @subpackage  Flexx_Client_Plugin/VC_Elements/Base
 */

namespace Flexx_Client_Plugin\VC_Elements\Template;

// No direct access
if ( ! defined( 'WPINC' ) ) {
	die;
}

abstract class Flexx_VC_Element_Template {

	/**
	 * Function: Flexx_VC_Element_Base constructor
	 */

	public function __construct() {

		add_shortcode( $this->shortcode_name() , array( $this, 'register_shortcode' ) );
		add_action( 'vc_before_init', array( $this, 'vc_map_shortcode' ) );

	}


	/**
	 * Functions: register abstract functions to force use of these in child elements
	 */
	abstract function shortcode_name();
	public abstract function register_shortcode( $atts, $content = false );
	public abstract function vc_map_shortcode();

}
