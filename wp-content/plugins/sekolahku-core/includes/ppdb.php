<?php
/**
 * PPDB — registration form + admin management.
 *
 * @package SekolahkuCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * PPDB field definitions.
 *
 * @return array
 */
function sekolahku_core_client_hash() {
	$salt = '';
	if ( function_exists( 'wp_get_session_token' ) ) {
		$salt = wp_get_session_token();
	}
	if ( ! $salt && isset( $_SERVER['REMOTE_ADDR'] ) ) {
		$salt = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
	}
	return md5( $salt . ( isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '' ) );
}

/**
 * PPDB field definitions.
 *
 * @return array
 */
function sekolahku_core_ppdb_fields() {
	return array(
		'nama_lengkap' => array( 'label' => 'Nama Lengkap Calon Siswa', 'type' => 'text', 'required' => true, 'full' => false ),
		'tempat_lahir' => array( 'label' => 'Tempat Lahir', 'type' => 'text', 'required' => false, 'full' => false ),
		'tanggal_lahir'=> array( 'label' => 'Tanggal Lahir', 'type' => 'date', 'required' => true, 'full' => false ),
		'jenis_kelamin'=> array( 'label' => 'Jenis Kelamin', 'type' => 'select', 'required' => true, 'full' => false, 'options' => array( 'Laki-laki', 'Perempuan' ) ),
		'nisn'         => array( 'label' => 'NISN / NIK', 'type' => 'text', 'required' => false, 'full' => false ),
		'asal_sekolah' => array( 'label' => 'Asal Sekolah', 'type' => 'text', 'required' => false, 'full' => false ),
		'alamat'       => array( 'label' => 'Alamat Lengkap', 'type' => 'textarea', 'required' => true, 'full' => true ),
		'nama_ortu'    => array( 'label' => 'Nama Orang Tua / Wali', 'type' => 'text', 'required' => true, 'full' => false ),
		'hp'           => array( 'label' => 'No. HP / WhatsApp Orang Tua', 'type' => 'tel', 'required' => true, 'full' => false ),
		'email'        => array( 'label' => 'Email Orang Tua / Wali', 'type' => 'email', 'required' => true, 'full' => false ),
		'jalur'        => array( 'label' => 'Jalur Pendaftaran', 'type' => 'select', 'required' => true, 'full' => false, 'options' => array( 'Reguler', 'Prestasi', 'Zonasi', 'Beasiswa' ) ),
		'catatan'      => array( 'label' => 'Catatan (opsional)', 'type' => 'textarea', 'required' => false, 'full' => true ),
	);
}

/**
 * Register shortcode.
 */
function sekolahku_core_ppdb_shortcode() {
	add_shortcode( 'sekolahku_ppdb', 'sekolahku_core_ppdb_render' );
}
add_action( 'init', 'sekolahku_core_ppdb_shortcode' );

/**
 * Handle form submission (PRG).
 */
