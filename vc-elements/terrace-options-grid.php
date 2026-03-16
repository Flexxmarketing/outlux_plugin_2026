<?php
/**
 * Plugin class to set up custom Visual Composer element for terrace solution options grid
 *
 * @link        https://flexxmarketing.nl/
 * @since       2.0.1
 *
 * @package     Flexx_Client_Plugin
 * @subpackage  Flexx_Client_Plugin/VC_Elements/Custom
 */

use Flexx_Client_Plugin\VC_Elements\Template\Flexx_VC_Element_Template;

// No direct access
if ( ! defined( 'WPINC' ) ) {
    die;
}

if ( ! class_exists( __NAMESPACE__ . '\Flexx_VC_Terrace_Options_Grid' ) ) {

    class Flexx_VC_Terrace_Options_Grid extends Flexx_VC_Element_Template {

        /**
         * Get the shortcode name.
         */
        public function shortcode_name(): string {
            return 'flexx-terrace-options-grid';
        }

        /**
         * Restrict element to terrace-solution child posts only.
         */
        protected function editor_visibility_rules(): array {
            return array(
                'only_on_post_type' => 'terrace-solution',
                'only_on_child'     => true,
            );
        }

        /**
         * Class constructor.
         */
        public function __construct() {
            parent::__construct();
        }

        /**
         * Map shortcode into Visual Composer (for use in content elements list).
         *
         * @throws \Exception
         */
        public function vc_map_shortcode() {

            vc_map( array(
                'name'                    => 'Opties terrasoverkapping',
                'base'                    => $this->shortcode_name(),
                'class'                   => 'flexx-custom-vc-item',
                'description'             => 'Toont een grid met opties voor een terrasoverkapping.',
                'content_element'         => true,
                'show_settings_on_create' => true,
                'icon'                    => 'vc_custom_flexx_icon',
                'category'                => 'Flexx',
                'admin_enqueue_css'       => FLEXX_CP_ASSETS_URL . '/css/vc-element-view.css',
                'admin_enqueue_js'        => FLEXX_CP_ASSETS_URL . '/js/vc-element-view.js',
                'js_view'                 => 'VcCustomElementView',
                'params'                  => array(

                    // Group: Algemeen (header)
                    array(
                        'type'        => 'textfield',
                        'heading'     => 'Titel',
                        'param_name'  => 'title',
                        'description' => 'Hoofdtitel boven het opties-blok, bijvoorbeeld "Opties".',
                        'group'       => 'Algemeen',
                        'admin_label' => true,
                    ),
                    array(
                        'type'        => 'textarea',
                        'heading'     => 'Tekst',
                        'param_name'  => 'text',
                        'description' => 'Korte inleidingstekst onder de titel.',
                        'group'       => 'Algemeen',
                    ),

                    // Group: Opties (cards)
                    array(
                        'type'        => 'param_group',
                        'heading'     => 'Opties',
                        'param_name'  => 'options',
                        'description' => 'Voeg hier de verschillende opties toe.',
                        'group'       => 'Opties',
                        'params'      => array(
                            array(
                                'type'        => 'attach_image',
                                'heading'     => 'Afbeelding',
                                'param_name'  => 'image',
                                'description' => 'Kies of upload een afbeelding voor deze optie.',
                            ),
                            array(
                                'type'        => 'textfield',
                                'heading'     => 'Titel',
                                'param_name'  => 'option_title',
                                'description' => 'Titel van de optie, bijvoorbeeld "LED verlichting".',
                                'admin_label' => true,
                            ),
                            array(
                                'type'        => 'textarea',
                                'heading'     => 'Beschrijving',
                                'param_name'  => 'description',
                                'description' => 'Korte omschrijving van de optie.',
                            ),
                        ),
                    ),

                ),
            ) );
        }

        /**
         * Register the shortcode's HTML output.
         *
         * @param array $atts Shortcode attributes.
         * @param string|null $content Shortcode content.
         *
         * @return string
         */
        public function register_shortcode( $atts, $content = null ) {

            $defaults = array(
                'title'   => __( 'Opties', 'flexx-client-plugin' ),
                'text'    => '',
                'options' => '',
            );

            $atts = shortcode_atts( $defaults, $atts );

            $options = vc_param_group_parse_atts( $atts['options'] );

            $cards_html = '';
            if ( ! empty( $options ) ) {
                foreach ( $options as $option ) {
                    $image_id    = isset( $option['image'] ) ? (int) $option['image'] : 0;
                    $card_title  = $option['option_title'] ?? '';
                    $description = $option['description'] ?? '';

                    if ( $card_title === '' && $description === '' && ! $image_id ) {
                        continue;
                    }

                    $image_html = '';
                    if ( $image_id ) {
                        $image_html = '<div class="loop-terrace-option__image">' . flexx_srcset_image( $image_id, 'flexx-medium' ) . '</div>';
                    }

                    $cards_html .= '
                        <div class="item-wrap" data-inview>
                            <div class="loop-terrace-option">
                                ' . $image_html . '
                                <div class="loop-terrace-option__body">
                                    ' . ( $card_title !== '' ? '<h3 class="title">' . esc_html( $card_title ) . '</h3>' : '' ) . '
                                    ' . ( $description !== '' ? '<p class="text">' . esc_html( $description ) . '</p>' : '' ) . '
                                </div>
                            </div>
                        </div>';
                }
            }

            $html = '';
            $html .= '<div class="terrace-options wpb_content_element">';
            $html .= '    <div class="terrace-options__header" data-inview>';
            $html .= '        <h2 class="title display-2">' . esc_html( $atts['title'] ) . '</h2>';
            if ( $atts['text'] !== '' ) {
                $html .= '    <p class="intro">' . esc_html( $atts['text'] ) . '</p>';
            }
            $html .= '    </div>';

            if ( $cards_html !== '' ) {
                $html .= '<div class="terrace-options__items">';
                $html .= $cards_html;
                $html .= '</div>';
            }

            $html .= '</div>';

            return $html;
        }
    }

    new Flexx_VC_Terrace_Options_Grid();
}

