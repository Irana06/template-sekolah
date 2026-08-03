<?php
/**
 * Meta boxes, admin columns and save handlers.
 *
 * @package SekolahkuCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register meta boxes.
 */
function sekolahku_core_register_metaboxes() {
	add_meta_box(
		'sk-guru-details',
		'Detail Guru / Staff',
		'sekolahku_core_guru_metabox',
		'guru',
		'normal',
		'high'
	);

	add_meta_box(
		'sk-prestasi-details',
		'Detail Prestasi',
		'sekolahku_core_prestasi_metabox',
		'prestasi',
		'normal',
		'high'
	);

	add_meta_box(
		'sk-agenda-details',
		'Detail Agenda',
		'sekolahku_core_agenda_metabox',
		'agenda',
		'normal',
		'high'
	);

	add_meta_box(
		'sk-galeri-details',
		'Foto Galeri',
		'sekolahku_core_galeri_metabox',
		'galeri',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'sekolahku_core_register_metaboxes' );

/**
 * Helpers: get/save meta.
 */
function sekolahku_core_meta( $post_id, $key, $default = '' ) {
	$value = get_post_meta( $post_id, sekolahku_core_prefix() . $key, true );
	return '' !== $value ? $value : $default;
}

/**
 * Guru metabox.
 *
 * @param WP_Post $post Post object.
 */
function sekolahku_core_guru_metabox( $post ) {
	wp_nonce_field( 'sk_guru_meta', 'sk_guru_meta_nonce' );
	$jabatan = sekolahku_core_meta( $post->ID, 'jabatan' );
	$mapel   = sekolahku_core_meta( $post->ID, 'mapel' );
	?>
	<p>
		<label for="sk_jabatan"><strong>Jabatan / Bidang</strong> (wajib, contoh: Guru Matematika, Kepala Sekolah, Staf TU)</label><br>
		<input type="text" id="sk_jabatan" name="sk_jabatan" value="<?php echo esc_attr( $jabatan ); ?>" class="widefat" />
	</p>
	<p>
		<label for="sk_mapel"><strong>Keahlian / Mata Pelajaran</strong> (opsional, contoh: Matematika, Bahasa Inggris, Bimbingan Konseling)</label><br>
		<input type="text" id="sk_mapel" name="sk_mapel" value="<?php echo esc_attr( $mapel ); ?>" class="widefat" />
	</p>
	<p class="description">Foto guru diisi lewat panel "Gambar Unggulan" di samping kanan. Disarankan foto potret (3:4).</p>
	<?php
}

/**
 * Prestasi metabox.
 *
 * @param WP_Post $post Post object.
 */
function sekolahku_core_prestasi_metabox( $post ) {
	wp_nonce_field( 'sk_prestasi_meta', 'sk_prestasi_meta_nonce' );
	$kategori = sekolahku_core_meta( $post->ID, 'kategori', 'Akademik' );
	$tingkat  = sekolahku_core_meta( $post->ID, 'tingkat', 'Kota/Kabupaten' );		$tahun    = sekolahku_core_meta( $post->ID, 'tahun', wp_date( 'Y' ) );
	?>
	<p>
		<label for="sk_kategori"><strong>Kategori</strong></label><br>
		<select id="sk_kategori" name="sk_kategori" class="widefat">
			<?php foreach ( array( 'Akademik', 'Non-Akademik' ) as $opt ) : ?>
				<option value="<?php echo esc_attr( $opt ); ?>" <?php selected( $kategori, $opt ); ?>><?php echo esc_html( $opt ); ?></option>
			<?php endforeach; ?>
		</select>
	</p>
	<p>
		<label for="sk_tingkat"><strong>Tingkat Lomba</strong></label><br>
		<select id="sk_tingkat" name="sk_tingkat" class="widefat">
			<?php foreach ( array( 'Sekolah', 'Kecamatan', 'Kota/Kabupaten', 'Provinsi', 'Nasional', 'Internasional' ) as $opt ) : ?>
				<option value="<?php echo esc_attr( $opt ); ?>" <?php selected( $tingkat, $opt ); ?>><?php echo esc_html( $opt ); ?></option>
			<?php endforeach; ?>
		</select>
	</p>
	<p>
		<label for="sk_tahun"><strong>Tahun</strong></label><br>
		<input type="number" id="sk_tahun" name="sk_tahun" min="2000" max="2100" value="<?php echo esc_attr( $tahun ); ?>" class="widefat" />
	</p>
	<?php
}

/**
 * Agenda metabox.
 *
 * @param WP_Post $post Post object.
 */
function sekolahku_core_agenda_metabox( $post ) {
	wp_nonce_field( 'sk_agenda_meta', 'sk_agenda_meta_nonce' );
	$tanggal = sekolahku_core_meta( $post->ID, 'tanggal' );
	$waktu   = sekolahku_core_meta( $post->ID, 'waktu', '08.00 – 12.00 WIB' );
	$lokasi  = sekolahku_core_meta( $post->ID, 'lokasi' );
	?>
	<p>
		<label for="sk_tanggal"><strong>Tanggal Kegiatan</strong> (wajib)</label><br>
		<input type="date" id="sk_tanggal" name="sk_tanggal" value="<?php echo esc_attr( $tanggal ); ?>" class="widefat" />
	</p>
	<p>
		<label for="sk_waktu"><strong>Waktu</strong> (contoh: 08.00 – 12.00 WIB)</label><br>
		<input type="text" id="sk_waktu" name="sk_waktu" value="<?php echo esc_attr( $waktu ); ?>" class="widefat" />
	</p>
	<p>
		<label for="sk_lokasi"><strong>Lokasi</strong> (contoh: Aula Sekolah, Lapangan)</label><br>
		<input type="text" id="sk_lokasi" name="sk_lokasi" value="<?php echo esc_attr( $lokasi ); ?>" class="widefat" />
	</p>
	<?php
}

/**
 * Galeri metabox (repeatable image picker).
 *
 * @param WP_Post $post Post object.
 */
function sekolahku_core_galeri_metabox( $post ) {
	wp_nonce_field( 'sk_galeri_meta', 'sk_galeri_meta_nonce' );
	$ids = get_post_meta( $post->ID, sekolahku_core_prefix() . 'foto_ids', true );
	$ids = is_array( $ids ) ? array_map( 'absint', $ids ) : array();
	?>
	<div class="sk-gallery-metabox">
		<p class="description">Klik "Tambah Foto" untuk memilih foto dari Media Library atau mengunggah foto baru. Foto pertama akan otomatis menjadi sampul album.</p>
		<input type="hidden" name="sk_foto_ids" id="sk_foto_ids" value="<?php echo esc_attr( implode( ',', $ids ) ); ?>" />
		<div class="sk-gallery-grid" id="sk_gallery_grid">
			<?php foreach ( $ids as $att_id ) : ?>
				<?php $thumb = wp_get_attachment_image( $att_id, 'thumbnail' ); ?>
				<?php if ( $thumb ) : ?>
					<div class="sk-gallery-thumb" data-id="<?php echo esc_attr( $att_id ); ?>">
						<?php echo $thumb; // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<button type="button" class="sk-gallery-remove" aria-label="Hapus foto">&times;</button>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
		<button type="button" class="button" id="sk_gallery_add">➕ Tambah Foto</button>
	</div>
	<?php
}

/**
 * Save meta for managed post types.
 *
 * @param int $post_id Post ID.
 */
function sekolahku_core_save_meta( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$type = get_post_type( $post_id );
	$map  = array(
		'guru'     => array( 'sk_guru_meta_nonce', 'sk_guru_meta' ),
		'prestasi' => array( 'sk_prestasi_meta_nonce', 'sk_prestasi_meta' ),
		'agenda'   => array( 'sk_agenda_meta_nonce', 'sk_agenda_meta' ),
		'galeri'   => array( 'sk_galeri_meta_nonce', 'sk_galeri_meta' ),
	);

	if ( ! isset( $map[ $type ] ) ) {
		return;
	}

	list( $nonce_field, $nonce_action ) = $map[ $type ];

	if ( ! isset( $_POST[ $nonce_field ] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ $nonce_field ] ) ), $nonce_action ) ) {
		return;
	}

	switch ( $type ) {
		case 'guru':
			$jabatan = isset( $_POST['sk_jabatan'] ) ? sanitize_text_field( wp_unslash( $_POST['sk_jabatan'] ) ) : '';
			$mapel   = isset( $_POST['sk_mapel'] ) ? sanitize_text_field( wp_unslash( $_POST['sk_mapel'] ) ) : '';
			update_post_meta( $post_id, sekolahku_core_prefix() . 'jabatan', $jabatan );
			update_post_meta( $post_id, sekolahku_core_prefix() . 'mapel', $mapel );
			$sk_post = get_post( $post_id );
			if ( '' === trim( (string) $sk_post->post_excerpt ) && $jabatan ) {
				wp_update_post( array( 'ID' => $post_id, 'post_excerpt' => $jabatan ) );
			}
			break;

		case 'prestasi':
			$kategori = isset( $_POST['sk_kategori'] ) ? sanitize_text_field( wp_unslash( $_POST['sk_kategori'] ) ) : 'Akademik';
			$tingkat  = isset( $_POST['sk_tingkat'] ) ? sanitize_text_field( wp_unslash( $_POST['sk_tingkat'] ) ) : 'Kota/Kabupaten';
			$tahun    = isset( $_POST['sk_tahun'] ) ? absint( $_POST['sk_tahun'] ) : (int) date( 'Y' );
			update_post_meta( $post_id, sekolahku_core_prefix() . 'kategori', $kategori );
			update_post_meta( $post_id, sekolahku_core_prefix() . 'tingkat', $tingkat );
			update_post_meta( $post_id, sekolahku_core_prefix() . 'tahun', $tahun );
			$sk_post = get_post( $post_id );
			if ( '' === trim( (string) $sk_post->post_excerpt ) ) {
				wp_update_post( array( 'ID' => $post_id, 'post_excerpt' => $kategori . ' · ' . $tingkat . ' · ' . $tahun ) );
			}
			break;

		case 'agenda':
			$tanggal = isset( $_POST['sk_tanggal'] ) ? sanitize_text_field( wp_unslash( $_POST['sk_tanggal'] ) ) : '';
			$waktu   = isset( $_POST['sk_waktu'] ) ? sanitize_text_field( wp_unslash( $_POST['sk_waktu'] ) ) : '';
			$lokasi  = isset( $_POST['sk_lokasi'] ) ? sanitize_text_field( wp_unslash( $_POST['sk_lokasi'] ) ) : '';

			update_post_meta( $post_id, sekolahku_core_prefix() . 'tanggal', $tanggal );
			update_post_meta( $post_id, sekolahku_core_prefix() . 'waktu', $waktu );
			update_post_meta( $post_id, sekolahku_core_prefix() . 'lokasi', $lokasi );

			// Sync event date into post date so ordering/date display works everywhere.
			// Gunakan update langsung via $wpdb agar status tetap 'publish' meski tanggal di masa depan
			// (wp_insert_post akan mengubah status menjadi 'future' untuk tanggal mendatang).
			if ( preg_match( '/^\d{4}-\d{2}-\d{2}$/', $tanggal ) ) {
				global $wpdb;
				$wpdb->update(
					$wpdb->posts,
					array(
						'post_date'     => $tanggal . ' 08:00:00',
						'post_date_gmt' => get_gmt_from_date( $tanggal . ' 08:00:00' ),
						'post_status'   => 'publish',
					),
					array( 'ID' => $post_id )
				);
			}
			$sk_post = get_post( $post_id );
			if ( '' === trim( (string) $sk_post->post_excerpt ) && $lokasi ) {
				wp_update_post( array( 'ID' => $post_id, 'post_excerpt' => $lokasi ) );
			}
			break;

		case 'galeri':
			$raw    = isset( $_POST['sk_foto_ids'] ) ? sanitize_text_field( wp_unslash( $_POST['sk_foto_ids'] ) ) : '';
			$ids    = array_filter( array_map( 'absint', explode( ',', $raw ) ) );
			update_post_meta( $post_id, sekolahku_core_prefix() . 'foto_ids', $ids );
			// Auto set featured image from first photo if none set.
			if ( $ids && ! has_post_thumbnail( $post_id ) ) {
				set_post_thumbnail( $post_id, reset( $ids ) );
			}
			break;
	}
}
add_action( 'save_post', 'sekolahku_core_save_meta' );

