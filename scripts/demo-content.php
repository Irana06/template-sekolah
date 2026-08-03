<?php
/**
 * Demo content importer for the Sekolahku template.
 *
 * Usage (from project root, with the DB running):
 *   "C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe" scripts/demo-content.php --run
 *
 * Only runs when WP is loaded from this project and --run is passed.
 *
 * @package Sekolahku
 */

if ( PHP_SAPI !== 'cli' ) {
	die( 'CLI only.' );
}

$args = $_SERVER['argv'] ?? array();
if ( ! in_array( '--run', $args, true ) ) {
	echo "Usage: php scripts/demo-content.php --run\n";
	echo "(dibutuhkan flag --run untuk mencegah eksekusi tidak sengaja)\n";
	exit( 1 );
}

$wp_load = dirname( __DIR__ ) . '/wp-load.php';
if ( ! file_exists( $wp_load ) ) {
	die( 'wp-load.php tidak ditemukan. Jalankan dari folder proyek WordPress.' . PHP_EOL );
}

// wp-config.php membuat WP_HOME dari $_SERVER['HTTP_HOST'] / REQUEST_SCHEME yang tidak
// tersedia di CLI. Baca domain asli dari database dulu agar home_url() benar.
$sk_wp_config = file_get_contents( dirname( $wp_load ) . '/wp-config.php' );
$sk_db_name   = '';
$sk_db_user   = '';
$sk_db_pass   = '';
$sk_db_host   = 'localhost';
$sk_prefix    = 'wp_';
preg_match( "/define\s*\(\s*'DB_NAME'\s*,\s*'([^']*)'/i", $sk_wp_config, $m ) && $sk_db_name = $m[1];
preg_match( "/define\s*\(\s*'DB_USER'\s*,\s*'([^']*)'/i", $sk_wp_config, $m ) && $sk_db_user = $m[1];
preg_match( "/define\s*\(\s*'DB_PASSWORD'\s*,\s*'([^']*)'/i", $sk_wp_config, $m ) && $sk_db_pass = $m[1];
preg_match( "/define\s*\(\s*'DB_HOST'\s*,\s*'([^']*)'/i", $sk_wp_config, $m ) && $sk_db_host = $m[1];
preg_match( "/\$table_prefix\s*=\s*'([^']*)'/i", $sk_wp_config, $m ) && $sk_prefix = $m[1];

if ( ! empty( $sk_db_name ) && ! isset( $_SERVER['HTTP_HOST'] ) ) {
	$sk_mysqli = @new mysqli( $sk_db_host, $sk_db_user, $sk_db_pass, $sk_db_name );
	if ( ! $sk_mysqli->connect_error ) {
		$sk_row = $sk_mysqli->query( "SELECT option_value FROM " . $sk_prefix . "options WHERE option_name='home' LIMIT 1" );
		if ( $sk_row && $sk_home = $sk_row->fetch_row() ) {
			$sk_parsed = parse_url( $sk_home[0] );
			if ( ! empty( $sk_parsed['host'] ) ) {
				$_SERVER['HTTP_HOST']     = $sk_parsed['host'];
				$_SERVER['REQUEST_SCHEME'] = isset( $sk_parsed['scheme'] ) ? $sk_parsed['scheme'] : 'http';
			}
		}
		$sk_mysqli->close();
	}
}

require_once $wp_load;

// Jadikan user admin sebagai user saat ini agar konten tidak di-kses (iframe peta tetap tersimpan)
// dan media punya penulis yang valid.
$sk_demo_admin = get_users( array( 'role' => 'administrator', 'number' => 1 ) );
if ( ! empty( $sk_demo_admin ) ) {
	wp_set_current_user( $sk_demo_admin[0]->ID );
}

// Izinkan upload SVG selama proses import.
add_filter(
	'upload_mimes',
	function ( $mimes ) {
		$mimes['svg']  = 'image/svg+xml';
		$mimes['svgz'] = 'image/svg+xml';
		return $mimes;
	}
);
add_filter(
	'wp_check_filetype_and_ext',
	function ( $data, $file, $filename, $mimes ) {
		if ( 'svg' === strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) ) ) {
			$data['ext']  = 'svg';
			$data['type'] = 'image/svg+xml';
		}
		return $data;
	},
	10,
	4
);

define( 'SK_DEMO_THEME', 'sekolahku' );

function sk_demo_log( $msg ) {
	echo '  ' . $msg . PHP_EOL;
}

