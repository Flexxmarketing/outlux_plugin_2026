<?php

/**
 * Plugin class to handle custom WPBakery Page Builder (Visual Composer) settings
 *
 * @link        https://flexxmarketing.nl/
 * @since       1.0.0
 *
 * @package     Flexx_Client_Plugin
 * @subpackage  Flexx_Client_Plugin/Functions
 */

namespace Flexx_Client_Plugin\Settings;

use WP_Query;
use Flexx_Client_Plugin\Settings\Image_Settings;

// No direct access
if ( ! defined( 'WPINC' ) ) {
    die;
}

class VC_Settings {

    /**
     * @var $custom_vc_params array
     */

    private $custom_vc_params;


    /**
     * Function: VC_Settings constructor
     */

    public function __construct( $custom_vc_params ) {

        $this->custom_vc_params = $custom_vc_params;

        add_action( 'vc_before_init', array( $this, 'remove_standard_elements' ) );
        add_action( 'vc_before_init', array( $this, 'remove_standard_params' ) );
        add_action( 'vc_before_init', array( $this, 'add_custom_params' ) );
        add_action( 'vc_before_init', array( $this, 'custom_param_types' ) );
        add_action( 'vc_before_init', array( $this, 'disable_frontend_editor' ) );
        add_action( 'vc_before_init', array( $this, 'set_post_type_support' ) );
        // Element availability is handled per-element via Flexx_VC_Element_Template::editor_visibility_rules().

    }


    /**
     * Function: remove unnecessary standard Visual Composer elements
     *
     * @since 1.0.0
     */

    public function remove_standard_elements() {
        vc_remove_element( 'vc_section' );
        vc_remove_element( 'vc_icon' );
        vc_remove_element( 'vc_message' );
        vc_remove_element( 'vc_text_separator' );
        vc_remove_element( 'vc_hoverbox' );
        vc_remove_element( 'vc_separator' );
        vc_remove_element( 'vc_tweetmeme' );
        vc_remove_element( 'vc_facebook' );
        vc_remove_element( 'vc_pinterest' );
        vc_remove_element( 'vc_googleplus' );
        vc_remove_element( 'vc_toggle' );
        vc_remove_element( 'vc_gallery' );
        vc_remove_element( 'vc_images_carousel' );
        vc_remove_element( 'vc_tabs' );
        vc_remove_element( 'vc_tour' );
        vc_remove_element( 'vc_accordion' );
        vc_remove_element( 'vc_tta_tabs' );
        vc_remove_element( 'vc_tta_tour' );
        vc_remove_element( 'vc_tta_accordion' );
        vc_remove_element( 'vc_tta_pageable' );
        vc_remove_element( 'vc_tta_section' );
        vc_remove_element( 'vc_custom_heading' );
        vc_remove_element( 'vc_btn' );
        vc_remove_element( 'vc_cta' );
        vc_remove_element( 'vc_widget_sidebar' );
        vc_remove_element( 'vc_posts_slider' );
        vc_remove_element( 'vc_flickr' );
        vc_remove_element( 'vc_progress_bar' );
        vc_remove_element( 'vc_pie' );
        vc_remove_element( 'vc_round_chart' );
        vc_remove_element( 'vc_line_chart' );
        vc_remove_element( 'vc_basic_grid' );
        vc_remove_element( 'vc_media_grid' );
        vc_remove_element( 'vc_masonry_grid' );
        vc_remove_element( 'vc_masonry_media_grid' );
        vc_remove_element( 'vc_gutenberg' );
        vc_remove_element( 'vc_wp_meta' );
        vc_remove_element( 'vc_wp_calendar' );
        vc_remove_element( 'vc_wp_recentcomments' );
        vc_remove_element( 'vc_wp_pages' );
        vc_remove_element( 'vc_wp_tagcloud' );
        vc_remove_element( 'vc_wp_custommenu' );
        vc_remove_element( 'vc_wp_text' );
        vc_remove_element( 'vc_wp_posts' );
        vc_remove_element( 'vc_wp_categories' );
        vc_remove_element( 'vc_wp_rss' );
        vc_remove_element( 'vc_wp_search' );
        vc_remove_element( 'vc_wp_archives' );
        vc_remove_element( 'vc_wp_links' );
        vc_remove_element( 'vc_acf' );
        vc_remove_element( 'vc_empty_space' );
        vc_remove_element( 'vc_zigzag' );
        vc_remove_element( 'vc_goo_maps' );
        vc_remove_element( 'vc_gmaps' );
        vc_remove_element( 'vc_copyright' );
        vc_remove_element( 'vc_pricing_table' );
        vc_remove_element( 'vc_tta_toggle');
        vc_remove_element( 'vc_tta_toggle_section' );
        vc_remove_element( 'vc_video' );
    }


