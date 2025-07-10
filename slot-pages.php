<?php
/**
 * Plugin Name: Slot Pages
 * Plugin URI: https://github.com/adnnco/slot-pages
 * Description: A plugin to manage and display slot information in a user-friendly and SEO-friendly way.
 * Version: 1.0.2
 * Requires at least: 6.4.1
 * Requires PHP: 8.2
 * Author: adnnco
 * Author URI: https://github.com/adnnco/
 * Text Domain: slot-pages
 * Domain Path: /languages
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package SlotPages
 */

namespace SlotPages;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin version.
 */
const VERSION = '1.0.2';

/**
 * Plugin directory path.
 */
define( 'SLOT_PAGES_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Plugin directory URL.
 */
define( 'SLOT_PAGES_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Plugin version constant for caching.
 */
define( 'SLOT_PAGES_VERSION', VERSION );

/**
 * Autoload classes.
 */
spl_autoload_register( function ( $class ) {
	// Check if the class is in our namespace.
	if ( strpos( $class, 'SlotPages\\' ) !== 0 ) {
		return;
	}

	// Remove namespace from class name.
	$class_name = str_replace( 'SlotPages\\', '', $class );

	// Convert class name to file path.
	$file_path = str_replace( '\\', '/', $class_name );
	$file_path = SLOT_PAGES_PLUGIN_DIR . 'classes/' . $file_path . '.php';

	// Check if file exists before requiring.
	if ( file_exists( $file_path ) ) {
		require_once $file_path;
	}
} );

/**
 * Initialize the plugin.
 *
 * @return void
 */
function init(): void {
	// Initialize the plugin using the Core class.
	$plugin = Core::get_instance();
	$plugin->init();

	// Load text domain for translations.
	load_plugin_textdomain( 'slot-pages', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

}

add_action( 'plugins_loaded', __NAMESPACE__ . '\\init' );

/**
 * Activation hook callback.
 *
 * @return void
 */
function activate(): void {
	// Flush rewrite rules after creating custom post type.
	flush_rewrite_rules();
}

register_activation_hook( __FILE__, __NAMESPACE__ . '\\activate' );

/**
 * Deactivation hook callback.
 *
 * @return void
 */
function deactivate(): void {
	// Cleanup on deactivation.
	flush_rewrite_rules();
}

register_deactivation_hook( __FILE__, __NAMESPACE__ . '\\deactivate' );