function sk_demo_hr() {
	echo str_repeat( '─', 60 ) . PHP_EOL;
}

echo PHP_EOL;
sk_demo_hr();
echo '  IMPORT KONTEN DEMO — SEKOLAHKU' . PHP_EOL;
sk_demo_hr();

/* ---------------------------------------------------------------------
 * 1. Aktifkan tema & plugin
 * ------------------------------------------------------------------- */

if ( get_stylesheet() !== SK_DEMO_THEME ) {
	sk_demo_log( 'Mengaktifkan tema Sekolahku…' );
	switch_theme( SK_DEMO_THEME );
}

if ( ! function_exists( 'sekolahku_setup' ) ) {
	require_once get_theme_root() . '/' . SK_DEMO_THEME . '/functions.php';
}

require_once ABSPATH . 'wp-admin/includes/plugin.php';
if ( ! is_plugin_active( 'sekolahku-core/sekolaku-core.php' ) ) {
	sk_demo_log( 'Mengaktifkan plugin Sekolahku Core…' );
	activate_plugin( 'sekolahku-core/sekolaku-core.php' );
}
if ( ! function_exists( 'sekolahku_core_register_post_types' ) ) {
	require_once WP_PLUGIN_DIR . '/sekolahku-core/includes/post-types.php';
	require_once WP_PLUGIN_DIR . '/sekolahku-core/includes/metaboxes.php';
	require_once WP_PLUGIN_DIR . '/sekolahku-core/includes/frontend.php';
	require_once WP_PLUGIN_DIR . '/sekolahku-core/includes/ppdb.php';
}

// Pastikan registrasi berjalan meski hook init sudah lewat.
if ( function_exists( 'sekolahku_register_patterns' ) ) {
	sekolahku_register_patterns();
}
if ( function_exists( 'sekolahku_core_register_post_types' ) ) {
	sekolahku_core_register_post_types();
}

/* ---------------------------------------------------------------------
 * 2. Pengaturan dasar situs
 * ------------------------------------------------------------------- */

sk_demo_log( 'Mengatur pengaturan dasar…' );
update_option( 'blogname', 'Sekolah Cendekia' );
update_option( 'blogdescription', 'Membangun Generasi Unggul & Berkarakter' );
update_option( 'timezone_string', 'Asia/Jakarta' );
update_option( 'date_format', 'd F Y' );
update_option( 'start_of_week', 1 );
update_option( 'permalink_structure', '/%postname%/' );

// Nonaktifkan plugin bawaan yang tidak diperlukan.
if ( is_plugin_active( 'hello.php' ) ) {
	deactivate_plugins( 'hello.php' );
}
if ( is_plugin_active( 'akismet/akismet.php' ) ) {
	deactivate_plugins( 'akismet/akismet.php' );
}

/* ---------------------------------------------------------------------
 * 3. Import media (SVG) ke Media Library
 * ------------------------------------------------------------------- */

$demo_dir = get_theme_file_path( '/assets/demo' );

function sk_demo_get_attachment_by_title( $title ) {
	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'title'          => $title,
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
		)
	);
	return $existing ? $existing[0]->ID : 0;
}

function sk_demo_import_media( $file, $title ) {
	$existing = sk_demo_get_attachment_by_title( $title );
	if ( $existing ) {
		return $existing;
	}
	$contents = file_get_contents( $file );
	if ( false === $contents ) {
		return 0;
	}
	$upload = wp_upload_bits( basename( $file ), null, $contents );
	if ( ! empty( $upload['error'] ) ) {
		sk_demo_log( '  ⚠️ Gagal upload ' . basename( $file ) . ': ' . $upload['error'] );
		return 0;
	}
	$attach_id = wp_insert_attachment(
		array(
			'post_mime_type' => 'image/svg+xml',
			'post_title'     => $title,
			'post_content'   => '',
			'post_status'    => 'inherit',
		),
		$upload['file']
	);
	return $attach_id;
}