function sekolahku_core_ppdb_handle() {
	if ( empty( $_POST['sk_ppdb_submit'] ) ) {
		return;
	}

	if ( ! isset( $_POST['sk_ppdb_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sk_ppdb_nonce'] ) ), 'sk_ppdb_form' ) ) {
		return;
	}

	// Honeypot: bots fill this hidden field.
	if ( ! empty( $_POST['sk_ppdb_website'] ) ) {
		wp_safe_redirect( wp_get_referer() ? wp_get_referer() : home_url( '/' ) );
		exit;
	}

	$errors = array();
	$data   = array();

	foreach ( sekolahku_core_ppdb_fields() as $key => $field ) {
		$value = isset( $_POST[ 'sk_' . $key ] ) ? wp_unslash( $_POST[ 'sk_' . $key ] ) : '';

		if ( 'email' === $field['type'] ) {
			$value = sanitize_email( $value );
		} elseif ( 'textarea' === $field['type'] ) {
			$value = sanitize_textarea_field( $value );
		} else {
			$value = sanitize_text_field( $value );
		}

		$data[ $key ] = $value;

		if ( $field['required'] && '' === trim( $value ) ) {
			$errors[] = $field['label'];
		}
	}

	if ( isset( $data['email'] ) && $data['email'] && ! is_email( $data['email'] ) ) {
		$errors[] = 'Format email tidak valid';
	}

	if ( ! empty( $errors ) ) {
		setcookie( 'sk_ppdb_errors', wp_json_encode( $errors ), 0, COOKIEPATH, COOKIE_DOMAIN, is_ssl() );
		// Store entered values briefly so the form can repopulate.
		update_option( 'sk_ppdb_draft_' . sekolahku_core_client_hash(), $data, false );
		wp_safe_redirect( add_query_arg( 'sk_ppdb', 'error', wp_get_referer() ? wp_get_referer() : home_url( '/' ) ) );
		exit;
	}

	// Save registration.
	$post_id = wp_insert_post(
		array(
			'post_type'   => 'ppdb_registrasi',
			'post_status' => 'publish',
			'post_title'  => $data['nama_lengkap'],
		)
	);

	if ( is_wp_error( $post_id ) ) {
		wp_safe_redirect( add_query_arg( 'sk_ppdb', 'error', wp_get_referer() ? wp_get_referer() : home_url( '/' ) ) );
		exit;
	}

	foreach ( $data as $key => $value ) {
		update_post_meta( $post_id, sekolahku_core_prefix() . 'ppdb_' . $key, $value );
	}
	update_post_meta( $post_id, sekolahku_core_prefix() . 'ppdb_status', 'Baru' );
	update_post_meta( $post_id, sekolahku_core_prefix() . 'ppdb_waktu', current_time( 'mysql' ) );

	// Notify admin by email (best effort).
	$to      = get_option( 'admin_email' );
	$subject = 'Pendaftar PPDB Baru: ' . $data['nama_lengkap'];
	$body    = "Ada pendaftar baru melalui website:\n\n"
		. 'Nama: ' . $data['nama_lengkap'] . "\n"
		. 'Jalur: ' . $data['jalur'] . "\n"
		. 'HP: ' . $data['hp'] . "\n"
		. 'Email: ' . $data['email'] . "\n"
		. 'Asal Sekolah: ' . $data['asal_sekolah'] . "\n\n"
		. 'Kelola di: ' . admin_url( 'admin.php?page=sekolahku-ppdb' );
	wp_mail( $to, $subject, $body );

	wp_safe_redirect( add_query_arg( 'sk_ppdb', 'success', wp_get_referer() ? wp_get_referer() : home_url( '/' ) ) );
	exit;
}
add_action( 'template_redirect', 'sekolahku_core_ppdb_handle' );

/**
 * Render the PPDB form.
 *
 * @return string
 */
