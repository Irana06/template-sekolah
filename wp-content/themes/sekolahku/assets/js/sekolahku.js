/**
 * Sekolahku — theme scripts.
 * Sticky header state, scroll reveal, stat counters, gallery lightbox, back-to-top.
 */
(function () {
	'use strict';

	var d = document;

	/* ------------------------------------------------------------
	 * Sticky header shadow + back-to-top button
	 * ---------------------------------------------------------- */
	function initHeader() {
		var header = d.querySelector('.sk-header-wrap');
		var toTop = d.createElement('button');
		toTop.className = 'sk-to-top';
		toTop.setAttribute('aria-label', 'Kembali ke atas');
		toTop.innerHTML = '↑';
		d.body.appendChild(toTop);

		function onScroll() {
			var y = window.scrollY || 0;
			if (header) {
				header.classList.toggle('is-scrolled', y > 10);
			}
			toTop.classList.toggle('is-visible', y > 500);
		}

		window.addEventListener('scroll', onScroll, { passive: true });
		onScroll();

		toTop.addEventListener('click', function () {
			window.scrollTo({ top: 0, behavior: 'smooth' });
		});
	}

	/* ------------------------------------------------------------
	 * Scroll reveal
	 * ---------------------------------------------------------- */
	function initReveal() {
		var els = d.querySelectorAll(
			'.sk-news > .wp-block-group > *,' +
			'.sk-news .sk-card,' +
			'.sk-prestasi .sk-card,' +
			'.sk-agenda .sk-card,' +
			'.sk-ekskul .sk-card,' +
			'.sk-testi,' +
			'.sk-info-card,' +
			'.sk-stat,' +
			'.sk-kontak-item,' +
			'.sk-vm-card,' +
			'.sk-sambutan-cols > *'
		);

		if (!('IntersectionObserver' in window)) {
			els.forEach(function (el) { el.classList.add('is-visible'); });
			return;
		}

		var io = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						entry.target.classList.add('is-visible');
						io.unobserve(entry.target);
					}
				});
			},
			{ threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
		);

		els.forEach(function (el) {
			el.classList.add('sk-reveal');
			io.observe(el);
		});
	}

	/* ------------------------------------------------------------
	 * Animated counters (stats)
	 * ---------------------------------------------------------- */
	function initCounters() {
		var nums = d.querySelectorAll('.sk-stat-num[data-target]');
		if (!nums.length) return;

		var prefersReduced = window.matchMedia &&
			window.matchMedia('(prefers-reduced-motion: reduce)').matches;

		function animate(el) {
			var target = parseFloat(el.getAttribute('data-target')) || 0;
			if (prefersReduced) {
				el.textContent = target;
				return;
			}
			var duration = 1400;
			var start = null;
			var suffix = el.textContent.replace(/[0-9,.]/g, '');

			function step(ts) {
				if (!start) start = ts;
				var progress = Math.min((ts - start) / duration, 1);
				var eased = 1 - Math.pow(1 - progress, 3);
				el.textContent = Math.round(target * eased) + suffix;
				if (progress < 1) {
					requestAnimationFrame(step);
				}
			}
			requestAnimationFrame(step);
		}

		if (!('IntersectionObserver' in window)) {
			nums.forEach(animate);
			return;
		}

		var io = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						animate(entry.target);
						io.unobserve(entry.target);
					}
				});
			},
			{ threshold: 0.4 }
		);

		nums.forEach(function (el) { io.observe(el); });
	}

	/* ------------------------------------------------------------
	 * Gallery lightbox
	 * ---------------------------------------------------------- */
	function initLightbox() {
		var gallery = d.querySelector('.sk-gallery');
		if (!gallery) return;

		var lb = d.createElement('div');
		lb.className = 'sk-lightbox';
		lb.setAttribute('role', 'dialog');
		lb.setAttribute('aria-modal', 'true');
		lb.innerHTML =
			'<button class="sk-lightbox__close" aria-label="Tutup">×</button>' +
			'<img alt="" />' +
			'<div class="sk-lightbox__caption"></div>';
		d.body.appendChild(lb);

		var lbImg = lb.querySelector('img');
		var lbCap = lb.querySelector('.sk-lightbox__caption');

		gallery.addEventListener('click', function (e) {
			var item = e.target.closest('.sk-gallery__item');
			if (!item) return;
			var img = item.querySelector('img');
			if (!img) return;
			lbImg.src = img.getAttribute('data-full') || img.src;
			lbImg.alt = img.alt || '';
			lbCap.textContent = item.getAttribute('data-caption') || '';
			lb.classList.add('is-open');
			d.body.style.overflow = 'hidden';
		});

		function close() {
			lb.classList.remove('is-open');
			d.body.style.overflow = '';
		}

		lb.addEventListener('click', function (e) {
			if (e.target === lb || e.target.classList.contains('sk-lightbox__close')) {
				close();
			}
		});

		d.addEventListener('keydown', function (e) {
			if (e.key === 'Escape') close();
		});
	}

	/* ------------------------------------------------------------
	 * Boot
	 * ---------------------------------------------------------- */
	function boot() {
		initHeader();
		initReveal();
		initCounters();
		initLightbox();
	}

	if (d.readyState === 'loading') {
		d.addEventListener('DOMContentLoaded', boot);
	} else {
		boot();
	}
})();
