<?php
/**
 * Kontak pattern.
 *
 * @package Sekolahku
 */

return '<!-- wp:group {"align":"full","className":"sk-kontak","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull sk-kontak">
	<!-- wp:group {"className":"sk-section-head sk-section-head-center","layout":{"type":"constrained"}} -->
	<div class="wp-block-group sk-section-head sk-section-head-center">
		<!-- wp:paragraph {"className":"sk-kicker","fontSize":"small","align":"center"} -->
		<p class="sk-kicker has-small-font-size has-text-align-center">Hubungi Kami</p>
		<!-- /wp:paragraph -->
		<!-- wp:heading {"level":2,"className":"sk-section-title","textAlign":"center"} -->
		<h2 class="wp-block-heading sk-section-title has-text-align-center">Informasi Kontak &amp; Lokasi</h2>
		<!-- /wp:heading -->
	</div>
	<!-- /wp:group -->

	<!-- wp:columns {"className":"sk-kontak-cards"} -->
	<div class="wp-block-columns sk-kontak-cards">
		<!-- wp:column {"className":"sk-kontak-card"} -->
		<div class="wp-block-column sk-kontak-card">
			<!-- wp:group {"className":"sk-kontak-item","border":{"color":"var:preset|color|sky","width":"1px","style":"solid"},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group sk-kontak-item has-border-color" style="border-style:solid;border-width:1px;border-color:var(--wp--preset--color--sky)">
				<!-- wp:paragraph {"className":"sk-kontak-icon"} --><p class="sk-kontak-icon">📍</p><!-- /wp:paragraph -->
				<!-- wp:heading {"level":3,"fontSize":"large"} --><h3 class="wp-block-heading has-large-font-size">Alamat</h3><!-- /wp:heading -->
				<!-- wp:paragraph --><p>Jl. Pendidikan No. 1, Kecamatan Kota, Kota Anda 12345</p><!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column {"className":"sk-kontak-card"} -->
		<div class="wp-block-column sk-kontak-card">
			<!-- wp:group {"className":"sk-kontak-item","border":{"color":"var:preset|color|sky","width":"1px","style":"solid"},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group sk-kontak-item has-border-color" style="border-style:solid;border-width:1px;border-color:var(--wp--preset--color--sky)">
				<!-- wp:paragraph {"className":"sk-kontak-icon"} --><p class="sk-kontak-icon">📞</p><!-- /wp:paragraph -->
				<!-- wp:heading {"level":3,"fontSize":"large"} --><h3 class="wp-block-heading has-large-font-size">Telepon / WhatsApp</h3><!-- /wp:heading -->
				<!-- wp:paragraph --><p>(021) 1234-5678<br>0812-3456-7890</p><!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column {"className":"sk-kontak-card"} -->
		<div class="wp-block-column sk-kontak-card">
			<!-- wp:group {"className":"sk-kontak-item","border":{"color":"var:preset|color|sky","width":"1px","style":"solid"},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group sk-kontak-item has-border-color" style="border-style:solid;border-width:1px;border-color:var(--wp--preset--color--sky)">
				<!-- wp:paragraph {"className":"sk-kontak-icon"} --><p class="sk-kontak-icon">✉️</p><!-- /wp:paragraph -->
				<!-- wp:heading {"level":3,"fontSize":"large"} --><h3 class="wp-block-heading has-large-font-size">Email &amp; Jam Kerja</h3><!-- /wp:heading -->
				<!-- wp:paragraph --><p>info@sekolahku.sch.id<br>Senin – Jumat, 07.00 – 16.00 WIB</p><!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<!-- wp:html -->
	<iframe class="sk-map" title="Peta Lokasi Sekolah" src="https://www.openstreetmap.org/export/embed.html?bbox=106.78%2C-6.26%2C106.84%2C-6.21&amp;layer=mapnik&amp;marker=-6.23%2C106.81" loading="lazy"></iframe>
	<!-- /wp:html -->
</div>
<!-- /wp:group -->';
