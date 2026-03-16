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

if ( ! class_exists( __NAMESPACE__ . '\Flexx_VC_USPS' ) ) {

    class Flexx_VC_USPS extends Flexx_VC_Element_Template {

        /**
         * Get the shortcode name
         *
         * @return string
         */
        function shortcode_name(): string {
            return 'flexx-usps';
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
                "name"                    => "USP's",
                "base"                    => $this->shortcode_name(),
                "class"                   => "flexx-custom-vc-item",
                "description"             => "Voegt een USP's element toe aan de pagina.",
                "content_element"         => true,
                "show_settings_on_create" => true,
                "icon"                    => 'vc_custom_flexx_icon',
                "category"                => "Flexxmarketing",
                'admin_enqueue_css'       => FLEXX_CP_ASSETS_URL . '/css/vc-element-view.css',
                'admin_enqueue_js'        => FLEXX_CP_ASSETS_URL . '/js/vc-element-view.js',
                'js_view'                 => 'VcCustomElementView',
                "params"                  => array(

                    array(
                        "type"        => "param_group",
                        "heading"     => "USP's",
                        "param_name"  => "usps",
                        "description" => "Voeg hier de USP's toe.",
                        "group"       => "Stappen",
                        "params"      => array(

                            array(
                                "type"        => "textfield",
                                "heading"     => "Titel",
                                "param_name"  => "title",
                                "description" => "Vul hier de titel in.",
                                "group"       => "Algemeen",
                                "admin_label" => true,
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

            $html = $usps = '';

            extract( shortcode_atts( array(
                'usps' => '',
            ), $atts ) );

            $count = 1;
            $usps_html = '';
            $usps = vc_param_group_parse_atts( $usps );
            foreach ( $usps as $usp ) {
                if ( $count > 4 ) {
                    break; // Stop after 4 USP's
                }

                if ( $count > 1 ) {
                    $usps_html .= '<div class="usp-divider" data-inview><span class="divider"></span></div>';
                }

                $usps_html .= '
                    <div class="loop-usp" data-inview>
                        <div class="loop-usp__body">
                            <h3 class="title">' . $usp['title'] . '</h3>
                        </div>
                    </div> 
               ';
                $count++;
            }

            $html .= '
                <div class="usps wpb_content_element">
                    <div class="usps__inner">
                        ' . $usps_html . '
                    </div>
                </div>
            ';

            return $html;
        }
    }

    new Flexx_VC_USPS();
}