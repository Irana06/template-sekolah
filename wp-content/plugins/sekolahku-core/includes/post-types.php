<?php
/**
 * Custom post types & admin menu structure.
 *
 * @package SekolahkuCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register all content post types.
 */
function sekolahku_core_register_post_types() {

	// Guru & Staff.
	register_post_type(
		'guru',
		array(
			'labels'          => array(
				'name'               => 'Guru & Staff',
				'singular_name'      => 'Guru / Staff',
				'add_new'            => 'Tambah Guru',
				'add_new_item'       => 'Tambah Guru Baru',
				'edit_item'          => 'Edit Guru',
				'new_item'           => 'Guru Baru',
				'view_item'          => 'Lihat Profil',
				'search_items'       => 'Cari Guru',
				'not_found'          => 'Belum ada data guru.',
				'not_found_in_trash' => 'Tidak ada guru di tempat sampah.',
				'all_items'          => 'Semua Guru',
			),
			'public'          => true,
			'has_archive'     => true,
			'menu_icon'       => 'dashicons-businessperson',
			'show_in_menu'    => false,
			'show_in_rest'    => true,
			'supports'        => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'rewrite'         => array( 'slug' => 'guru-staff' ),
		)
	);

	// Prestasi.
	register_post_type(
		'prestasi',
		array(
			'labels'          => array(
				'name'               => 'Prestasi',
				'singular_name'      => 'Prestasi',
				'add_new'            => 'Tambah Prestasi',
				'add_new_item'       => 'Tambah Prestasi Baru',
				'edit_item'          => 'Edit Prestasi',
				'new_item'           => 'Prestasi Baru',
				'view_item'          => 'Lihat Prestasi',
				'search_items'       => 'Cari Prestasi',
				'not_found'          => 'Belum ada data prestasi.',
				'not_found_in_trash' => 'Tidak ada prestasi di tempat sampah.',
				'all_items'          => 'Semua Prestasi',
			),
			'public'          => true,
			'has_archive'     => true,
			'menu_icon'       => 'dashicons-awards',
			'show_in_menu'    => false,
			'show_in_rest'    => true,
			'supports'        => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'rewrite'         => array( 'slug' => 'prestasi' ),
		)
	);

	// Agenda.
	register_post_type(
		'agenda',
		array(
			'labels'          => array(
				'name'               => 'Agenda',
				'singular_name'      => 'Agenda',
				'add_new'            => 'Tambah Agenda',
				'add_new_item'       => 'Tambah Agenda Baru',
				'edit_item'          => 'Edit Agenda',
				'new_item'           => 'Agenda Baru',
				'view_item'          => 'Lihat Agenda',
				'search_items'       => 'Cari Agenda',
				'not_found'          => 'Belum ada agenda.',
				'not_found_in_trash' => 'Tidak ada agenda di tempat sampah.',
				'all_items'          => 'Semua Agenda',
			),
			'public'          => true,
			'has_archive'     => true,
			'menu_icon'       => 'dashicons-calendar-alt',
			'show_in_menu'    => false,
			'show_in_rest'    => true,
			'supports'        => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'rewrite'         => array( 'slug' => 'agenda' ),
		)
	);

	// Ekstrakurikuler.
	register_post_type(
		'ekskul',
		array(
			'labels'          => array(
				'name'               => 'Ekstrakurikuler',
				'singular_name'      => 'Ekstrakurikuler',
				'add_new'            => 'Tambah Ekskul',
				'add_new_item'       => 'Tambah Ekskul Baru',
				'edit_item'          => 'Edit Ekskul',
				'new_item'           => 'Ekskul Baru',
				'view_item'          => 'Lihat Ekskul',
				'search_items'       => 'Cari Ekskul',
				'not_found'          => 'Belum ada ekstrakurikuler.',
				'not_found_in_trash' => 'Tidak ada ekskul di tempat sampah.',
				'all_items'          => 'Semua Ekskul',
			),
			'public'          => true,
			'has_archive'     => true,
			'menu_icon'       => 'dashicons-star-filled',
			'show_in_menu'    => false,
			'show_in_rest'    => true,
			'supports'        => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'rewrite'         => array( 'slug' => 'ekstrakurikuler' ),
		)
	);

	// Galeri.
	register_post_type(
		'galeri',
		array(
			'labels'          => array(
				'name'               => 'Galeri',
				'singular_name'      => 'Galeri',
				'add_new'            => 'Tambah Album',
				'add_new_item'       => 'Tambah Album Baru',
				'edit_item'          => 'Edit Album',
				'new_item'           => 'Album Baru',
				'view_item'          => 'Lihat Album',
				'search_items'       => 'Cari Album',
				'not_found'          => 'Belum ada album galeri.',
				'not_found_in_trash' => 'Tidak ada album di tempat sampah.',
				'all_items'          => 'Semua Album',
			),
			'public'          => true,
			'has_archive'     => true,
			'menu_icon'       => 'dashicons-format-gallery',
			'show_in_menu'    => false,
			'show_in_rest'    => true,
			'supports'        => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'rewrite'         => array( 'slug' => 'galeri' ),
		)
	);

	// PPDB registrations (private).
	register_post_type(
		'ppdb_registrasi',
		array(
			'labels'          => array(
				'name'          => 'Pendaftar PPDB',
				'singular_name' => 'Pendaftar',
			),
			'public'          => false,
			'show_ui'         => false,
			'show_in_menu'    => false,
			'supports'        => array( 'title' ),
			'capability_type' => 'post',
		)
	);
}
add_action( 'init', 'sekolahku_core_register_post_types' );

