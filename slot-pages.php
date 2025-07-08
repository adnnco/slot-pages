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
define( 'SLOT_PAGES_VERSION', '1.0.0' );
define( 'SLOT_PAGES_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SLOT_PAGES_PLUGIN_URL', plugin_dir_url( __FILE__ ) );