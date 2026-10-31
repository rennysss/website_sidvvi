<?php if ( ! empty($success)): ?><div class="alert alert-success small"><?= e($success) ?></div><?php endif; ?>
<?php if ( ! empty($error)): ?><div class="alert alert-danger small"><?= e($error) ?></div><?php endif; ?>
<p class="small text-muted">Escribe tu correo y te enviaremos un enlace para crear una nueva contraseña.</p>
<?= form_open(admin_url('recuperar')) ?>
	<div class="form-group"><label for="email">Correo</label><input type="email" class="form-control" id="email" name="email" required autofocus></div>
	<button type="submit" class="btn btn-primary btn-block">Enviar enlace</button>
<?= form_close() ?>
<p class="text-center small mt-3 mb-0"><a href="<?= admin_url() ?>">Volver al inicio de sesión</a></p>
