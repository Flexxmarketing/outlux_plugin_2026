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

if ( ! class_exists( __NAMESPACE__ . '\Flexx_VC_Contact_CTA' ) ) {

	class Flexx_VC_Contact_CTA extends Flexx_VC_Element_Template {

		/**
		 * Get the shortcode name
		 */
		public function shortcode_name(): string {
			return 'flexx-contact-cta';
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
				'name'                    => 'Contact CTA',
				'base'                    => $this->shortcode_name(),
				'class'                   => 'flexx-custom-vc-item',
				'description'             => 'Contact call-to-action met persoon, titel en knop.',
				'content_element'         => true,
				'show_settings_on_create' => true,
				'icon'                    => FLEXX_CP_IMG_URL . 'vc-icon.svg',
				'category'                => 'Flexx',
				'admin_enqueue_js'        => FLEXX_CP_ASSETS_URL . '/js/vc-element-view.js',
				'admin_enqueue_css'       => FLEXX_CP_ASSETS_URL . '/css/vc-element-view.css',
				'js_view'                 => 'VcCustomElementView',
				'params'                  => array(

					// Contactpersoon.
					array(
						'type'        => 'attach_image',
						'heading'     => 'Afbeelding contactpersoon',
						'param_name'  => 'contact_image',
						'description' => 'Selecteer of upload een afbeelding van de contactpersoon.',
						'group'       => 'Contactpersoon',
						'admin_label' => true,
					),
					array(
						'type'        => 'textfield',
						'heading'     => 'Naam contactpersoon',
						'param_name'  => 'contact_name',
						'description' => 'Bijvoorbeeld "Tim Claes".',
						'group'       => 'Contactpersoon',
						'admin_label' => true,
					),
					array(
						'type'        => 'textfield',
						'heading'     => 'Subtitel contactpersoon',
						'param_name'  => 'contact_subtitle',
						'description' => 'Bijvoorbeeld functie, "Zaakvoerder".',
						'group'       => 'Contactpersoon',
					),

					// Tekst.
					array(
						'type'        => 'textfield',
						'heading'     => 'Titel',
						'param_name'  => 'title',
						'description' => 'Hoofdtitel, bijvoorbeeld "Je eigen terras bespreken?".',
						'group'       => 'Tekst',
						'admin_label' => true,
					),
					array(
						'type'        => 'textarea',
						'heading'     => 'Subtekst',
						'param_name'  => 'text',
						'description' => 'Korte toelichtende tekst onder de titel.',
						'group'       => 'Tekst',
					),

					// Knop.
					array(
						'type'        => 'vc_link',
						'heading'     => 'Knop',
						'param_name'  => 'button_link',
						'description' => 'Instellingen voor de call-to-action knop, bijvoorbeeld "Kom in contact".',
						'group'       => 'Knop',
						'admin_label' => true,
					),
				),
			) );
		}

		/**
		 * Register the shortcode's HTML output
		 *
		 * @param array       $atts Shortcode attributes.
		 * @param string|null $content Shortcode content.
		 *
		 * @return string
		 */
		public function register_shortcode( $atts, $content = null ) {

			$html = '';

			$defaults = array(
				'contact_image'   => '',
				'contact_name'    => '',
				'contact_subtitle'=> '',
				'title'           => '',
				'text'            => '',
				'button_link'     => '',
			);

			$atts = shortcode_atts( $defaults, $atts );

			// Contact image.
			$contact_image_html = '';
			if ( ! empty( $atts['contact_image'] ) ) {
				$contact_image_html = '
                    <div class="person-image">
                        ' . flexx_srcset_image( (int) $atts['contact_image'] ) . '
                    </div>
                ';
			}

			// Button.
			$button_html = '';
			if ( ! empty( $atts['button_link'] ) ) {
				$link = vc_build_link( $atts['button_link'] );
				if ( ! empty( $link['url'] ) ) {
					$button_html = '
                        <a href="' . esc_url( $link['url'] ) . '" class="btn btn--primary" target="' . esc_attr( $link['target'] ) . '">
                            <span class="label">' . esc_html( $link['title'] ) . '</span>
                        </a>
                    ';
				}
			}

			$html .= '<div class="contact-cta wpb_content_element">';
			$html .= '  <div class="contact-cta__inner">';

			$html .= '
                <div class="contact-cta__person" data-inview>
                    ' . $contact_image_html . '
                    <div class="person-body">
                        ' . ( $atts['contact_name'] ? '<p class="name">' . esc_html( $atts['contact_name'] ) . '</p>' : '' ) . '
                        ' . ( $atts['contact_subtitle'] ? '<p class="subtitle">' . esc_html( $atts['contact_subtitle'] ) . '</p>' : '' ) . '
                    </div>
                </div>
            ';

			$html .= '
                <div class="contact-cta__content" data-inview>
                    <div class="inner-wrapper">
                        ' . ( $atts['title'] ? '<h2 class="title display-2">' . esc_html( $atts['title'] ) . '</h2>' : '' ) . '
                        ' . ( $atts['text'] ? '<p class="intro">' . esc_html( $atts['text'] ) . '</p>' : '' ) . '
                    </div>
                    <div class="actions">
                        ' . $button_html . '
                    </div>
                </div>
            ';

			$html .= '  </div>';
			$html .= '</div>';

			return $html;
		}
	}

	new Flexx_VC_Contact_CTA();
}

