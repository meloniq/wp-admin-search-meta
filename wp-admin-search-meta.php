<?php
/**
 * Plugin Name: WP-Admin Search Post Meta
 * Plugin URI: https://wordpress.org/plugins/wp-admin-search-meta/
 *
 * Description: Search WordPress admin posts by custom fields (post meta) directly from the default search.
 * Tags: admin, search, post meta, meta fields
 *
 * Requires at least: 4.9
 * Requires PHP:      7.4
 * Version: 0.4
 *
 * Author: MELONIQ.NET
 * Author URI: https://blog.meloniq.net/
 *
 * License: GPLv2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Text Domain: wp-admin-search-meta
 *
 * @package WPAdminSearchMeta
 */

// If this file is accessed directly, then abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Plugin version and textdomain constants.
 */
define( 'WPASM_VERSION', '0.4' );
define( 'WPASM_TD', 'wp-admin-search-meta' );

/**
 * Include and initialize class.
 */
if ( ! class_exists( 'WPASM_Search' ) ) {
	require_once __DIR__ . '/src/class-wpasm-search.php';
}

WPASM_Search::init();
