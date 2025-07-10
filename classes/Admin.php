<?php
/**
 * Admin class.
 *
 * @package SlotPages
 */

namespace SlotPages;

/**
 * Class for managing admin functionality.
 */
class Admin {
	/**
	 * Initialize hooks and actions.
	 *
	 * @return void
	 */
	public function init(): void {
		// Enqueue admin styles and scripts.
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
	}

	/**
	 * Enqueue admin styles and scripts.
	 *
	 * @param string $hook Current admin page.
	 * @return void
	 */
	public function enqueue_admin_assets( string $hook ): void {
		global $post;

		// Only load on slot post type edit screens.
		if ( ! ( $hook === 'post.php' || $hook === 'post-new.php' ) ||
			 ! is_object( $post ) ||
			 $post->post_type !== PostTypes::POST_TYPE ) {
			return;
		}

		// Enqueue styles.
		wp_enqueue_style(
			'slot-admin-styles',
			SLOT_PAGES_PLUGIN_URL . 'assets/css/slot-admin.css',
			[],
			SLOT_PAGES_VERSION
		);
	}

	/**
	 * Add settings page.
	 * 
	 * This is a placeholder for future functionality.
	 *
	 * @return void
	 */
	public function add_settings_page(): void {
		add_submenu_page(
			'edit.php?post_type=' . PostTypes::POST_TYPE,
			__( 'Slot Pages Settings', 'slot-pages' ),
			__( 'Settings', 'slot-pages' ),
			'manage_options',
			'slot-pages-settings',
			[ $this, 'render_settings_page' ]
		);
	}

	/**
	 * Render settings page.
	 * 
	 * This is a placeholder for future functionality.
	 *
	 * @return void
	 */
	public function render_settings_page(): void {
		// Placeholder for future settings page.
		echo '<div class="wrap">';
		echo '<h1>' . esc_html__( 'Slot Pages Settings', 'slot-pages' ) . '</h1>';
		echo '<p>' . esc_html__( 'Settings for the Slot Pages plugin.', 'slot-pages' ) . '</p>';
		echo '</div>';
	}
}