<?php
/**
 * Core plugin class.
 *
 * @package SlotPages
 */

namespace SlotPages;

/**
 * Core class for the Slot Pages plugin.
 *
 * Handles initialization and loading of all plugin components.
 */
class Core {
	/**
	 * Plugin instance.
	 *
	 * @var Core|null
	 */
	private static ?Core $instance = null;

	/**
	 * Post Types instance.
	 *
	 * @var PostTypes|null
	 */
	private ?PostTypes $post_types = null;

	/**
	 * Taxonomies instance.
	 *
	 * @var Taxonomies|null
	 */
	private ?Taxonomies $taxonomies = null;

	/**
	 * Blocks instance.
	 *
	 * @var Blocks|null
	 */
	private ?Blocks $blocks = null;

	/**
	 * Templates instance.
	 *
	 * @var Templates|null
	 */
	private ?Templates $templates = null;

	/**
	 * Admin instance.
	 *
	 * @var Admin|null
	 */
	private ?Admin $admin = null;

	/**
	 * Get the singleton instance of the class.
	 *
	 * @return Core The singleton instance.
	 */
	public static function get_instance(): Core {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Private constructor to prevent direct instantiation.
	 */
	private function __construct() {
		// Initialize components.
		$this->post_types = new PostTypes();
		$this->taxonomies = new Taxonomies();
		$this->blocks     = new Blocks();
		$this->templates  = new Templates();
		$this->admin      = new Admin();
	}

	/**
	 * Initialize the plugin.
	 *
	 * @return void
	 */
	public function init(): void {
		// Initialize components.
		$this->post_types->init();
		$this->taxonomies->init();
		$this->blocks->init();
		$this->templates->init();
		$this->admin->init();

		// Register activation and deactivation hooks.
		register_activation_hook( SLOT_PAGES_PLUGIN_DIR . 'slot-pages.php', [ $this, 'activate' ] );
		register_deactivation_hook( SLOT_PAGES_PLUGIN_DIR . 'slot-pages.php', [ $this, 'deactivate' ] );
	}

	/**
	 * Plugin activation hook.
	 *
	 * @return void
	 */
	public function activate(): void {
		// Initialize post-types and taxonomies.
		$this->post_types->register_post_types();
		$this->taxonomies->register_taxonomies();

		// Flush rewrite rules.
		flush_rewrite_rules();
	}

	/**
	 * Plugin deactivation hook.
	 *
	 * @return void
	 */
	public function deactivate(): void {
		// Flush rewrite rules.
		flush_rewrite_rules();
	}
}
