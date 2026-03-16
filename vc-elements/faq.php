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

if ( ! class_exists( __NAMESPACE__ . '\Flexx_VC_FAQ' ) ) {

    class Flexx_VC_FAQ extends Flexx_VC_Element_Template {

        /**
         * Get the shortcode name
         *
         * @return string
         */
        function shortcode_name(): string {
            return 'flexx-faq';
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
                "name"                    => "Veelgestelde vragen",
                "base"                    => $this->shortcode_name(),
                "class"                   => "flexx-custom-vc-item",
                "description"             => "Toont de veelgestelde vragen vanuit het FAQ overzicht.",
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

            $html       = '';
            $faqs_html  = '';
            $faqs       = array();
            $post_type  = 'faq';

            // Gebruik de huidige pagina-query op het FAQ archief, zoals bij stack-terrace-solutions.
            if ( is_post_type_archive( $post_type ) && have_posts() ) {
                while ( have_posts() ) {
                    the_post();

                    $faqs[] = array(
                        'title' => get_the_title(),
                        'text'  => get_the_content(),
                    );
                }
            } else {
                // Fallback: losse query voor alle FAQ's (bijv. als shortcode elders gebruikt zou worden).
                $query = new \WP_Query( array(
                    'post_type'      => $post_type,
                    'posts_per_page' => -1,
                    'post_status'    => 'publish',
                    'orderby'        => 'menu_order',
                    'order'          => 'ASC',
                ) );

                if ( $query->have_posts() ) {
                    while ( $query->have_posts() ) {
                        $query->the_post();

                        $faqs[] = array(
                            'title' => get_the_title(),
                            'text'  => get_the_content(),
                        );
                    }

                    wp_reset_postdata();
                }
            }

            $faq_count = count( $faqs );
            $half = ceil( $faq_count / 2 );
            $columns = array_chunk( $faqs, $half );

            foreach ( $columns as $column ) {
                $faqs_html .= '<div class="faq-column" data-group-reveal>';
                foreach ( $column as $faq ) {
                    $faq['title'] = ! empty( $faq['title'] ) ? flexx_replace( $faq['title'], '*', 'strong' ) : '';

                    $faqs_html .= '
                        <div class="loop-faq-item" data-inview>
                            <div class="loop-faq-item__summary" data-cursor-icon="plus">
                                <h3 class="title">' . $faq['title'] . '</h3>
                                ' . flexx_get_icon( 'plus', '', false ) . '
                            </div>
                            <div class="loop-faq-item__content" style="display: none;">
                                ' . wpautop( $faq['text'] ) . '
                            </div>
                        </div>
                    ';
                }
                $faqs_html .= '</div>';
            }

            $html .= '
                <div class="faq-list wpb_content_element">
                    <div class="faq-list__columns">
                        ' . $faqs_html . '
                    </div>
                </div>
            ';

            return $html;
        }
    }

    new Flexx_VC_FAQ();
}