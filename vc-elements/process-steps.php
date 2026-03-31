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

if ( ! class_exists( __NAMESPACE__ . '\Flexx_VC_Process_Steps' ) ) {

    class Flexx_VC_Process_Steps extends Flexx_VC_Element_Template {

        /**
         * Get the shortcode name
         *
         * @return string
         */
        function shortcode_name(): string {
            return 'flexx-process-steps';
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
                "name"                    => "Processtappen",
                "base"                    => $this->shortcode_name(),
                "class"                   => "flexx-custom-vc-item",
                "description"             => "Voeg een slider toe met processtappen.",
                "content_element"         => true,
                "show_settings_on_create" => true,
                "icon"                    => 'vc_custom_flexx_icon',
                "category"                => "Flexxmarketing",
                'admin_enqueue_css'       => FLEXX_CP_ASSETS_URL . '/css/vc-element-view.css',
                'admin_enqueue_js'        => FLEXX_CP_ASSETS_URL . '/js/vc-element-view.js',
                'js_view'                 => 'VcCustomElementView',
                "params"                  => array(

                    array(
                        "type"        => "textfield",
                        "heading"     => "Titel",
                        "param_name"  => "title",
                        "description" => "Vul hier de titel in.",
                        "group"       => "Algemeen",
                        "admin_label" => true,
                    ),

                    array(
                        "type"        => "textarea",
                        "heading"     => "Tekst",
                        "param_name"  => "text",
                        "description" => "Vul hier de tekst in.",
                        "group"       => "Algemeen",
                    ),

                    array(
                        "type"        => "vc_link",
                        "heading"     => "Knop",
                        "param_name"  => "link",
                        "description" => "Vul hier de link in voor de knop.",
                        "group"       => "Algemeen",
                    ),

                    array(
                        "type"        => "param_group",
                        "heading"     => "Processstappen",
                        "param_name"  => "steps",
                        "description" => "Voeg hier de processtappen toe.",
                        "group"       => "Stappen",
                        "params"      => array(

                            array(
                                "type"        => "attach_image",
                                "heading"     => "Afbeelding",
                                "param_name"  => "image",
                                "description" => "Kies of upload een afbeelding voor deze stap.",
                                "group"       => "Algemeen",
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
                                "type"        => "textarea",
                                "heading"     => "Tekst",
                                "param_name"  => "text",
                                "description" => "Vul hier de tekst in.",
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

            $html = $title = $text = $link = $steps = '';

            wp_enqueue_script('flexx-vendor-swiper');
            wp_enqueue_script('flexx-custom-swiper');

            extract( shortcode_atts( array(
                'title' => '',
                'text'  => '',
                'link'  => '',
                'steps' => '',
            ), $atts ) );

            $counter = 1;
            $steps_html = '';
            $steps      = vc_param_group_parse_atts( $steps );
            foreach ( $steps as $step ) {
                $steps_html .= '
                    <div class="swiper-slide">
                        <div class="loop-step" data-inview>
                            <div class="loop-step__image">
                                ' . flexx_srcset_image( $step['image'] ) . '
                            </div>
                            <div class="loop-step__body">
                                <span class="number">' . $counter . '</span>
                                <h3 class="title">' . $step['title'] . '</h3>
                                <p class="text">' . $step['text'] . '</p>
                            </div>
                        </div> 
                    </div>
               ';
                $counter++;
            }

            $button_html = '';
            $link = vc_build_link( $link );
            if ( ! empty( $link['url'] ) ) {
                $button_html .= '
                    <div class="steps__footer" data-inview>
                        ' . flexx_button( array(
                            'text'    => $link['title'] ?: 'Lees meer',
                            'url'     => $link['url'],
                            'classes' => 'btn btn--cta',
                            'icon'    => 'arrow-right-alt',
                            'echo'     => false,
                        ) ) . '
                    </div>
                ';
            }


            $html .= '
                <div class="steps wpb_content_element">
                    
                    <div class="steps__header" data-inview>
                        <h2 class="title display-2">' . $title . '</h2>
                        <p class="text lead">' . $text . '</p>
                    </div>
                    
                    <div class="steps__slider">
                        <div class="swiper process-swiper">
                            <div class="swiper-wrapper">
                                ' . $steps_html . '
                            </div>
                        </div>
                        <div class="steps-slider-pagination" style="--slides:' . ($counter - 2) . ';" data-inview></div>
                    </div>
                    
                    ' . $button_html . '
                    
                </div>
            ';

            return $html;
        }
    }

    new Flexx_VC_Process_Steps();
}