function sekolahku_core_ppdb_render() {
	$message = '';
	$type    = '';

	$status = isset( $_GET['sk_ppdb'] ) ? sanitize_key( wp_unslash( $_GET['sk_ppdb'] ) ) : '';
	if ( 'success' === $status ) {
		$message = '✅ Terima kasih! Data pendaftaran Anda telah kami terima. Tim kami akan menghubungi Anda melalui WhatsApp/email.';
		$type    = 'success';
	} elseif ( 'error' === $status ) {
		$errors = isset( $_COOKIE['sk_ppdb_errors'] ) ? json_decode( wp_unslash( $_COOKIE['sk_ppdb_errors'] ), true ) : array();
		// Hapus cookie error setelah dibaca.
		if ( isset( $_COOKIE['sk_ppdb_errors'] ) ) {
			setcookie( 'sk_ppdb_errors', '', time() - HOUR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN, is_ssl() );
		}
		if ( is_array( $errors ) && $errors ) {
			$message = '⚠️ Mohon lengkapi isian berikut: <strong>' . esc_html( implode( ', ', $errors ) ) . '</strong>';
		} else {
			$message = '⚠️ Ada kesalahan pada pengisian formulir. Mohon periksa kembali.';
		}
		$type = 'error';
	}

	// Repopulate draft values from a previous failed submit.
	$sk_draft_key = 'sk_ppdb_draft_' . sekolahku_core_client_hash();
	$draft = get_option( $sk_draft_key );
	if ( $status ) {
		delete_option( $sk_draft_key );
	}
	if ( ! is_array( $draft ) ) {
		$draft = array();
	}

	ob_start();
	?>
	<div class="sk-ppdb-wrap">
		<?php if ( $message ) : ?>
			<div class="sk-ppdb-msg sk-ppdb-msg--<?php echo esc_attr( $type ); ?>"><?php echo wp_kses_post( $message ); ?></div>
		<?php endif; ?>

		<form class="sk-ppdb-form" method="post" action="">
			<?php wp_nonce_field( 'sk_ppdb_form', 'sk_ppdb_nonce' ); ?>
			<p style="display:none !important;"><label>Jangan diisi jika Anda manusia <input type="text" name="sk_ppdb_website" value="" tabindex="-1" autocomplete="off" /></label></p>

			<?php foreach ( sekolahku_core_ppdb_fields() as $key => $field ) : ?>
				<div class="sk-field <?php echo $field['full'] ? 'sk-field-full' : ''; ?>">
					<label for="sk_<?php echo esc_attr( $key ); ?>">
						<?php echo esc_html( $field['label'] ); ?>
						<?php if ( $field['required'] ) : ?><span class="sk-required">*</span><?php endif; ?>
					</label>

					<?php if ( 'select' === $field['type'] ) : ?>
						<select id="sk_<?php echo esc_attr( $key ); ?>" name="sk_<?php echo esc_attr( $key ); ?>">
							<option value="">— Pilih —</option>
							<?php foreach ( $field['options'] as $opt ) : ?>
								<option value="<?php echo esc_attr( $opt ); ?>" <?php selected( isset( $draft[ $key ] ) ? $draft[ $key ] : '', $opt ); ?>><?php echo esc_html( $opt ); ?></option>
							<?php endforeach; ?>
						</select>
					<?php elseif ( 'textarea' === $field['type'] ) : ?>
						<textarea id="sk_<?php echo esc_attr( $key ); ?>" name="sk_<?php echo esc_attr( $key ); ?>" rows="3"><?php echo esc_textarea( isset( $draft[ $key ] ) ? $draft[ $key ] : '' ); ?></textarea>
					<?php else : ?>
						<input type="<?php echo esc_attr( $field['type'] ); ?>" id="sk_<?php echo esc_attr( $key ); ?>" name="sk_<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( isset( $draft[ $key ] ) ? $draft[ $key ] : '' ); ?>" />
					<?php endif; ?>
				</div>
			<?php endforeach; ?>

			<button type="submit" name="sk_ppdb_submit" class="sk-ppdb-submit">📝 Kirim Pendaftaran</button>
		</form>

		<p class="sk-ppdb-note">Dengan mengirim formulir, Anda menyetujui data tersebut digunakan untuk keperluan penerimaan peserta didik baru.</p>
	</div>
	<?php
	return ob_get_clean();
}

/* -------------------------------------------------------------------------
 * Admin: list, status, delete, export
 * ----------------------------------------------------------------------- */

/**
 * Admin page: list of registrations.
 */
