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

if ( ! class_exists( __NAMESPACE__ . '\Flexx_VC_Recent_Realizations' ) ) {

    class Flexx_VC_Recent_Realizations extends Flexx_VC_Element_Template {

        /**
         * Get the shortcode name
         *
         * @return string
         */
        function shortcode_name(): string {
            return 'flexx-recent-realizations';
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

            // Build list of realizations for manual selection.
            $realizations_choices = array();
            $realizations_posts   = get_posts( array(
                'post_type'      => 'realizations',
                'posts_per_page' => -1,
                'orderby'        => 'title',
                'order'          => 'ASC',
                'post_status'    => 'publish',
            ) );

            if ( ! empty( $realizations_posts ) ) {
                foreach ( $realizations_posts as $post ) {
                    $realizations_choices[ get_the_title( $post ) ] = $post->ID;
                }
            }

            vc_map( array(
                "name"                    => "Realisaties overzicht",
                "base"                    => $this->shortcode_name(),
                "class"                   => "flexx-custom-vc-item",
                "description"             => "Voegt een overzicht toe van recente realisaties.",
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
                        "description" => "Vul de tekst in.",
                        "group"       => "Algemeen",
                        "holder"      => "div",
                    ),

                    array(
                        "type"        => "textfield",
                        "heading"     => "Tekst boven de knop",
                        "param_name"  => "button_text",
                        "description" => "Vul hier de tekst in die boven de knop getoond moet worden.",
                        "group"       => "Algemeen",
                    ),

                    array(
                        "type"        => "vc_link",
                        "heading"     => "Knop (optioneel)",
                        "param_name"  => "button",
                        "description" => "Kies de knop instellingen.",
                        "group"       => "Algemeen",
                    ),

                    array(
                        "type"        => "dropdown",
                        "heading"     => "Selectiemodus realisaties",
                        "param_name"  => "selection_mode",
                        "description" => "Kies of realisaties automatisch (laatste) of handmatig geselecteerd worden.",
                        "group"       => "Algemeen",
                        "value"       => array(
                            "Automatisch (laatste realisaties)"          => "auto",
                            "Handmatig geselecteerde realisaties (max 4)" => "manual",
                        ),
                        "std"         => "auto",
                    ),

                    array(
                        "type"        => "dual_listbox",
                        "heading"     => "Geselecteerde realisaties",
                        "param_name"  => "manual_realizations",
                        "description" => "Kies hier maximaal 4 realisaties die je wilt tonen.",
                        "group"       => "Algemeen",
                        "value"       => $realizations_choices,
                        "dependency"  => array(
                            "element" => "selection_mode",
                            "value"   => array( "manual" ),
                        ),
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

            $html = $button_text = $button = $selection_mode = $manual_realizations = '';

            extract( shortcode_atts( array(
                'button_text'         => '',
                'button'              => '',
                'selection_mode'      => 'auto',
                'manual_realizations' => '',
            ), $atts ) );

            $button_html = '';
            if ( $button ) {
                $button_link = vc_build_link( $button );
                $button_html .= '<div class="button">';
                $button_html .= flexx_button( array(
                    'text'   => $button_link['title'],
                    'url'    => $button_link['url'],
                    'class'  => 'btn btn--outline',
                    'target' => $button_link['target'],
                    'echo'   => false,
                ) );
                $button_html .= '</div>';
            }

            // Fetch posts
            if ( $selection_mode === 'manual' && ! empty( $manual_realizations ) ) {
                $ids = array_filter( array_map( 'intval', explode( ',', $manual_realizations ) ) );
                $ids = array_values( array_unique( $ids ) );
                $ids = array_slice( $ids, 0, 4 ); // max 4

                $args = array(
                    'post_type'      => 'realizations',
                    'posts_per_page' => count( $ids ),
                    'post__in'       => $ids,
                    'orderby'        => 'post__in',
                );
            } else {
                $args = array(
                    'post_type'      => 'realizations',
                    'posts_per_page' => 4,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                );

                // If current page is post remove it from the list
                if ( is_singular( 'realizations' ) ) {
                    $args['post__not_in'] = array( get_the_ID() );
                }

                // Get posts with same category as current post
                if ( is_singular( 'realizations' ) ) {
                    $categories = get_the_terms( get_post( get_the_ID() ), 'solutions' );
                    $categories = is_array( $categories ) ? wp_list_pluck( $categories, 'term_id' ) : array();
                    if ( ! empty( $categories ) ) {
                        $args['tax_query'] = array(
                            array(
                                'taxonomy' => 'solutions',
                                'field'    => 'term_id',
                                'terms'    => $categories,
                            ),
                        );
                    }
                }
            }

            $realizations_html = '';
            $posts = new WP_Query( $args );

            if ( $posts->have_posts() ) {

                while ( $posts->have_posts() ) {
                    $posts->the_post();

                    ob_start();
                    get_template_part( 'templates/loop-realizations' );
                    $realizations_html .= '<div class="item-wrap" data-inview>' . ob_get_clean() . '</div>';
                }

                wp_reset_postdata();
            }


            $html .= '
                <div class="recent-realizations wpb_content_element">
                    
                    <div class="recent-realizations__header" data-inview>
				        ' . $content . '
                    </div>
                    
                    <div class="recent-realizations__wrapper">
                        ' . $realizations_html . '
                    </div>
                    
                    <div class="recent-realizations__footer" data-inview>
                        ' . ( $button_text ? '<p class="footer-text">' . $button_text . '</p>' : '' ) . '
                        ' . $button_html . '
                    </div>
                    
                </div>
			 ';

            return $html;

        }

    }

    new Flexx_VC_Recent_Realizations();
}
