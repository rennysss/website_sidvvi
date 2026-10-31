<?php if ( ! empty($error)): ?><div class="alert alert-danger small"><?= $error ?></div><?php endif; ?>
<?= form_open(admin_url('restablecer/'.$token)) ?>
	<div class="form-group"><label for="password">Nueva contraseña</label><input type="password" class="form-control" id="password" name="password" minlength="8" required autocomplete="new-password"></div>
	<div class="form-group"><label for="password_confirm">Confirmar contraseña</label><input type="password" class="form-control" id="password_confirm" name="password_confirm" required autocomplete="new-password"></div>
	<button type="submit" class="btn btn-primary btn-block">Guardar contraseña</button>
<?= form_close() ?>
