<?php
/**
 * Post Types class.
 *
 * @package SlotPages
 */

namespace SlotPages;

/**
 * Class for managing custom post types.
 */
class PostTypes {
	/**
	 * Post type name.
	 *
	 * @var string
	 */
	const POST_TYPE = 'slot';

	/**
	 * Meta fields definition.
	 *
	 * @var array
	 */
	private array $meta_fields = [
		'_slot_rating'    => [
			'label'       => 'Star Rating',
			'type'        => 'number',
			'attributes'  => 'min="1" max="5" step="1"',
			'description' => 'Rate from 1 to 5 stars',
			'sanitize'    => 'intval',
		],
		'_slot_provider'  => [
			'label'    => 'Provider Name',
			'type'     => 'taxonomy_select',
			'taxonomy' => 'slot_provider',
			'sanitize' => 'intval',
		],
		'_slot_rtp'       => [
			'label'       => 'RTP (%)',
			'type'        => 'number',
			'attributes'  => 'min="0" max="100" step="0.1"',
			'description' => 'Return to player percentage',
			'sanitize'    => 'floatval',
		],
		'_slot_min_wager' => [
			'label'      => 'Minimum Wager',
			'type'       => 'number',
			'attributes' => 'step="0.01"',
			'sanitize'   => 'floatval',
		],
		'_slot_max_wager' => [
			'label'      => 'Maximum Wager',
			'type'       => 'number',
			'attributes' => 'step="0.01"',
			'sanitize'   => 'floatval',
		],
	];

	/**
	 * Initialize hooks and actions.
	 *
	 * @return void
	 */
	public function init(): void {
		// Register custom post type.
		add_action( 'init', [ $this, 'register_post_types' ] );

		// Metabox functionality.
		add_action( 'add_meta_boxes_' . self::POST_TYPE, [ $this, 'add_meta_boxes' ] );
		add_action( 'save_post_' . self::POST_TYPE, [ $this, 'save_meta_boxes' ], 10, 2 );
	}

	/**
	 * Register the slot custom post type.
	 *
	 * @return void
	 */
	public function register_post_types(): void {
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

		register_post_type( self::POST_TYPE, $args );
	}

	/**
	 * Add meta boxes for slot attributes.
	 *
	 * @return void
	 */
	public function add_meta_boxes(): void {
		add_meta_box(
			'slot_attributes',
			__( 'Slot Attributes', 'slot-pages' ),
			[ $this, 'render_meta_box' ],
			self::POST_TYPE,
			'normal',
			'default'
		);
	}

	/**
	 * Render the meta box content.
	 *
	 * @param \WP_Post $post The post object.
	 * @return void
	 */
	public function render_meta_box( \WP_Post $post ): void {
		// Add nonce for security.
		wp_nonce_field( 'slot_meta_box', 'slot_meta_box_nonce' );

		echo '<div class="slots-meta-container">';

		// First row (Rating and Provider).
		echo '<div class="slots-meta-row">';
		$this->render_field( '_slot_rating', $post->ID );
		$this->render_field( '_slot_provider', $post->ID );
		echo '</div>';

		// Second row (RTP, Min and Max Wager).
		echo '<div class="slots-meta-row">';
		$this->render_field( '_slot_rtp', $post->ID );
		$this->render_field( '_slot_min_wager', $post->ID );
		$this->render_field( '_slot_max_wager', $post->ID );
		echo '</div>';

		echo '</div>';
	}

	/**
	 * Render a single meta field.
	 *
	 * @param string $key Meta key.
	 * @param int    $post_id Post ID.
	 * @return void
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
	 * Render a taxonomy select dropdown.
	 *
	 * @param string $field_id Field ID.
	 * @param string $taxonomy Taxonomy slug.
	 * @param mixed  $selected_value Currently selected value.
	 * @return void
	 */
	private function render_taxonomy_select( string $field_id, string $taxonomy, $selected_value ): void {
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
	 * Save meta box data.
	 *
	 * @param int      $post_id The post ID.
	 * @param \WP_Post $post The post object.
	 * @return void
	 */
	public function save_meta_boxes( int $post_id, \WP_Post $post ): void {
		// Check if nonce is set.
		if ( ! isset( $_POST['slot_meta_box_nonce'] ) ) {
			return;
		}

		// Verify nonce.
		if ( ! wp_verify_nonce( $_POST['slot_meta_box_nonce'], 'slot_meta_box' ) ) {
			return;
		}

		// If this is autosave, our form has not been submitted.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check user permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Process each field.
		foreach ( $this->meta_fields as $meta_key => $field ) {
			$field_id = str_replace( '_', '', $meta_key );

			if ( isset( $_POST[ $field_id ] ) ) {
				$value = $_POST[ $field_id ];

				// Sanitize based on field config.
				$sanitize_callback = $field['sanitize'] ?? 'sanitize_text_field';
				$sanitized_value   = $sanitize_callback( $value );

				// Update post meta.
				update_post_meta( $post_id, $meta_key, $sanitized_value );

				// Special handling for taxonomy relationships.
				if ( $field['type'] === 'taxonomy_select' && ! empty( $value ) ) {
					wp_set_object_terms( $post_id, (int) $value, $field['taxonomy'] );
				}
			}
		}
	}

	/**
	 * Get meta fields definition.
	 *
	 * @return array Meta fields definition.
	 */
	public function get_meta_fields(): array {
		return $this->meta_fields;
	}
}