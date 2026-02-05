<?php

/**
 * Plugin class to set up custom Visual Composer element
 *
 * @link        https://flexxmarketing.nl/
 * @since       1.0.0
 *
 * @package     Flexx_Client_Plugin
 * @subpackage  Flexx_Client_Plugin/VC_Elements/Custom
 */

use Flexx_Client_Plugin\VC_Elements\Template\Flexx_VC_Element_Template;
use Flexx_Client_Plugin\Settings\VC_Settings;

// No direct access
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( __NAMESPACE__ . '\Flexx_VC_Global_Block' ) ) {

	class Flexx_VC_Global_Block extends Flexx_VC_Element_Template {

		/**
		 * Function: set the shortcode name
		 *
		 * @return string
		 *
		 */

		function shortcode_name() { return 'flexx-global-block'; }


		/**
		 * Function: Flexx_VC_Global_Block constructor
		 */

		public function __construct() {
			parent::__construct();
		}


		/**
		 * Function: map shortcode into Visual Composer (for use in content elements list)
		 *
		 * @throws \Exception
		 */

		public function vc_map_shortcode() {

			vc_map( array(
				"name"                    => "Vast blok",
				"base"                    => $this->shortcode_name(),
				"class"                   => "flexx-custom-vc-item",
				"description"             => "Hiermee voeg je een vast/vaker voorkomend blok toe.",
				"content_element"         => true,
				"show_settings_on_create" => true,
				"icon"                    => FLEXX_CP_IMG_URL . "vc-icon.svg",
				"category"                => "Flexx",
				"params"                  => array(

					array(
						"type"        => "dropdown",
						"heading"     => "Blok",
						"param_name"  => "block",
						"description" => "Kies het blok dat getoond moet worden.",
						"group"       =>"Algemeen",
						"value"       => VC_Settings::get_global_blocks(),
						"admin_label" => true,
					),

				)

			) );

		}


		/**
		 * Function: register the shortcode's HTML output
		 *
		 * @param $atts
		 * @param null $content
		 *
		 * @return mixed
		 */

		public function register_shortcode( $atts, $content = null ) {

			$block = '';

			extract( shortcode_atts( array(
				'block' => '',
			), $atts ) );

			if ( flexx_not_empty( $block ) ) {
				$content = get_post_field( 'post_content', $block );
				$content = apply_filters('the_content', $content);
			}

			return $content;

		}

	}

	new Flexx_VC_Global_Block();

}
