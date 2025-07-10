<?php
/**
 * Taxonomies class.
 *
 * @package SlotPages
 */

namespace SlotPages;

/**
 * Class for managing custom taxonomies.
 */
class Taxonomies {
	/**
	 * Taxonomy name.
	 *
	 * @var string
	 */
	const TAXONOMY = 'slot_provider';

	/**
	 * Initialize hooks and actions.
	 *
	 * @return void
	 */
	public function init(): void {
		// Register custom taxonomy.
		add_action( 'init', [ $this, 'register_taxonomies' ] );
	}

	/**
	 * Register the slot provider taxonomy.
	 *
	 * @return void
	 */
	public function register_taxonomies(): void {
		$labels = [
			'name'                  => _x( 'Providers', 'taxonomy general name', 'slot-pages' ),
			'singular_name'         => _x( 'Provider', 'taxonomy singular name', 'slot-pages' ),
			'search_items'          => __( 'Search Providers', 'slot-pages' ),
			'all_items'             => __( 'All Providers', 'slot-pages' ),
			'parent_item'           => __( 'Parent Provider', 'slot-pages' ),
			'parent_item_colon'     => __( 'Parent Provider:', 'slot-pages' ),
			'edit_item'             => __( 'Edit Provider', 'slot-pages' ),
			'update_item'           => __( 'Update Provider', 'slot-pages' ),
			'add_new_item'          => __( 'Add New Provider', 'slot-pages' ),
			'menu_name'             => __( 'Providers', 'slot-pages' ),
			'view_item'             => __( 'View Provider', 'slot-pages' ),
			'not_found'             => __( 'No providers found.', 'slot-pages' ),
			'not_found_in_trash'    => __( 'No providers found in Trash.', 'slot-pages' ),
			'items_list_navigation' => __( 'Providers list navigation', 'slot-pages' ),
			'items_list'            => __( 'Providers list', 'slot-pages' ),
		];

		$args = [
			'labels'            => $labels,
			'hierarchical'      => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => [ 'slug' => 'provider' ],
		];

		register_taxonomy( self::TAXONOMY, [ PostTypes::POST_TYPE ], $args );
	}

	/**
	 * Get provider term by ID.
	 *
	 * @param int $term_id Term ID.
	 * @return \WP_Term|null Term object or null if not found.
	 */
	public function get_provider( int $term_id ): ?\WP_Term {
		$term = get_term( $term_id, self::TAXONOMY );
		
		if ( is_wp_error( $term ) || ! $term ) {
			return null;
		}
		
		return $term;
	}

	/**
	 * Get all providers.
	 *
	 * @param array $args Optional. Arguments to get_terms().
	 * @return array Array of WP_Term objects.
	 */
	public function get_providers( array $args = [] ): array {
		$default_args = [
			'taxonomy'   => self::TAXONOMY,
			'hide_empty' => false,
		];
		
		$args = wp_parse_args( $args, $default_args );
		$terms = get_terms( $args );
		
		if ( is_wp_error( $terms ) || ! $terms ) {
			return [];
		}
		
		return $terms;
	}
}