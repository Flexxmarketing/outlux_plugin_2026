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

if ( ! class_exists( __NAMESPACE__ . '\Flexx_VC_Brochure_Request' ) ) {

    class Flexx_VC_Brochure_Request extends Flexx_VC_Element_Template {

        /**
         * Get the shortcode name
         *
         * @return string
         */
        function shortcode_name(): string {
            return 'flexx-brochure-request';
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

            // Get all forms from gravity forms
            $forms        = GFAPI::get_forms();
            $form_options = array();

            $form_options[0] = 'Selecteer een formulier';
            foreach ( $forms as $form ) {
                $form_options[ $form['id'] ] = $form['title'];
            }

            vc_map( array(
                "name"                    => "Brochure aanvragen",
                "base"                    => $this->shortcode_name(),
                "class"                   => "flexx-custom-vc-item",
                "description"             => "Voegt een formulier toe waarmee bezoekers een brochure kunnen aanvragen.",
                "content_element"         => true,
                "show_settings_on_create" => true,
                "icon"                    => FLEXX_CP_IMG_URL . 'vc-icon.svg',
                "category"                => "Flexxmarketing",
                'admin_enqueue_js'        => FLEXX_CP_ASSETS_URL . '/js/vc-element-view.js',
                'admin_enqueue_css'       => FLEXX_CP_ASSETS_URL . '/css/vc-element-view.css',
                'js_view'                 => 'VcCustomElementView',
                "params"                  => array(

                    array(
                        "type"        => "textfield",
                        "heading"     => "Titel",
                        "param_name"  => "title",
                        "description" => "Vul de titel in.",
                        "group"       => "Algemeen",
                        "admin_label" => true,
                    ),

                    array(
                        "type"        => "textfield",
                        "heading"     => "Subtitel",
                        "param_name"  => "subtitle",
                        "description" => "Vul de subtitel in.",
                        "group"       => "Algemeen",
                    ),

                    array(
                        "type"        => "attach_image",
                        "heading"     => "Afbeelding",
                        "param_name"  => "image",
                        "description" => "Kies of upload een afbeelding van de brochure.",
                        "group"       => "Algemeen",
                    ),

                    array(
                        "type"        => "dropdown",
                        "heading"     => "Formulier",
                        "param_name"  => "form",
                        "description" => "Selecteer een formulier.",
                        "group"       => "Formulier",
                        "value"       => $form_options,
                        "save_always" => true,
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

            $html = $title = $subtitle = $image = $form = '';

            extract( shortcode_atts( array(
                'title'      => '',
                'subtitle'   => '',
                'image'      => '',
                'form'       => '',
            ), $atts ) );

            $form_html = gravity_form( $form, false, false, false, '', true, 1, false );

            $html .= '
                <div class="brochure-request wpb_content_element">
                
                    <div class="brochure-request__image">
                        ' . flexx_srcset_image($image) . '
                    </div>
                               
                    <div class="inner-wrapper">
                        <div class="brochure-request__content">
                            <h2 class="title">' . $title . '</h2>
                            <p class="subtitle">' . $subtitle . '</p>
                        </div>
                        <div class="brochure-request__form">
                            ' . $form_html . '    
                        </div>
                    </div>
                </div>
			 ';

            return $html;

        }

    }

    new Flexx_VC_Brochure_Request();
}
