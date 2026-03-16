<?php
/**
 * Abstract plugin class to handle base setup for custom Visual Composer elements
 *
 * @link        https://flexxmarketing.nl/
 * @since       1.0.0
 *
 * @package     Flexx_Client_Plugin
 * @subpackage  Flexx_Client_Plugin/VC_Elements/Base
 */

namespace Flexx_Client_Plugin\VC_Elements\Template;

// No direct access
if ( ! defined( 'WPINC' ) ) {
	die;
}

abstract class Flexx_VC_Element_Template {

	/**
	 * Function: Flexx_VC_Element_Base constructor
	 */

	public function __construct() {

		add_shortcode( $this->shortcode_name() , array( $this, 'register_shortcode' ) );
		add_action( 'vc_before_init', array( $this, 'maybe_vc_map_shortcode' ) );

	}

	/**
	 * Wrapper that conditionally registers the VC element in the admin element picker.
	 */
	public function maybe_vc_map_shortcode() {
		if ( ! $this->is_available_in_vc_editor() ) {
			return;
		}

		$this->vc_map_shortcode();
	}

	/**
	 * Override in child elements to restrict where the element is available in WPBakery.
	 *
	 * Supported rules:
	 * - only_on_post_type: string
	 * - only_on_archive_page_of: string (CPT key, resolved to a WP Page by rewrite slug)
	 * - only_on_top_level: bool (post_parent must be 0; post-new allowed)
	 * - only_on_child: bool (post_parent must not be 0; requires existing post)
	 *
	 * @return array
	 */
	protected function editor_visibility_rules(): array {
		return array();
	}

	/**
	 * Evaluate editor visibility rules for current edit context.
	 */
	protected function is_available_in_vc_editor(): bool {
		$rules = $this->editor_visibility_rules();
		if ( empty( $rules ) ) {
			return true;
		}

		$ctx = self::get_current_edit_context();

		if ( isset( $rules['only_on_post_type'] ) && is_string( $rules['only_on_post_type'] ) ) {
			if ( $ctx['post_type'] !== $rules['only_on_post_type'] ) {
				return false;
			}
		}

		if ( isset( $rules['only_on_archive_page_of'] ) && is_string( $rules['only_on_archive_page_of'] ) ) {
			$archive_page_id = self::get_archive_page_id_for_post_type( $rules['only_on_archive_page_of'] );
			if ( ! $archive_page_id || $ctx['post_type'] !== 'page' || (int) $ctx['post_id'] !== (int) $archive_page_id ) {
				return false;
			}
		}

		if ( ! empty( $rules['only_on_top_level'] ) ) {
			// Allow post-new (no ID yet). Once saved, we can enforce based on parent.
			if ( (int) $ctx['post_id'] !== 0 && (int) $ctx['post_parent'] !== 0 ) {
				return false;
			}
		}

		if ( ! empty( $rules['only_on_child'] ) ) {
			// Require an existing post with a parent.
			if ( (int) $ctx['post_id'] === 0 || (int) $ctx['post_parent'] === 0 ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Determine current post context while editing in wp-admin/WPBakery.
	 *
	 * @return array{post_id:int,post_type:string,post_parent:int}
	 */
	private static function get_current_edit_context(): array {
		$post_id = 0;

		if ( isset( $_GET['post'] ) ) {
			$post_id = (int) $_GET['post'];
		} elseif ( isset( $_POST['post_id'] ) ) {
			$post_id = (int) $_POST['post_id'];
		} elseif ( isset( $_REQUEST['post_id'] ) ) {
			$post_id = (int) $_REQUEST['post_id'];
		} elseif ( isset( $_REQUEST['post'] ) ) {
			$post_id = (int) $_REQUEST['post'];
		}

		$post_type   = '';
		$post_parent = 0;

		if ( $post_id > 0 ) {
			$post = get_post( $post_id );
			if ( $post ) {
				$post_type   = (string) $post->post_type;
				$post_parent = (int) $post->post_parent;
			}
		}

		if ( $post_type === '' ) {
			if ( isset( $_GET['post_type'] ) ) {
				$post_type = (string) $_GET['post_type'];
			} else {
				$post_type = 'post';
			}
		}

		return array(
			'post_id'     => $post_id,
			'post_type'   => $post_type,
			'post_parent' => $post_parent,
		);
	}

	/**
	 * Map a CPT archive to its WP Page (by rewrite slug), per this project convention.
	 */
	private static function get_archive_page_id_for_post_type( string $post_type ): int {
		$obj = get_post_type_object( $post_type );
		if ( ! $obj || empty( $obj->has_archive ) ) {
			return 0;
		}

		if ( empty( $obj->rewrite ) || empty( $obj->rewrite['slug'] ) ) {
			return 0;
		}

		$page = get_page_by_path( $obj->rewrite['slug'] );
		return $page ? (int) $page->ID : 0;
	}


	/**
	 * Functions: register abstract functions to force use of these in child elements
	 */
	abstract function shortcode_name();
	public abstract function register_shortcode( $atts, $content = false );
	public abstract function vc_map_shortcode();

}
