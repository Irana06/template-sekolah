<?php
/**
 * Admin "Panduan" page — friendly guide for non-technical admins.
 *
 * @package SekolahkuCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render the guide page.
 */
function sekolahku_core_panduan_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap sk-panduan">
		<h1 class="wp-heading-inline">📖 Panduan Mengelola Website</h1>
		<p>Panduan singkat untuk mengelola website sekolah. Semua menu sudah dibuat <strong>sederhana dan mudah</strong> — tidak perlu paham kode sama sekali.</p>

		<div class="sk-panduan-grid">

			<div class="sk-panduan-card">
				<h2>1️⃣ Mengganti Nama &amp; Logo Sekolah</h2>
				<ol>
					<li>Buka menu <strong>Pengaturan → Umum</strong>.</li>
					<li>Isi <em>Judul Situs</em> dengan nama sekolah Anda.</li>
					<li>Isi <em>Tagline</em> dengan motto sekolah (contoh: "Membangun Generasi Unggul").</li>
					<li>Logo: buka <strong>Tampilan → Kustomisasi</strong>? Tidak perlu! Gunakan <strong>Editor Situs</strong> (lihat langkah 8).</li>
				</ol>
			</div>

			<div class="sk-panduan-card">
				<h2>2️⃣ Mengedit Isi Halaman (Teks &amp; Gambar)</h2>
				<ol>
					<li>Buka <strong>Halaman</strong> lalu pilih halaman yang ingin diubah (misal: Beranda).</li>
					<li>Klik teks yang ingin diganti, lalu ketik langsung seperti di Word.</li>
					<li>Gambar: klik gambar → klik ikon <em>Ganti</em> → pilih/unggah foto baru.</li>
					<li>Klik <strong>Perbarui</strong> untuk menyimpan.</li>
				</ol>
			</div>

			<div class="sk-panduan-card">
				<h2>3️⃣ Menambah Berita / Informasi</h2>
				<ol>
					<li>Buka <strong>Konten Sekolah → Berita</strong> → <strong>Tambah Baru</strong>.</li>
					<li>Tulis judul berita dan isi beritanya.</li>
					<li>Foto: gunakan panel <em>Gambar Unggulan</em> di sebelah kanan.</li>
					<li>Klik <strong>Terbitkan</strong>. Berita langsung tampil di halaman Berita &amp; Beranda.</li>
				</ol>
			</div>

			<div class="sk-panduan-card">
				<h2>4️⃣ Menambah Guru &amp; Staff</h2>
				<ol>
					<li>Buka <strong>Konten Sekolah → Guru &amp; Staff</strong> → <strong>Tambah Guru</strong>.</li>
					<li>Isi nama, foto (Gambar Unggulan), jabatan, dan keahlian.</li>
					<li>Klik <strong>Terbitkan</strong>. Profil otomatis tampil di halaman Guru &amp; Staff.</li>
				</ol>
			</div>

			<div class="sk-panduan-card">
				<h2>5️⃣ Menambah Prestasi</h2>
				<ol>
					<li>Buka <strong>Konten Sekolah → Prestasi</strong> → <strong>Tambah Prestasi</strong>.</li>
					<li>Isi judul, kategori (Akademik/Non-Akademik), tingkat lomba, dan tahun.</li>
					<li>Tambahkan gambar/foto piagam atau piala (opsional, Gambar Unggulan).</li>
					<li>Klik <strong>Terbitkan</strong>.</li>
				</ol>
			</div>

			<div class="sk-panduan-card">
				<h2>6️⃣ Menambah Agenda &amp; Ekstrakurikuler</h2>
				<ol>
					<li><strong>Agenda:</strong> Konten Sekolah → Agenda → Tambah Agenda. Isi tanggal, waktu, lokasi, lalu Terbitkan.</li>
					<li><strong>Ekskul:</strong> Konten Sekolah → Ekstrakurikuler → Tambah Ekskul. Unggah ikon/logo di Gambar Unggulan (disarankan kotak 1:1), tulis deskripsi, lalu Terbitkan.</li>
				</ol>
			</div>

			<div class="sk-panduan-card">
				<h2>7️⃣ Menambah Album Galeri</h2>
				<ol>
					<li>Buka <strong>Konten Sekolah → Galeri</strong> → <strong>Tambah Album</strong>.</li>
					<li>Tulis judul album (contoh: "Kegiatan MPLS 2026").</li>
					<li>Pada bagian <em>Foto Galeri</em>, klik <strong>Tambah Foto</strong> → pilih/unggah foto.</li>
					<li>Foto pertama otomatis menjadi sampul. Klik <strong>Terbitkan</strong>.</li>
				</ol>
			</div>

			<div class="sk-panduan-card">
				<h2>8️⃣ Mengubah Warna, Menu, &amp; Header/Footer</h2>
				<ol>
					<li>Buka <strong>Tampilan → Editor</strong> (Editor Situs).</li>
					<li><strong>Gaya:</strong> klik ikon lingkaran warna (kanan atas) untuk mengubah warna tombol, teks, dan bagian lain.</li>
					<li><strong>Menu:</strong> klik bagian menu di header untuk menambah/mengatur menu.</li>
					<li><strong>Footer &amp; Header:</strong> klik langsung bagian tersebut lalu edit, atau gunakan pola (patterns) yang tersedia.</li>
					<li>Klik <strong>Simpan</strong> di pojok kanan atas.</li>
				</ol>
			</div>

			<div class="sk-panduan-card">
				<h2>9️⃣ Mengelola Pendaftar PPDB</h2>
				<ol>
					<li>Buka <strong>Konten Sekolah → Pendaftar PPDB</strong>.</li>
					<li>Klik <strong>Lihat detail</strong> untuk melihat isi formulir pendaftar.</li>
					<li>Ubah status: <em>Baru → Dihubungi → Diterima/Ditolak</em> lalu klik <strong>Simpan</strong>.</li>
					<li>Unduh semua data ke Excel/CSV dengan tombol <strong>Export CSV</strong>.</li>
				</ol>
			</div>

			<div class="sk-panduan-card">
				<h2>🔟 Tips Penting</h2>
				<ul>
					<li>Foto gedung/kegiatan lebih baik diunggah langsung daripada menyalin dari website lain (lebih cepat &amp; legal).</li>
					<li>Ukuran foto disarankan maksimal 1–2 MB per file.</li>
					<li>Pastikan mengklik <strong>Perbarui</strong>/<strong>Terbitkan</strong> setelah mengubah konten.</li>
					<li>Minta pendampingan jika ingin menambah halaman baru — atau cukup gunakan <em>Pola (patterns) Sekolahku</em> di editor.</li>
					<li><strong>Catatan teknis:</strong> tautan menu &amp; footer mengasumsikan website berada di domain utama (misal <code>nama-sekolah.sch.id</code>). Jika website dipasang di subfolder, minta pengembang menyesuaikan tautannya.</li>
				</ul>
			</div>

		</div>

		<div class="sk-panduan-help">
			<p><strong>Butuh bantuan lebih lanjut?</strong> Hubungi pengembang/administrator website Anda.</p>
		</div>
	</div>
	<?php
}
