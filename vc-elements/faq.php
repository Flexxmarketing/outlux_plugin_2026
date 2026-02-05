<?php
/**
 * Plugin class to set up custom Visual Composer element with toggles
 *
 * @link        https://flexxmarketing.nl/
 * @since       1.0.0
 *
 * @package     Flexx_Client_Plugin
 * @subpackage  Flexx_Client_Plugin/VC_Elements/Custom
 */

use Flexx_Client_Plugin\VC_Elements\Template\Flexx_VC_Element_Template;

if ( ! defined( 'WPINC' ) ) {
    die;
}

if ( ! class_exists( __NAMESPACE__ . '\Flexx_VC_FAQ' ) ) {

    class Flexx_VC_FAQ extends Flexx_VC_Element_Template {

        /**
         * Get the shortcode name
         *
         * @return string
         */
        function shortcode_name(): string {
            return 'flexx-faq';
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
                "name"                    => "Veelgestelde vragen",
                "base"                    => $this->shortcode_name(),
                "class"                   => "flexx-custom-vc-item",
                "description"             => "Voeg een blok toe met veelgestelde vragen die uitklapbaar zijn.",
                "content_element"         => true,
                "show_settings_on_create" => true,
                "icon"                    => 'vc_custom_flexx_icon',
                "category"                => "Flexx",
                'admin_enqueue_css'       => FLEXX_CP_ASSETS_URL . '/css/vc-element-view.css',
                'admin_enqueue_js'        => FLEXX_CP_ASSETS_URL . '/js/vc-element-view.js',
                'js_view'                 => 'VcCustomElementView',
                "params"                  => array(

                    array(
                        "type"        => "param_group",
                        "heading"     => "Veelgestelde vragen",
                        "param_name"  => "faqs",
                        "description" => "Voeg hier de veelgestelde vragen toe.",
                        "group"       => "Algemeen",
                        "params"      => array(

                            array(
                                "type"        => "textfield",
                                "heading"     => "Titel",
                                "param_name"  => "title",
                                "description" => "Vul hier de titel in.",
                                "admin_label" => true,
                            ),

                            array(
                                "type"        => "textarea",
                                "heading"     => "Tekst",
                                "param_name"  => "text",
                                "description" => "De tekst van de vraag en het antwoord.",
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

            $html = $faqs = $faqs_html = '';

            extract( shortcode_atts( array(
                'faqs'  => '',
            ), $atts ) );

            $faqs  = vc_param_group_parse_atts( $faqs );

            $faq_count = count( $faqs );
            $half = ceil( $faq_count / 2 );
            $columns = array_chunk( $faqs, $half );

            foreach ( $columns as $column ) {
                $faqs_html .= '<div class="faq-column" data-group-reveal>';
                foreach ( $column as $faq ) {
                    $faq['title'] = ! empty( $faq['title'] ) ? flexx_replace( $faq['title'], '*', 'strong' ) : '';

                    $faqs_html .= '
                        <div class="loop-faq-item" data-inview>
                            <div class="loop-faq-item__summary" data-cursor-icon="plus">
                                <h3 class="title">' . $faq['title'] . '</h3>
                                ' . flexx_get_icon( 'chevron-down', '', false ) . '
                            </div>
                            <div class="loop-faq-item__content" style="display: none;">
                                ' . wpautop( $faq['text'] ) . '
                            </div>
                        </div>
                    ';
                }
                $faqs_html .= '</div>';
            }

            $html .= '
                <div class="faq-list wpb_content_element">
                    <div class="faq-list__columns">
                        ' . $faqs_html . '
                    </div>
                </div>
            ';

            return $html;
        }
    }

    new Flexx_VC_FAQ();
}