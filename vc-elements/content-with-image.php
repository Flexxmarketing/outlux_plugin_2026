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

if ( ! class_exists( __NAMESPACE__ . '\Flexx_VC_Image_With_Content' ) ) {

    class Flexx_VC_Image_With_Content extends Flexx_VC_Element_Template {

        /**
         * Get the shortcode name
         *
         * @return string
         */
        function shortcode_name(): string {
            return 'flexx-image-with-content';
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
                "name"                    => "Afbeelding met tekst",
                "base"                    => $this->shortcode_name(),
                "class"                   => "flexx-custom-vc-item",
                "description"             => "Hiermee voeg je een blok toe met een afbeelding en tekst.",
                "content_element"         => true,
                "show_settings_on_create" => true,
                "icon"                    => FLEXX_CP_IMG_URL . 'vc-icon.svg',
                "category"                => "Flexx",
                'admin_enqueue_js'        => FLEXX_CP_ASSETS_URL . '/js/vc-element-view.js',
                'js_view'                 => 'VcCustomElementView',
                "params"                  => array(

                    array(
                        "type"        => "dropdown",
                        "heading"     => "Vormgeving",
                        "param_name"  => "layout",
                        "description" => "Kies een van de opties.",
                        "group"       => "Algemeen",
                        "value"       => array(
                            "Afbeelding links, tekst rechts" => "",
                            "Afbeelding rechts, tekst links" => "reverse",
                        ),
                        "save_always" => true,
                    ),

                    array(
                        "type"        => "textarea_html",
                        "heading"     => "Tekst",
                        "param_name"  => "content",
                        "description" => "Vul de tekst in.",
                        "group"       => "Algemeen",
                        "holder"      => "div",
                    ),

                    array(
                        "type"        => "attach_image",
                        "heading"     => "Afbeelding",
                        "param_name"  => "image",
                        "description" => "Selecteer of upload een afbeelding.",
                        "group"       => "Algemeen",
                    ),

                    array(
                        "type"        => "vc_link",
                        "heading"     => "Knop (optioneel)",
                        "param_name"  => "button_one",
                        "description" => "Kies de knop instellingen.",
                        "group"       => "Algemeen",
                    ),

                    array(
                        "type"        => "vc_link",
                        "heading"     => "Knop (optioneel)",
                        "param_name"  => "button_two",
                        "description" => "Kies de knop instellingen.",
                        "group"       => "Algemeen",
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

            $html = $layout = $media_type = $image = $button_one = $button_two = '';

            extract( shortcode_atts( array(
                'layout'     => '',
                'image'      => '',
                'button_one' => '',
                'button_two' => '',
            ), $atts ) );

            $buttons_html = '';
            if ( $button_one || $button_two ) {
                $buttons_html .= '<div class="buttons">';
                if ( $button_one ) {
                    $button_one = vc_build_link( $button_one );
                    $buttons_html .= '<a href="' . $button_one['url'] . '" class="btn btn--secondary" target="' . $button_one['target'] . '"><span class="label">' . $button_one['title'] . '</span></a>';
                }
                if ( $button_two ) {
                    $button_two = vc_build_link( $button_two );
                    $buttons_html .= '<a href="' . $button_two['url'] . '" class="btn btn--outline-white" target="' . $button_two['target'] . '"><span class="label">' . $button_two['title'] . '</span></a>';
                }
                $buttons_html .= '</div>';
            }

            $html .= '
				<div class="media-with-content media-with-content--' . $media_type . ( $layout === 'reverse' ? ' media-with-content--reverse' : '' ) . ' wpb_content_element">
					
					<div class="media-with-content__media">
						<div class="media-holder" data-reveal>
							' . flexx_srcset_image($image, 'flexx-large') . '
						</div>
					</div>
					
					<div class="media-with-content__content" data-reveal>
						' . wpautop( $content ) . '
						' . $buttons_html . '
					</div>
					
				</div>
			';

            return $html;

        }

    }

    new Flexx_VC_Image_With_Content();
}
