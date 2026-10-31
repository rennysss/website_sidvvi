<?php if ($msg = $this->session->flashdata('success')): ?><div class="alert alert-success small"><?= e($msg) ?></div><?php endif; ?>
<?php if ( ! empty($error)): ?><div class="alert alert-danger small"><?= e($error) ?></div><?php endif; ?>
<p class="passkey-unsupported text-warning small" hidden>Este navegador no puede usar Face ID, Touch ID ni Windows Hello. Entra con tu contraseña.</p>
<div class="passkey-alert"></div>
<button type="button" class="btn btn-primary btn-block js-passkey mb-3" data-mode="login"
	data-options="<?= admin_url('webauthn/login_options') ?>"
	data-verify="<?= admin_url('webauthn/login_verify') ?>">
	<i class="fas fa-fingerprint"></i> Entrar con este equipo
</button>
<p class="text-center text-muted small">Face ID, Touch ID o Windows Hello, sin contraseña si ya registraste este equipo.</p>
<hr>
<p class="text-center text-muted small mb-3">Primer acceso o equipo nuevo</p>
<?= form_open(admin_url('login')) ?>
	<div class="form-group">
		<label for="email">Correo</label>
		<input type="email" class="form-control" id="email" name="email" value="<?= e(set_value('email')) ?>" required autofocus autocomplete="username">
	</div>
	<div class="form-group">
		<label for="password">Contraseña</label>
		<input type="password" class="form-control" id="password" name="password" required autocomplete="current-password">
	</div>
	<button type="submit" class="btn btn-primary btn-block">Iniciar sesión</button>
<?= form_close() ?>
<p class="text-center small mt-3 mb-0"><a href="<?= admin_url('recuperar') ?>">¿Olvidaste tu contraseña?</a></p>
