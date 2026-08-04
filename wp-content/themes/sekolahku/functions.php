<?php
/**
 * Sekolahku — theme functions.
 *
 * @package Sekolahku
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SEKOLAHKU_VERSION', '1.0.1' );

/**
 * Theme setup.
 */
function sekolahku_setup() {
	load_theme_textdomain( 'sekolahku', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array(
		'height'      => 120,
		'width'       => 120,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'editor-styles' );

	register_nav_menus(
		array(
			'primary' => __( 'Menu Utama', 'sekolahku' ),
		)
	);
}
add_action( 'after_setup_theme', 'sekolahku_setup' );

/**
 * Enqueue front-end assets.
 */
function sekolahku_assets() {
	wp_enqueue_style(
		'sekolahku',
		get_template_directory_uri() . '/assets/css/sekolahku.css',
		array(),
		filemtime( get_template_directory() . '/assets/css/sekolahku.css' )
	);

	wp_enqueue_script(
		'sekolahku',
		get_template_directory_uri() . '/assets/js/sekolahku.js',
		array(),
		filemtime( get_template_directory() . '/assets/js/sekolahku.js' ),
		true
	);

	wp_localize_script(
		'sekolahku',
		'sekolahkuData',
		array(
			'homeUrl' => esc_url( home_url( '/' ) ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'sekolahku_assets' );

/**
 * Load theme styles inside the block editor so patterns preview correctly.
 */
function sekolahku_editor_assets() {
	wp_enqueue_style(
		'sekolahku-editor',
		get_template_directory_uri() . '/assets/css/sekolahku.css',
		array(),
		SEKOLAHKU_VERSION
	);
}
add_action( 'enqueue_block_editor_assets', 'sekolahku_editor_assets' );

/**
 * Register block pattern category.
 */
function sekolahku_register_pattern_category() {
	register_block_pattern_category(
		'sekolahku',
		array(
			'label' => __( 'Sekolahku', 'sekolahku' ),
		)
	);
}
add_action( 'init', 'sekolahku_register_pattern_category' );

/**
 * Get the markup of a bundled pattern by slug.
 *
 * Pattern files live in /patterns and simply return their block markup.
 *
 * @param string $slug Pattern slug (file name without .php).
 * @return string
 */
function sekolahku_get_pattern( $slug ) {
	static $cache = array();

	if ( isset( $cache[ $slug ] ) ) {
		return $cache[ $slug ];
	}

	$file = get_template_directory() . '/patterns/' . sanitize_file_name( $slug ) . '.php';
	if ( ! file_exists( $file ) ) {
		return '';
	}

	$cache[ $slug ] = (string) include $file;
	return $cache[ $slug ];
}

/**
 * Register all bundled patterns.
 */
function sekolahku_register_patterns() {
	$dir   = get_template_directory() . '/patterns';
	$files = glob( $dir . '/*.php' );

	if ( ! $files ) {
		return;
	}

	foreach ( $files as $file ) {
		$slug = basename( $file, '.php' );

		register_block_pattern(
			'sekolahku/' . $slug,
			array(
				'title'      => sekolahku_pattern_title( $slug ),
				'categories' => array( 'sekolahku' ),
				'content'    => sekolahku_get_pattern( $slug ),
			)
		);
	}
}
add_action( 'init', 'sekolahku_register_patterns', 20 );

/**
 * Human readable title for a pattern slug.
 *
 * @param string $slug Pattern slug.
 * @return string
 */
function sekolahku_pattern_title( $slug ) {
	$titles = array(
		'hero'       => __( 'Banner Utama (Hero)', 'sekolahku' ),
		'stats'      => __( 'Angka Statistik', 'sekolahku' ),
		'info-cards' => __( 'Kartu Informasi Cepat', 'sekolahku' ),
		'sambutan'   => __( 'Sambutan Kepala Sekolah', 'sekolahku' ),
		'visi-misi'  => __( 'Visi & Misi', 'sekolahku' ),
		'berita'     => __( 'Berita Terbaru', 'sekolahku' ),
		'agenda'     => __( 'Agenda Kegiatan', 'sekolahku' ),
		'prestasi'   => __( 'Prestasi Sekolah', 'sekolahku' ),
		'ekskul'     => __( 'Ekstrakurikuler', 'sekolahku' ),
		'testimoni'  => __( 'Kata Mereka (Testimoni)', 'sekolahku' ),
		'cta-ppdb'   => __( 'Ajakan Daftar PPDB', 'sekolahku' ),
		'kontak'     => __( 'Kontak & Lokasi', 'sekolahku' ),
	);

	return isset( $titles[ $slug ] ) ? $titles[ $slug ] : ucwords( str_replace( '-', ' ', $slug ) );
}

/**
 * Helper: theme demo asset URL.
 *
 * @param string $file File name inside /assets/demo.
 * @return string
 */
function sekolahku_demo_asset( $file ) {
	return esc_url( get_template_directory_uri() . '/assets/demo/' . $file );
}

/**
 * Build block markup for a section heading (kicker + title + optional CTA).
 *
 * @param string $kicker    Small label above the title.
 * @param string $title     Section title.
 * @param string $desc      Optional description.
 * @param string $link_url  Optional "see all" link.
 * @param string $link_text Optional link label.
 * @return string
 */
function sekolahku_section_head( $kicker, $title, $desc = '', $link_url = '', $link_text = '' ) {
	$out  = '<!-- wp:group {"className":"sk-section-head","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"bottom"}} -->';
	$out .= '<div class="wp-block-group sk-section-head">';
	$out .= '<!-- wp:group {"className":"sk-section-head-text","layout":{"type":"constrained"}} --><div class="wp-block-group sk-section-head-text">';
	$out .= '<!-- wp:paragraph {"className":"sk-kicker","fontSize":"small"} --><p class="sk-kicker has-small-font-size">' . esc_html( $kicker ) . '</p><!-- /wp:paragraph -->';
	$out .= '<!-- wp:heading {"level":2,"className":"sk-section-title"} --><h2 class="wp-block-heading sk-section-title">' . esc_html( $title ) . '</h2><!-- /wp:heading -->';
	if ( $desc ) {
		$out .= '<!-- wp:paragraph {"className":"sk-section-desc"} --><p class="sk-section-desc">' . esc_html( $desc ) . '</p><!-- /wp:paragraph -->';
	}
	$out .= '</div><!-- /wp:group -->';
	if ( $link_url ) {
		$out .= '<!-- wp:buttons {"className":"sk-section-cta"} --><div class="wp-block-buttons sk-section-cta">';
		$out .= '<!-- wp:button {"className":"is-style-sk-navy","fontSize":"small"} -->';
		$out .= '<div class="wp-block-button is-style-sk-navy has-custom-font-size has-small-font-size"><a class="wp-block-button__link" href="' . esc_url( $link_url ) . '">' . esc_html( $link_text ? $link_text : 'Lihat Semua' ) . ' →</a></div>';
		$out .= '<!-- /wp:button -->';
		$out .= '</div><!-- /wp:buttons -->';
	}
	$out .= '</div><!-- /wp:group -->';
	return $out;
}

/**
 * Add a subtle "sticky header" body class when the fixed header is used.
 */
function sekolahku_body_classes( $classes ) {
	$classes[] = 'sk-site';
	return $classes;
}
add_filter( 'body_class', 'sekolahku_body_classes' );
