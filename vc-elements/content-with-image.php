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
                "category"                => "Flexxmarketing",
                'admin_enqueue_js'        => FLEXX_CP_ASSETS_URL . '/js/vc-element-view.js',
                'admin_enqueue_css'       => FLEXX_CP_ASSETS_URL . '/css/vc-element-view.css',
                'js_view'                 => 'VcCustomElementView',
                "params"                  => array(

                    array(
                        "type"        => "dropdown",
                        "heading"     => "Vormgeving",
                        "param_name"  => "layout",
                        "description" => "Kies een van de opties.",
                        "group"       => "Algemeen",
                        "value"       => array(
                            "Afbeelding(en) links, tekst rechts" => "",
                            "Afbeelding(en) rechts, tekst links" => "reverse",
                        ),
                        "save_always" => true,
                        "admin_label" => true,
                    ),

                    array(
                        "type"        => "textfield",
                        "heading"     => "Titel",
                        "param_name"  => "title",
                        "description" => "Vul hier de titel in.",
                        "group"       => "Algemeen",
                        "admin_label" => true,
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
                        "type"        => "attach_images",
                        "heading"     => "Afbeelding(en)",
                        "param_name"  => "images",
                        "description" => "Selecteer of upload een afbeelding.",
                        "group"       => "Algemeen",
                        "admin_label" => true,
                    ),

                    array(
                        "type"        => "vc_link",
                        "heading"     => "Knop (optioneel)",
                        "param_name"  => "link",
                        "description" => "Kies de knop instellingen.",
                        "group"       => "Algemeen",
                        "admin_label" => true,
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

            $html = $layout = $title = $images = $link = '';

            extract( shortcode_atts( array(
                'layout' => '',
                'title'  => '',
                'images'  => '',
                'link'   => '',
            ), $atts ) );

            $buttons_html = '';
            if ( $link ) {
                $buttons_html .= '<div class="buttons">';
                if ( $link ) {
                    $link = vc_build_link( $link );

                    $icon_html = '';
                    if ( str_contains( $link['url'], 'goto' ) ) {
                        $icon_html = flexx_get_icon( 'arrow-down', '', false );
                    } else {
                        $icon_html = flexx_get_icon( 'arrow-right-alt', '', false);
                    }

                    $buttons_html .= '
                        <a href="' . $link['url'] . '" class="btn btn--primary" target="' . $link['target'] . '">
                            <span class="label">' . $link['title'] . '</span>
                            ' . $icon_html . '
                        </a>
                    ';
                }
                $buttons_html .= '</div>';
            }

            $images_html = '';
            $count = 1;
            $images = explode( ',', $images );
            foreach ( $images as $img_id ) {
                if ( $count > 2 ) {
                    break;
                }
                $images_html .= '
                    <picture class="image-holder" data-inview>
                        ' . flexx_srcset_image( $img_id, 'flexx-extra-large', false ) . '
                    </picture>
                ';
                $count++;
            }

            $html .= '
				<div class="media-with-content' . ( $layout === 'reverse' ? ' media-with-content--reverse' : '' ) . ' wpb_content_element">
				
				    ' . ( count($images) > 1 ? '<div class="media-with-content__title" data-inview><h2 class="title">' . $title . '</h2></div>' : '') . '
					
					<div class="inner-wrapper">
                        <div class="media-with-content__image' . ( count( $images ) > 1 ? 's' : '' ) . '">
                            ' . $images_html . '
                        </div>
                        
                        <div class="media-with-content__content" data-inview>
                        ' . ( count($images) === 1 ? '<h2 class="title">' . $title . '</h2>' : '') . '
                            ' . wpautop( $content ) . '
                            ' . $buttons_html . '
                        </div>
                    </div>
					
				</div>
			';

            return $html;

        }

    }

    new Flexx_VC_Image_With_Content();
}
