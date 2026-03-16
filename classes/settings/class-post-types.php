<?php

/**
 * Plugin class to generate custom post types
 *
 * @link        https://flexxmarketing.nl/
 * @since       1.0.0
 *
 * @package     Flexx_Client_Plugin
 * @subpackage  Flexx_Client_Plugin/Functions
 */

namespace Flexx_Client_Plugin\Settings;

// No direct access
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Post_Types {

	/**
	 * Function: Post_Types constructor
	 *
	 * @param $post_types
	 */

	public function __construct( $post_types ) {

		// For each of the passed post types, run the create post type function and init the action(s)
		foreach ( $post_types as $t ) {
			add_action( 'init', function () use ( $t ) {
				$this->create_post_type( $t );
			} );
		}

		// Add post states for post types
		add_filter( 'display_post_states', array( $this, 'set_post_states' ), 10, 2 );

	}


	/**
	 * Function to generate custom post types
	 *
	 * @param $settings
	 *
	 * @since 1.0.0
	 */

	public function create_post_type( $settings ) {

		// Check if post type should have a detail page
		if ( $settings['rewrite'] ) {
			$rewrite = is_array( $settings['rewrite'] ) ? $settings['rewrite'] : array(
				'slug'       => $settings['rewrite'],
				'with_front' => false,
				'pages'      => true,
				'feeds'      => false,
			);
		} else {
			$rewrite = false;
		}

		// Check if post type should have an archive page
		$archive = $settings['archive'] ? $settings['archive'] : false;

		// Check if post type has custom support
		$supports = array_key_exists('supports', $settings) ? $settings['supports'] : array( 'title', 'editor' );

		// Check if post type should be hierarchical
		$hierarchical = array_key_exists( 'hierarchical', $settings ) ? $settings['hierarchical'] : false;

		// Set up icon fill color
		if (strpos($settings['icon'], '<svg') !== false) {
			$icon = preg_replace('/<path([^>]*)fill="[^"]*"([^>]*)>/', '<path$1$2>', $settings['icon']);
			$icon = str_replace('path', 'path fill="#fff"', $icon);
			$icon = 'data:image/svg+xml;base64,' . base64_encode( $icon );
		} else {
            $icon = $settings['icon'];
        }

		// Set up labels for post type
		$labels = array(
			'name'                  => _x( $settings['name'], "Post Type General Name", "flexx-client-plugin" ),
			'singular_name'         => _x( $settings['singular'], "Post Type Singular Name", "flexx-client-plugin" ),
			'menu_name'             => $settings['menu_name'],
			'name_admin_bar'        => $settings['menu_name'],

			// Singular
			'add_new_item'          => "Nieuwe " . strtolower( $settings['singular'] ),
			'new_item'              => "Nieuwe " . strtolower( $settings['singular'] ),

			// Plural
			'view_items'            => "Bekijk " . strtolower( $settings['name'] ),
			'search_items'          => "Zoek " . strtolower( $settings['name'] ),
			'items_list'            => "Lijst met " . strtolower( $settings['name'] ),
			'filter_items_list'     => "Lijst met " . strtolower( $settings['name'] ),

			// General
			'archives'              => "Archief",
			'attributes'            => "Attributen",
			'parent_item_colon'     => "Bovenliggend item",
			'all_items'             => "Alle items",
			'add_new'               => "Nieuwe toevoegen",
			'edit_item'             => "Bewerken",
			'update_item'           => "Bijwerken",
			'view_item'             => "Bekijk",
			'not_found'             => "Niet gevonden",
			'not_found_in_trash'    => "Niet gevonden in prullenbak",
			'featured_image'        => "Afbeelding",
			'set_featured_image'    => "Kies afbeelding",
			'remove_featured_image' => "Verwijder",
			'use_featured_image'    => "Gebruik",
			'insert_into_item'      => "Invoegen",
			'uploaded_to_this_item' => "Geüpload",
			'items_list_navigation' => "Lijst navigatie",
		);

		// Set up arguments for post type
		$args = array(
			'label'               => $settings['singular'],
			'labels'              => $labels,
			'supports'            => $supports,
			'hierarchical'        => $hierarchical,
			'public'              => $settings['public'],
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 20,
			'menu_icon'           => $icon,
			'show_in_admin_bar'   => $settings['show_in_admin_bar'],
			'show_in_nav_menus'   => $settings['show_in_nav_menus'],
			'can_export'          => true,
			'has_archive'         => $archive,
			'exclude_from_search' => $settings['exclude_from_search'],
			'publicly_queryable'  => $settings['publicly_queryable'],
			'rewrite'             => $rewrite,
			'capability_type'     => 'page',
			'show_in_rest'        => true,
		);

		register_post_type( strtolower( $settings['post_type_key'] ), $args );

	}


	/**
	 * Function: set post states (archive pages) for custom post type archives
	 *
	 * @since 1.0.0
	 */

	public function set_post_states( $states, $post ) {

		// Get the post types
		$post_types = get_option( 'flexx-post-types' );

		// Add post states for every post type with archive page
        foreach ( $post_types as $k => $v ) {
            $object = get_post_type_object( $k );

            if ( ! $object ) {
                continue; // Skip if post type is invalid
            }

            if ( is_bool( $object->has_archive ) ) {
                $slug = $object->has_archive ? $object->rewrite['slug'] : $object->has_archive;
            } else {
                $slug = false;
            }

            if ( $slug ) {
                $page = get_page_by_path( $slug );
                if ( $page && $page->ID === $post->ID ) {
                    $states[ 'flexx_' . $slug ] = 'Overzichtspagina ' . strtolower( $object->label );
                }
            }
        }

    }

}