function sk_demo_import_avatar( $initials, $bg, $fg, $name ) {
	$title = 'Avatar ' . $name;
	$existing = sk_demo_get_attachment_by_title( $title );
	if ( $existing ) {
		return $existing;
	}
	$svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400">'
		. '<rect width="400" height="400" fill="' . $bg . '"/>'
		. '<circle cx="200" cy="150" r="82" fill="' . $fg . '" opacity="0.22"/>'
		. '<text x="200" y="235" text-anchor="middle" font-family="Arial, sans-serif" font-size="120" font-weight="800" fill="' . $fg . '">' . $initials . '</text>'
		. '</svg>';
	$upload = wp_upload_bits( 'avatar-' . sanitize_title( $name ) . '.svg', null, $svg );
	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}
	return wp_insert_attachment(
		array(
			'post_mime_type' => 'image/svg+xml',
			'post_title'     => $title,
			'post_content'   => '',
			'post_status'    => 'inherit',
		),
		$upload['file']
	);
}

sk_demo_log( 'Mengimpor gambar demo ke Media Library…' );
$media = array(
	'logo'        => sk_demo_import_media( $demo_dir . '/logo.svg', 'Logo Sekolah' ),
	'hero'        => sk_demo_import_media( $demo_dir . '/hero.svg', 'Ilustrasi Gedung Sekolah' ),
	'kegiatan_1'  => sk_demo_import_media( $demo_dir . '/kegiatan-1.svg', 'Kegiatan Belajar di Kelas' ),
	'kegiatan_2'  => sk_demo_import_media( $demo_dir . '/kegiatan-2.svg', 'Perpustakaan Sekolah' ),
	'kegiatan_3'  => sk_demo_import_media( $demo_dir . '/kegiatan-3.svg', 'Kegiatan Olahraga' ),
	'kegiatan_4'  => sk_demo_import_media( $demo_dir . '/kegiatan-4.svg', 'Laboratorium IPA' ),
	'kegiatan_5'  => sk_demo_import_media( $demo_dir . '/kegiatan-5.svg', 'Pentas Seni & Musik' ),
	'kegiatan_6'  => sk_demo_import_media( $demo_dir . '/kegiatan-6.svg', 'Suasana Lingkungan Sekolah' ),
	'prestasi_1'  => sk_demo_import_media( $demo_dir . '/prestasi-1.svg', 'Piala Prestasi' ),
	'prestasi_2'  => sk_demo_import_media( $demo_dir . '/prestasi-2.svg', 'Medali Emas' ),
	'prestasi_3'  => sk_demo_import_media( $demo_dir . '/prestasi-3.svg', 'Pita Penghargaan' ),
	'prestasi_4'  => sk_demo_import_media( $demo_dir . '/prestasi-4.svg', 'Sertifikat' ),
	'eks_robotik' => sk_demo_import_media( $demo_dir . '/ekskul-robotik.svg', 'Ikon Robotik' ),
	'eks_futsal'  => sk_demo_import_media( $demo_dir . '/ekskul-futsal.svg', 'Ikon Futsal' ),
	'eks_seni'    => sk_demo_import_media( $demo_dir . '/ekskul-seni.svg', 'Ikon Seni Rupa' ),
	'eks_musik'   => sk_demo_import_media( $demo_dir . '/ekskul-musik.svg', 'Ikon Musik' ),
	'eks_pramuka' => sk_demo_import_media( $demo_dir . '/ekskul-pramuka.svg', 'Ikon Pramuka' ),
	'eks_bahasa'  => sk_demo_import_media( $demo_dir . '/ekskul-bahasa.svg', 'Ikon English Club' ),
);

$avatar_colors = array(
	array( '#e9f1fa', '#0b2a4a' ),
	array( '#fdf0e0', '#c46a1b' ),
	array( '#eefbf2', '#2f7d4f' ),
	array( '#f3effd', '#6a4fb3' ),
	array( '#fdf0f0', '#b13c3c' ),
	array( '#e9f7f8', '#1b7a86' ),
);
$guru_names = array( 'Ahmad Fauzi', 'Rina Marlina', 'Budi Santoso', 'Dewi Anggraini', 'Joko Prasetyo', 'Siti Nurhaliza' );
$guru_initials = array( 'AF', 'RM', 'BS', 'DA', 'JP', 'SN' );

$media['avatar'] = array();
foreach ( $guru_names as $i => $name ) {
	$media['avatar'][] = sk_demo_import_avatar( $guru_initials[ $i ], $avatar_colors[ $i ][0], $avatar_colors[ $i ][1], $name );
}

// Logo situs.
if ( ! empty( $media['logo'] ) ) {
	set_theme_mod( 'custom_logo', $media['logo'] );
}

/* ---------------------------------------------------------------------
 * 4. Helper membuat konten
 * ------------------------------------------------------------------- */

