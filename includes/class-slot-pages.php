<?php

/**
 * Class Slot_Pages
 *
 * Handles all functionality related to slot custom post type,
 * including registration, taxonomies, and meta data.
 */
class Slot_Pages {

	/**
	 * Meta fields definition
	 */
	private $meta_fields = [
		'_slot_rating'    => [
			'label'       => 'Star Rating',
			'type'        => 'number',
			'attributes'  => 'min="1" max="5" step="1"',
			'description' => 'Rate from 1 to 5 stars',
			'sanitize'    => 'intval'
		],
		'_slot_provider'  => [
			'label'    => 'Provider Name',
			'type'     => 'taxonomy_select',
			'taxonomy' => 'slot_provider',
			'sanitize' => 'intval'
		],
		'_slot_rtp'       => [
			'label'       => 'RTP (%)',
			'type'        => 'text',
			'description' => 'Return to player percentage',
			'sanitize'    => 'sanitize_text_field'
		],
		'_slot_min_wager' => [
			'label'      => 'Minimum Wager',
			'type'       => 'number',
			'attributes' => 'step="0.01"',
			'sanitize'   => 'floatval'
		],
		'_slot_max_wager' => [
			'label'      => 'Maximum Wager',
			'type'       => 'number',
			'attributes' => 'step="0.01"',
			'sanitize'   => 'floatval'
		]
	];

	/**
	 * Initialize hooks and actions
	 */
	public function init() {
		// Register custom post type and taxonomy
		add_action( 'init', [ $this, 'register_slot_post_type' ] );
		add_action( 'init', [ $this, 'register_slot_provider_taxonomy' ] );

		// Metabox functionality
		add_action( 'add_meta_boxes_slot', [ $this, 'add_meta_boxes' ] );
		add_action( 'save_post_slot', [ $this, 'save_meta_boxes' ], 10, 2 );

		// Register blocks
		add_action( 'init', [ $this, 'register_blocks' ] );

		// Enqueue admin styles
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
	}

	/**
	 * Register the slot custom post type
	 */
	public function register_slot_post_type(): void {
		$labels = [
			'name'               => __( 'Slots', 'slot-pages' ),
			'singular_name'      => __( 'Slot', 'slot-pages' ),
			'menu_name'          => __( 'Slots', 'slot-pages' ),
			'name_admin_bar'     => __( 'Slot', 'slot-pages' ),
			'add_new'            => __( 'Add New', 'slot-pages' ),
			'add_new_item'       => __( 'Add New Slot', 'slot-pages' ),
			'new_item'           => __( 'New Slot', 'slot-pages' ),
			'edit_item'          => __( 'Edit Slot', 'slot-pages' ),
			'view_item'          => __( 'View Slot', 'slot-pages' ),
			'all_items'          => __( 'All Slots', 'slot-pages' ),
			'search_items'       => __( 'Search Slots', 'slot-pages' ),
			'not_found'          => __( 'No slots found.', 'slot-pages' ),
			'not_found_in_trash' => __( 'No slots found in Trash.', 'slot-pages' ),
		];

		$args = [
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => [ 'slug' => 'slot' ],
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 5,
			'menu_icon'          => 'dashicons-games',
			'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
			'show_in_rest'       => true,
		];

		register_post_type( 'slot', $args );
	}

	/**
	 * Register the slot provider taxonomy
	 */
	public function register_slot_provider_taxonomy(): void {
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

		register_taxonomy( 'slot_provider', [ 'slot' ], $args );
	}

	/**
	 * Enqueue admin styles and scripts
	 *
	 * @param string $hook Current admin page
	 */
	public function enqueue_admin_assets( $hook ): void {
		global $post;

		// Only load on slot post type edit screens
		if ( ! ( $hook === 'post.php' || $hook === 'post-new.php' ) ||
		     ! is_object( $post ) ||
		     $post->post_type !== 'slot' ) {
			return;
		}

		// Enqueue styles as separate file instead of inline
		wp_enqueue_style(
			'slot-admin-styles',
			SLOT_PAGES_PLUGIN_URL . 'assets/css/slot-admin.css',
			[],
			SLOT_PAGES_VERSION
		);
	}

	/**
	 * Add meta boxes for slot details
	 */
	public function add_meta_boxes(): void {
		add_meta_box(
			'slot_details',
			__( 'Slot Details', 'slot-pages' ),
			[ $this, 'render_meta_box' ],
			'slot',
			'normal',
			'default'
		);
	}

