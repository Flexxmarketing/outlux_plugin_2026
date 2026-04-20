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

if ( ! class_exists( __NAMESPACE__ . '\Flexx_VC_Experience_Gallery' ) ) {

    class Flexx_VC_Experience_Gallery extends Flexx_VC_Element_Template {

        /**
         * Get the shortcode name
         *
         * @return string
         */
        function shortcode_name(): string {
            return 'flexx-experience-gallery';
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
                "name"                    => "Ervaring gallerij",
                "base"                    => $this->shortcode_name(),
                "class"                   => "flexx-custom-vc-item",
                "description"             => "Hiermee kun je een ervaring gallery maken.",
                "content_element"         => true,
                "show_settings_on_create" => true,
                "icon"                    => FLEXX_CP_IMG_URL . 'vc-icon.svg',
                "category"                => "Flexxmarketing",
                'admin_enqueue_js'        => FLEXX_CP_ASSETS_URL . '/js/vc-element-view.js',
                'admin_enqueue_css'       => FLEXX_CP_ASSETS_URL . '/css/vc-element-view.css',
                'js_view'                 => 'VcCustomElementView',
                "params"                  => array(

                    array(
                        "type"        => "textarea_html",
                        "heading"     => "Tekst",
                        "param_name"  => "content",
                        "description" => "Vul de tekst in.",
                        "group"       => "Algemeen",
                        "holder"      => "div",
                    ),

                    array(
                        "type"        => "vc_link",
                        "heading"     => "Knop",
                        "param_name"  => "link",
                        "description" => "Vul hier de link in voor de knop.",
                        "group"       => "Algemeen",
                        "admin_label" => true,
                    ),

                    array(
                        "type"        => "attach_images",
                        "heading"     => "Gallerij afbeeldingen",
                        "param_name"  => "images",
                        "description" => "Kies of upload hier de afbeeldingen die je wilt gebruiken in de gallery.",
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

            $html = $link = $images = '';

            extract( shortcode_atts( array(
                'link'   => '',
                'images' => '',
            ), $atts ) );

            $images_html = '';
            $images = explode( ',', $images );
            $count = 0;
            foreach ( $images as $image ) {
                $images_html .= '
                    <div class="image-wrapper parallax-img" data-parallax-speed="' . ( 15 + rand(15, 50) ) . '">
                        <picture class="image-holder" data-inview>
                            ' . flexx_srcset_image( $image, 'flexx-ultra-large', false ) . '
                        </picture>
                    </div>
                ';
                $count++;
            }

            $link_html = '';
            $link = vc_build_link( $link );
            if ( ! empty( $link['url'] ) ) {

                $icon = 'arrow-right-alt';
                if ( str_contains( $link['url'], 'goto' ) ) {
                    $icon = 'arrow-down';
                }

                $link_html = '<div class="button">' . flexx_button( array(
                    'text'    => $link['title'] ?: 'Lees meer',
                    'url'     => $link['url'],
                    'class'   => 'btn btn--cta',
                    'icon'    => $icon,
                    'echo'    => false,
                ) ) . '</div>';
            }

            $html .= '
				<div class="experience-gallery wpb_content_element">
				    <div class="experience-gallery__content" data-inview>
				        <div class="body">
				            ' . wpautop( $content ) . '
                        </div>
				        ' . $link_html . '
                    </div>
					
					<div class="experience-gallery__images">
					    ' . $images_html . '
                    </div>
				</div>
			';

            return $html;

        }

    }

    new Flexx_VC_Experience_Gallery();
}
