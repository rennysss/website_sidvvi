/* SIDVVI · JS del panel */
(function ($) {
	'use strict';

	// Menú lateral en móvil
	$('[data-toggle-sidebar]').on('click', function () { $('body').toggleClass('sidebar-open'); });

	// Confirmación antes de eliminar
	$('form[data-confirm]').on('submit', function (e) {
		if (!window.confirm($(this).data('confirm'))) e.preventDefault();
	});

	// Nombre del archivo en inputs de archivo
	$('.custom-file-input').on('change', function () {
		var name = this.files && this.files[0] ? this.files[0].name : '';
		$(this).next('.custom-file-label').text(name || 'Seleccionar archivo...');
	});

	// Vista previa de íconos Font Awesome
	$('.js-icon').on('input', function () {
		$(this).closest('.input-group').find('.icon-preview i').attr('class', this.value || 'fas fa-question');
	});

	// Editor de texto enriquecido
	if ($.fn.summernote) {
		$('.js-editor').summernote({
			lang: 'es-ES', height: 280,
			toolbar: [
				['style', ['style']], ['font', ['bold', 'italic', 'underline', 'clear']],
				['para', ['ul', 'ol', 'paragraph']], ['insert', ['link', 'picture', 'video', 'table']],
				['view', ['codeview']]
			],
			styleTags: ['p', 'h3', 'h4', 'h5', 'blockquote']
		});
	}

	// Gráficas del panel
	if (window.Chart) {
		Chart.defaults.font.family = 'Inter, system-ui, sans-serif';
		var $m = $('#chartMessages');
		if ($m.length) {
			new Chart($m[0], { type: 'bar', data: { labels: $m.data('labels'), datasets: [{ label: 'Mensajes', data: $m.data('values'), backgroundColor: '#23477A', borderRadius: 6 }] },
				options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } } });
		}
		var $s = $('#chartSpecialties');
		if ($s.length) {
			new Chart($s[0], { type: 'doughnut', data: { labels: $s.data('labels'), datasets: [{ data: $s.data('values'),
				backgroundColor: ['#23477A', '#B8143F', '#4F8FC9', '#142C4F', '#E06C8A', '#8FB6DE', '#5F6F82', '#C9D6E8'] }] },
				options: { plugins: { legend: { position: 'bottom', labels: { boxWidth: 12 } } }, cutout: '62%' } });
		}
		var $r = $('#chartReport');
		if ($r.length) {
			new Chart($r[0], { type: 'bar', data: { labels: $r.data('labels'), datasets: [{ label: 'Total', data: $r.data('values'), backgroundColor: '#23477A', borderRadius: 6 }] },
				options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } } });
		}
	}

	// Campana de notificaciones del panel
	function vapidKey(base64) {
		var pad = '='.repeat((4 - base64.length % 4) % 4);
		var str = atob((base64 + pad).replace(/-/g, '+').replace(/_/g, '/'));
		var out = new Uint8Array(str.length);
		for (var i = 0; i < str.length; i++) out[i] = str.charCodeAt(i);
		return out;
	}
	var vapid = $('meta[name="vapid-public"]').attr('content');
	var swUrl = $('meta[name="sw-url"]').attr('content');
	var swScope = $('meta[name="sw-scope"]').attr('content');
	if (vapid && swUrl && window.isSecureContext && 'serviceWorker' in navigator && 'PushManager' in window) {
		$('#pushToggle').prop('hidden', false).on('click', function () {
			Notification.requestPermission().then(function (perm) {
				if (perm !== 'granted') {
					window.alert('El navegador no autorizó las notificaciones.');
					return;
				}
				navigator.serviceWorker.register(swUrl, { scope: swScope }).then(function (reg) {
					return reg.pushManager.subscribe({ userVisibleOnly: true, applicationServerKey: vapidKey(vapid) });
				}).then(function (sub) {
					var json = sub.toJSON();
					var body = {};
					body[$('meta[name="csrf-name"]').attr('content')] = $('meta[name="csrf-hash"]').attr('content');
					body.endpoint = json.endpoint;
					body.p256dh = json.keys.p256dh;
					body.auth = json.keys.auth;
					return $.ajax({ url: $('meta[name="push-subscribe"]').attr('content'), method: 'POST', data: body, dataType: 'json' });
				}).done(function (r) {
					window.alert((r && r.message) || 'Notificaciones activadas.');
				}).fail(function () {
					window.alert('No se pudo activar la notificación en este navegador.');
				});
			});
		});
	}
})(jQuery);
