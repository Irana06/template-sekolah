<?php
/**
 * Ekstrakurikuler pattern.
 *
 * @package Sekolahku
 */

$head = sekolahku_section_head( 'Kembangkan Bakatmu', 'Ekstrakurikuler', 'Pilih wadah yang tepat untuk mengembangkan bakat dan minatmu.', '/ekstrakurikuler', 'Lihat Semua Ekskul' );

return '<!-- wp:group {"align":"full","className":"sk-ekskul","backgroundColor":"sky","layout":{"type":"constrained","contentSize":"1360px"}} -->
<div class="wp-block-group alignfull sk-ekskul has-sky-background-color has-background">
	' . $head . '
	<!-- wp:query {"query":{"perPage":6,"postType":"ekskul"},"className":"sk-query"} -->
	<div class="wp-block-query sk-query">
		<!-- wp:post-template -->
		<!-- wp:group {"className":"sk-card sk-card-avatar","layout":{"type":"constrained"}} -->
		<div class="wp-block-group sk-card sk-card-avatar">
			<!-- wp:post-featured-image {"isLink":true,"className":"sk-card-media sk-card-media-square"} /-->
			<!-- wp:group {"className":"sk-card-body sk-card-body-center","layout":{"type":"constrained"}} -->
			<div class="wp-block-group sk-card-body sk-card-body-center">
				<!-- wp:post-title {"isLink":true,"level":3,"fontSize":"large"} /-->
				<!-- wp:post-excerpt {"moreText":"","excerptLength":12} /-->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
		<!-- /wp:post-template -->
	</div>
	<!-- /wp:query -->
</div>
<!-- /wp:group -->';
