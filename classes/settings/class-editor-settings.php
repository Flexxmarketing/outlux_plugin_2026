<?php
/**
 * Plugin class to handle custom editor (TinyMCE) settings
 *
 * @link        https://flexxmarketing.nl/
 * @since       1.0.0
 *
 * @package     Flexx_Client_Plugin
 * @subpackage  Flexx_Client_Plugin/Functions
 */

namespace Flexx_Client_Plugin\Settings;

// No direct access
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Editor_Settings {

	/**
	 * @var $style_formats array
	 */

	private $style_formats;


	/**
	 * Function: Editor_Settings constructor
	 *
	 * @param $style_formats
	 */

	public function __construct( $style_formats ) {

		$this->style_formats = $style_formats;

		add_filter( 'mce_buttons_2', array( $this, 'add_styles_button' ) );
		add_filter( 'tiny_mce_before_init', array( $this, 'add_custom_styles' ) );
		add_filter( 'mce_css', array($this, 'add_custom_css' ) );
	}


	/**
	 * Function: add a styles button so that custom text styles can be applied
	 *
	 * @param $buttons
	 * @return mixed
	 *
	 * @since 1.0.0
	 */

	public function add_styles_button( $buttons ) {
		array_unshift( $buttons, 'styleselect' );

		return $buttons;
	}


	/**
	 * Function: add custom styles to the styles selector
	 *
	 * @param $settings
	 *
	 * @return mixed
	 *
	 * @since 1.0.0
	 */

	public function add_custom_styles( $settings ) {

		// Get style formats
		$style_formats = $this->style_formats;

		// Check if custom style formats are set, otherwise just return the base settings
		if ( ! empty( $style_formats ) ) {

			if ( ! isset( $settings['style_formats'] ) ) {
				$settings['style_formats'] = '';
			}

			$settings['style_formats'] .= json_encode( $style_formats );

		}

		return $settings;

	}


	/**
	 * Function: Add custom TinyMCE overrides stylesheet (for more accurate display of additional styles)
	 *
	 * @param $mce_css
	 * @return string
	 *
	 * @since 1.0.0
	 */

	public function add_custom_css( $mce_css ) {
		if ( ! empty( $mce_css ) ) {
			$mce_css .= ',';
		}

		$mce_css .= FLEXX_CP_CSS_URL . 'editor.css';

		return $mce_css;
	}

}