function sekolahku_core_ppdb_admin_page() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return;
	}

	// Handle status update / delete.
	$notice = '';
	if ( isset( $_POST['sk_ppdb_admin'] ) && check_admin_referer( 'sk_ppdb_admin', 'sk_ppdb_admin_nonce' ) ) {
		$entry_id = isset( $_POST['sk_entry'] ) ? absint( $_POST['sk_entry'] ) : 0;

		if ( 'delete' === $_POST['sk_ppdb_admin'] && $entry_id ) {
			wp_delete_post( $entry_id, true );
			$notice = 'Data pendaftar berhasil dihapus.';
		} elseif ( 'status' === $_POST['sk_ppdb_admin'] && $entry_id ) {
			$status = isset( $_POST['sk_status'] ) ? sanitize_text_field( wp_unslash( $_POST['sk_status'] ) ) : 'Baru';
			update_post_meta( $entry_id, sekolahku_core_prefix() . 'ppdb_status', $status );
			$notice = 'Status pendaftar berhasil diperbarui.';
		}
	}

	// CSV export.
	if ( isset( $_GET['export'] ) && '1' === $_GET['export'] ) {
		check_admin_referer( 'sk_ppdb_export' );
		sekolahku_core_ppdb_export_csv();
	}

	$fields   = sekolahku_core_ppdb_fields();
	$paged    = isset( $_GET['paged'] ) ? max( 1, absint( $_GET['paged'] ) ) : 1;
	$per_page = 20;

	$query = new WP_Query(
		array(
			'post_type'      => 'ppdb_registrasi',
			'post_status'    => 'publish',
			'posts_per_page' => $per_page,
			'paged'          => $paged,
			'orderby'        => 'meta_value_num',
			'meta_key'       => sekolahku_core_prefix() . 'ppdb_waktu',
			'order'          => 'DESC',
		)
	);

	echo '<div class="wrap sk-ppdb-admin">';
	echo '<h1 class="wp-heading-inline">📋 Data Pendaftar PPDB</h1>';
	echo ' <a class="page-title-action" href="' . esc_url( wp_nonce_url( admin_url( 'admin.php?page=sekolahku-ppdb&export=1' ), 'sk_ppdb_export' ) ) . '">⬇️ Export CSV</a>';

	if ( $notice ) {
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html( $notice ) . '</p></div>';
	}

	echo '<p>Berikut adalah data pendaftar yang masuk melalui formulir PPDB di website. Anda dapat mengubah status dan menghapus data.</p>';

	echo '<table class="widefat striped sk-ppdb-table">';
	echo '<thead><tr>'
		. '<th>No</th><th>Nama</th><th>Jalur</th><th>No. HP</th><th>Asal Sekolah</th><th>Waktu Daftar</th><th>Status</th><th>Aksi</th>'
		. '</tr></thead><tbody>';

	if ( $query->have_posts() ) {
		$i = ( $paged - 1 ) * $per_page;
		while ( $query->have_posts() ) {
			$query->the_post();
			$id      = get_the_ID();
			$i++;
			$status  = sekolahku_core_meta( $id, 'ppdb_status', 'Baru' );
			$nama    = sekolahku_core_meta( $id, 'ppdb_nama_lengkap' );
			$jalur   = sekolahku_core_meta( $id, 'ppdb_jalur' );
			$hp      = sekolahku_core_meta( $id, 'ppdb_hp' );
			$asal    = sekolahku_core_meta( $id, 'ppdb_asal_sekolah' );
			$waktu   = sekolahku_core_meta( $id, 'ppdb_waktu' );
			$waktu   = $waktu ? date_i18n( 'd M Y, H:i', strtotime( $waktu ) ) : '—';

			echo '<tr>';
			echo '<td>' . esc_html( $i ) . '</td>';
			echo '<td><strong>' . esc_html( $nama ) . '</strong><br><a href="#" class="sk-ppdb-detail-toggle" data-target="sk-entry-' . esc_attr( $id ) . '">Lihat detail ↓</a></td>';
			echo '<td>' . esc_html( $jalur ) . '</td>';
			echo '<td>' . esc_html( $hp ) . '</td>';
			echo '<td>' . esc_html( $asal ) . '</td>';
			echo '<td>' . esc_html( $waktu ) . '</td>';

			echo '<td>';
			echo '<form method="post" class="sk-status-form">';
			wp_nonce_field( 'sk_ppdb_admin', 'sk_ppdb_admin_nonce' );
			echo '<input type="hidden" name="sk_ppdb_admin" value="status" />';
			echo '<input type="hidden" name="sk_entry" value="' . esc_attr( $id ) . '" />';
			echo '<select name="sk_status">';
			foreach ( array( 'Baru', 'Dihubungi', 'Diterima', 'Ditolak' ) as $opt ) {
				echo '<option value="' . esc_attr( $opt ) . '" ' . selected( $status, $opt, false ) . '>' . esc_html( $opt ) . '</option>';
			}
			echo '</select> <button class="button button-small">Simpan</button>';
			echo '</form>';
			echo '</td>';

			echo '<td>';
			echo '<form method="post" onsubmit="return confirm(\'Hapus data pendaftar ini?\');">';
			wp_nonce_field( 'sk_ppdb_admin', 'sk_ppdb_admin_nonce' );
			echo '<input type="hidden" name="sk_ppdb_admin" value="delete" />';
			echo '<input type="hidden" name="sk_entry" value="' . esc_attr( $id ) . '" />';
			echo '<button class="button-link-delete button">🗑️</button>';
			echo '</form>';
			echo '</td>';
			echo '</tr>';

			// Detail row.
			echo '<tr class="sk-ppdb-detail" id="sk-entry-' . esc_attr( $id ) . '" style="display:none;"><td colspan="8"><div class="sk-ppdb-detail-grid">';
			foreach ( $fields as $key => $field ) {
				$value = sekolahku_core_meta( $id, 'ppdb_' . $key );
				if ( '' === trim( (string) $value ) ) {
					continue;
				}
				echo '<div><strong>' . esc_html( $field['label'] ) . '</strong><br>' . esc_html( $value ) . '</div>';
			}
			echo '</div></td></tr>';
		}
	} else {
		echo '<tr><td colspan="8">Belum ada pendaftar. Formulir PPDB dapat dipasang di halaman mana pun menggunakan kode <code>[sekolahku_ppdb]</code>.</td></tr>';
	}

	echo '</tbody></table>';

	// Pagination.
	if ( $query->max_num_pages > 1 ) {
		echo '<div class="tablenav"><div class="tablenav-pages">';
		echo paginate_links(
			array(
				'base'    => add_query_arg( 'paged', '%#%' ),
				'format'  => '',
				'current' => $paged,
				'total'   => $query->max_num_pages,
			)
		);
		echo '</div></div>';
	}

	wp_reset_postdata();
	echo '</div>';
}