function sk_demo_delete_by_slug( $slug, $type = 'post' ) {
	$existing = get_page_by_path( $slug, OBJECT, $type );
	if ( $existing ) {
		wp_delete_post( $existing->ID, true );
	}
}

function sk_demo_make_page( $slug, $title, $content = '' ) {
	sk_demo_delete_by_slug( $slug, 'page' );
	return wp_insert_post(
		array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_content' => $content,
		)
	);
}

function sk_demo_make_content_post( $type, $slug, $title, $content, $excerpt, $thumb, $meta = array() ) {
	sk_demo_delete_by_slug( $slug, $type );
	$post_id = wp_insert_post(
		array(
			'post_type'    => $type,
			'post_status'  => 'publish',
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_content' => $content,
			'post_excerpt' => $excerpt,
		)
	);
	if ( $thumb ) {
		set_post_thumbnail( $post_id, $thumb );
	}
	foreach ( $meta as $key => $value ) {
		update_post_meta( $post_id, '_sekolahku_' . $key, $value );
	}
	return $post_id;
}

function sk_demo_para( $text ) {
	return '<!-- wp:paragraph --><p>' . $text . '</p><!-- /wp:paragraph -->';
}

sk_demo_log( 'Membuat halaman…' );

/* ---------------------------------------------------------------------
 * 5. Halaman & konten
 * ------------------------------------------------------------------- */

$k = $media;

// Beranda: rangkaian pattern.
$home_content  = sekolahku_get_pattern( 'hero' );
$home_content .= sekolahku_get_pattern( 'stats' );
$home_content .= sekolahku_get_pattern( 'info-cards' );
$home_content .= sekolahku_get_pattern( 'sambutan' );
$home_content .= sekolahku_get_pattern( 'berita' );
$home_content .= sekolahku_get_pattern( 'agenda' );
$home_content .= sekolahku_get_pattern( 'prestasi' );
$home_content .= sekolahku_get_pattern( 'ekskul' );
$home_content .= sekolahku_get_pattern( 'testimoni' );
$home_content .= sekolahku_get_pattern( 'cta-ppdb' );
$home_content .= sekolahku_get_pattern( 'kontak' );

$page_beranda = sk_demo_make_page( 'beranda', 'Beranda', $home_content );

// Profil.
$profil_content  = sekolahku_get_pattern( 'sambutan' );
$profil_content .= sekolahku_get_pattern( 'visi-misi' );
$profil_content .= sk_demo_para( 'Dikenal sebagai sekolah yang ramah dan berprestasi, Sekolah Cendekia berkomitmen memberikan pendidikan terbaik bagi setiap peserta didik. Dengan dukungan tenaga pendidik profesional dan fasilitas yang memadai, kami percaya setiap anak dapat berkembang sesuai potensinya.' );
$profil_content .= sk_demo_para( 'Sebagai bagian dari masyarakat, sekolah kami juga aktif dalam kegiatan sosial dan kemitraan dengan orang tua, alumni, serta instansi terkait demi kemajuan pendidikan di Indonesia.' );
$page_profil  = sk_demo_make_page( 'profil', 'Profil Sekolah', $profil_content );

// Halaman Berita (posts page).
$page_berita  = sk_demo_make_page( 'berita', 'Berita & Informasi', '' );

// Halaman PPDB.
$ppdb_content  = sk_demo_para( 'Selamat datang di halaman <strong>Penerimaan Peserta Didik Baru (PPDB)</strong> Sekolah Cendekia. Silakan isi formulir di bawah ini dengan data yang benar. Panitia akan menghubungi Anda melalui WhatsApp/email setelah pendaftaran diterima.' );
$ppdb_content .= sk_demo_para( 'Syarat pendaftaran: foto 3x4, akta kelahiran, kartu keluarga, dan rapor terakhir. Periode pendaftaran: 1 Februari – 30 Juni 2026.' );
$ppdb_content .= '<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Formulir Pendaftaran Online</h2><!-- /wp:heading -->';
$ppdb_content .= '<!-- wp:shortcode -->[sekolahku_ppdb]<!-- /wp:shortcode -->';
$page_ppdb   = sk_demo_make_page( 'ppdb', 'PPDB — Pendaftaran Online', $ppdb_content );

// Halaman Kontak.
$page_kontak = sk_demo_make_page( 'kontak', 'Kontak Kami', sekolahku_get_pattern( 'kontak' ) );

