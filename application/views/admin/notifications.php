<div class="row">
	<div class="col-lg-5 mb-4">
		<div class="card admin-card">
			<div class="card-header"><h2 class="h6 mb-0">Enviar aviso</h2></div>
			<div class="card-body">
				<?php if ( ! $can_edit): ?><p class="text-muted">Solo un editor o el superadministrador pueden enviar avisos.</p><?php endif; ?>
				<?= form_open(admin_url('notifications')) ?>
					<div class="form-group">
						<label for="n-title">Título</label>
						<input type="text" class="form-control" id="n-title" name="title" maxlength="150" required <?= $can_edit ? '' : 'disabled' ?>>
					</div>
					<div class="form-group">
						<label for="n-body">Mensaje</label>
						<textarea class="form-control" id="n-body" name="body" rows="3" maxlength="500" required <?= $can_edit ? '' : 'disabled' ?></textarea>
					</div>
					<div class="form-group">
						<label for="n-url">Enlace al abrirlo</label>
						<input type="text" class="form-control" id="n-url" name="url" placeholder="<?= e(admin_url('dashboard')) ?>" <?= $can_edit ? '' : 'disabled' ?>>
					</div>
					<div class="form-group">
						<label for="n-audience">Destino</label>
						<select class="custom-select" id="n-audience" name="audience" <?= $can_edit ? '' : 'disabled' ?>>
							<option value="all">Todos los que activaron la campana</option>
							<option value="superadmin">Superadministradores</option>
							<option value="editor">Editores</option>
							<option value="viewer">Consulta</option>
						</select>
					</div>
					<button class="btn btn-primary" <?= $can_edit ? '' : 'disabled' ?>>Enviar</button>
				<?= form_close() ?>
				<p class="small text-muted mt-3 mb-0">Cada persona activa la campana en la barra superior de su navegador. Hace falta HTTPS (en local, localhost sirve).</p>
			</div>
		</div>
	</div>
	<div class="col-lg-7 mb-4">
		<div class="card admin-card">
			<div class="card-header"><h2 class="h6 mb-0">Envíos recientes</h2></div>
			<ul class="list-group list-group-flush">
				<?php foreach ($rows as $n): ?>
				<li class="list-group-item">
					<strong class="d-block"><?= e($n->title) ?></strong>
					<span class="small"><?= e($n->body) ?></span>
					<small class="text-muted d-block mt-1"><?= fecha_es($n->created_at, 'short') ?> · <?= (int) $n->sent_count ?> navegador<?= (int) $n->sent_count === 1 ? '' : 'es' ?></small>
				</li>
				<?php endforeach; ?>
				<?php if ( ! $rows): ?><li class="list-group-item text-muted small">Todavía no se envían avisos.</li><?php endif; ?>
			</ul>
		</div>
	</div>
</div>
