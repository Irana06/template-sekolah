<?php
/**
 * Principal welcome pattern.
 *
 * @package Sekolahku
 */

return '<!-- wp:group {"align":"full","className":"sk-sambutan","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull sk-sambutan">
	<!-- wp:columns {"verticalAlignment":"center","className":"sk-sambutan-cols"} -->
	<div class="wp-block-columns are-vertically-aligned-center sk-sambutan-cols">
		<!-- wp:column {"className":"sk-sambutan-media"} -->
		<div class="wp-block-column sk-sambutan-media">
			<!-- wp:image {"sizeSlug":"large","className":"sk-sambutan-img"} -->
			<figure class="wp-block-image size-large sk-sambutan-img"><img src="' . sekolahku_demo_asset( 'kegiatan-1.svg' ) . '" alt="Foto kepala sekolah"/></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column {"className":"sk-sambutan-text"} -->
		<div class="wp-block-column sk-sambutan-text">
			<!-- wp:paragraph {"className":"sk-kicker","fontSize":"small"} -->
			<p class="sk-kicker has-small-font-size">Sambutan Kepala Sekolah</p>
			<!-- /wp:paragraph -->
			<!-- wp:heading {"level":2,"className":"sk-section-title"} -->
			<h2 class="wp-block-heading sk-section-title">"Pendidikan adalah jembatan menuju masa depan yang lebih baik"</h2>
			<!-- /wp:heading -->
			<!-- wp:paragraph -->
			<p>Assalamualaikum warahmatullahi wabarakatuh. Selamat datang di website [Nama Sekolah]. Website ini kami hadirkan sebagai jendela informasi bagi Bapak/Ibu orang tua, siswa, dan masyarakat umum untuk mengenal sekolah kami lebih dekat.</p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph -->
			<p>Kami percaya setiap anak memiliki potensi yang luar biasa. Tugas kami adalah menggali dan mengembangkannya dengan penuh kasih, disiplin, dan keteladanan. Mari bersama-sama mendampingi putra-putri kita meraih mimpi mereka.</p>
			<!-- /wp:paragraph -->
			<!-- wp:group {"className":"sk-sign","layout":{"type":"constrained"}} -->
			<div class="wp-block-group sk-sign">
				<!-- wp:paragraph {"className":"sk-sign-name"} -->
				<p class="sk-sign-name"><strong>Drs. Ahmad Fauzi, M.Pd.</strong></p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph {"className":"sk-sign-role","fontSize":"small"} -->
				<p class="sk-sign-role has-small-font-size">Kepala Sekolah</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->';
