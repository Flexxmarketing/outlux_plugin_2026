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

if ( ! class_exists( __NAMESPACE__ . '\Flexx_VC_Image_With_Content_Contact' ) ) {

	class Flexx_VC_Image_With_Content_Contact extends Flexx_VC_Element_Template {

		/**
		 * Get the shortcode name
		 *
		 * @return string
		 */
		function shortcode_name(): string {
			return 'flexx-image-with-content-contact';
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
				'name'                    => 'Afbeelding met tekst + contact',
				'base'                    => $this->shortcode_name(),
				'class'                   => 'flexx-custom-vc-item',
				'description'             => 'Blok met afbeelding(en), tekst, contactgegevens en knoppen.',
				'content_element'         => true,
				'show_settings_on_create' => true,
				'icon'                    => FLEXX_CP_IMG_URL . 'vc-icon.svg',
				'category'                => 'Flexx',
				'admin_enqueue_js'        => FLEXX_CP_ASSETS_URL . '/js/vc-element-view.js',
				'admin_enqueue_css'       => FLEXX_CP_ASSETS_URL . '/css/vc-element-view.css',
				'js_view'                 => 'VcCustomElementView',
				'params'                  => array(

					array(
						'type'        => 'dropdown',
						'heading'     => 'Vormgeving',
						'param_name'  => 'layout',
						'description' => 'Kies een van de opties.',
						'group'       => 'Algemeen',
						'value'       => array(
							'Afbeelding(en) links, tekst rechts'  => '',
							'Afbeelding(en) rechts, tekst links'  => 'reverse',
						),
						'save_always' => true,
						'admin_label' => true,
					),

					array(
						'type'        => 'textfield',
						'heading'     => 'Titel',
						'param_name'  => 'title',
						'description' => 'Vul hier de titel in.',
						'group'       => 'Algemeen',
						'admin_label' => true,
					),

					array(
						'type'        => 'textarea_html',
						'heading'     => 'Tekst',
						'param_name'  => 'content',
						'description' => 'Vul de tekst in.',
						'group'       => 'Algemeen',
						'holder'      => 'div',
					),

					array(
						'type'        => 'attach_images',
						'heading'     => 'Afbeelding(en)',
						'param_name'  => 'images',
						'description' => 'Selecteer of upload een afbeelding.',
						'group'       => 'Algemeen',
						'admin_label' => true,
					),

					array(
						'type'        => 'vc_link',
						'heading'     => 'Primaire knop (optioneel)',
						'param_name'  => 'link',
						'description' => 'Kies de instellingen voor de primaire knop (bijvoorbeeld "Vrijblijvende offerte").',
						'group'       => 'Knoppen',
					),

					array(
						'type'        => 'vc_link',
						'heading'     => 'Secundaire knop (optioneel)',
						'param_name'  => 'secondary_link',
						'description' => 'Kies de instellingen voor de secundaire knop (bijvoorbeeld "Naar showtuin").',
						'group'       => 'Knoppen',
					),

					array(
						'type'        => 'textfield',
						'heading'     => 'Telefoonnummer',
						'param_name'  => 'phone_number',
						'description' => 'Wordt gebruikt voor de tel:-link. Vul alleen cijfers (en eventueel +) in.',
						'group'       => 'Contact',
					),

					array(
						'type'        => 'textfield',
						'heading'     => 'E-mailadres',
						'param_name'  => 'email_address',
						'description' => 'Wordt gebruikt voor de mailto:-link.',
						'group'       => 'Contact',
					),

					array(
						'type'        => 'textfield',
						'heading'     => 'WhatsApp nummer',
						'param_name'  => 'whatsapp_number',
						'description' => 'Wordt gebruikt voor de WhatsApp-link (https://wa.me/). Vul alleen cijfers (en eventueel landcode zonder +) in.',
						'group'       => 'Contact',
					),

					array(
						'type'        => 'textarea',
						'heading'     => 'Adres',
						'param_name'  => 'address',
						'description' => 'Vul het adres in. Standaard wordt dit automatisch gevuld vanuit de algemene contactgegevens.',
						'group'       => 'Contact',
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

			$html = $layout = $title = $images = $link = $secondary_link = '';
			$phone_label = $phone_number = $email_label = $email_address = $whatsapp_label = $whatsapp_number = $address = '';

			$contact_details = function_exists( 'flexx_get_contact_details' ) ? flexx_get_contact_details() : array();

			$default_phone_number  = isset( $contact_details['phone'] ) ? $contact_details['phone'] : '';
			$default_email_address = isset( $contact_details['email'] ) ? $contact_details['email'] : '';

			$first_line_parts = array();
			if ( ! empty( $contact_details['street'] ) ) {
				$first_line_parts[] = $contact_details['street'];
			}
			if ( ! empty( $contact_details['house_number'] ) ) {
				$first_line_parts[] = $contact_details['house_number'] . ',';
			}

			$second_line_parts = array();
			if ( ! empty( $contact_details['postal_code'] ) ) {
				$second_line_parts[] = $contact_details['postal_code'];
			}
			if ( ! empty( $contact_details['city'] ) ) {
				$second_line_parts[] = $contact_details['city'];
			}

			$default_address_lines = array();
			if ( ! empty( $first_line_parts ) ) {
				$default_address_lines[] = implode( ' ', $first_line_parts );
			}
			if ( ! empty( $second_line_parts ) ) {
				$default_address_lines[] = implode( ' ', $second_line_parts );
			}

			$default_address = implode( "\n", $default_address_lines );

			extract( shortcode_atts( array(
				'layout'          => '',
				'title'           => '',
				'images'          => '',
				'link'            => '',
				'secondary_link'  => '',
				'phone_label'     => '',
				'phone_number'    => $default_phone_number,
				'email_label'     => '',
				'email_address'   => $default_email_address,
				'whatsapp_label'  => '',
				'whatsapp_number' => '',
				'address'         => $default_address,
			), $atts ) );

			// Primaire knop.
			$buttons_html = '';
			if ( $link || $secondary_link ) {
				$buttons_html .= '<div class="buttons">';

				if ( $link ) {
					$link = vc_build_link( $link );

					$icon_html = '';
					if ( ! empty( $link['url'] ) ) {
						if ( str_contains( $link['url'], 'goto' ) ) {
							$icon_html = flexx_get_icon( 'arrow-down', '', false );
						} else {
							$icon_html = flexx_get_icon( 'arrow-right-alt', '', false );
						}

						$buttons_html .= '
                        <a href="' . esc_url( $link['url'] ) . '" class="btn btn--outline" target="' . esc_attr( $link['target'] ) . '">
                            <span class="label">' . esc_html( $link['title'] ) . '</span>
                            ' . $icon_html . '
                        </a>
                    ';
					}
				}

				// Secundaire knop.
				if ( $secondary_link ) {
					$secondary_link = vc_build_link( $secondary_link );

                    $icon_html = '';
					if ( ! empty( $secondary_link['url'] ) ) {
                        if ( str_contains( $secondary_link['url'], 'goto' ) ) {
                            $icon_html = flexx_get_icon( 'arrow-down', '', false );
                        } else {
                            $icon_html = flexx_get_icon( 'arrow-right-alt', '', false );
                        }

						$buttons_html .= '
                        <a href="' . esc_url( $secondary_link['url'] ) . '" class="btn btn--primary" target="' . esc_attr( $secondary_link['target'] ) . '">
                            <span class="label">' . esc_html( $secondary_link['title'] ) . '</span>
                            ' . $icon_html . '
                        </a>
                    ';
					}
				}

				$buttons_html .= '</div>';
			}

			// Contact links.
			$contact_links_html = '';

			$phone_href    = '';
			$email_href    = '';
			$whatsapp_href = '';
			$address_href  = '';

			if ( $phone_number ) {
				// Verwijder spaties voor tel:-link.
				$sanitized_phone = flexx_format_phone( $phone_number );
				$phone_href      = 'tel:' . $sanitized_phone;
			}

			if ( $email_address ) {
				$email_href = 'mailto:' . antispambot($email_address);
			}

			if ( $whatsapp_number ) {
				$sanitized_whatsapp = preg_replace( '/\s+/', '', $whatsapp_number );
				$whatsapp_href      = 'https://wa.me/' . $sanitized_whatsapp;
			}
			if ( $address ) {
				$address_query = urlencode( str_replace( array( "\r\n", "\r", "\n" ), ' ', $address ) );
				$address_href  = 'https://www.google.com/maps/search/?api=1&query=' . $address_query;
			}

			if ( $address_href || $phone_href || $email_href || $whatsapp_href ) {
				$contact_links_html .= '<div class="contact-links">';

				if ( $address_href ) {
					$contact_links_html .= '
                        <a href="' . esc_url( $address_href ) . '" class="contact-link contact-link--address" target="_blank" rel="noopener">
                            ' . flexx_get_icon( 'location-pin', '', false ) . '
                            <span class="label">' . esc_html( preg_replace( '/\s+/', ' ', $address ) ) . '</span>
                        </a>
                    ';
				}

				if ( $phone_href ) {
					$label = $phone_label ? $phone_label : $phone_number;
					$contact_links_html .= '
                        <a href="' . esc_url( $phone_href ) . '" class="contact-link contact-link--phone">
                            ' . flexx_get_icon( 'phone', '', false ) . '
                            <span class="label">' . esc_html( $label ) . '</span>
                        </a>
                    ';
				}

				if ( $email_href ) {
					$label = $email_label ? $email_label : $email_address;
					$contact_links_html .= '
                        <a href="' . esc_url( $email_href ) . '" class="contact-link contact-link--email">
                            ' . flexx_get_icon( 'email', '', false ) . '
                            <span class="label">' . esc_html( $label ) . '</span>
                        </a>
                    ';
				}

				if ( $whatsapp_href ) {
					$label = $whatsapp_label ? $whatsapp_label : 'WhatsApp';
					$contact_links_html .= '
                        <a href="' . esc_url( $whatsapp_href ) . '" class="contact-link contact-link--whatsapp">
                            ' . flexx_get_icon( 'social_media/whatsapp', '', false ) . '
                            <span class="label">' . __('WhatsApp', 'flexx-client') . '</span>
                        </a>
                    ';
				}

				$contact_links_html .= '</div>';
			}

			// Afbeeldingen.
			$images_html = '';
			$count       = 1;
			$images      = explode( ',', $images );
			foreach ( $images as $img_id ) {
				if ( $count > 2 ) {
					break;
				}
				$images_html .= '
                    <picture class="image-holder" data-inview>
                        ' . flexx_srcset_image( $img_id, 'flexx-extra-large', false ) . '
                    </picture>
                ';
				$count ++;
			}

			$html .= '
				<div class="media-with-content media-with-content--contact' . ( $layout === 'reverse' ? ' media-with-content--reverse' : '' ) . ' wpb_content_element">
					
					<div class="inner-wrapper">
                        <div class="media-with-content__image' . ( count( $images ) > 1 ? 's' : '' ) . '">
                            ' . $images_html . '
                        </div>
                        
                        <div class="media-with-content__content" data-inview>
                            <h1 class="display-1">' . $title . '</h1>
                            ' . wpautop( $content ) . '
                            ' . $contact_links_html . '
                            ' . $buttons_html . '
                        </div>
                    </div>
					
				</div>
			';

			return $html;

		}

	}

	new Flexx_VC_Image_With_Content_Contact();
}

