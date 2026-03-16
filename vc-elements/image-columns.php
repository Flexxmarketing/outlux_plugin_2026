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

// No direct access
if ( ! defined( 'WPINC' ) ) {
    die;
}

if ( ! class_exists( __NAMESPACE__ . '\Flexx_VC_Image_Columns' ) ) {

    class Flexx_VC_Image_Columns extends Flexx_VC_Element_Template {

        /**
         * Get the shortcode name
         *
         * @return string
         */
        function shortcode_name(): string {
            return 'flexx-image-columns';
        }

        /**
         * Class constructor
         */
        public function __construct() {
            parent::__construct();
        }


        /**
         * Map shortcode into Visual Composer (for use in content elements list)
         *
         * @throws \Exception
         */
        public function vc_map_shortcode() {

            vc_map( array(
                "name"                    => "2 Afbeeldingen",
                "base"                    => $this->shortcode_name(),
                "class"                   => "flexx-custom-vc-item",
                "description"             => "Hiermee voeg je een blok toe met twee kolommen met een afbeelding.",
                "content_element"         => true,
                "show_settings_on_create" => true,
                "icon"                    => FLEXX_CP_IMG_URL . 'vc-icon.svg',
                "category"                => "Flexxmarketing",
                'admin_enqueue_js'        => FLEXX_CP_ASSETS_URL . '/js/vc-element-view.js',
                'js_view'                 => 'VcCustomElementView',
                "params"                  => array(

                    array(
                        "type"        => "attach_image",
                        "heading"     => "Afbeelding",
                        "param_name"  => "image_one",
                        "description" => "Selecteer of upload een afbeelding.",
                        "group"       => "Algemeen",
                    ),

                    array(
                        "type"        => "attach_image",
                        "heading"     => "Afbeelding",
                        "param_name"  => "image_two",
                        "description" => "Selecteer of upload een afbeelding.",
                        "group"       => "Algemeen",
                    ),

                    array(
                        "type"        => "dropdown",
                        "heading"     => "Aspect ratio",
                        "param_name"  => "aspect_ratio",
                        "description" => "Kies de verhouding voor beide afbeeldingen.",
                        "group"       => "Algemeen",
                        "value"       => array(
                            "890 / 595 (Standaard)" => "890-595",
                            "50 / 75"   => "50-75",
                        ),
                        "save_always" => true,
                    ),

                )

            ) );

        }


        /**
         * Register the shortcode's HTML output
         *
         * @param array $atts Shortcode attributes.
         * @param string|null $content Shortcode content.
         *
         * @return string
         */
        public function register_shortcode( $atts, $content = null ) {

            $html = $image_one = $image_two = $aspect_ratio = '';

            extract( shortcode_atts( array(
                'image_one'    => '',
                'image_two'    => '',
                'aspect_ratio' => '',
            ), $atts ) );

            $aspect_ratio_class = $aspect_ratio ? ' aspect-ratio-' . sanitize_html_class( $aspect_ratio ) : '';

            // Determine correct media output (image or inline video) for first column
            $media_one_html = '';
            if ( $image_one ) {
                $media_one_html = flexx_srcset_image( $image_one, 'flexx-huge', false );
            }

            // Determine correct media output (image or inline video) for second column
            $media_two_html = '';
            if ( $image_two ) {
                $media_two_html = flexx_srcset_image( $image_two, 'flexx-huge', false );
            }

            $html .= '
				<div class="image-columns wpb_content_element">
					
					<div class="image-columns__column">
						<div class="image-wrapper parallax-img" data-parallax-speed="-10">
							<div class="image-holder' . $aspect_ratio_class . '" data-inview>
								' . $media_one_html . '
							</div>
						</div>
					</div>
					
					<div class="image-columns__column">
						<div class="image-wrapper parallax-img" data-parallax-speed="10">
							<div class="image-holder' . $aspect_ratio_class . '" data-inview>
								' . $media_two_html . '
							</div>
						</div>
					</div>
					
				</div>
			';

            return $html;

        }

    }

    new Flexx_VC_Image_Columns();
}
