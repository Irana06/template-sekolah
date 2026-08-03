<?php
/**
 * Prestasi pattern.
 *
 * @package Sekolahku
 */

$head = sekolahku_section_head( 'Bangga Sekolahku', 'Prestasi Terbaru', 'Raihan membanggakan siswa dan sekolah di berbagai ajang.', '/prestasi', 'Lihat Semua Prestasi' );

return '<!-- wp:group {"align":"full","className":"sk-prestasi","layout":{"type":"constrained","contentSize":"1360px"}} -->
<div class="wp-block-group alignfull sk-prestasi">
	' . $head . '
	<!-- wp:query {"query":{"perPage":4,"postType":"prestasi"},"className":"sk-query"} -->
	<div class="wp-block-query sk-query">
		<!-- wp:post-template -->
		<!-- wp:group {"className":"sk-card","layout":{"type":"constrained"}} -->
		<div class="wp-block-group sk-card">
			<!-- wp:post-featured-image {"isLink":true,"className":"sk-card-media"} /-->
			<!-- wp:group {"className":"sk-card-body","layout":{"type":"constrained"}} -->
			<div class="wp-block-group sk-card-body">
				<!-- wp:post-excerpt {"moreText":"","excerptLength":8,"className":"sk-card-badge"} /-->
				<!-- wp:post-title {"isLink":true,"level":3,"fontSize":"large"} /-->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
		<!-- /wp:post-template -->
	</div>
	<!-- /wp:query -->
</div>
<!-- /wp:group -->';