/* ---------------------------------------------------------------------
 * 6. Berita
 * ------------------------------------------------------------------- */

sk_demo_log( 'Membuat berita…' );
sk_demo_make_content_post( 'post', 'ppdb-2026-dibuka', 'Pendaftaran Peserta Didik Baru 2026/2027 Resmi Dibuka',
	sk_demo_para( 'Kabar gembira! Sekolah Cendekia resmi membuka pendaftaran peserta didik baru untuk tahun ajaran 2026/2027. Pendaftaran dapat dilakukan secara online melalui website ini.' )
	. sk_demo_para( 'Jalur yang tersedia: Reguler, Prestasi, Zonasi, dan Beasiswa. Pastikan menyiapkan dokumen yang diperlukan sebelum mengisi formulir.' ),
	'Kabar gembira — pendaftaran peserta didik baru tahun ajaran 2026/2027 telah dibuka.',
	$k['kegiatan_1']
);

sk_demo_make_content_post( 'post', 'juara-olimpiade-sains', 'Siswa Kami Raih Juara 1 Olimpiade Sains Nasional',
	sk_demo_para( 'Prestasi membanggakan kembali ditorehkan oleh siswa Sekolah Cendekia. Tim olimpiade sains kami berhasil meraih Juara 1 pada ajang Olimpiade Sains Nasional tingkat SMP.' )
	. sk_demo_para( 'Kemenangan ini merupakan hasil kerja keras siswa dan bimbingan intensif para guru pembina. Kami ucapkan selamat dan terus berprestasi!' ),
	'Tim olimpiade sains meraih Juara 1 di ajang OSN. Selamat!',
	$k['kegiatan_4']
);

sk_demo_make_content_post( 'post', 'mpls-meriah', 'Kegiatan MPLS Hari Pertama Berjalan Meriah',
	sk_demo_para( 'Hari pertama Masa Pengenalan Lingkungan Sekolah (MPLS) berlangsung meriah. Para siswa baru disambut dengan berbagai kegiatan perkenalan yang menyenangkan.' ),
	'MPLS hari pertama berlangsung meriah dengan berbagai kegiatan perkenalan.',
	$k['kegiatan_3']
);

sk_demo_make_content_post( 'post', 'perpustakaan-baru', 'Perpustakaan Sekolah Kini Punya 500 Judul Buku Baru',
	sk_demo_para( 'Perpustakaan Sekolah Cendekia kedatangan 500 judul buku baru, mulai dari literasi, sains, hingga buku cerita. Siswa diharapkan semakin gemar membaca.' ),
	'Perpustakaan diperkaya dengan 500 judul buku baru untuk siswa.',
	$k['kegiatan_2']
);

sk_demo_make_content_post( 'post', 'pentas-seni-tahun-ajaran', 'Pentas Seni dan Budaya Akhir Tahun Ajaran',
	sk_demo_para( 'Menutup tahun ajaran dengan meriah, sekolah menggelar pentas seni dan budaya. Seluruh siswa menampilkan kreativitas mereka di atas panggung.' ),
	'Pentas seni akhir tahun ajaran menampilkan kreativitas siswa.',
	$k['kegiatan_5']
);

/* ---------------------------------------------------------------------
 * 7. Guru & Staff
 * ------------------------------------------------------------------- */

sk_demo_log( 'Membuat data guru & staff…' );
$guru_data = array(
	array( 'drs-ahmad-fauzi', 'Drs. Ahmad Fauzi, M.Pd.', 'Kepala Sekolah', 'Manajemen Pendidikan', 0 ),
	array( 'rina-marlina', 'Rina Marlina, S.Pd.', 'Guru Matematika', 'Matematika', 1 ),
	array( 'budi-santoso', 'Budi Santoso, S.Kom.', 'Guru Informatika', 'Informatika & Robotik', 2 ),
	array( 'dewi-anggraini', 'Dewi Anggraini, S.Pd., M.Pd.', 'Guru Bahasa Indonesia', 'Bahasa Indonesia', 3 ),
	array( 'joko-prasetyo', 'Joko Prasetyo, S.Pd.', 'Guru PJOK', 'Pendidikan Jasmani', 4 ),
	array( 'siti-nurhaliza', 'Siti Nurhaliza, S.Ag.', 'Guru Pendidikan Agama', 'Pendidikan Agama', 5 ),
);

