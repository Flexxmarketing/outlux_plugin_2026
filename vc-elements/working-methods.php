<?php
/**
 * Plugin class to set up custom Visual Composer element for working methods (werkwijzen)
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

if ( ! class_exists( __NAMESPACE__ . '\Flexx_VC_Working_Methods' ) ) {

    class Flexx_VC_Working_Methods extends Flexx_VC_Element_Template {

        /**
         * Get the shortcode name
         *
         * @return string
         */
        function shortcode_name(): string {
            return 'flexx-working-methods';
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
                "name"                    => "Werkwijzen",
                "base"                    => $this->shortcode_name(),
                "class"                   => "flexx-custom-vc-item",
                "description"             => "Toont een overzicht van de werkwijzen in stappen.",
                "content_element"         => true,
                "show_settings_on_create" => true,
                "icon"                    => FLEXX_CP_IMG_URL . 'vc-icon.svg',
                "category"                => "Flexxmarketing",
                'admin_enqueue_js'        => FLEXX_CP_ASSETS_URL . '/js/vc-element-view.js',
                'admin_enqueue_css'       => FLEXX_CP_ASSETS_URL . '/css/vc-element-view.css',
                'js_view'                 => 'VcCustomElementView',
                "params"                  => array(

                    array(
                        "type"        => "param_group",
                        "heading"     => "Werkwijzen",
                        "param_name"  => "steps",
                        "description" => "Voeg hier de verschillende stappen van de werkwijze toe.",
                        "group"       => "Stappen",
                        "params"      => array(

                            array(
                                "type"        => "textfield",
                                "heading"     => "Titel",
                                "param_name"  => "title",
                                "description" => "Vul hier de titel van de stap in.",
                                "group"       => "Algemeen",
                                "admin_label" => true,
                            ),

                            array(
                                "type"        => "textarea",
                                "heading"     => "Tekst",
                                "param_name"  => "text",
                                "description" => "Korte toelichting bij deze stap.",
                                "group"       => "Algemeen",
                            ),

                            array(
                                "type"        => "attach_image",
                                "heading"     => "Afbeelding",
                                "param_name"  => "image",
                                "description" => "Selecteer of upload de afbeelding die bij deze stap hoort.",
                                "group"       => "Algemeen",
                            ),

                        ),
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

            $html  = '';
            $steps = '';

            extract( shortcode_atts( array(
                'steps' => '',
            ), $atts ) );

            $steps_items = vc_param_group_parse_atts( $steps );

            if ( empty( $steps_items ) || ! is_array( $steps_items ) ) {
                return '';
            }

            $steps_html = '';
            $count      = 1;

            foreach ( $steps_items as $step ) {

                $title = isset( $step['title'] ) ? $step['title'] : '';
                $text  = isset( $step['text'] ) ? $step['text'] : '';
                $image = isset( $step['image'] ) ? $step['image'] : '';

                if ( ! $title && ! $text && ! $image ) {
                    continue;
                }

                $is_reverse = $count % 2 === 0;

                $image_html = '';
                if ( $image ) {
                    $image_html = '
                        <div class="working-methods-item__image" data-inview>
                            <picture class="image-holder">
                                ' . flexx_srcset_image( $image, 'flexx-extra-large', false ) . '
                            </picture>
                        </div>
                    ';
                }

                $steps_html .= '
                    <div class="working-methods-item' . ( $is_reverse ? ' working-methods-item--reverse' : '' ) . '" data-group-reveal>
                        <div class="inner-wrapper">
                            <div class="working-methods-item__content" data-inview>
                                <div class="step-indicator">
                                    <span class="step-number">' . $count . '</span>
                                </div>
                                <div class="bopy">
                                    <h3 class="title">' . $title . '</h3>
                                    ' . ( $text ? wpautop( $text ) : '' ) . '
                                </div>
                            </div>
                            ' . $image_html . '
                        </div>
                    </div>
                ';

                $count ++;
            }

            if ( ! $steps_html ) {
                return '';
            }

            $html .= '
                <div class="working-methods wpb_content_element">
                    <div class="working-methods__inner">
                        ' . $steps_html . '
                    </div>
                </div>
            ';

            return $html;
        }
    }

    new Flexx_VC_Working_Methods();
}