/**
 * Admin list columns for managed post types.
 */
function sekolahku_core_manage_columns( $columns ) {
	$type = get_current_screen() ? get_current_screen()->post_type : '';

	if ( 'guru' === $type ) {
		$out = array();
		foreach ( $columns as $key => $label ) {
			$out[ $key ] = $label;
			if ( 'title' === $key ) {
				$out['sk_jabatan'] = 'Jabatan';
			}
		}
		$out['thumbnail'] = 'Foto';
		unset( $out['date'] );
		return $out;
	}

	if ( 'prestasi' === $type ) {
		$columns['sk_kategori'] = 'Kategori';
		$columns['sk_tingkat']  = 'Tingkat';
		$columns['sk_tahun']    = 'Tahun';
		$columns['thumbnail']   = 'Gambar';
		unset( $columns['date'] );
		return $columns;
	}

	if ( 'agenda' === $type ) {
		$columns['sk_tanggal'] = 'Tanggal';
		$columns['sk_lokasi']  = 'Lokasi';
		unset( $columns['date'] );
		return $columns;
	}

	if ( 'ekskul' === $type ) {
		$columns['thumbnail'] = 'Ikon';
		unset( $columns['date'] );
		return $columns;
	}

	if ( 'galeri' === $type ) {
		$columns['thumbnail'] = 'Sampul';
		$columns['sk_jumlah']  = 'Jumlah Foto';
		unset( $columns['date'] );
		return $columns;
	}

	return $columns;
}
add_filter( 'manage_guru_posts_columns', 'sekolahku_core_manage_columns' );
add_filter( 'manage_prestasi_posts_columns', 'sekolahku_core_manage_columns' );
add_filter( 'manage_agenda_posts_columns', 'sekolahku_core_manage_columns' );
add_filter( 'manage_ekskul_posts_columns', 'sekolahku_core_manage_columns' );
add_filter( 'manage_galeri_posts_columns', 'sekolahku_core_manage_columns' );