foreach ( $guru_data as $g ) {
	sk_demo_make_content_post(
		'guru',
		$g[0],
		$g[1],
		sk_demo_para( 'Berikut adalah profil singkat ' . $g[1] . '. Beliau merupakan ' . strtolower( $g[2] ) . ' di Sekolah Cendekia dan berkomitmen memberikan pengajaran terbaik bagi siswa.' ),
		$g[2],
		$media['avatar'][ $g[4] ],
		array( 'jabatan' => $g[2], 'mapel' => $g[3] )
	);
}

/* ---------------------------------------------------------------------
 * 8. Prestasi
 * ------------------------------------------------------------------- */

sk_demo_log( 'Membuat data prestasi…' );
$prestasi_data = array(
	array( 'juara-1-osn-matematika', 'Juara 1 Olimpiade Sains Bidang Matematika', 'Akademik', 'Nasional', '2026', $k['prestasi_2'] ),
	array( 'juara-2-robotik-provinsi', 'Juara 2 Lomba Robotik Tingkat Provinsi', 'Non-Akademik', 'Provinsi', '2026', $k['prestasi_1'] ),
	array( 'juara-umum-festival-seni', 'Juara Umum Festival Seni Pelajar', 'Non-Akademik', 'Kota/Kabupaten', '2025', $k['prestasi_3'] ),
	array( 'medali-emas-bahasa-inggris', 'Medali Emas Kompetisi Bahasa Inggris', 'Akademik', 'Nasional', '2025', $k['prestasi_4'] ),
	array( 'juara-3-futsal', 'Juara 3 Futsal Antar Sekolah', 'Non-Akademik', 'Kecamatan', '2025', $k['eks_futsal'] ),
);

foreach ( $prestasi_data as $p ) {
	sk_demo_make_content_post(
		'prestasi',
		$p[0],
		$p[1],
		sk_demo_para( 'Selamat kepada seluruh siswa yang telah mengharumkan nama sekolah. Prestasi ini menjadi motivasi bagi seluruh warga sekolah untuk terus berkarya.' ),
		strtoupper( $p[2] ) . ' · ' . strtoupper( $p[3] ) . ' · ' . $p[4],
		$p[5],
		array( 'kategori' => $p[2], 'tingkat' => $p[3], 'tahun' => $p[4] )
	);
}

/* ---------------------------------------------------------------------
 * 9. Agenda
 * ------------------------------------------------------------------- */

sk_demo_log( 'Membuat data agenda…' );
$now        = time();
$agenda_data = array(
	array( 'rapat-orang-tua', 'Rapat Orang Tua & Wali Murid', 7, '08.00 – 11.00 WIB', 'Aula Sekolah' ),
	array( 'try-out-kelas-akhir', 'Try Out USBN Kelas Akhir', 14, '07.30 – 12.00 WIB', 'Ruang Kelas' ),
	array( 'upacara-hardiknas', 'Upacara Peringatan Hari Pendidikan Nasional', 21, '07.00 – 08.30 WIB', 'Lapangan Sekolah' ),
	array( 'pekan-olahraga-seni', 'Pekan Olahraga & Seni Antar Kelas', 30, '08.00 – 15.00 WIB', 'Lapangan Sekolah' ),
	array( 'bakti-sosial', 'Kegiatan Bakti Sosial', 45, '07.30 – 12.00 WIB', 'Lingkungan Sekolah' ),
);

foreach ( $agenda_data as $a ) {
	$date    = date( 'Y-m-d', $now + $a[2] * DAY_IN_SECONDS );
	$post_id = sk_demo_make_content_post(
		'agenda',
		$a[0],
		$a[1],
		sk_demo_para( 'Agenda ini diselenggarakan untuk seluruh warga sekolah. Kehadiran dan partisipasi Anda sangat kami harapkan.' ),
		'🕘 ' . $a[3] . ' · 📍 ' . $a[4],
		'',
		array( 'tanggal' => $date, 'waktu' => $a[3], 'lokasi' => $a[4] )
	);
	// Sinkronkan tanggal acara ke post date agar urutan & tampilan tanggal benar.
	// Update langsung via \$wpdb agar status tetap 'publish' meski tanggal di masa depan.
	global $wpdb;
	$wpdb->update(
		$wpdb->posts,
		array(
			'post_date'     => $date . ' 08:00:00',
			'post_date_gmt' => get_gmt_from_date( $date . ' 08:00:00' ),
			'post_status'   => 'publish',
		),
		array( 'ID' => $post_id )
	);
}

