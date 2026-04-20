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

if ( ! class_exists( __NAMESPACE__ . '\Flexx_VC_Reviews_Overview' ) ) {

    class Flexx_VC_Reviews_Overview extends Flexx_VC_Element_Template {

        /**
         * Get the shortcode name
         *
         * @return string
         */
        function shortcode_name(): string {
            return 'flexx-reviews-overview';
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
                "name"                    => "Google reviews overzicht",
                "base"                    => $this->shortcode_name(),
                "class"                   => "flexx-custom-vc-item",
                "description"             => "Voeg een overzicht toe met Google reviews.",
                "content_element"         => true,
                "show_settings_on_create" => false,
                "icon"                    => 'vc_custom_flexx_icon',
                "category"                => "Flexxmarketing",
                'admin_enqueue_css'       => FLEXX_CP_ASSETS_URL . '/css/vc-element-view.css',
                'admin_enqueue_js'        => FLEXX_CP_ASSETS_URL . '/js/vc-element-view.js',
                'js_view'                 => 'VcCustomElementView',
                "params"                  => array(

                    array(
                        "type"        => "empty",
                        "heading"     => "Geen opties",
                        "param_name"  => "empty",
                        "description" => "Deze shortcode heeft geen opties.",
                        "group"       => "Algemeen",
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

            extract( shortcode_atts( array(), $atts ) );

            // Fetch posts
            $args = array(
                'post_type'      => 'google_review',
                'posts_per_page' => -1,
                'orderby'        => 'date',
                'order'          => 'DESC',
            );

            $review_html = '';
            $review = new WP_Query( $args );

            if ( $review->have_posts() ) {

                while ( $review->have_posts() ) {
                    $review->the_post();

                    ob_start();
                    get_template_part( 'templates/loop-google-reviews-overview' );
                    $review_html .= '
                        <div class="item-wrap" data-inview>
                            ' . ob_get_clean() . '
                        </div>
                    ';
                }

                wp_reset_postdata();
            }

            $place_url = get_option( 'fgr_place_url' ) ?: '';

            $html .= '
                <div class="google-reviews-overview wpb_content_element">
                    
                    <div class="google-reviews-overview__inner">
                        ' . $review_html . '
                    </div>
                    
                </div>
            ';

            return $html;
        }
    }

    new Flexx_VC_Reviews_Overview();
}