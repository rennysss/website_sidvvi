<div class="row">
	<div class="col-lg-7">
		<div class="card admin-card mb-4">
			<div class="card-header"><h2 class="h6 mb-0">Registrar este equipo</h2></div>
			<div class="card-body">
				<p class="text-muted">La próxima vez, en la pantalla de acceso, elige <strong>Entrar con este equipo</strong>. El navegador pedirá Face ID, Touch ID o Windows Hello y no hará falta la contraseña.</p>
				<p class="passkey-unsupported text-warning" hidden>Este navegador no puede usar la verificación del equipo. Entra con tu contraseña.</p>
				<div class="passkey-alert"></div>
				<button type="button" class="btn btn-primary js-passkey" data-mode="register"
					data-options="<?= admin_url('webauthn/register_options') ?>"
					data-verify="<?= admin_url('webauthn/register_verify') ?>">
					<i class="fas fa-fingerprint"></i> Registrar Face ID, Touch ID o Windows Hello
				</button>
				<a href="<?= e($continue) ?>" class="btn btn-link">Continuar al panel</a>
			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<div class="card admin-card">
			<div class="card-header"><h2 class="h6 mb-0">Equipos registrados</h2></div>
			<ul class="list-group list-group-flush">
				<?php foreach ($devices as $d): ?>
				<li class="list-group-item d-flex align-items-center">
					<div>
						<strong class="d-block"><?= e($d->label ?: 'Equipo') ?></strong>
						<small class="text-muted">Desde <?= fecha_es($d->created_at, 'short') ?><?php if ($d->last_used_at): ?> · último uso <?= fecha_es($d->last_used_at, 'short') ?><?php endif; ?></small>
					</div>
					<?= form_open(admin_url('seguridad/revoke/'.$d->id), array('class' => 'ml-auto', 'data-confirm' => '¿Quitar este equipo?')) ?>
						<button class="btn btn-sm btn-outline-danger">Quitar</button>
					<?= form_close() ?>
				</li>
				<?php endforeach; ?>
				<?php if ( ! $devices): ?><li class="list-group-item text-muted small">Todavía no hay equipos registrados.</li><?php endif; ?>
			</ul>
		</div>
	</div>
</div>