    /**
     * Function: remove certain params for standard VC elements
     *
     * @since 1.0.0
     */

    public function remove_standard_params() {

        // Set array of standard VC elements to remove standard CSS animation(s) from
        $animation_remove = array(
            'vc_row',
            'vc_column',
            'vc_single_image',
            'vc_video',
            'vc_gmaps',
            'vc_column_text',
            'vc_seperator',
            'vc_zigzag',
            'vc_raw_html',
            'vc_empty_space'
        );

        // If function 'vc_remove_param' exists, use it to delete certain params
        if ( function_exists( 'vc_remove_param' ) ) {
            foreach ( $animation_remove as $e ) {
                vc_remove_param( $e, 'css_animation' );
            }
        }

        // Remove params in VC ROWS
        vc_remove_param( 'vc_row', 'rtl_reverse' );
        vc_remove_param( 'vc_row', 'full_width' );
        vc_remove_param( 'vc_row', 'full_height' );
        vc_remove_param( 'vc_row', 'columns_placement' );
        vc_remove_param( 'vc_row', 'video_bg' );
        vc_remove_param( 'vc_row', 'video_bg_url' );
        vc_remove_param( 'vc_row', 'video_bg_parallax' );
        vc_remove_param( 'vc_row', 'parallax' );
        vc_remove_param( 'vc_row', 'parallax_image' );
        vc_remove_param( 'vc_row', 'parallax_speed_bg' );
        vc_remove_param( 'vc_row', 'parallax_speed_video' );
        vc_remove_param( 'vc_row', 'gap' );

        // Remove params in VC COLUMNS
        vc_remove_param( 'vc_column', 'parallax' );
        vc_remove_param( 'vc_column', 'parallax_image' );
        vc_remove_param( 'vc_column', 'parallax_speed_bg' );
        vc_remove_param( 'vc_column', 'video_bg' );
        vc_remove_param( 'vc_column', 'video_bg_url' );
        vc_remove_param( 'vc_column', 'video_bg_parallax' );
        vc_remove_param( 'vc_column', 'parallax_speed_video' );

        // Remove params in VC SINGLE IMAGE
        vc_remove_param( 'vc_single_image', 'img_size' );
        vc_remove_param( 'vc_single_image', 'title' );
        vc_remove_param( 'vc_single_image', 'source' );
        vc_remove_param( 'vc_single_image', 'custom_src' );
        vc_remove_param( 'vc_single_image', 'external_img_size' );
        vc_remove_param( 'vc_single_image', 'external_style' );
        vc_remove_param( 'vc_single_image', 'external_border_color' );
        vc_remove_param( 'vc_single_image', 'add_caption' );
        vc_remove_param( 'vc_single_image', 'caption' );
        vc_remove_param( 'vc_single_image', 'style' );
        vc_remove_param( 'vc_single_image', 'border_color' );
    }


    /**
     * Function: add custom params to standard VC elements
     *
     * @throws \Exception
     * @since 1.0.0
     */

    public function add_custom_params() {

        if ( function_exists( 'vc_add_param' ) ) {

            $custom_vc_params = $this->custom_vc_params;

            if ( ! empty( $custom_vc_params ) ) {
                foreach ( $custom_vc_params as $param ) {

                    $values = array();
                    if ( ! empty( $param['values'] ) ) {
                        foreach ( $param['values'] as $k => $v ) {
                            $values[ $k ] = $v;
                        }
                    }

                    $param_settings = array(
                        'type'        => $param['type'],
                        'heading'     => $param['heading'],
                        'param_name'  => $param['param_name'],
                        'weight'      => $param['weight'],
                        'value'       => $values,
                        'description' => $param['description'],
                    );

                    if ( array_key_exists( 'dependency', $param ) ) {
                        $param_settings['dependency'] = $param['dependency'];
                    }

                    vc_add_param( $param['vc_element'], $param_settings );

                }
            }

        }

    }


