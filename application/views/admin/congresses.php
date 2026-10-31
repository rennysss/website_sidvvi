<div class="alert alert-info d-flex align-items-center">
	<i class="fas fa-info-circle fa-lg mr-3"></i>
	<div>Los congresos, el registro de asistentes y las constancias se administran en el <strong>subsistema de Congresos</strong>. Aquí solo se muestra lo que aparece en el sitio web.</div>
	<a href="<?= e($app_url) ?>" target="_blank" class="btn btn-primary btn-sm ml-auto text-nowrap">Abrir subsistema</a>
</div>
<?php foreach (array('Próximos' => $upcoming, 'Anteriores' => $past) as $label => $list): ?>
<div class="card admin-card mb-4">
	<div class="card-header"><h2 class="h6 mb-0"><?= $label ?> <span class="badge badge-light"><?= count($list) ?></span></h2></div>
	<div class="table-responsive"><table class="table table-hover mb-0 admin-table">
		<thead><tr><th></th><th>Nombre</th><th>Fechas</th><th>Sede</th><th>Modalidad</th><th></th></tr></thead>
		<tbody>
		<?php foreach ($list as $c): ?>
			<tr><td><img src="<?= img_url($c->image) ?>" class="thumb" alt=""></td><td><?= e($c->name) ?></td><td class="text-nowrap"><?= rango_fechas_es($c->starts_at, $c->ends_at) ?></td><td><?= e($c->city) ?></td><td><?= e(ucfirst($c->modality)) ?></td>
			<td class="text-right"><a href="<?= site_url('congresos/'.$c->slug) ?>" target="_blank" class="btn btn-sm btn-light" title="Ver en el sitio"><i class="fas fa-external-link-alt"></i></a></td></tr>
		<?php endforeach; ?>
		</tbody>
	</table></div>
</div>
<?php endforeach; ?>
