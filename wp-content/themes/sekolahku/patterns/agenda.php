<?php
/**
 * Agenda pattern.
 *
 * @package Sekolahku
 */

$head = sekolahku_section_head( 'Yang Akan Datang', 'Agenda Kegiatan', 'Catat jadwal penting sekolah agar tidak terlewat.', '/agenda', 'Lihat Semua Agenda' );

return '<!-- wp:group {"align":"full","className":"sk-agenda","backgroundColor":"sky","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull sk-agenda has-sky-background-color has-background">
	' . $head . '
	<!-- wp:query {"query":{"perPage":3,"postType":"agenda","order":"asc"},"className":"sk-query sk-query-agenda"} -->
	<div class="wp-block-query sk-query sk-query-agenda">
		<!-- wp:post-template -->
		<!-- wp:group {"className":"sk-card sk-card-horizontal","layout":{"type":"constrained"}} -->
		<div class="wp-block-group sk-card sk-card-horizontal">
			<!-- wp:post-date {"displayType":"custom","format":"d M","className":"sk-card-date"} /-->
			<!-- wp:group {"className":"sk-card-body","layout":{"type":"constrained"}} -->
			<div class="wp-block-group sk-card-body">
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
