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

if ( ! class_exists( __NAMESPACE__ . '\Flexx_VC_Stack_Terrace_Solution_Childeren' ) ) {

    class Flexx_VC_Stack_Terrace_Solution_Childeren extends Flexx_VC_Element_Template {

        /**
         * Get the shortcode name
         *
         * @return string
         */
        function shortcode_name(): string {
            return 'flexx-stack-terrace-solution-children';
        }

        protected function editor_visibility_rules(): array {
            return array(
                'only_on_post_type'  => 'terrace-solution',
                'only_on_top_level'  => true,
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
                "name"                    => "Hoofdpagina overkappingen",
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
            $posts_data       = [];
            $post_type_slug   = 'terrace-solution';
            $current_post_id  = is_singular( $post_type_slug ) ? get_queried_object_id() : 0;
            $parent_post_id   = $current_post_id ?: 0;

            $args = array(
                'post_type'      => $post_type_slug,
                'posts_per_page' => -1,
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
                'post_parent'    => $parent_post_id,
            );

            $custom_query = new WP_Query( $args );

            if ( $custom_query->have_posts() ) {
                while ( $custom_query->have_posts() ) {
                    $custom_query->the_post();

                    $post_id      = get_the_ID();
                    $post_meta    = flexx_get_post_meta();
                    $posts_data[] = array(
                        'title' => flexx_get_single_meta('stack_title', $post_meta) ?: get_the_title(),
                        'subtitle' => flexx_get_single_meta('header_subtitle', $post_meta) ?: '',
                        'link'  => array(
                            'url'  => get_permalink(),
                            'text' => flexx_get_single_meta( 'stack_link-text', $post_meta ) ?: __( 'Bekijk overkapping', 'flexx-client-plugin' ),
                        ),
                        'image' => flexx_get_single_meta( 'stack_image', $post_meta ) ?: get_post_thumbnail_id(),
                        'badges' => get_the_terms( $post_id, 'solution_tags' ) ?: null,
                    );
                }

                wp_reset_postdata();
            }

            $solutions_html = '';
            foreach ( $posts_data as $post ) {
                $badges_html = '';
                if ( ! empty( $post['badges'] ) ) {
                    foreach ( $post['badges'] as $badge ) {
                        $badges_html .= '<span class="badge badge--primary">' . $badge->name . '</span>';
                    }
                }

                $solutions_html .= '
                    <div class="loop-terrace-solution" data-inview>
                        <div class="loop-terrace-solution__image">
                            ' . flexx_srcset_image( $post['image'], 'flexx-huge' ) . '
                        </div>
                        <div class="loop-terrace-solution__content">
                            <h3 class="title">' . $post['title'] . '</h3>
                            ' . ( ! empty( $post['subtitle'] ) ? '<p class="subtitle">' . $post['subtitle'] . '</p>' : '' ) . '
                            <div class="button-wrapper">
                                <a href="' . $post['link']['url'] . '" class="btn btn--cta">
                                    ' . $post['link']['text'] . '
                                    ' . flexx_get_icon( 'arrow-right-alt', '', false ) . '
                                </a>
                            </div>
                        </div>
                        <div class="loop-terrace-solution__badges">
                            ' . $badges_html . '
                        </div>
                    </div>
                ';
            }

            $html .= '
				<div class="terrace-solutions wpb_content_element">
				    <div class="terrace-solutions__content" data-inview>
				        ' . wpautop( $content ) . '
                    </div>
					<div class="terrace-solutions__inner">
					    <div class="wide-container">
					        ' . $solutions_html . '
                        </div>
                    </div>
				</div>
			';

            return $html;

        }

    }

    new Flexx_VC_Stack_Terrace_Solution_Childeren();
}