	/**
	 * Render the meta box content
	 *
	 * @param WP_Post $post The post object
	 */
	public function render_meta_box( $post ): void {
		// Add nonce for security
		wp_nonce_field( 'slot_meta_box', 'slot_meta_box_nonce' );

		echo '<div class="slots-meta-container">';

		// First row (Rating and Provider)
		echo '<div class="slots-meta-row">';
		$this->render_field( '_slot_rating', $post->ID );
		$this->render_field( '_slot_provider', $post->ID );
		echo '</div>';

		// Second row (RTP, Min and Max Wager)
		echo '<div class="slots-meta-row">';
		$this->render_field( '_slot_rtp', $post->ID );
		$this->render_field( '_slot_min_wager', $post->ID );
		$this->render_field( '_slot_max_wager', $post->ID );
		echo '</div>';

		echo '</div>';
	}

	/**
	 * Render a single meta field
	 *
	 * @param string $key Meta key
	 * @param int $post_id Post ID
	 */
	private function render_field( string $key, int $post_id ): void {
		if ( ! isset( $this->meta_fields[ $key ] ) ) {
			return;
		}

		$field    = $this->meta_fields[ $key ];
		$field_id = str_replace( '_', '', $key );
		$value    = get_post_meta( $post_id, $key, true );

		echo '<div class="slots-meta-field">';
		echo '<label for="' . esc_attr( $field_id ) . '">' . esc_html( $field['label'] ) . '</label>';

		switch ( $field['type'] ) {
			case 'taxonomy_select':
				$this->render_taxonomy_select( $field_id, $field['taxonomy'], $value );
				break;

			default:
				echo '<input type="' . esc_attr( $field['type'] ) . '" id="' . esc_attr( $field_id ) . '" name="' . esc_attr( $field_id ) . '" ' . ( isset( $field['attributes'] ) ? $field['attributes'] : '' ) . ' value="' . esc_attr( $value ) . '" />';
				break;
		}

		if ( ! empty( $field['description'] ) ) {
			echo '<p class="description">' . esc_html( $field['description'] ) . '</p>';
		}

		echo '</div>';
	}

	/**
	 * Render a taxonomy select dropdown
	 *
	 * @param string $field_id Field ID
	 * @param string $taxonomy Taxonomy slug
	 * @param mixed $selected_value Currently selected value
	 */
	private function render_taxonomy_select( $field_id, $taxonomy, $selected_value ): void {
		$terms = get_terms( [
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
		] );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			echo '<input type="text" id="' . esc_attr( $field_id ) . '" name="' . esc_attr( $field_id ) . '" value="" />';

			return;
		}

		echo '<select id="' . esc_attr( $field_id ) . '" name="' . esc_attr( $field_id ) . '">';
		echo '<option value="">' . esc_html__( 'Select a Provider', 'slot-pages' ) . '</option>';

		foreach ( $terms as $term ) {
			echo '<option value="' . esc_attr( $term->term_id ) . '" ' . selected( $selected_value, $term->term_id, false ) . '>' . esc_html( $term->name ) . '</option>';
		}

		echo '</select>';
	}

	/**
	 * Save meta box data
	 *
	 * @param int $post_id The post ID
	 * @param WP_Post $post The post object
	 */
	public function save_meta_boxes( $post_id, $post ): void {
		// Check if nonce is set
		if ( ! isset( $_POST['slot_meta_box_nonce'] ) ) {
			return;
		}

		// Verify nonce
		if ( ! wp_verify_nonce( $_POST['slot_meta_box_nonce'], 'slot_meta_box' ) ) {
			return;
		}

		// If this is autosave, our form has not been submitted
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check user permissions
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Process each field
		foreach ( $this->meta_fields as $meta_key => $field ) {
			$field_id = str_replace( '_', '', $meta_key );

			if ( isset( $_POST[ $field_id ] ) ) {
				$value = $_POST[ $field_id ];

				// Sanitize based on field config
				$sanitize_callback = $field['sanitize'] ?? 'sanitize_text_field';
				$sanitized_value   = $sanitize_callback( $value );

				// Update post meta
				update_post_meta( $post_id, $meta_key, $sanitized_value );

				// Special handling for taxonomy relationships
				if ( $field['type'] === 'taxonomy_select' && ! empty( $value ) ) {
					wp_set_object_terms( $post_id, (int) $value, $field['taxonomy'] );
				}
			}
		}
	}

	/**
	 * Register custom blocks
	 */

	public function register_blocks(): void {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		wp_register_script(
			'slots-grid-editor-script',
			SLOT_PAGES_PLUGIN_URL . 'blocks/slots-grid/index.js',
			[ 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components' ],
			SLOT_PAGES_VERSION,
			true
		);

		wp_register_style(
			'slots-grid-style',
			SLOT_PAGES_PLUGIN_URL . 'blocks/slots-grid/style.css',
			[],
			SLOT_PAGES_VERSION
		);

		register_block_type( SLOT_PAGES_PLUGIN_DIR . 'blocks/slots-grid' );

		// Register slot detail block
		register_block_type( SLOT_PAGES_PLUGIN_DIR . 'blocks/slot-detail' );
	}

}