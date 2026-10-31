<div class="card admin-card">
	<div class="card-body">
		<p class="text-muted">Estos datos se cifran en la base de datos. El panel no vuelve a mostrarlos. Si dejas un campo vacío, se conserva el valor anterior. La cuenta tiene que ser de Instagram Business o Creator.</p>
		<?= form_open(admin_url('instagram_connect')) ?>
			<div class="form-group">
				<label for="ig-user">Usuario público</label>
				<div class="input-group">
					<div class="input-group-prepend"><span class="input-group-text">@</span></div>
					<input type="text" class="form-control" id="ig-user" name="instagram_user" value="<?= e($ig_user) ?>" maxlength="30" autocomplete="off">
				</div>
			</div>
			<?php foreach ($state as $key => $item): ?>
			<div class="form-group">
				<label for="<?= e($key) ?>"><?= e($item['label']) ?></label>
				<input type="password" class="form-control" id="<?= e($key) ?>" name="<?= e($key) ?>" value="" autocomplete="new-password" placeholder="<?= $item['filled'] ? 'Guardado. Escribe solo si vas a reemplazarlo.' : 'Sin configurar' ?>">
				<?php if ($item['filled']): ?>
				<div class="custom-control custom-checkbox mt-2">
					<input type="checkbox" class="custom-control-input" id="clear-<?= e($key) ?>" name="clear_<?= e($key) ?>" value="1">
					<label class="custom-control-label" for="clear-<?= e($key) ?>">Quitar el valor guardado</label>
				</div>
				<small class="text-muted d-block mt-1">
					Configurado<?= $item['updated'] ? ' el '.fecha_es($item['updated'], 'short') : '' ?>
					<?php if ($item['expires']): ?> · renovar antes del <?= fecha_es($item['expires'], 'short') ?><?php endif; ?>
				</small>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>
			<button class="btn btn-primary">Guardar</button>
			<a class="btn btn-link" href="<?= admin_url('instagram') ?>">Volver a las publicaciones</a>
		<?= form_close() ?>
	</div>
</div>
