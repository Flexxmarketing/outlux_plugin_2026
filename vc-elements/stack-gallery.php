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

if ( ! class_exists( __NAMESPACE__ . '\Flexx_VC_Stack_Gallery' ) ) {

    class Flexx_VC_Stack_Gallery extends Flexx_VC_Element_Template {

        /**
         * Get the shortcode name
         *
         * @return string
         */
        function shortcode_name(): string {
            return 'flexx-stack-gallery';
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
                "name"                    => "Rolodex gallerij",
                "base"                    => $this->shortcode_name(),
                "class"                   => "flexx-custom-vc-item",
                "description"             => "Hiermee kun je een rolodex gallery maken.",
                "content_element"         => true,
                "show_settings_on_create" => true,
                "icon"                    => FLEXX_CP_IMG_URL . 'vc-icon.svg',
                "category"                => "Flexxmarketing",
                'admin_enqueue_js'        => FLEXX_CP_ASSETS_URL . '/js/vc-element-view.js',
                'admin_enqueue_css'       => FLEXX_CP_ASSETS_URL . '/css/vc-element-view.css',
                'js_view'                 => 'VcCustomElementView',
                "params"                  => array(

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

            $html = $images = '';

            wp_enqueue_script('flexx-vendor-swiper');
            wp_enqueue_script('flexx-custom-swiper');

            extract( shortcode_atts( array(
                'images' => '',
            ), $atts ) );

            $images_html = '';
            $images = explode( ',', $images );
            foreach ( $images as $image ) {
                $images_html .= '
                    <div class="swiper-slide">
                        <div class="loop-stack-image">
                            ' . flexx_srcset_image( $image, 'flexx-huge' ) . '
                        </div>
                    </div>
                ';
            }

            $html .= '
				<div class="stack-gallery wpb_content_element">
					<div class="stack-gallery__inner" data-inview>
					    <div class="wide-container">
					        <div class="swiper stack-swiper">
                                <div class="swiper-wrapper">
                                    ' . $images_html . '
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="stack-gallery__pagination" data-inview>
                        <div class="stack-swiper-pagination"></div>
                    </div>
				</div>
			';

            return $html;

        }

    }
    new Flexx_VC_Stack_Gallery();
}
