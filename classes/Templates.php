<?php
/**
 * Templates class.
 *
 * @package SlotPages
 */

namespace SlotPages;

/**
 * Class for managing custom templates.
 */
class Templates {
	/**
	 * Initialize hooks and actions.
	 *
	 * @return void
	 */
	public function init(): void {
		// Filter the single template with our custom template.
		add_filter( 'single_template', [ $this, 'load_slot_template' ] );
	}

	/**
	 * Load custom template for slot post type.
	 *
	 * @param string $template The path of the template to include.
	 * @return string The path of the template to include.
	 */
	public function load_slot_template( string $template ): string {
		global $post;

		// Check if we're viewing a single slot post.
		if ( is_singular( PostTypes::POST_TYPE ) ) {
			// First check if the theme has a single-slot.php template.
			$theme_template = locate_template( [ 'single-' . PostTypes::POST_TYPE . '.php' ] );

			// If the theme has a template, use that.
			if ( $theme_template ) {
				return $theme_template;
			}

			// Otherwise, use our plugin's template.
			$plugin_template = SLOT_PAGES_PLUGIN_DIR . 'templates/single-slot.php';
			if ( file_exists( $plugin_template ) ) {
				return $plugin_template;
			}
		}

		// Return the original template if we're not on a slot post or if our template doesn't exist.
		return $template;
	}
}