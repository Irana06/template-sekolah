<?php
/**
 * Front-end rendering helpers (single CPT pages).
 *
 * @package SekolahkuCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render an info box item.
 *
 * @param string $icon  Emoji icon.
 * @param string $label Small uppercase label.
 * @param string $value Value text.
 * @return string
 */
function sekolahku_core_info_item( $icon, $label, $value ) {
	if ( '' === trim( (string) $value ) ) {
		return '';
	}
	return '<div class="sk-info-box__item">'
		. '<span class="sk-info-box__icon">' . esc_html( $icon ) . '</span>'
		. '<span><span class="sk-info-box__label">' . esc_html( $label ) . '</span>'
		. '<span class="sk-info-box__value">' . esc_html( $value ) . '</span></span>'
		. '</div>';
}

/**
 * Prepend meta info card to single CPT content.
 *
 * @param string $content Post content.
 * @return string
 */
function sekolahku_core_single_content( $content ) {
	if ( ! is_singular( array( 'guru', 'prestasi', 'agenda', 'galeri' ) ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	$post_id = get_the_ID();
	$type    = get_post_type( $post_id );
	$box     = '';

	if ( 'guru' === $type ) {
		$jabatan = sekolahku_core_meta( $post_id, 'jabatan' );
		$mapel   = sekolahku_core_meta( $post_id, 'mapel' );
		$box     = '<div class="sk-info-box">'
			. sekolahku_core_info_item( '👔', 'Jabatan', $jabatan )
			. sekolahku_core_info_item( '📚', 'Bidang / Mata Pelajaran', $mapel )
			. '</div>';
	}

	if ( 'prestasi' === $type ) {
		$kategori = sekolahku_core_meta( $post_id, 'kategori' );
		$tingkat  = sekolahku_core_meta( $post_id, 'tingkat' );
		$tahun    = sekolahku_core_meta( $post_id, 'tahun' );
		$box      = '<div class="sk-info-box">'
			. sekolahku_core_info_item( '🏅', 'Kategori', $kategori )
			. sekolahku_core_info_item( '🏆', 'Tingkat', $tingkat )
			. sekolahku_core_info_item( '📅', 'Tahun', $tahun )
			. '</div>';
	}

	if ( 'agenda' === $type ) {
		$tanggal = sekolahku_core_meta( $post_id, 'tanggal' );
		$waktu   = sekolahku_core_meta( $post_id, 'waktu' );
		$lokasi  = sekolahku_core_meta( $post_id, 'lokasi' );
		$tgl_hr  = $tanggal ? date_i18n( 'l, d F Y', strtotime( $tanggal ) ) : '';
		$box     = '<div class="sk-info-box">'
			. sekolahku_core_info_item( '🗓️', 'Tanggal', $tgl_hr )
			. sekolahku_core_info_item( '🕘', 'Waktu', $waktu )
			. sekolahku_core_info_item( '📍', 'Lokasi', $lokasi )
			. '</div>';
	}

	if ( 'galeri' === $type ) {
		$ids = get_post_meta( $post_id, sekolahku_core_prefix() . 'foto_ids', true );
		if ( is_array( $ids ) && $ids ) {
			$box = sekolahku_core_render_gallery( $ids );
		}
	}

	if ( ! $box ) {
		return $content;
	}

	return $box . $content;
}
add_filter( 'the_content', 'sekolahku_core_single_content', 10 );

/**
 * Render a gallery grid from attachment IDs.
 *
 * @param array $ids Attachment IDs.
 * @return string
 */
function sekolahku_core_render_gallery( $ids ) {
	$out  = '<div class="sk-gallery">';
	foreach ( $ids as $id ) {
		$id    = absint( $id );
		$img   = wp_get_attachment_image( $id, 'large' );
		$full  = wp_get_attachment_image_url( $id, 'full' );
		$cap   = wp_get_attachment_caption( $id );
		if ( ! $img ) {
			continue;
		}
		$out .= '<figure class="sk-gallery__item" data-caption="' . esc_attr( $cap ) . '">'
			. str_replace( '<img ', '<img data-full="' . esc_url( $full ) . '" ', $img )
			. '</figure>';
	}
	$out .= '</div>';
	return $out;
}
