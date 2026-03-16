<?php
/**
 * Plugin class to set up custom Visual Composer element with toggles
 *
 * @link        https://flexxmarketing.nl/
 * @since       1.0.0
 *
 * @package     Flexx_Client_Plugin
 * @subpackage  Flexx_Client_Plugin/VC_Elements/Custom
 */

use Flexx_Client_Plugin\VC_Elements\Template\Flexx_VC_Element_Template;

if ( ! defined( 'WPINC' ) ) {
    die;
}

if ( ! class_exists( __NAMESPACE__ . '\Flexx_VC_Reviews_Slider' ) ) {

    class Flexx_VC_Reviews_Slider extends Flexx_VC_Element_Template {

        /**
         * Get the shortcode name
         *
         * @return string
         */
        function shortcode_name(): string {
            return 'flexx-reviews-slider';
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
                "name"                    => "Google reviews",
                "base"                    => $this->shortcode_name(),
                "class"                   => "flexx-custom-vc-item",
                "description"             => "Voeg een slider toe met Google reviews.",
                "content_element"         => true,
                "show_settings_on_create" => true,
                "icon"                    => 'vc_custom_flexx_icon',
                "category"                => "Flexxmarketing",
                'admin_enqueue_css'       => FLEXX_CP_ASSETS_URL . '/css/vc-element-view.css',
                'admin_enqueue_js'        => FLEXX_CP_ASSETS_URL . '/js/vc-element-view.js',
                'js_view'                 => 'VcCustomElementView',
                "params"                  => array(

                    array(
                        "type"        => "textfield",
                        "heading"     => "Titel",
                        "param_name"  => "title",
                        "description" => "Vul hier de titel in.",
                        "group"       => "Algemeen",
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

            $html = $title = $text = '';

            wp_enqueue_script('flexx-vendor-swiper');
            wp_enqueue_script('flexx-custom-swiper');

            extract( shortcode_atts( array(
                'title' => '',
                'text'  => '',
            ), $atts ) );

            // Fetch posts
            $args = array(
                'post_type'      => 'google_review',
                'posts_per_page' => 6,
                'orderby'        => 'date',
                'order'          => 'DESC',
                'skip_empty_content' => true,
            );

            // Check if meta review_image_ids is empty and skip those posts
            $args['meta_query'] = array(
                array(
                    'key'     => 'review_image_ids',
                    'value'   => '',
                    'compare' => '!=',
                ),
            );

            $review_html = '';
            $review = new WP_Query( $args );

            if ( $review->have_posts() ) {

                while ( $review->have_posts() ) {
                    $review->the_post();

                    ob_start();
                    get_template_part( 'templates/loop-google-reviews' );
                    $review_html .= '
                        <div class="swiper-slide">
                            <div class="item-wrap" data-inview>
                                ' . ob_get_clean() . '
                            </div>
                        </div>
                    ';
                }

                wp_reset_postdata();
            }

            $place_url = get_option( 'fgr_place_url' ) ?: '';

            $html .= '
                <div class="google-reviews wpb_content_element">
                    
                    <div class="google-reviews__header" data-inview>
                        <h2 class="title display-2">' . $title . '</h2>
                        <div class="google-reviews-badge">
                            <a href="' . $place_url . '" target="_blank" rel="noopener noreferrer" class="google-reviews-link">
                                <span class="google-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/><path d="M1 1h22v22H1z" fill="none"/></svg>
                                </span>
                                <span class="link-text">' . __( 'Bekijk alle reviews op Google', 'flexx-client-plugin' ) . '</span>
                            </a>
                        </div>
                    </div>
                    
                    <div class="google-reviews__slider">
                        <div class="swiper google-reviews-swiper">
                            <div class="swiper-wrapper">
                                ' . $review_html . '
                            </div>
                        </div>
                        <div class="google-reviews-slider-pagination" data-inview></div>
                    </div>
                    
                </div>
            ';

            return $html;
        }
    }

    new Flexx_VC_Reviews_Slider();
}