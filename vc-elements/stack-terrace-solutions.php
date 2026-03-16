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

if ( ! class_exists( __NAMESPACE__ . '\Flexx_VC_Stack_Terrace_Solutions' ) ) {

    class Flexx_VC_Stack_Terrace_Solutions extends Flexx_VC_Element_Template {

        /**
         * Get the shortcode name
         *
         * @return string
         */
        function shortcode_name(): string {
            return 'flexx-stack-terrace-solutions';
        }

        protected function editor_visibility_rules(): array {
            return array(
                'only_on_archive_page_of' => 'terrace-solution',
            );
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
                "name"                    => "Overkappingen",
                "base"                    => $this->shortcode_name(),
                "class"                   => "flexx-custom-vc-item",
                "description"             => "Hiermee toon je de overkappingen",
                "content_element"         => true,
                "show_settings_on_create" => true,
                "icon"                    => FLEXX_CP_IMG_URL . 'vc-icon.svg',
                "category"                => "Flexxmarketing",
                'admin_enqueue_js'        => FLEXX_CP_ASSETS_URL . '/js/vc-element-view.js',
                'admin_enqueue_css'       => FLEXX_CP_ASSETS_URL . '/css/vc-element-view.css',
                'js_view'                 => 'VcCustomElementView',
                "params"                  => array(

                    array(
                        "type"        => "textarea_html",
                        "heading"     => "Tekst",
                        "param_name"  => "content",
                        "description" => "Vul hier de tekst in die je wilt tonen bij de overkappingen.",
                        "group"       => "Algemeen",
                        "holder"      => "div",
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

            $html = '';

            wp_enqueue_script( 'flexx-vendor-swiper' );
            wp_enqueue_script( 'flexx-custom-swiper' );

            // Initialize an array to hold the post data
            $posts_data     = [];
            $post_type_slug = 'terrace-solution';
            // Check if we're on the archive page for the specified post type
            if ( is_post_type_archive( $post_type_slug ) && have_posts() ) {
                while ( have_posts() ) {
                    the_post();

                    // Skip posts that have a parent (not top-level)
                    if ( get_post()->post_parent !== 0 ) {
                        continue;
                    }

                    $post_meta    = flexx_get_post_meta();
                    $posts_data[] = array(
                        'title' => get_the_title(),
                        'link'  => array(
                            'url'    => get_permalink(),
                            'text'   => flexx_get_single_meta( 'stack_link-text', $post_meta ) ?: __('Bekijk overkapping', 'flexx-client-plugin')
                        ),
                        'image' => flexx_get_single_meta( 'stack_image', $post_meta ) ?: get_post_thumbnail_id(),
                    );
                }
            } else {
                $args = array(
                    'post_type'      => $post_type_slug,
                    'posts_per_page' => -1,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                    'post_parent'    => 0, // This ensures only top-level items are fetched
                );

                $custom_query = new WP_Query( $args );

                if ( $custom_query->have_posts() ) {
                    while ( $custom_query->have_posts() ) {
                        $custom_query->the_post();

                        // Added meta retrieval here as it was missing in your original else-block
                        $post_meta = flexx_get_post_meta();

                        $posts_data[] = array(
                            'title' => get_the_title(),
                            'link'  => array(
                                'url'    => get_permalink(),
                                'text'   => flexx_get_single_meta( 'stack_link-text', $post_meta ) ?: 'View terrace solution'
                            ),
                            'image' => flexx_get_single_meta( 'stack_image', $post_meta ) ?: get_post_thumbnail_id(),
                        );
                    }
                    wp_reset_postdata();
                }
            }

            $stacks_html = '';
            foreach ( $posts_data as $post ) {
                $stacks_html .= '
                    <div class="swiper-slide" data-title="' . $post['title'] . '">
                        <div class="loop-stack-terrace-solution">
                            <div class="loop-stack-terrace-solution__image">
                                ' . flexx_srcset_image( $post['image'], 'flexx-huge' ) . '
                            </div>
                            <div class="loop-stack-terrace-solution__content">
                                <h3 class="title">' . $post['title'] . '</h3>
                                <div class="button-wrapper">
                                    <a href="' . $post['link']['url'] . '" class="btn btn--cta">
                                        ' . $post['link']['text'] . '
                                        ' . flexx_get_icon( 'arrow-right-alt', '', false ) . '
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                ';
            }

            $html .= '
				<div class="stack-terrace-solutions wpb_content_element">
				    <div class="stack-terrace-solutions__content" data-inview>
				        ' . wpautop( $content ) . '
                    </div>
				    <div class="stack-terrace-solutions__pagination" data-inview>
				        <div class="swiper-terrace-pagination"></div>
                    </div>
					<div class="stack-terrace-solutions__inner" data-inview>
					    <div class="wide-container">
					        <div class="swiper stack-terrace-swiper">
                                <div class="swiper-wrapper">
                                    ' . $stacks_html . '
                                </div>
                            </div>
                        </div>
                    </div>
				</div>
			';

            return $html;

        }

    }

    new Flexx_VC_Stack_Terrace_Solutions();
}