/**
 * Render admin list column values.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function sekolahku_core_render_column( $column, $post_id ) {
	switch ( $column ) {
		case 'thumbnail':
			echo get_the_post_thumbnail( $post_id, array( 56, 56 ) );
			break;

		case 'sk_jabatan':
			echo esc_html( sekolahku_core_meta( $post_id, 'jabatan' ) );
			break;

		case 'sk_kategori':
			echo esc_html( sekolahku_core_meta( $post_id, 'kategori' ) );
			break;

		case 'sk_tingkat':
			echo esc_html( sekolahku_core_meta( $post_id, 'tingkat' ) );
			break;

		case 'sk_tahun':
			echo esc_html( sekolahku_core_meta( $post_id, 'tahun' ) );
			break;

		case 'sk_tanggal':
			$t = sekolahku_core_meta( $post_id, 'tanggal' );
			echo $t ? esc_html( date_i18n( 'd M Y', strtotime( $t ) ) ) : '—';
			break;

		case 'sk_lokasi':
			echo esc_html( sekolahku_core_meta( $post_id, 'lokasi' ) );
			break;

		case 'sk_jumlah':
			$ids = get_post_meta( $post_id, sekolahku_core_prefix() . 'foto_ids', true );
			echo is_array( $ids ) ? count( $ids ) : 0;
			break;
	}
}
add_action( 'manage_guru_posts_custom_column', 'sekolahku_core_render_column', 10, 2 );
add_action( 'manage_prestasi_posts_custom_column', 'sekolahku_core_render_column', 10, 2 );
add_action( 'manage_agenda_posts_custom_column', 'sekolahku_core_render_column', 10, 2 );
add_action( 'manage_ekskul_posts_custom_column', 'sekolahku_core_render_column', 10, 2 );
add_action( 'manage_galeri_posts_custom_column', 'sekolahku_core_render_column', 10, 2 );

/**
 * Admin assets.
 */
function sekolahku_core_admin_assets( $hook ) {
	$screen = get_current_screen();
	$post_type = $screen ? $screen->post_type : '';

	if ( in_array( $post_type, sekolahku_core_post_types(), true ) || false !== strpos( $hook, 'sekolahku' ) ) {
		wp_enqueue_style( 'sekolahku-core-admin', SEKOLAHKU_CORE_URL . 'assets/css/admin.css', array(), SEKOLAHKU_CORE_VERSION );
	}

	if ( 'galeri' === $post_type ) {
		wp_enqueue_media();
		wp_enqueue_script( 'sekolahku-core-gallery', SEKOLAHKU_CORE_URL . 'assets/js/gallery.js', array( 'jquery' ), SEKOLAHKU_CORE_VERSION, true );
	}
}
add_action( 'admin_enqueue_scripts', 'sekolahku_core_admin_assets' );