/**
 * Build the "Konten Sekolah" admin menu group.
 */
function sekolahku_core_admin_menu() {
	$capability = 'edit_posts';

	// Top-level menu. Gunakan edit_posts agar editor/penulis juga bisa melihat menu ini.
	add_menu_page(
		'Konten Sekolah',
		'Konten Sekolah',
		'edit_posts',
		'sekolahku',
		'sekolahku_core_dashboard_page',
		'dashicons-welcome-learn-more',
		4
	);

	// Submenu items, in the order admins should see them.
	$items = array(
		0 => array( 'edit.php', 'Berita & Informasi', 'Berita' ),
		1 => array( 'edit.php?post_type=guru', 'Guru & Staff', 'Guru & Staff' ),
		2 => array( 'edit.php?post_type=prestasi', 'Prestasi', 'Prestasi' ),
		3 => array( 'edit.php?post_type=agenda', 'Agenda', 'Agenda' ),
		4 => array( 'edit.php?post_type=ekskul', 'Ekstrakurikuler', 'Ekstrakurikuler' ),
		5 => array( 'edit.php?post_type=galeri', 'Galeri', 'Galeri' ),
		6 => array( 'sekolahku-ppdb', 'Data Pendaftar PPDB', 'Pendaftar PPDB' ),
		7 => array( 'sekolahku-panduan', 'Panduan', 'Panduan' ),
	);

	foreach ( $items as $item ) {
		list( $slug, $page_title, $menu_title ) = $item;
		add_submenu_page(
			'sekolahku',
			$page_title,
			$menu_title,
			$capability,
			$slug,
			( 'sekolahku-ppdb' === $slug ) ? 'sekolahku_core_ppdb_admin_page' : ( ( 'sekolahku-panduan' === $slug ) ? 'sekolahku_core_panduan_page' : '' )
		);
	}
}
add_action( 'admin_menu', 'sekolahku_core_admin_menu' );

/**
 * Dashboard page under Konten Sekolah (overview + quick links).
 */
function sekolahku_core_dashboard_page() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return;
	}

	$counts = array(
		'Berita'            => wp_count_posts( 'post' )->publish,
		'Guru & Staff'      => wp_count_posts( 'guru' )->publish,
		'Prestasi'          => wp_count_posts( 'prestasi' )->publish,
		'Agenda'            => wp_count_posts( 'agenda' )->publish,
		'Ekstrakurikuler'   => wp_count_posts( 'ekskul' )->publish,
		'Galeri'            => wp_count_posts( 'galeri' )->publish,
	);

	$ppdb_count = wp_count_posts( 'ppdb_registrasi' );
	$ppdb_total = isset( $ppdb_count->publish ) ? $ppdb_count->publish : 0;

	echo '<div class="wrap sk-dashboard">';
	echo '<h1 class="wp-heading-inline">🏫 Konten Sekolah</h1>';
	echo '<p>Kelola seluruh isi website sekolah dari satu tempat. Setiap menu memiliki form yang mudah diisi — tanpa perlu paham kode.</p>';

	echo '<div class="sk-dash-grid">';
	$links = array(
		'Berita'          => 'edit.php',
		'Guru & Staff'    => 'edit.php?post_type=guru',
		'Prestasi'        => 'edit.php?post_type=prestasi',
		'Agenda'          => 'edit.php?post_type=agenda',
		'Ekstrakurikuler' => 'edit.php?post_type=ekskul',
		'Galeri'          => 'edit.php?post_type=galeri',
	);
	foreach ( $links as $label => $url ) {
		$num = isset( $counts[ $label ] ) ? $counts[ $label ] : 0;
		echo '<a class="sk-dash-card" href="' . esc_url( admin_url( $url ) ) . '">';
		echo '<span class="sk-dash-num">' . esc_html( $num ) . '</span>';
		echo '<span class="sk-dash-label">' . esc_html( $label ) . '</span>';
		echo '<span class="sk-dash-go">Kelola →</span>';
		echo '</a>';
	}
	echo '<a class="sk-dash-card sk-dash-card--gold" href="' . esc_url( admin_url( 'admin.php?page=sekolahku-ppdb' ) ) . '">';
	echo '<span class="sk-dash-num">' . esc_html( $ppdb_total ) . '</span>';
	echo '<span class="sk-dash-label">Pendaftar PPDB</span>';
	echo '<span class="sk-dash-go">Lihat Data →</span>';
	echo '</a>';
	echo '</div>';

	echo '<div class="sk-dash-tip">';
	echo '<p><strong>💡 Butuh bantuan?</strong> Buka menu <a href="' . esc_url( admin_url( 'admin.php?page=sekolahku-panduan' ) ) . '">Panduan</a> untuk langkah-langkah mengelola website.</p>';
	echo '</div>';
	echo '</div>';
}