    /**
     * Function: add custom param types to VC
     *
     * @since 1.0.0
     */

    public function custom_param_types() {

        if ( function_exists( 'vc_add_shortcode_param' ) ) {
            vc_add_shortcode_param( 'file_picker', array(
                $this,
                'filepicker_param_type'
            ), FLEXX_CP_ASSETS_URL . '/js/vc-file-picker.js' );
            vc_add_shortcode_param( 'dropdown_multi', array( $this, 'dropdown_multi_param_type' ) );
            vc_add_shortcode_param( 'empty', array( $this, 'empty_item_param_type' ) );
            vc_add_shortcode_param( 'dual_listbox', array(
                $this,
                'dual_listbox_param_type'
            ), FLEXX_CP_ASSETS_URL . '/js/vc-dual-listbox.js' );
        }

    }


    /**
     * Function: custom file picker parameter
     *
     * @param $settings
     * @param $value
     *
     * @return string
     *
     * @since 1.0.0
     *
     */

    public function filepicker_param_type( $settings, $value ) {
        $output            = '';
        $select_file_class = '';
        $remove_file_class = ' hidden';
        $attachment_url    = wp_get_attachment_url( $value );

        if ( $attachment_url ) {
            $select_file_class = ' hidden';
            $remove_file_class = '';
        }

        $initial_text = "Kies een bestand met de knop hieronder.";

        $output .= '
		<div class="file_picker_block">
	        <div data-initial-text="' . $initial_text . '" class="' . esc_attr( $settings['type'] ) . '_display" style="font-style: italic; margin-bottom: 15px;">
                ' . ( strlen( trim( $attachment_url ) ) > 0 ? $attachment_url : $initial_text ) . '
	        </div>

	        <input type="hidden" name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .
                   esc_attr( $settings['param_name'] ) . ' ' .
                   esc_attr( $settings['type'] ) . '_field" value="' . esc_attr( $value ) . '" />
	        <button class="button file-picker-button' . $select_file_class . '">' . "Kies bestand" . '</button>
	        <button class="button file-remover-button' . $remove_file_class . '">' . "Verwijder bestand" . '</button>
		</div>
		';

        return $output;
    }


    /**
     * Function: add custom multi select param type
     *
     * @param $param
     * @param $value
     *
     * @return string
     *
     * @since 1.0.0
     *
     */

    public function dropdown_multi_param_type( $param, $value ) {
        $param_line = '';
        $param_line .= '<select multiple name="' . esc_attr( $param['param_name'] ) . '" class="wpb_vc_param_value wpb-input wpb-select ' . esc_attr( $param['param_name'] ) . ' ' . esc_attr( $param['type'] ) . '">';

        foreach ( $param['value'] as $text_val => $val ) {
            if ( is_numeric( $text_val ) && ( is_string( $val ) || is_numeric( $val ) ) ) {
                $text_val = $val;
            }
            $text_val = __( $text_val, "js_composer" );
            $selected = '';

            if ( ! is_array( $value ) ) {
                $param_value_arr = explode( ',', $value );
            } else {
                $param_value_arr = $value;
            }

            if ( $value !== '' && in_array( $val, $param_value_arr ) ) {
                $selected = ' selected="selected"';
            }
            $param_line .= '<option class="' . $val . '" value="' . $val . '" ' . $selected . '>' . $text_val . '</option>';
        }

        $param_line .= '</select>';

        return $param_line;
    }


    /**
     * Function: add custom dual listbox param type
     *
     * @param $param
     * @param $value
     *
     * @return string
     *
     * @since 1.0.0
     *
     */