/* ---------------------------------------------------------------------
 * 10. Ekstrakurikuler
 * ------------------------------------------------------------------- */

sk_demo_log( 'Membuat data ekstrakurikuler…' );
$ekskul_data = array(
	array( 'robotik', 'Robotik', 'Belajar merancang, merakit, dan memprogram robot.', $k['eks_robotik'] ),
	array( 'futsal', 'Futsal', 'Mengasah kebugaran, kerja sama tim, dan sportivitas.', $k['eks_futsal'] ),
	array( 'seni-rupa', 'Seni Rupa', 'Mengembangkan kreativitas melalui menggambar dan melukis.', $k['eks_seni'] ),
	array( 'musik', 'Musik', 'Paduan suara dan alat musik untuk menyalurkan bakat seni.', $k['eks_musik'] ),
	array( 'pramuka', 'Pramuka', 'Membentuk karakter mandiri, disiplin, dan peduli sesama.', $k['eks_pramuka'] ),
	array( 'english-club', 'English Club', 'Berlatih percakapan bahasa Inggris dengan cara menyenangkan.', $k['eks_bahasa'] ),
);

foreach ( $ekskul_data as $e ) {
	sk_demo_make_content_post( 'ekskul', $e[0], $e[1], sk_demo_para( $e[2] ), $e[2], $e[3] );
}

/* ---------------------------------------------------------------------
 * 11. Galeri
 * ------------------------------------------------------------------- */

sk_demo_log( 'Membuat album galeri…' );
$galeri_data = array(
	array( 'mpls-2026', 'MPLS 2026', array( 'kegiatan_1', 'kegiatan_3', 'kegiatan_6', 'kegiatan_4' ) ),
	array( 'hari-kemerdekaan', 'Peringatan HUT Kemerdekaan RI', array( 'kegiatan_3', 'kegiatan_5', 'kegiatan_6', 'kegiatan_2' ) ),
	array( 'kunjungan-edukasi', 'Kunjungan Edukasi', array( 'kegiatan_4', 'kegiatan_2', 'kegiatan_1', 'kegiatan_6' ) ),
	array( 'pentas-seni', 'Pentas Seni Akhir Tahun', array( 'kegiatan_5', 'kegiatan_3', 'kegiatan_1', 'kegiatan_2' ) ),
);

foreach ( $galeri_data as $g ) {
	$ids = array();
	foreach ( $g[2] as $key ) {
		$ids[] = $k[ $key ];
	}
	sk_demo_make_content_post( 'galeri', $g[0], $g[1], '', '', $ids[0], array( 'foto_ids' => $ids ) );
}

/* ---------------------------------------------------------------------
 * 12. Halaman depan & menu
 * ------------------------------------------------------------------- */

update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $page_beranda );
update_option( 'page_for_posts', $page_berita );

sk_demo_log( 'Membuat menu navigasi…' );

// Hapus post wp_navigation lama (agar script bisa dijalankan ulang).
$old_navs = get_posts(
	array(
		'post_type'   => 'wp_navigation',
		'numberposts' => 20,
	)
);
foreach ( $old_navs as $old_nav ) {
	wp_delete_post( $old_nav->ID, true );
}

/**
 * Serialized navigation-link block.
 *
 * @param string $title   Label.
 * @param string $kind    'post-type' or 'custom'.
 * @param int    $page_id Page ID (post-type links).
 * @param string $url     URL (custom links).
 * @return string
 */
