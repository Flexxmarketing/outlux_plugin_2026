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

if ( ! class_exists( __NAMESPACE__ . '\Flexx_VC_Recent_Posts' ) ) {

    class Flexx_VC_Recent_Posts extends Flexx_VC_Element_Template {

        /**
         * Get the shortcode name
         *
         * @return string
         */
        function shortcode_name(): string {
            return 'flexx-recent-posts';
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
                "name"                    => "Recent blogs",
                "base"                    => $this->shortcode_name(),
                "class"                   => "flexx-custom-vc-item",
                "description"             => "Voegt een overzicht van recente blogs toe.",
                "content_element"         => true,
                "show_settings_on_create" => true,
                "icon"                    => FLEXX_CP_IMG_URL . 'vc-icon.svg',
                "category"                => "Flexx",
                'admin_enqueue_js'        => FLEXX_CP_ASSETS_URL . '/js/vc-element-view.js',
                'admin_enqueue_css'       => FLEXX_CP_ASSETS_URL . '/css/vc-element-view.css',
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

                    array(
                        "type"        => "vc_link",
                        "heading"     => "Knop (optioneel)",
                        "param_name"  => "button",
                        "description" => "Kies de knop instellingen.",
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

            $html = $title = $button = '';

            extract( shortcode_atts( array(
                'title' => '',
                'button' => '',
            ), $atts ) );

            $button_html = '';
            if ( $button ) {
                $button_link = vc_build_link( $button );
                $button_html .= '<div class="button">';
                $button_html .= flexx_button( array(
                    'text'   => $button_link['title'],
                    'url'    => $button_link['url'],
                    'class'  => 'btn btn--light',
                    'target' => $button_link['target'],
                    'echo'   => false,
                ) );
                $button_html .= '</div>';
            }

            // Fetch posts
            $args = array(
                'post_type'      => 'post',
                'posts_per_page' => 3,
                'orderby'        => 'date',
                'order'          => 'DESC',
            );

            // If current page is post remove it from the list
            if ( is_singular( 'post' ) ) {
                $args['post__not_in'] = array( get_the_ID() );
            }

            // Get posts with same category as current post
//            if ( is_singular( 'post' ) ) {
//                $categories = wp_get_post_categories( get_the_ID() );
//                if ( ! empty( $categories ) ) {
//                    $args['category__in'] = $categories;
//                }
//            }

            $blogs_html = '';
            $blogs = new WP_Query( $args );

            if ( $blogs->have_posts() ) {

                while ( $blogs->have_posts() ) {
                    $blogs->the_post();

                    ob_start();
                    get_template_part( 'templates/loop-post' );
                    $blogs_html .= '<div class="item-wrap">' . ob_get_clean() . '</div>';
                }

                wp_reset_postdata();
            }


            $html .= '
                <div class="recent-posts wpb_content_element">
                    
                    <div class="recent-posts__header" data-reveal>
				        <h2 class="title">' . $title . '</h2>
				        ' . $button_html . '
                    </div>
                    
                    <div class="recent-posts__wrapper" data-group-reveal>
                        ' . $blogs_html . '
                    </div>
                    
                    <div class="recent-posts__footer" data-reveal>
                        ' . $button_html . '
                    </div>
                    
                </div>
			 ';

            return $html;

        }

    }

    new Flexx_VC_Recent_Posts();
}
