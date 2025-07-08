<?php
/*
 * Plugin Name: Slot Pages
 * Plugin URI: https://github.com/adnnco/slot-pages
 * Description: A plugin to manage and display slot information in a user-friendly and SEO-friendly way.
* Version: 1.0.0
* Requires at least: 6.4.1
* Requires PHP:      8.2
* Author: adnnco
* Author URI: https://github.com/adnnco/
*/


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants
define( 'SLOT_PAGES_VERSION', time() );
define( 'SLOT_PAGES_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SLOT_PAGES_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include required files
require_once SLOT_PAGES_PLUGIN_DIR . 'includes/class-slot-pages.php';

// Initialize the plugin
function slot_pages_init(): void {
	$plugin = new Slot_Pages();
	$plugin->init();
}

add_action( 'plugins_loaded', 'slot_pages_init' );

// Activation hook
register_activation_hook( __FILE__, 'slot_pages_activate' );
function slot_pages_activate() {
	// Flush rewrite rules after creating custom post type
	flush_rewrite_rules();
}

// Deactivation hook
register_deactivation_hook( __FILE__, 'slot_pages_deactivate' );
function slot_pages_deactivate() {
	// Cleanup on deactivation
	flush_rewrite_rules();
}