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

if ( ! class_exists( __NAMESPACE__ . '\Flexx_VC_Featured_Service_Bento' ) ) {

    class Flexx_VC_Featured_Service_Bento extends Flexx_VC_Element_Template {

        /**
         * Get the shortcode name
         *
         * @return string
         */
        function shortcode_name(): string {
            return 'flexx-featured-service-bento';
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
                "name"                    => "Uitgelichte dienst - Bento",
                "base"                    => $this->shortcode_name(),
                "class"                   => "flexx-custom-vc-item",
                "description"             => "Hiermee kun je een uitgelichte dienst in bento stijl toevoegen.",
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
                'link'         => '',
                'images'       => '',
            ), $atts ) );

            $count = 0;
            $bento_html = '';
            $images = explode( ',', $images );
            foreach ( $images as $image ) {

                if ( $count === 1 ) {
                    $link = vc_build_link( $link );
                    if ( $link['url'] ) {
                        $button_html = '<div class="button">' . flexx_button( array(
                            'text'  => $link['title'],
                            'url'   => $link['url'],
                            'class' => 'btn btn--outline',
                            'icon'  => 'arrow-right-alt',
                            'echo'  => false,
                        ) ) . '</div>';

                        $bento_html .= '
                            <div class="item-wrap">
                                <div class="loop-bento-content" data-inview>
                                    <div class="body">
                                        ' . wpautop( $content ) . '
                                    </div>
                                    ' . $button_html . '
                                </div>
                            </div>
                        ';
                    }
                }

                $bento_html .= '
                    <div class="item-wrap">
                        <div class="loop-bento-image" data-inview>
                            ' . flexx_srcset_image( $image, 'flexx-ultra-large' ) . '
                        </div>
                    </div>
                ';
                $count++;
            }

            $html .= '
				<div class="featured-service-bento wpb_content_element">
					' . $bento_html . '
				</div>
			';

            return $html;

        }

    }

    new Flexx_VC_Featured_Service_Bento();
}
