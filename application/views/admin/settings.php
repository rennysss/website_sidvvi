<?php $groups = Settings::$groups; ?>
<?= form_open(admin_url('settings')) ?>
<div class="card admin-card">
	<div class="card-header">
		<ul class="nav nav-tabs card-header-tabs" role="tablist">
			<?php $i = 0; foreach ($grouped as $g => $items): ?>
			<li class="nav-item"><a class="nav-link<?= $i++ === 0 ? ' active' : '' ?>" data-toggle="tab" href="#tab-<?= $g ?>" role="tab"><i class="<?= $groups[$g][1] ?? 'fas fa-cog' ?>"></i> <?= $groups[$g][0] ?? ucfirst($g) ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<div class="card-body tab-content">
		<?php $i = 0; foreach ($grouped as $g => $items): ?>
		<div class="tab-pane fade<?= $i++ === 0 ? ' show active' : '' ?>" id="tab-<?= $g ?>" role="tabpanel">
			<?php foreach ($items as $s): ?>
			<div class="form-group">
				<label for="s_<?= $s->key ?>"><?= e($s->label) ?></label>
				<?php if ($s->type === 'textarea'): ?>
				<textarea class="form-control" id="s_<?= $s->key ?>" name="s[<?= $s->key ?>]" rows="3"><?= e($s->value) ?></textarea>
				<?php else: ?>
				<input type="text" class="form-control" id="s_<?= $s->key ?>" name="s[<?= $s->key ?>]" value="<?= e($s->value) ?>">
				<?php endif; ?>
			</div>
			<?php endforeach; ?>
		</div>
		<?php endforeach; ?>
		<div class="alert alert-light small mb-0"><i class="fas fa-lock"></i> Las credenciales (base de datos, SMTP, llaves de Instagram y PUSH) se configuran en el archivo <code>.env</code> del servidor, no aquí.</div>
	</div>
	<div class="card-footer"><button class="btn btn-primary"><i class="fas fa-save"></i> Guardar configuración</button></div>
</div>
<?= form_close() ?>
