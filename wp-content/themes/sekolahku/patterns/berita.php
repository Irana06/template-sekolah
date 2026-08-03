<?php
/**
 * Latest news pattern.
 *
 * @package Sekolahku
 */

$head = sekolahku_section_head( 'Kabar Terkini', 'Berita & Informasi Terbaru', 'Ikuti perkembangan kegiatan, prestasi, dan pengumuman terbaru dari sekolah kami.', '/berita', 'Lihat Semua Berita' );

return '<!-- wp:group {"align":"full","className":"sk-news","layout":{"type":"constrained","contentSize":"1360px"}} -->
<div class="wp-block-group alignfull sk-news">
	' . $head . '
	<!-- wp:query {"query":{"perPage":3,"postType":"post"},"className":"sk-query"} -->
	<div class="wp-block-query sk-query">
		<!-- wp:post-template -->
		<!-- wp:group {"className":"sk-card","layout":{"type":"constrained"}} -->
		<div class="wp-block-group sk-card">
			<!-- wp:post-featured-image {"isLink":true,"className":"sk-card-media"} /-->
			<!-- wp:group {"className":"sk-card-body","layout":{"type":"constrained"}} -->
			<div class="wp-block-group sk-card-body">
				<!-- wp:post-date {"fontSize":"small"} /-->
				<!-- wp:post-title {"isLink":true,"level":3,"fontSize":"large"} /-->
				<!-- wp:post-excerpt {"moreText":"","excerptLength":18} /-->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
		<!-- /wp:post-template -->
	</div>
	<!-- /wp:query -->
</div>
<!-- /wp:group -->';