    public function dual_listbox_param_type( $param, $value ) {
        $selected        = is_array( $value ) ? $value : explode( ',', $value );
        $options         = $param['value'];
        $custom_ordering = $param['custom_ordering'] ?? false;

        $output = '<div class="dual-listbox-wrapper" data-name="' . esc_attr( $param['param_name'] ) . '">';
        $output .= '<div class="dual-listbox">';
        $output .= '<div class="select-wrapper">';
        $output .= '<label class="available-items-label">' . __( 'Beschikbare items', 'js_composer' ) . '</label>';
        $output .= '<select multiple class="available-items">';

        foreach ( $options as $label => $val ) {
            if ( is_numeric( $label ) ) {
                $label = $val;
            }
            if ( ! in_array( $val, $selected ) ) {
                $output .= '<option value="' . esc_attr( $val ) . '">' . esc_html( $label ) . '</option>';
            }
        }

        $output .= '</select>';
        $output .= '</div>';
        $output .= '<div class="dual-listbox-controls">';
        $output .= '<button type="button" class="add">&raquo;</button>';
        $output .= '<button type="button" class="remove">&laquo;</button>';
        $output .= '</div>';
        $output .= '<div class="select-wrapper">';
        $output .= '<label class="selected-items-label">' . __( 'Geselecteerde items', 'js_composer' ) . '</label>';
        $output .= '<select multiple class="selected-items">';

        foreach ( $selected as $val ) {
            // Find the label for this value
            $label = array_search( $val, $options );
            if ( $label === false && isset( $options[ $val ] ) ) {
                // fallback if value is key instead of value
                $label = $val;
                $val   = $options[ $val ];
            }

            if ( $label !== false ) {
                if ( is_numeric( $label ) ) {
                    $label = $val;
                }
                $output .= '<option value="' . esc_attr( $val ) . '">' . esc_html( $label ) . '</option>';
            }
        }

        $output .= '</select>';
        $output .= '</div>';

        if ( ! $custom_ordering ) {
            $output .= '<div class="dual-listbox-controls">';
            $output .= '<button type="button" class="move-up">&uarr;</button>';
            $output .= '<button type="button" class="move-down">&darr;</button>';
            $output .= '</div>';
        }

        $output .= '</div>';

        // hidden input to save final value
        $output .= '<input type="hidden" class="wpb_vc_param_value dual-listbox-hidden ' . esc_attr( $param['param_name'] ) . '" name="' . esc_attr( $param['param_name'] ) . '" value="' . esc_attr( $value ) . '" />';
        $output .= '</div>';

        return $output;
    }


    /**
     * Function: add custom message for empty elements
     *
     * @param $param
     * @param $value
     *
     * @return string
     *
     * @since 1.0.0
     *
     */

    public function empty_item_param_type( $param, $value ) {
        if ( ! empty( $param['description'] ) ) {
            return '<span>' . $param['description'] . '</span>';
        } else {
            return '<span>' . "Dit blok heeft geen instelbare opties." . '</span>';
        }
    }


    /**
     * Function: disable Visual Composer front-end editor
     *
     * @since 1.0.0
     */

    public function disable_frontend_editor() {
        vc_disable_frontend();
    }


    /**
     * Function: set default post types for Visual Composer
     *
     * @since 1.0.0
     */

    public function set_post_type_support() {

        // Set up base post types for VC
        $vc_post_types = array(
            'page',
            'post'
        );

        // Get custom post types
        $post_types = get_option( 'flexx-post-types' );

        // Add custom post types to VC if necessary
        foreach ( $post_types as $k => $v ) {
            if ( $v ) {
                $vc_post_types[] = $k;
            }
        }

        // Run the VC function
        vc_set_default_editor_post_types( $vc_post_types );

    }


    /**
     * Function: helper function to get all the available global blocks
     *
     * @since 1.0.0
     */

    public static function get_global_blocks() {

        if ( ! is_admin() ) return;

        $args = array(
            'post_type'      => 'global_blocks',
            'posts_per_page' => -1,
            'orderby'        => 'title',
        );

        $blocks = new WP_Query( $args );

        $block_list = array(
            'Kies een blok...' => '',
        );

        if ( $blocks->have_posts() ) {
            while ( $blocks->have_posts() ) {
                $blocks->the_post();
                $block_list[ get_the_title() ] = get_the_id();
            }
        }

        wp_reset_query();

        return $block_list;

    }


}
