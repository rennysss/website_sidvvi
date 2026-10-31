/* SIDVVI · JS del sitio público (jQuery 3 + Bootstrap 4) */
(function ($) {
	'use strict';

	// ---------- CSRF en todas las peticiones Ajax ----------
	var csrf = { name: $('meta[name="csrf-name"]').attr('content'), hash: $('meta[name="csrf-hash"]').attr('content') };
	function updateCsrf(c) {
		if (!c) return;
		csrf = c;
		$('input[name="' + c.name + '"]').val(c.hash);
	}

	// ---------- Encabezado fijo + volver arriba ----------
	var $header = $('#siteHeader'), $top = $('.back-to-top');
	$(window).on('scroll', function () {
		var y = $(this).scrollTop();
		$header.toggleClass('is-scrolled', y > 40);
		$top.toggleClass('show', y > 500);
	}).trigger('scroll');
	$top.on('click', function (e) { e.preventDefault(); $('html, body').animate({ scrollTop: 0 }, 400); });

	// En escritorio el menú desplegable abre con hover; el clic navega a la sección
	$('.navbar .dropdown > .dropdown-toggle').on('click', function (e) {
		if (window.innerWidth >= 992) { window.location = this.href; }
	});

	// ---------- Animaciones al hacer scroll + contadores ----------
	if ('IntersectionObserver' in window) {
		$('.section .card, .section-title, .stat-item, .doc-item, .quick-links li').addClass('reveal');
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (en) {
				if (!en.isIntersecting) return;
				$(en.target).addClass('is-visible');
				io.unobserve(en.target);
			});
		}, { threshold: 0.12 });
		$('.reveal').each(function () { io.observe(this); });

		var co = new IntersectionObserver(function (entries) {
			entries.forEach(function (en) {
				if (!en.isIntersecting) return;
				var $el = $(en.target), end = parseInt($el.data('count'), 10) || 0;
				$({ n: 0 }).animate({ n: end }, { duration: 1400, step: function (v) { $el.text(Math.ceil(v)); } });
				co.unobserve(en.target);
			});
		}, { threshold: 0.5 });
		$('[data-count]').each(function () { co.observe(this); });
	}

	// ---------- Directorio: filtros Ajax ----------
	var $dir = $('#directoryFilters'), $res = $('#directoryResults'), timer;
	function loadDirectory(page) {
		var params = $dir.serializeArray().filter(function (p) { return p.value !== ''; });
		if (page && page > 1) params.push({ name: 'page', value: page });
		var qs = $.param(params);
		$res.addClass('loading');
		$.getJSON($dir.data('ajax') + (qs ? '?' + qs : ''))
			.done(function (r) {
				$res.html(r.data.html);
				history.replaceState(null, '', $dir.attr('action') + (qs ? '?' + qs : ''));
			})
			.always(function () { $res.removeClass('loading'); });
	}
	if ($dir.length) {
		$dir.on('change', 'select', function () { loadDirectory(1); });
		$dir.on('input', 'input[type=search]', function () { clearTimeout(timer); timer = setTimeout(function () { loadDirectory(1); }, 350); });
		$dir.on('submit', function (e) { e.preventDefault(); loadDirectory(1); });
		$res.on('click', '.page-link', function (e) {
			e.preventDefault();
			loadDirectory($(this).data('page'));
			$('html, body').animate({ scrollTop: $dir.offset().top - 110 }, 300);
		});
	}

	// ---------- Formularios Ajax (contacto, membresía) ----------
	function sendForm($f) {
		var $btn = $f.find('[type=submit]'), $alert = $f.find('.form-alert');
		$f.find('.is-invalid').removeClass('is-invalid');
		$alert.empty();
		$btn.prop('disabled', true).data('txt', $btn.html()).html('<span class="spinner-border spinner-border-sm mr-2"></span>Enviando...');

		$.ajax({ url: $f.attr('action'), method: 'POST', data: $f.serialize(), dataType: 'json' })
			.done(function (r) {
				updateCsrf(r.csrf);
				$alert.html('<div class="alert alert-success"><i class="fas fa-check-circle mr-1"></i> ' + r.message + '</div>');
				$f[0].reset();
			})
			.fail(function (xhr) {
				var r = xhr.responseJSON || {};
				updateCsrf(r.csrf);
				if (r.data && r.data.errors) {
					$.each(r.data.errors, function (field, msg) {
						var $in = $f.find('[name="' + field + '"]').addClass('is-invalid');
						$in.closest('.form-group').find('.invalid-feedback').text(msg);
					});
					$f.find('.is-invalid').first().trigger('focus');
				}
				$alert.html('<div class="alert alert-danger">' + (r.message || 'Ocurrió un error, intenta de nuevo.') + '</div>');
			})
			.always(function () { $btn.prop('disabled', false).html($btn.data('txt')); });
	}
	$('.ajax-form').on('submit', function (e) {
		e.preventDefault();
		var $f = $(this);
		var action = $f.data('recaptcha');
		if (window.SIDVVI.recaptcha && action && window.grecaptcha) {
			grecaptcha.ready(function () {
				grecaptcha.execute(window.SIDVVI.recaptcha, { action: action }).then(function (token) {
					var $h = $f.find('input[name="recaptcha_token"]');
					if (!$h.length) $h = $('<input type="hidden" name="recaptcha_token">').appendTo($f);
					$h.val(token);
					sendForm($f);
				});
			});
			return;
		}
		sendForm($f);
	});

	// ---------- PWA ----------
	// El service worker solo funciona en HTTPS (o localhost)
	if ('serviceWorker' in navigator && (location.protocol === 'https:' || location.hostname === 'localhost')) {
		window.addEventListener('load', function () {
			navigator.serviceWorker.register(window.SIDVVI.baseUrl + 'service-worker.js').catch(function () {});
		});
	}
})(jQuery);
