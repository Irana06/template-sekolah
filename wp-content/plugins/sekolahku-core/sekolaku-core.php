<?php
/**
 * Plugin Name:       Sekolahku Core
 * Plugin URI:        https://freebuff.com
 * Description:       Inti konten untuk tema Sekolahku: menu admin "Konten Sekolah" dengan form mudah untuk Guru & Staff, Prestasi, Agenda, Ekstrakurikuler, Galeri, dan modul PPDB (form pendaftaran + data pendaftar + export CSV). Dibuat khusus untuk admin yang awam teknis.
 * Version:           1.0.0
 * Requires at least: 6.6
 * Requires PHP:      7.4
 * Author:            Freebuff Studio
 * Author URI:        https://freebuff.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       sekolahku-core
 *
 * @package SekolahkuCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SEKOLAHKU_CORE_VERSION', '1.0.0' );
define( 'SEKOLAHKU_CORE_DIR', plugin_dir_path( __FILE__ ) );
define( 'SEKOLAHKU_CORE_URL', plugin_dir_url( __FILE__ ) );

/**
 * Load plugin modules.
 */
function sekolahku_core_include_modules() {
	require_once SEKOLAHKU_CORE_DIR . 'includes/post-types.php';
	require_once SEKOLAHKU_CORE_DIR . 'includes/metaboxes.php';
	require_once SEKOLAHKU_CORE_DIR . 'includes/frontend.php';
	require_once SEKOLAHKU_CORE_DIR . 'includes/ppdb.php';
	require_once SEKOLAHKU_CORE_DIR . 'includes/panduan.php';
}
add_action( 'plugins_loaded', 'sekolahku_core_include_modules' );

/**
 * Flush rewrite rules on activation so CPT archives work right away.
 */
function sekolahku_core_activate() {
	require_once SEKOLAHKU_CORE_DIR . 'includes/post-types.php';
	sekolahku_core_register_post_types();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'sekolahku_core_activate' );

/**
 * Flush rewrite rules on deactivation.
 */
function sekolahku_core_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'sekolahku_core_deactivate' );

/**
 * Allowed post types managed by this plugin.
 *
 * @return array
 */
function sekolahku_core_post_types() {
	return array( 'guru', 'prestasi', 'agenda', 'ekskul', 'galeri' );
}

/**
 * Meta key prefix.
 */
function sekolahku_core_prefix() {
	return '_sekolahku_';
}
