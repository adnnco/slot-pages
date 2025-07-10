<?php
/**
 * Blocks class.
 *
 * @package SlotPages
 */

namespace SlotPages;

/**
 * Class for managing Gutenberg blocks.
 */
class Blocks {
	/**
	 * Initialize hooks and actions.
	 *
	 * @return void
	 */
	public function init(): void {
		// Register blocks.
		add_action( 'init', [ $this, 'register_blocks' ] );
	}

	/**
	 * Register custom blocks.
	 *
	 * @return void
	 */
	public function register_blocks(): void {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		// Register slots grid block.
		$this->register_slots_grid_block();

		// Register slot detail block.
		$this->register_slot_detail_block();
	}

	/**
	 * Register slots grid block.
	 *
	 * @return void
	 */
	private function register_slots_grid_block(): void {
		wp_register_script(
			'slots-grid-editor-script',
			SLOT_PAGES_PLUGIN_URL . 'blocks/slots-grid/index.js',
			[ 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-block-editor' ],
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
	}

	/**
	 * Register slot detail block.
	 *
	 * @return void
	 */
	private function register_slot_detail_block(): void {
		wp_register_script(
			'slot-detail-editor-script',
			SLOT_PAGES_PLUGIN_URL . 'blocks/slot-detail/index.js',
			[ 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-block-editor', 'wp-i18n', 'wp-data' ],
			SLOT_PAGES_VERSION,
			true
		);

		wp_register_style(
			'slot-detail-style',
			SLOT_PAGES_PLUGIN_URL . 'blocks/slot-detail/style.css',
			[],
			SLOT_PAGES_VERSION
		);

		register_block_type( SLOT_PAGES_PLUGIN_DIR . 'blocks/slot-detail' );
	}

	/**
	 * Format slot meta value for display.
	 *
	 * @param mixed  $value  Meta value.
	 * @param string $format Format type.
	 * @return string Formatted value.
	 */
	public static function format_meta_value( $value, string $format ): string {
		switch ( $format ) {
			case 'stars':
				$stars = str_repeat( '★', min( 5, (int) $value ) ) . str_repeat( '☆', 5 - min( 5, (int) $value ) );
				return '<span class="meta-value star-rating">' . esc_html( $stars ) . '</span>';

			case 'taxonomy':
				$taxonomies = new Taxonomies();
				$term       = $taxonomies->get_provider( (int) $value );
				if ( $term ) {
					return '<span class="meta-value">' . esc_html( $term->name ) . '</span>';
				}
				return '';

			case 'percentage':
				return '<span class="meta-value">' . esc_html( $value ) . '%</span>';

			case 'currency':
				return '<span class="meta-value">' . esc_html( number_format( (float) $value, 2 ) ) . '</span>';

			default:
				return '<span class="meta-value">' . esc_html( $value ) . '</span>';
		}
	}

	/**
	 * Get meta fields configuration for blocks.
	 *
	 * @return array Meta fields configuration.
	 */
	public static function get_meta_fields_config(): array {
		return [
			'_slot_rating'    => [ 'label' => __( 'Rating', 'slot-pages' ), 'format' => 'stars' ],
			'_slot_provider'  => [ 'label' => __( 'Provider', 'slot-pages' ), 'format' => 'taxonomy' ],
			'_slot_rtp'       => [ 'label' => __( 'RTP', 'slot-pages' ), 'format' => 'percentage' ],
			'_slot_min_wager' => [ 'label' => __( 'Min Wager', 'slot-pages' ), 'format' => 'currency' ],
			'_slot_max_wager' => [ 'label' => __( 'Max Wager', 'slot-pages' ), 'format' => 'currency' ],
		];
	}
}
