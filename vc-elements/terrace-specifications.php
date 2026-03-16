<?php
/**
 * Plugin class to set up custom Visual Composer element for terrace solution specifications
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

if ( ! class_exists( __NAMESPACE__ . '\Flexx_VC_Terrace_Specifications' ) ) {

    class Flexx_VC_Terrace_Specifications extends Flexx_VC_Element_Template {

        /**
         * Get the shortcode name.
         */
        public function shortcode_name(): string {
            return 'flexx-terrace-specifications';
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
                'name'                    => 'Specificaties terrasoverkapping',
                'base'                    => $this->shortcode_name(),
                'class'                   => 'flexx-custom-vc-item',
                'description'             => 'Toont specificaties, bedrijfsgegevens, beschrijving en opties van een terrasoverkapping.',
                'content_element'         => true,
                'show_settings_on_create' => true,
                'icon'                    => 'vc_custom_flexx_icon',
                'category'                => 'Flexx',
                'admin_enqueue_css'       => FLEXX_CP_ASSETS_URL . '/css/vc-element-view.css',
                'admin_enqueue_js'        => FLEXX_CP_ASSETS_URL . '/js/vc-element-view.js',
                'js_view'                 => 'VcCustomElementView',
                'params'                  => array(

                    // Group: Algemeen
                    array(
                        'type'        => 'textfield',
                        'heading'     => 'Titel',
                        'param_name'  => 'general_title',
                        'description' => 'Hoofdtitel boven het specificatieblok, bijvoorbeeld "Specificaties".',
                        'group'       => 'Algemeen',
                        'admin_label' => true,
                    ),
                    array(
                        'type'        => 'param_group',
                        'heading'     => 'Specificaties',
                        'param_name'  => 'specifications',
                        'description' => 'Voeg hier de productspecificaties toe (label + waarde).',
                        'group'       => 'Algemeen',
                        'params'      => array(
                            array(
                                'type'        => 'textfield',
                                'heading'     => 'Label',
                                'param_name'  => 'label',
                                'description' => 'Bijvoorbeeld "Maximale afmetingen".',
                                'admin_label' => true,
                            ),
                            array(
                                'type'        => 'textfield',
                                'heading'     => 'Waarde',
                                'param_name'  => 'value',
                                'description' => 'Bijvoorbeeld "breedte onbeperkt, uitval max. 3000 mm".',
                            ),
                        ),
                    ),

                    // Group: Bedrijf
                    array(
                        'type'        => 'textfield',
                        'heading'     => 'Bedrijfsblok titel',
                        'param_name'  => 'company_title',
                        'description' => 'Titel boven het bedrijfsblok, bijvoorbeeld "Toepassing ... overkapping".',
                        'group'       => 'Bedrijf',
                        'admin_label' => false,
                    ),
                    array(
                        'type'        => 'textfield',
                        'heading'     => 'Bedrijfstak',
                        'param_name'  => 'company_branch',
                        'description' => 'Bijvoorbeeld "Particulier & horeca".',
                        'group'       => 'Bedrijf',
                    ),
                    array(
                        'type'        => 'attach_image',
                        'heading'     => 'Merk / Brand afbeelding',
                        'param_name'  => 'company_brand',
                        'description' => 'Upload het merklogo dat naast de bedrijfsinfo wordt getoond.',
                        'group'       => 'Bedrijf',
                    ),

                    // Group: Description
                    array(
                        'type'        => 'textfield',
                        'heading'     => 'Omschrijving titel',
                        'param_name'  => 'description_title',
                        'description' => 'Titel boven de omschrijving, bijvoorbeeld "Omschrijving ... overkapping".',
                        'group'       => 'Description',
                    ),
                    array(
                        'type'        => 'param_group',
                        'heading'     => 'USP\'s',
                        'param_name'  => 'description_usps',
                        'description' => 'Voeg hier USP\'s toe (één per regel).',
                        'group'       => 'Description',
                        'params'      => array(
                            array(
                                'type'        => 'textfield',
                                'heading'     => 'USP',
                                'param_name'  => 'usp',
                                'description' => 'Bijvoorbeeld "Glasdak met ranke vormgeving".',
                                'admin_label' => true,
                            ),
                        ),
                    ),

                    // Group: Opties
                    array(
                        'type'        => 'textfield',
                        'heading'     => 'Opties titel',
                        'param_name'  => 'options_title',
                        'description' => 'Titel boven de opties, bijvoorbeeld "Opties ... overkapping".',
                        'group'       => 'Opties',
                    ),
                    array(
                        'type'        => 'param_group',
                        'heading'     => 'Opties',
                        'param_name'  => 'options',
                        'description' => 'Voeg hier de beschikbare opties toe (één per regel).',
                        'group'       => 'Opties',
                        'params'      => array(
                            array(
                                'type'        => 'textfield',
                                'heading'     => 'Optie',
                                'param_name'  => 'option',
                                'description' => 'Bijvoorbeeld "Glaswanden rondom".',
                                'admin_label' => true,
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
                'general_title'     => __( 'Specificaties', 'flexx-client-plugin' ),
                'specifications'    => '',
                'company_title'     => '',
                'company_branch'    => '',
                'company_brand'     => '',
                'description_title' => '',
                'description_usps'  => '',
                'options_title'     => '',
                'options'           => '',
            );

            $atts = shortcode_atts( $defaults, $atts );

            $specifications   = vc_param_group_parse_atts( $atts['specifications'] );
            $description_usps = vc_param_group_parse_atts( $atts['description_usps'] );
            $options          = vc_param_group_parse_atts( $atts['options'] );

            $specs_html = '';
            if ( ! empty( $specifications ) ) {
                foreach ( $specifications as $spec ) {
                    $label = $spec['label'] ?? '';
                    $value = $spec['value'] ?? '';
                    if ( $label === '' && $value === '' ) {
                        continue;
                    }
                    $specs_html .= '
                        <div class="specifications-wrapper__row">
                            <div class="label">' . esc_html( $label ) . ':</div>
                            <div class="value">' . esc_html( $value ) . '</div>
                        </div>';
                }
            }

            $company_html = '';
            if ( $atts['company_title'] !== '' || $atts['company_branch'] !== '' || (int) $atts['company_brand'] !== 0 ) {
                $brand_html = '';
                if ( (int) $atts['company_brand'] ) {
                    $brand_html = flexx_srcset_image( (int) $atts['company_brand'], 'flexx-small' );
                }

                $company_html .= '
                    <div class="terrace-specs__section section-company" data-inview>
                        <div class="section-company__body">
                            ' . ( $atts['company_title'] !== '' ? '<h3 class="subtitle">' . esc_html( $atts['company_title'] ) . '</h3>' : '' ) . '
                            ' . ( $atts['company_branch'] !== '' ? '<p class="company-branch">' . esc_html( $atts['company_branch'] ) . '</p>' : '' ) . '
                        </div>
                        <div class="section-company__brand">
                            ' . $brand_html . '
                        </div>
                    </div>';
            }

            $description_html = '';
            if ( ! empty( $description_usps ) || $atts['description_title'] !== '' ) {
                $description_html .= '
                    <div class="terrace-specs__section section-description" data-inview>
                        ' . ( $atts['description_title'] !== '' ? '<h3 class="subtitle">' . esc_html( $atts['description_title'] ) . '</h3>' : '' );

                if ( ! empty( $description_usps ) ) {
                    $description_html .= '<ul class="terrace-specs__list section-description__usps">';
                    foreach ( $description_usps as $usp_item ) {
                        $usp = $usp_item['usp'] ?? '';
                        if ( $usp === '' ) {
                            continue;
                        }
                        $description_html .= '<li>' . esc_html( $usp ) . '</li>';
                    }
                    $description_html .= '</ul>';
                }

                $description_html .= '</div>';
            }

            $options_html = '';
            if ( ! empty( $options ) || $atts['options_title'] !== '' ) {
                $options_html .= '
                    <div class="terrace-specs__section section-options" data-inview>
                        ' . ( $atts['options_title'] !== '' ? '<h3 class="subtitle">' . esc_html( $atts['options_title'] ) . '</h3>' : '' );

                if ( ! empty( $options ) ) {
                    $options_html .= '<ul class="terrace-specs__list section-options__options">';
                    foreach ( $options as $option_item ) {
                        $option = $option_item['option'] ?? '';
                        if ( $option === '' ) {
                            continue;
                        }
                        $options_html .= '<li>' . esc_html( $option ) . '</li>';
                    }
                    $options_html .= '</ul>';
                }

                $options_html .= '</div>';
            }

            $html = '';
            $html .= '<div class="terrace-specs wpb_content_element">';
            $html .= '  <div class="terrace-specs__inner">';
            $html .= '      <h2 class="title display-2" data-inview>' . esc_html( $atts['general_title'] ) . '</h2>';

            if ( $specs_html !== '' ) {
                $html .= '      <div class="terrace-specs__section specifications-wrapper" data-inview>';
                $html .= $specs_html;
                $html .= '      </div>';
            }

            $html .= $company_html;
            $html .= $description_html;
            $html .= $options_html;

            $html .= '  </div>';
            $html .= '</div>';

            return $html;
        }
    }

    new Flexx_VC_Terrace_Specifications();
}

