<?php
/**
 * Plugin class to set up custom Visual Composer element: Image Gallery Slider
 * Swiper slider with GLightbox for images (attach_images).
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

if ( ! class_exists( __NAMESPACE__ . '\Flexx_VC_Image_Gallery_Slider' ) ) {

	class Flexx_VC_Image_Gallery_Slider extends Flexx_VC_Element_Template {

		/**
		 * Get the shortcode name
		 *
		 * @return string
		 */
		function shortcode_name(): string {
			return 'flexx-image-gallery-slider';
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
					'name'                    => 'Image gallery slider',
					'base'                    => $this->shortcode_name(),
					'class'                   => 'flexx-custom-vc-item',
					'description'             => 'Image gallery slider with Swiper and lightbox (GLightbox).',
					'content_element'         => true,
					'show_settings_on_create' => true,
					'icon'                    => FLEXX_CP_IMG_URL . 'vc-icon.svg',
					'category'                => 'Flexx',
					'admin_enqueue_js'        => FLEXX_CP_ASSETS_URL . '/js/vc-element-view.js',
					'admin_enqueue_css'       => FLEXX_CP_ASSETS_URL . '/css/vc-element-view.css',
					'js_view'                 => 'VcCustomElementView',
					'params'                  => array(
						array(
							'type'        => 'attach_images',
							'heading'     => 'Gallery images',
							'param_name'  => 'images',
							'description' => 'Select or upload images for the gallery slider. Click to open in lightbox.',
							'group'       => 'Algemeen',
							'admin_label' => true,
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

			wp_enqueue_script( 'flexx-vendor-swiper' );
			wp_enqueue_script( 'flexx-custom-swiper' );
			wp_enqueue_script( 'flexx-vendor-glightbox' );

			$atts = shortcode_atts(
				array(
					'images' => '',
				),
				$atts
			);

			$images_html = '';
			$image_ids   = array_filter( array_map( 'trim', explode( ',', $atts['images'] ) ) );
			$block_id    = 'image-gallery-slider-' . uniqid();

			foreach ( $image_ids as $image_id ) {
				$full_src = wp_get_attachment_image_src( (int) $image_id, 'full' );
				$full_url = $full_src ? $full_src[0] : '';
				$images_html .= '
					<div class="swiper-slide">
						<div class="loop-image-gallery-slide">
							<a href="' . esc_url( $full_url ) . '" class="glightbox" data-gallery="' . esc_attr( $block_id ) . '">
								' . flexx_srcset_image( (int) $image_id, 'flexx-huge' ) . '
							</a>
						</div>
					</div>
				';
			}

			$html = '
				<div class="image-gallery-slider wpb_content_element" id="' . esc_attr( $block_id ) . '" data-inview>
					<div class="image-gallery-slider__inner">
						<div class="swiper image-gallery-swiper">
							<div class="swiper-wrapper">
								' . $images_html . '
							</div>
						</div>
					</div>
					<div class="image-gallery-slider__arrows">
					    <div class="image-gallery-swiper-button image-gallery-swiper-button-prev">
                            ' . flexx_get_icon( 'arrow-left-alt', '', false ) . '
                        </div>
                        <div class="image-gallery-swiper-button image-gallery-swiper-button-next">
                            ' . flexx_get_icon( 'arrow-right-alt', '', false ) . '
                        </div>
                    </div>
				</div>
			';

			// Init GLightbox for this block (one gallery per block)
			$html .= "
			<script>
			(function() {
				var block = document.getElementById('" . esc_js( $block_id ) . "');
				if (!block) return;
				function initLightbox() {
					if (typeof GLightbox === 'undefined') return;
					var links = block.querySelectorAll('a.glightbox');
					if (links.length === 0) return;
					new GLightbox({ selector: '#' + block.id + ' a.glightbox' });
				}
				if (document.readyState === 'loading') {
					document.addEventListener('DOMContentLoaded', initLightbox);
				} else {
					initLightbox();
				}
			})();
			</script>
			";

			return $html;
		}
	}
	new Flexx_VC_Image_Gallery_Slider();
}
