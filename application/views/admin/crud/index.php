<?php
/** Listado genérico del CRUD */
$render_cell = function ($row, $key, $f) use ($options) {
	$v = $row->$key ?? NULL;
	switch ($f['type'] ?? 'text') {
		case 'image':
			return $v ? '<img src="'.img_url($v).'" class="thumb" alt="">' : '<span class="thumb thumb-empty"><i class="far fa-image"></i></span>';
		case 'file':
			return $v ? '<a href="'.base_url($v).'" target="_blank"><i class="far fa-file"></i> Ver</a>' : '<span class="text-muted">—</span>';
		case 'checkbox':
			return $v ? '<span class="badge badge-success">Sí</span>' : '<span class="badge badge-secondary">No</span>';
		case 'icon':
			return '<i class="'.e($v).' fa-lg text-primary"></i>';
		case 'select':
			if ($key === 'status') return status_label($v);
			return e($options[$key][$v] ?? '—');
		case 'datetime':
		case 'date':
			return $v ? fecha_es($v, 'short') : '<span class="text-muted">—</span>';
		default:
			if (in_array($key, array('created_at', 'last_login_at'))) return $v ? date('d/m/Y H:i', strtotime($v)) : '—';
			return e(str_limit(strip_tags((string) $v), 60));
	}
};
?>
<div class="card admin-card">
	<div class="card-header d-flex flex-wrap align-items-center">
		<h2 class="h6 mb-0 mr-3"><i class="<?= $c->icon ?> text-primary mr-1"></i> <?= e($c->title) ?> <span class="badge badge-light"><?= (int) $total ?></span></h2>
		<?php if ($c->searchable): ?>
		<form class="form-inline my-2 my-md-0 ml-md-auto" method="get">
			<div class="input-group input-group-sm">
				<input type="search" name="q" class="form-control" placeholder="Buscar..." value="<?= e($q) ?>">
				<div class="input-group-append"><button class="btn btn-outline-secondary"><i class="fas fa-search"></i></button></div>
			</div>
		</form>
		<?php endif; ?>
		<?php if ($c->can_create): ?>
		<a href="<?= admin_url($c->route.'/create') ?>" class="btn btn-primary btn-sm ml-2 <?= $c->searchable ? '' : 'ml-auto' ?>"><i class="fas fa-plus"></i> Nuevo</a>
		<?php endif; ?>
	</div>
	<?php if ($c->help): ?><div class="card-body py-2 small text-muted border-bottom"><i class="fas fa-info-circle"></i> <?= e($c->help) ?></div><?php endif; ?>
	<?php if ( ! empty($toolbar)): ?><div class="card-body py-2 border-bottom"><?= $toolbar ?></div><?php endif; ?>
	<div class="table-responsive">
		<table class="table table-hover mb-0 admin-table">
			<thead><tr>
				<?php foreach ($columns as $k => $f): ?><th><?= e($f['label']) ?></th><?php endforeach; ?>
				<th class="text-right">Acciones</th>
			</tr></thead>
			<tbody>
			<?php foreach ($rows as $row): ?>
				<tr>
					<?php foreach ($columns as $k => $f): ?><td><?= $render_cell($row, $k, $f) ?></td><?php endforeach; ?>
					<td class="text-right text-nowrap">
						<a href="<?= admin_url($c->route.'/edit/'.$row->id) ?>" class="btn btn-sm btn-light" title="<?= $c->can_edit ? 'Editar' : 'Ver' ?>"><i class="fas fa-<?= $c->can_edit ? 'pen' : 'eye' ?>"></i></a>
						<?php if ($c->can_delete): ?>
						<?= form_open(admin_url($c->route.'/delete/'.$row->id), array('class' => 'd-inline', 'data-confirm' => '¿Eliminar este registro? Esta acción no se puede deshacer.')) ?>
							<button class="btn btn-sm btn-light text-danger" title="Eliminar"><i class="fas fa-trash"></i></button>
						<?= form_close() ?>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			<?php if ( ! $rows): ?><tr><td colspan="<?= count($columns) + 1 ?>" class="text-center text-muted py-5">No hay registros<?= $q ? ' para «'.e($q).'»' : '' ?>.</td></tr><?php endif; ?>
			</tbody>
		</table>
	</div>
	<?php if ($pages > 1): ?>
	<div class="card-footer">
		<ul class="pagination pagination-sm mb-0 justify-content-end">
			<?php for ($i = 1; $i <= $pages; $i++): ?>
			<li class="page-item<?= $i == $page ? ' active' : '' ?>"><a class="page-link" href="?<?= http_build_query(array_filter(array('q' => $q, 'page' => $i))) ?>"><?= $i ?></a></li>
			<?php endfor; ?>
		</ul>
	</div>
	<?php endif; ?>
</div>
