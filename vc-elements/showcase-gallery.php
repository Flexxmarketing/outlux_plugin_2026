<?php
/**
 * Plugin class to set up custom Visual Composer element: Showcase Gallery (GLightbox grid)
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

if ( ! class_exists( __NAMESPACE__ . '\Flexx_VC_Showcase_Gallery' ) ) {

	class Flexx_VC_Showcase_Gallery extends Flexx_VC_Element_Template {

		/**
		 * Get the shortcode name
		 *
		 * @return string
		 */
		function shortcode_name(): string {
			return 'flexx-showcase-gallery';
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

			vc_map(
				array(
					'name'                    => 'Showcase gallery',
					'base'                    => $this->shortcode_name(),
					'class'                   => 'flexx-custom-vc-item',
					'description'             => 'Eenvoudige afbeeldingsgalerij met GLightbox (grid, geen slider).',
					'content_element'         => true,
					'show_settings_on_create' => true,
					'icon'                    => FLEXX_CP_IMG_URL . 'vc-icon.svg',
					'category'                => 'Flexxmarketing',
					'admin_enqueue_js'        => FLEXX_CP_ASSETS_URL . '/js/vc-element-view.js',
					'admin_enqueue_css'       => FLEXX_CP_ASSETS_URL . '/css/vc-element-view.css',
					'js_view'                 => 'VcCustomElementView',
					'params'                  => array(

						array(
							'type'        => 'attach_images',
							'heading'     => 'Gallerij afbeeldingen',
							'param_name'  => 'images',
							'description' => 'Kies of upload hier de afbeeldingen voor de galerij.',
							'group'       => 'Algemeen',
						),

					),
				)
			);
		}

		/**
		 * Register the shortcode's HTML output
		 *
		 * @param array       $atts    Shortcode attributes.
		 * @param string|null $content Shortcode content.
		 *
		 * @return string
		 */
		public function register_shortcode( $atts, $content = null ) {

			$atts = shortcode_atts(
				array(
					'images' => '',
				),
				$atts
			);

			$image_ids = array_filter( array_map( 'trim', explode( ',', $atts['images'] ) ) );

			if ( empty( $image_ids ) ) {
				return '';
			}

			$gallery_id = 'showcase-gallery-' . uniqid();
			$block_id   = $gallery_id . '-block';

			$items_html = '';
			foreach ( $image_ids as $image_id ) {
				$full_src = wp_get_attachment_image_src( (int) $image_id, 'full' );
				if ( ! $full_src ) {
					continue;
				}

				$items_html .= '
					<div class="item-wrap" data-inview>
						<a href="' . esc_url( $full_src[0] ) . '" class="loop-showcase-gallery glightbox" data-gallery="' . esc_attr( $gallery_id ) . '">
							' . flexx_srcset_image( (int) $image_id, 'flexx-large' ) . '
						</a>
					</div>
				';
			}

			$html = '
				<div class="showcase-gallery wpb_content_element" id="' . esc_attr( $block_id ) . '">
					<div class="showcase-gallery__inner">
						' . $items_html . '
					</div>
				</div>
			';

			return $html;
		}
	}

	new Flexx_VC_Showcase_Gallery();
}