function sk_demo_nav_link( $title, $kind, $page_id = 0, $url = '' ) {
	$base = get_option( 'home' ); // Di CLI home_url() tidak bisa menebak host.
	$attrs = array(
		'label'          => $title,
		'kind'           => $kind,
		'isTopLevelLink' => true,
	);
	if ( 'post-type' === $kind ) {
		$page = get_post( $page_id );
		$attrs['type'] = 'page';
		$attrs['id']   = (int) $page_id;
		$attrs['url']  = trailingslashit( $base ) . ( $page ? $page->post_name : '' ) . '/';
	} else {
		$attrs['type'] = 'custom';
		$attrs['url']  = $url;
	}
	return '<!-- wp:navigation-link ' . wp_json_encode( $attrs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . ' /-->' . "\n";
}

$nav_base     = get_option( 'home' );
$nav_content  = sk_demo_nav_link( 'Beranda', 'post-type', $page_beranda );
$nav_content .= sk_demo_nav_link( 'Profil', 'post-type', $page_profil );
$nav_content .= sk_demo_nav_link( 'Berita', 'post-type', $page_berita );
$nav_content .= sk_demo_nav_link( 'Prestasi', 'custom', 0, $nav_base . '/prestasi/' );
$nav_content .= sk_demo_nav_link( 'Agenda', 'custom', 0, $nav_base . '/agenda/' );
$nav_content .= sk_demo_nav_link( 'Galeri', 'custom', 0, $nav_base . '/galeri/' );
$nav_content .= sk_demo_nav_link( 'PPDB', 'post-type', $page_ppdb );
$nav_content .= sk_demo_nav_link( 'Kontak', 'post-type', $page_kontak );

$nav_id = wp_insert_post(
	array(
		'post_type'    => 'wp_navigation',
		'post_status'  => 'publish',
		'post_title'   => 'Menu Utama',
		'post_name'    => 'menu-utama',
		'post_content' => $nav_content,
	)
);

// Suntikkan ref ke template part header agar navigasi tampil.
$header_content = file_get_contents( get_theme_file_path( '/parts/header.html' ) );
$header_content = str_replace(
	'<!-- wp:navigation {',
	'<!-- wp:navigation {"ref":' . (int) $nav_id . ',',
	$header_content
);

// Bersihkan template part lama yang salah format (jika ada).
$stray_parts = get_posts(
	array(
		'post_type'   => 'wp_template_part',
		'numberposts' => 20,
	)
);
foreach ( $stray_parts as $stray ) {
	if ( 'header' !== $stray->post_name ) {
		wp_delete_post( $stray->ID, true );
	}
}

$existing_part = get_posts(
	array(
		'post_type'   => 'wp_template_part',
		'name'        => 'header',
		'numberposts' => 1,
	)
);
$part_args = array(
	'post_type'    => 'wp_template_part',
	'post_name'    => 'header',
	'post_title'   => 'Header',
	'post_content' => $header_content,
	'post_status'  => 'publish',
);
if ( $existing_part ) {
	$part_args['ID'] = $existing_part[0]->ID;
}
$part_id = wp_insert_post( $part_args );
wp_set_object_terms( $part_id, 'header', 'wp_template_part_area' );
wp_set_object_terms( $part_id, 'sekolahku', 'wp_theme' );

/* ---------------------------------------------------------------------
 * 13. Bersih-bersih konten bawaan
 * ------------------------------------------------------------------- */

foreach ( array( 'hello-world', 'halo-dunia' ) as $sk_hello_slug ) {
	$hello = get_page_by_path( $sk_hello_slug, OBJECT, 'post' );
	if ( $hello ) {
		wp_delete_post( $hello->ID, true );
	}
}
foreach ( array( 'sample-page', 'laman-contoh', 'contoh-halaman' ) as $sk_sample_slug ) {
	$sample = get_page_by_path( $sk_sample_slug, OBJECT, 'page' );
	if ( $sample ) {
		wp_delete_post( $sample->ID, true );
	}
}

/* ---------------------------------------------------------------------
 * 14. Selesai
 * ------------------------------------------------------------------- */

flush_rewrite_rules();

echo PHP_EOL;
sk_demo_hr();
echo '  ✅ Import konten demo selesai!' . PHP_EOL;
sk_demo_hr();
echo PHP_EOL;
echo '  Halaman depan : ' . home_url( '/beranda/' ) . PHP_EOL;
echo '  Berita        : ' . home_url( '/berita/' ) . PHP_EOL;
echo '  Guru & Staff  : ' . home_url( '/guru-staff/' ) . PHP_EOL;
echo '  Prestasi      : ' . home_url( '/prestasi/' ) . PHP_EOL;
echo '  Agenda        : ' . home_url( '/agenda/' ) . PHP_EOL;
echo '  Ekskul        : ' . home_url( '/ekstrakurikuler/' ) . PHP_EOL;
echo '  Galeri        : ' . home_url( '/galeri/' ) . PHP_EOL;
echo '  PPDB (form)   : ' . home_url( '/ppdb/' ) . PHP_EOL;
echo '  Kontak        : ' . home_url( '/kontak/' ) . PHP_EOL;
echo PHP_EOL;
echo '  Admin         : ' . admin_url() . PHP_EOL;
echo PHP_EOL;
