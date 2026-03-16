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
use Flexx_Client_Plugin\Settings\VC_Settings;

// No direct access
if ( ! defined( 'WPINC' ) ) {
    die;
}

if ( ! class_exists( __NAMESPACE__ . '\Flexx_VC_Button_Block' ) ) {

    class Flexx_VC_Button_Block extends Flexx_VC_Element_Template {

        /**
         * Function: set the shortcode name
         *
         * @return string
         *
         */

        function shortcode_name() {
            return 'flexx-button-block';
        }


        /**
         * Function: Flexx_VC_Global_Block constructor
         */

        public function __construct() {
            parent::__construct();
        }


        /**
         * Function: map shortcode into Visual Composer (for use in content elements list)
         *
         * @throws \Exception
         */

        public function vc_map_shortcode() {

            vc_map( array(
                "name"                    => "Knop",
                "base"                    => $this->shortcode_name(),
                "class"                   => "flexx-custom-vc-item",
                "description"             => "Hiermee kan een blok met een knop worden toegevoegd.",
                "content_element"         => true,
                "show_settings_on_create" => true,
                "icon"                    => 'vc_custom_flexx_icon',
                'admin_enqueue_css'      =>  FLEXX_CP_ASSETS_URL . '/css/vc-element-view.css',
                "category"                => "Flexxmarketing",
                'admin_enqueue_js'        => FLEXX_CP_ASSETS_URL . '/js/vc-element-view.js',
                'js_view'                 => 'VcCustomElementView',
                "params"                  => array(

                    array(
                        "type"        => "vc_link",
                        "heading"     => "Knop",
                        "param_name"  => "link",
                        "description" => "Vul hier de link in voor de knop.",
                        "group"       => "Algemeen",
                        "admin_label" => true,
                    ),

                    array(
                        "type"        => "dropdown",
                        "heading"     => "Stijl",
                        "param_name"  => "style",
                        "description" => "Kies de stijl van de knop.",
                        "group"       => "Algemeen",
                        "value"       => array(
                            "Zwarte outline (Standaard)" => "outline",
                            "Witte outline"              => "outline-white",
                            "Geel"                       => "primary",
                        ),
                        "save_always" => true,
                        "admin_label" => true,
                    ),

                    array(
                        "type"        => "dropdown",
                        "heading"     => "Uitlijning",
                        "param_name"  => "alignment",
                        "description" => "Kies de uitlijning van de knop.",
                        "group"       => "Algemeen",
                        "value"       => array(
                            "Standaard" => "left",
                            "Midden"    => "center",
                            "Rechts"    => "right",
                        ),
                        "save_always" => true,
                        "admin_label" => true,
                    ),

                )

            ) );

        }


        /**
         * Function: register the shortcode's HTML output
         *
         * @param $atts
         * @param null $content
         *
         * @return mixed
         */

        public function register_shortcode( $atts, $content = null ) {

            $link = $style = $alignment = '';

            extract( shortcode_atts( array(
                'link'      => '',
                'style'     => '',
                'alignment' => '',
            ), $atts ) );

            $link = vc_build_link( $link );

            if ( ! empty( $link['url'] ) ) {

                $icon_html = '';
                if ( str_contains( $link['url'], 'goto' ) ) {
                    $icon_html = flexx_get_icon( 'arrow-down', '', false );
                } else {
                    $icon_html = flexx_get_icon( 'arrow-right-alt', '', false);
                }

                if ( empty( $style ) ) {
                    $style = 'outline';
                }

                $content = '
                    <div data-inview class="flexx_button_element wpb_content_element" style="text-align: ' . $alignment . ';">
                        <a href="' . esc_url( $link['url'] ) . '" class="btn btn--' . $style . '">
                            ' . $link['title'] . '
                            ' . $icon_html . '
                        </a>
                    </div>
                ';
            }

            return $content;

        }

    }

    new Flexx_VC_Button_Block();

}