/**
 * Output all registrations as CSV.
 */
function sekolahku_core_ppdb_export_csv() {
	$fields = sekolahku_core_ppdb_fields();
	$query  = new WP_Query(
		array(
			'post_type'      => 'ppdb_registrasi',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'meta_value_num',
			'meta_key'       => sekolahku_core_prefix() . 'ppdb_waktu',
			'order'          => 'DESC',
		)
	);

	$rows   = array();
	$header = array_merge( array( 'Status', 'Waktu Daftar' ), array_values( array_map( function ( $f ) {
		return $f['label'];
	}, $fields ) ) );
	$rows[] = $header;

	foreach ( $query->posts as $post ) {
		$id     = $post->ID;
		$row    = array(
			sekolahku_core_meta( $id, 'ppdb_status', 'Baru' ),
			sekolahku_core_meta( $id, 'ppdb_waktu' ),
		);
		foreach ( array_keys( $fields ) as $key ) {
			$row[] = sekolahku_core_meta( $id, 'ppdb_' . $key );
		}
		$rows[] = $row;
	}

	nocache_headers();
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=pendaftar-ppdb-' . date( 'Ymd-His' ) . '.csv' );

	$out = fopen( 'php://output', 'w' );
	fputs( $out, "\xEF\xBB\xBF" ); // UTF-8 BOM for Excel.
	foreach ( $rows as $row ) {
		fputcsv( $out, $row );
	}
	fclose( $out );
	exit;
}

/**
 * Enqueue small admin script for the detail toggle.
 */
function sekolahku_core_ppdb_admin_js() {
	$screen = get_current_screen();
	if ( ! $screen || 'sekolahku_page_sekolahku-ppdb' !== $screen->id ) {
		return;
	}
	?>
	<script>
	(function () {
		document.addEventListener('click', function (e) {
			var t = e.target.closest('.sk-ppdb-detail-toggle');
			if (!t) return;
			e.preventDefault();
			var row = document.getElementById(t.getAttribute('data-target'));
			if (row) row.style.display = row.style.display === 'none' ? '' : 'none';
		});
	})();
	</script>
	<?php
}
add_action( 'admin_footer', 'sekolahku_core_ppdb_admin_js' );
