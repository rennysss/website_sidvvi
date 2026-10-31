<div class="row">
	<?php foreach ($cards as $cd): ?>
	<div class="col-sm-6 col-xl-3 mb-4">
		<a href="<?= admin_url($cd[3]) ?>" class="card stat-card stat-<?= $cd[4] ?>">
			<div class="card-body d-flex align-items-center">
				<span class="stat-icon"><i class="<?= $cd[2] ?>"></i></span>
				<div class="ml-3"><div class="stat-number"><?= (int) $cd[1] ?></div><div class="stat-label"><?= e($cd[0]) ?></div></div>
			</div>
		</a>
	</div>
	<?php endforeach; ?>
</div>
<div class="row">
	<div class="col-lg-7 mb-4">
		<div class="card admin-card h-100"><div class="card-header"><h2 class="h6 mb-0">Mensajes de contacto (últimos 6 meses)</h2></div>
		<div class="card-body"><canvas id="chartMessages" height="140" data-labels='<?= json_encode(array_map(function ($m) { return fecha_es($m.'-01', 'month').' '.substr($m, 2, 2); }, array_keys($months))) ?>' data-values='<?= json_encode(array_values($months)) ?>'></canvas></div></div>
	</div>
	<div class="col-lg-5 mb-4">
		<div class="card admin-card h-100"><div class="card-header"><h2 class="h6 mb-0">Socios por especialidad</h2></div>
		<div class="card-body"><canvas id="chartSpecialties" height="200" data-labels='<?= e(json_encode(array_column($by_spec, 'label'), JSON_UNESCAPED_UNICODE)) ?>' data-values='<?= json_encode(array_map('intval', array_column($by_spec, 'total'))) ?>'></canvas></div></div>
	</div>
</div>
<div class="row">
	<div class="col-lg-4 mb-4">
		<div class="card admin-card h-100"><div class="card-header d-flex"><h2 class="h6 mb-0">Próximos congresos</h2><a href="<?= admin_url('congresses') ?>" class="ml-auto small">Ver todos</a></div>
			<ul class="list-group list-group-flush">
				<?php foreach ($upcoming as $c): ?><li class="list-group-item"><strong class="d-block small"><?= e($c->name) ?></strong><small class="text-muted"><?= rango_fechas_es($c->starts_at, $c->ends_at) ?> · <?= e($c->city) ?></small></li><?php endforeach; ?>
				<?php if ( ! $upcoming): ?><li class="list-group-item text-muted small">Sin eventos próximos.</li><?php endif; ?>
			</ul>
		</div>
	</div>
	<div class="col-lg-4 mb-4">
		<div class="card admin-card h-100"><div class="card-header d-flex"><h2 class="h6 mb-0">Últimos mensajes</h2><a href="<?= admin_url('messages') ?>" class="ml-auto small">Ver todos</a></div>
			<ul class="list-group list-group-flush">
				<?php foreach ($last_messages as $m): ?><li class="list-group-item"><a href="<?= admin_url('messages/edit/'.$m->id) ?>" class="d-flex"><span class="small"><strong class="d-block"><?= e($m->name) ?></strong><?= e(str_limit($m->subject, 40)) ?></span><span class="ml-auto"><?= status_label($m->status) ?></span></a></li><?php endforeach; ?>
				<?php if ( ! $last_messages): ?><li class="list-group-item text-muted small">Sin mensajes.</li><?php endif; ?>
			</ul>
		</div>
	</div>
	<div class="col-lg-4 mb-4">
		<div class="card admin-card h-100"><div class="card-header d-flex"><h2 class="h6 mb-0">Biblioteca</h2><?php if ($can_edit): ?><a href="<?= admin_url('articles/create') ?>" class="ml-auto small"><i class="fas fa-plus"></i> Nueva</a><?php endif; ?></div>
			<ul class="list-group list-group-flush">
				<?php foreach ($last_articles as $a): ?><li class="list-group-item"><a href="<?= admin_url('articles/edit/'.$a->id) ?>" class="d-flex"><span class="small"><?= e(str_limit($a->title, 48)) ?></span><span class="ml-auto pl-2"><?= status_label($a->status) ?></span></a></li><?php endforeach; ?>
			</ul>
		</div>
	</div>
</div>
