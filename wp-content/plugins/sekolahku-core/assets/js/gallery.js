/**
 * Sekolahku Core — Gallery metabox image picker.
 */
(function ($) {
	'use strict';

	$(function () {
		var $input = $('#sk_foto_ids');
		var $grid = $('#sk_gallery_grid');

		if (!$input.length || !$grid.length) {
			return;
		}

		var frame;

		function currentIds() {
			return $input
				.val()
				.split(',')
				.map(function (id) { return parseInt(id, 10); })
				.filter(function (id) { return !isNaN(id) && id > 0; });
		}

		function addThumbs(ids) {
			ids.forEach(function (id) {
				if ($grid.find('.sk-gallery-thumb[data-id="' + id + '"]').length) {
					return;
				}
				var thumb = wp.media.attachment(id);
				thumb.fetch().done(function () {
					var url = thumb.get('sizes') && thumb.get('sizes').thumbnail
						? thumb.get('sizes').thumbnail.url
						: thumb.get('url');
					$grid.append(
						'<div class="sk-gallery-thumb" data-id="' + id + '">' +
						'<img src="' + url + '" alt="" />' +
						'<button type="button" class="sk-gallery-remove" aria-label="Hapus foto">&times;</button>' +
						'</div>'
					);
					sync();
				});
			});
			sync();
		}

		function sync() {
			var ids = [];
			$grid.find('.sk-gallery-thumb').each(function () {
				ids.push($(this).attr('data-id'));
			});
			$input.val(ids.join(','));
		}

		$('#sk_gallery_add').on('click', function (e) {
			e.preventDefault();

			if (frame) {
				frame.open();
				return;
			}

			frame = wp.media({
				title: 'Pilih Foto Galeri',
				button: { text: 'Gunakan Foto' },
				library: { type: 'image' },
				multiple: true
			});

			frame.on('select', function () {
				var selection = frame.state().get('selection');
				var ids = selection.map(function (attachment) {
					return attachment.id;
				});
				addThumbs(ids);
			});

			frame.open();
		});

		$grid.on('click', '.sk-gallery-remove', function (e) {
			e.preventDefault();
			$(this).closest('.sk-gallery-thumb').remove();
			sync();
		});
	});
})(jQuery);
