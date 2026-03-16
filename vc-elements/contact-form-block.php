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

if ( ! class_exists( __NAMESPACE__ . '\Flexx_VC_Contact_Form_Block' ) ) {

	class Flexx_VC_Contact_Form_Block extends Flexx_VC_Element_Template {

		/**
		 * Get the shortcode name
		 *
		 * @return string
		 */
		function shortcode_name(): string {
			return 'flexx-contact-form-block';
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

			// Get all forms from gravity forms.
			$form_options = array();

			if ( class_exists( 'GFAPI' ) ) {
				$forms              = \GFAPI::get_forms();
				$form_options[0]    = 'Selecteer een formulier';
				foreach ( $forms as $form ) {
					$form_options[ $form['id'] ] = $form['title'];
				}
			} else {
				$form_options[0] = 'Gravity Forms niet beschikbaar';
			}

			vc_map( array(
				'name'                    => 'Contact + formulier',
				'base'                    => $this->shortcode_name(),
				'class'                   => 'flexx-custom-vc-item',
				'description'             => 'Blok met header, contactpersoon en formulier.',
				'content_element'         => true,
				'show_settings_on_create' => true,
				'icon'                    => FLEXX_CP_IMG_URL . 'vc-icon.svg',
				'category'                => 'Flexx',
				'admin_enqueue_js'        => FLEXX_CP_ASSETS_URL . '/js/vc-element-view.js',
				'admin_enqueue_css'       => FLEXX_CP_ASSETS_URL . '/css/vc-element-view.css',
				'js_view'                 => 'VcCustomElementView',
				'params'                  => array(

					// Header.
					array(
						'type'        => 'textfield',
						'heading'     => 'Titel',
						'param_name'  => 'header_title',
						'description' => 'Vul de titel in.',
						'group'       => 'Header',
						'admin_label' => true,
					),

					array(
						'type'        => 'textarea',
						'heading'     => 'Tekst',
						'param_name'  => 'header_text',
						'description' => 'Vul de tekst onder de titel in.',
						'group'       => 'Header',
					),

                    array(
                        'type'        => 'textfield',
                        'heading'     => 'Notificatie (optioneel)',
                        'param_name'  => 'notification',
                        'description' => 'Vul de notificatie in.',
                        'group'       => 'Header',
                    ),

					// Contactpersoon.
					array(
						'type'        => 'textfield',
						'heading'     => 'Naam contactpersoon',
						'param_name'  => 'contact_name',
						'description' => 'Vul de naam van de contactpersoon in.',
						'group'       => 'Contactpersoon',
					),

					array(
						'type'        => 'textfield',
						'heading'     => 'Subtitel contactpersoon',
						'param_name'  => 'contact_subtitle',
						'description' => 'Bijvoorbeeld functie of afdeling.',
						'group'       => 'Contactpersoon',
					),

					array(
						'type'        => 'attach_image',
						'heading'     => 'Afbeelding contactpersoon',
						'param_name'  => 'contact_image',
						'description' => 'Selecteer of upload een afbeelding van de contactpersoon.',
						'group'       => 'Contactpersoon',
					),

					// Formulier.
					array(
						'type'        => 'textfield',
						'heading'     => 'Formulier titel',
						'param_name'  => 'form_title',
						'description' => 'Titel boven het formulier.',
						'group'       => 'Formulier',
					),

					array(
						'type'        => 'dropdown',
						'heading'     => 'Formulier',
						'param_name'  => 'form_id',
						'description' => 'Selecteer een formulier.',
						'group'       => 'Formulier',
						'value'       => $form_options,
						'save_always' => true,
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

			$header_title    = '';
			$header_text     = '';
            $notification    = '';
			$contact_name    = '';
			$contact_subtitle = '';
			$contact_image   = '';
			$form_title      = '';
			$form_id         = '';

			extract( shortcode_atts( array(
				'header_title'     => '',
				'header_text'      => '',
                'notification'     => '',
				'contact_name'     => '',
				'contact_subtitle' => '',
				'contact_image'    => '',
				'form_title'       => '',
				'form_id'          => '',
			), $atts ) );

			$field_values = array();

			$page_title = get_the_title();

			if ( $page_title ) {
				$field_values['thisPageTitle'] = $page_title;
			}

			$form_html = '';
			if ( $form_id && class_exists( 'GFAPI' ) ) {
				$form_html = gravity_form( $form_id, false, false, false, $field_values, true, 1, false );
			}

			$contact_image_html = '';
			if ( $contact_image ) {
				$contact_image_html = '
                    <div class="contact-image" data-inview>
					    ' . flexx_srcset_image( $contact_image ) . '
                    </div>
				';
			}

			$html .= '<div class="contact-form-block wpb_content_element">';
            $html .= '<div class="contact-form-block__inner">';

			// Header section in lijn met andere blokken.
			if ( $header_title || $header_text ) {
				$html .= '
                    <div class="contact-form-block__header" data-inview>
                        ' . ( $header_title ? '<h2 class="title display-2">' . esc_html( $header_title ) . '</h2>' : '' ) . '
                        ' . ( $header_text ? '<div class="intro">' . wp_kses_post( wpautop( $header_text ) ) . '</div>' : '' ) . '
                        ' . ( $notification ? '<div class="notification">' . flexx_replace( $notification,  '*', 'strong' ) . '</div>' : '' ) . '
                    </div>
                ';
			}

			$html .= '
                    <div class="contact-form-block__contact" data-inview>
                        ' . $contact_image_html . '
                        <div class="contact-body">
                            ' . ( $contact_name ? '<p class="name">' . esc_html( $contact_name ) . '</p>' : '' ) . '
                            ' . ( $contact_subtitle ? '<p class="subtitle">' . esc_html( $contact_subtitle ) . '</p>' : '' ) . '
                        </div>
                    </div>

                    <div class="contact-form-block__form" data-inview>
                        ' . ( $form_title ? '<h3 class="contact-form-block__form-title">' . esc_html( $form_title ) . '</h3>' : '' ) . '
                        ' . $form_html . '
                    </div>
                </div>
            ';

			$html .= '</div>';

			return $html;

		}

	}

	new Flexx_VC_Contact_Form_Block();
}

