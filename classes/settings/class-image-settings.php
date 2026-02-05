<?php
/**
 * Plugin class to handle custom image settings
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

class Image_Settings {

	/**
	 * @var $image_sizes array
	 */

	private $image_sizes;


	/**
	 * Function: Image_Settings constructor
	 *
	 * @param $image_sizes
	 */

	public function __construct( $image_sizes ) {

		$this->image_sizes = $image_sizes;

		add_action('init', array($this, 'flexx_image_sizes'));

	}


	/**
	 * Function: register custom FLEXX image sizes
	 *
	 * @since 1.0.0
	 */

	public function flexx_image_sizes() {
		$image_sizes = $this->image_sizes;

		foreach($image_sizes as $k => $v) {
			add_image_size('flexx-' . $k, $v['width'], $v['height'], $v['crop']);
		}
	}


	/**
	 * Function: returns the custom FLEXX image sizes
	 *
	 * @return array
	 *
	 * @since 1.0.0
	 */

	public static function flexx_get_image_sizes() {
		$image_sizes    = get_intermediate_image_sizes();
		$flexx_image_sizes = array();

		foreach ( $image_sizes as $size ) {
			if ( strpos( $size, 'flexx-' ) !== false ) {

				$name = substr( $size, 3 );

				switch ( $name ) {
					case 'tiny':
						$flexx_image_sizes['Extra klein (200px)'] = $size;
						break;
					case 'small':
						$flexx_image_sizes['Klein (350px)'] = $size;
						break;
					case 'medium':
						$flexx_image_sizes['Middel (700px)'] = $size;
						break;
					case 'large':
						$flexx_image_sizes['Groter (1000px)'] = $size;
						break;
					case 'extra-large':
						$flexx_image_sizes['Extra groot (1400px)'] = $size;
						break;
					case 'ultra-large':
						$flexx_image_sizes['Ultra groot (2000px)'] = $size;
						break;
					default:
						$flexx_image_sizes[ $size ] = $size;
				}

			}
		}

		$flexx_image_sizes = array_reverse( $flexx_image_sizes, false );

		return $flexx_image_sizes;
	}

}
