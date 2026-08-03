<?php
/**
 * Visi & Misi pattern.
 *
 * @package Sekolahku
 */

return '<!-- wp:group {"align":"full","className":"sk-visi-misi","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull sk-visi-misi">
	<!-- wp:columns {"className":"sk-vm-cols"} -->
	<div class="wp-block-columns sk-vm-cols">
		<!-- wp:column {"className":"sk-vm-visi"} -->
		<div class="wp-block-column sk-vm-visi">
			<!-- wp:group {"className":"sk-vm-card","backgroundColor":"navy","textColor":"white","layout":{"type":"constrained"}} -->
			<div class="wp-block-group sk-vm-card has-white-color has-text-color has-navy-background-color has-background">
				<!-- wp:paragraph {"className":"sk-vm-icon"} --><p class="sk-vm-icon">🎯</p><!-- /wp:paragraph -->
				<!-- wp:heading {"level":3,"fontSize":"x-large"} -->
				<h3 class="wp-block-heading has-x-large-font-size">Visi</h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph -->
				<p>"Terwujudnya generasi yang beriman, berprestasi, berkarakter, dan berwawasan lingkungan."</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column {"className":"sk-vm-misi"} -->
		<div class="wp-block-column sk-vm-misi">
			<!-- wp:group {"className":"sk-vm-card","border":{"color":"var:preset|color|sky","width":"1px","style":"solid"},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group sk-vm-card has-border-color" style="border-style:solid;border-width:1px;border-color:var(--wp--preset--color--sky)">
				<!-- wp:paragraph {"className":"sk-vm-icon"} --><p class="sk-vm-icon">🚀</p><!-- /wp:paragraph -->
				<!-- wp:heading {"level":3,"fontSize":"x-large"} -->
				<h3 class="wp-block-heading has-x-large-font-size">Misi</h3>
				<!-- /wp:heading -->
				<!-- wp:list {"className":"sk-vm-list"} -->
				<ul class="sk-vm-list">
					<li>Menyelenggarakan pembelajaran yang aktif, kreatif, dan menyenangkan.</li>
					<li>Menumbuhkan karakter religius, disiplin, dan gotong royong.</li>
					<li>Mengembangkan bakat dan minat melalui program unggulan.</li>
					<li>Membangun kerjasama dengan orang tua dan masyarakat.</li>
					<li>Mewujudkan lingkungan sekolah yang bersih, hijau, dan aman.</li>
				</ul>
				<!-- /wp:list -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->';
