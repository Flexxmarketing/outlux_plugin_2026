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

class Taxonomies {

	/**
	 * Function: Post_Types constructor
	 *
	 * @param $taxonomies
	 */

	public function __construct( $taxonomies ) {

		// For each of the passed post types, run the create post type function and init the action(s)
		foreach ( $taxonomies as $t ) {

			add_action( 'init', function () use ( $t ) {
				$this->create_taxonomy( $t );
			} );
		}

	}


	/**
	 * Function to generate custom post types
	 *
	 * @param $settings
	 *
	 * @since 1.0.0
	 */

	public function create_taxonomy( $settings ) {

		$rewrite = $settings['rewrite'] ? array (
			'slug' => $settings['rewrite'],
			'with_front' => false,
			'hierarchical' => true
		) : false;

		$labels = array(
			'name'                       => _x( $settings['name'], 'Taxonomy General Name', "flexx-client-plugin" ),
			'singular_name'              => _x( $settings['singular'], 'Taxonomy Singular Name', "flexx-client-plugin" ),
			'menu_name'                  => $settings['menu_name'],
			'all_items'                  => 'Alle ' . $settings['name'],
			'parent_item'                => 'Bovenliggende ' . strtolower($settings['singular']),
			'parent_item_colon'          => 'Bovenliggende '. strtolower($settings['singular']) .':',
			'new_item_name'              => 'Nieuwe ' . strtolower($settings['singular']),
			'add_new_item'               => 'Nieuwe toevoegen',
			'edit_item'                  => 'Bewerken',
			'update_item'                => 'Bijwerken',
			'view_item'                  => 'Bekijk',
			'separate_items_with_commas' => 'Gescheiden door komma\'s',
			'add_or_remove_items'        => 'Toevoegen of verwijderen',
			'choose_from_most_used'      => 'Kies uit de meest gebruikte',
			'popular_items'              => 'Populaire ' . strtolower($settings['name']),
			'search_items'               => 'Zoek',
			'not_found'                  => 'Niet gevonden',
			'no_terms'                   => 'Geen ' . strtolower($settings['name']),
			'items_list'                 =>  $settings['singular'] . ' lijst',
			'items_list_navigation'      =>  $settings['singular'] . ' lijst navigatie',
		);

		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => $settings['hierarchical'],
			'public'                     => $settings['public'],
			'show_ui'                    => true,
			'show_admin_column'          => $settings['show_admin_column'],
			'show_in_nav_menus'          => $settings['show_in_nav_menus'],
			'show_tagcloud'              => false,
			'rewrite'                    => $rewrite,
		);

		register_taxonomy( $settings['taxonomy_key'], $settings['post_types'], $args );

	}

}
