/* SIDVVI · Acceso con Face ID, Touch ID o Windows Hello */
(function () {
	'use strict';

	function b64ToBuf(b64) {
		var pad = '='.repeat((4 - (b64.length % 4)) % 4);
		var str = atob((b64 + pad).replace(/-/g, '+').replace(/_/g, '/'));
		var buf = new Uint8Array(str.length);
		for (var i = 0; i < str.length; i++) buf[i] = str.charCodeAt(i);
		return buf.buffer;
	}

	function bufToB64(buf) {
		var bytes = new Uint8Array(buf);
		var s = '';
		for (var i = 0; i < bytes.length; i++) s += String.fromCharCode(bytes[i]);
		return btoa(s).replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');
	}

	function csrfBody(extra) {
		var name = document.querySelector('meta[name="csrf-name"]').content;
		var hash = document.querySelector('meta[name="csrf-hash"]').content;
		var body = new URLSearchParams();
		body.set(name, hash);
		Object.keys(extra || {}).forEach(function (k) { body.set(k, extra[k]); });
		return body;
	}

	function post(url, extra) {
		return fetch(url, {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
			body: csrfBody(extra).toString(),
			credentials: 'same-origin'
		}).then(function (r) {
			return r.json().then(function (j) {
				if (j.csrf) {
					document.querySelector('meta[name="csrf-name"]').content = j.csrf.name;
					document.querySelector('meta[name="csrf-hash"]').content = j.csrf.hash;
				}
				j._ok = r.ok && j.ok;
				return j;
			});
		});
	}

	function show(btn, message, ok) {
		var box = btn.closest('.card-body, .auth-card') || document.body;
		var alert = box.querySelector('.passkey-alert');
		if (!alert) return;
		alert.textContent = '';
		var div = document.createElement('div');
		div.className = 'alert alert-' + (ok ? 'success' : 'danger') + ' small';
		div.textContent = message;
		alert.appendChild(div);
	}

	function supported() {
		return window.isSecureContext && window.PublicKeyCredential;
	}

	document.querySelectorAll('.passkey-unsupported').forEach(function (el) {
		if (!supported()) el.hidden = false;
	});

	document.querySelectorAll('.js-passkey').forEach(function (btn) {
		if (!supported()) {
			btn.hidden = true;
			return;
		}
		btn.addEventListener('click', function () {
			var mode = btn.getAttribute('data-mode');
			btn.disabled = true;
			post(btn.getAttribute('data-options')).then(function (res) {
				if (!res._ok) throw new Error(res.message || 'No se pudo iniciar la verificación.');
				var publicKey = res.data.options.publicKey;
				publicKey.challenge = b64ToBuf(publicKey.challenge);
				if (publicKey.user) publicKey.user.id = b64ToBuf(publicKey.user.id);
				(publicKey.excludeCredentials || []).forEach(function (c) { c.id = b64ToBuf(c.id); });
				(publicKey.allowCredentials || []).forEach(function (c) { c.id = b64ToBuf(c.id); });
				var op = mode === 'register'
					? navigator.credentials.create({ publicKey: publicKey })
					: navigator.credentials.get({ publicKey: publicKey });
				return op;
			}).then(function (cred) {
				if (!cred) throw new Error('El equipo no devolvió una verificación.');
				var response = { clientDataJSON: bufToB64(cred.response.clientDataJSON) };
				if (mode === 'register') {
					response.attestationObject = bufToB64(cred.response.attestationObject);
				} else {
					response.authenticatorData = bufToB64(cred.response.authenticatorData);
					response.signature = bufToB64(cred.response.signature);
					if (cred.response.userHandle) response.userHandle = bufToB64(cred.response.userHandle);
				}
				return post(btn.getAttribute('data-verify'), {
					credential: JSON.stringify({ id: cred.id, response: response })
				});
			}).then(function (res) {
				if (!res._ok) throw new Error(res.message || 'No se pudo verificar este equipo.');
				if (res.data && res.data.redirect) {
					window.location = res.data.redirect;
					return;
				}
				show(btn, res.message, true);
				window.setTimeout(function () { window.location.reload(); }, 700);
			}).catch(function (err) {
				var message = err && err.message ? err.message : 'No se pudo usar la verificación del equipo.';
				if (err && err.name === 'NotAllowedError') {
					message = 'Se canceló la verificación, o este equipo no tiene Face ID, Touch ID ni Windows Hello activos.';
				}
				show(btn, message, false);
				btn.disabled = false;
			});
		});
	});
})();
