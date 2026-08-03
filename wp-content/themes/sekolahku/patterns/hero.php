<?php
/**
 * Hero banner pattern.
 *
 * @package Sekolahku
 */

return '<!-- wp:group {"align":"full","className":"sk-hero","backgroundColor":"navy","textColor":"white","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull sk-hero has-white-color has-text-color has-navy-background-color has-background">
	<!-- wp:group {"layout":{"type":"constrained"}} -->
	<div class="wp-block-group">
		<!-- wp:columns {"verticalAlignment":"center","className":"sk-hero-cols"} -->
		<div class="wp-block-columns are-vertically-aligned-center sk-hero-cols">
			<!-- wp:column {"verticalAlignment":"center","className":"sk-hero-text"} -->
			<div class="wp-block-column is-vertically-aligned-center sk-hero-text">
				<!-- wp:paragraph {"className":"sk-badge","fontSize":"small"} -->
				<p class="sk-badge has-small-font-size">🎓 PPDB Tahun Ajaran Baru Telah Dibuka</p>
				<!-- /wp:paragraph -->
				<!-- wp:heading {"level":1,"className":"sk-hero-title","fontSize":"huge"} -->
				<h1 class="wp-block-heading sk-hero-title has-huge-font-size">Membangun Generasi Unggul &amp; Berkarakter</h1>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"className":"sk-hero-sub","fontSize":"large"} -->
				<p class="sk-hero-sub has-large-font-size">Selamat datang di [Nama Sekolah] — tempat belajar yang nyaman, guru yang inspiratif, dan lingkungan yang mendukung tumbuh kembang putra-putri Anda.</p>
				<!-- /wp:paragraph -->
				<!-- wp:buttons {"className":"sk-hero-btns"} -->
				<div class="wp-block-buttons sk-hero-btns">
					<!-- wp:button {"className":"is-style-sk-gold"} -->
					<div class="wp-block-button is-style-sk-gold"><a class="wp-block-button__link" href="/ppdb">Daftar PPDB Sekarang →</a></div>
					<!-- /wp:button -->
					<!-- wp:button {"className":"is-style-sk-ghost"} -->
					<div class="wp-block-button is-style-sk-ghost"><a class="wp-block-button__link" href="/profil">Kenali Kami</a></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->
			</div>
			<!-- /wp:column -->

			<!-- wp:column {"verticalAlignment":"center","className":"sk-hero-media"} -->
			<div class="wp-block-column is-vertically-aligned-center sk-hero-media">
				<!-- wp:image {"sizeSlug":"large","className":"sk-hero-img"} -->
				<figure class="wp-block-image size-large sk-hero-img"><img src="' . sekolahku_demo_asset( 'hero.svg' ) . '" alt="Ilustrasi gedung sekolah"/></figure>
				<!-- /wp:image -->
				<!-- wp:group {"className":"sk-float sk-float-1","layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
				<div class="wp-block-group sk-float sk-float-1">
					<!-- wp:paragraph {"className":"sk-float-icon"} --><p class="sk-float-icon">🏆</p><!-- /wp:paragraph -->
					<!-- wp:paragraph {"className":"sk-float-text","fontSize":"small"} -->
					<p class="sk-float-text has-small-font-size"><strong>100+ Prestasi</strong><br>akademik &amp; non-akademik</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
				<!-- wp:group {"className":"sk-float sk-float-2","layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
				<div class="wp-block-group sk-float sk-float-2">
					<!-- wp:paragraph {"className":"sk-float-icon"} --><p class="sk-float-icon">⭐</p><!-- /wp:paragraph -->
					<!-- wp:paragraph {"className":"sk-float-text","fontSize":"small"} -->
					<p class="sk-float-text has-small-font-size"><strong>Akreditasi A</strong><br>standar nasional</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:column -->
		</div>
		<!-- /wp:columns -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->';
