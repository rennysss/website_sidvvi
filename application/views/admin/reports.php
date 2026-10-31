<div class="card admin-card mb-4">
	<div class="card-body">
		<form class="form-row align-items-end" method="get" action="<?= admin_url('reports') ?>">
			<div class="form-group col-md-4">
				<label for="rep-type">Reporte</label>
				<select class="custom-select" id="rep-type" name="type" required>
					<option value="">Selecciona...</option>
					<?php foreach ($types as $k => $label): ?>
					<option value="<?= e($k) ?>" <?= $filters['type'] === $k ? 'selected' : '' ?>><?= e($label) ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="form-group col-md-3">
				<label for="rep-from">Desde</label>
				<input type="date" class="form-control" id="rep-from" name="from" value="<?= e($filters['from']) ?>">
			</div>
			<div class="form-group col-md-3">
				<label for="rep-to">Hasta</label>
				<input type="date" class="form-control" id="rep-to" name="to" value="<?= e($filters['to']) ?>">
			</div>
			<div class="form-group col-md-2">
				<button class="btn btn-primary btn-block">Ver</button>
			</div>
		</form>
	</div>
</div>

<?php if ($report): ?>
<div class="d-flex flex-wrap align-items-center mb-3">
	<p class="text-muted small mb-2 mb-md-0 mr-auto"><?= e($report['note']) ?></p>
	<a class="btn btn-sm btn-outline-secondary mr-2" href="<?= admin_url('reports/csv') ?>?<?= e(http_build_query($filters)) ?>"><i class="fas fa-file-csv"></i> CSV</a>
	<a class="btn btn-sm btn-outline-primary" href="<?= admin_url('reports/pdf') ?>?<?= e(http_build_query($filters)) ?>"><i class="fas fa-file-pdf"></i> PDF con QR</a>
</div>
<div class="row">
	<div class="col-lg-5 mb-4">
		<div class="card admin-card h-100">
			<div class="card-header"><h2 class="h6 mb-0"><?= e($report['title']) ?></h2></div>
			<div class="card-body">
				<?php if ($report['values']): ?>
				<canvas id="chartReport" height="220" data-labels='<?= e(json_encode($report['labels'], JSON_UNESCAPED_UNICODE)) ?>' data-values='<?= e(json_encode($report['values'])) ?>'></canvas>
				<?php else: ?>
				<p class="text-muted mb-0">Sin registros en este corte.</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="col-lg-7 mb-4">
		<div class="card admin-card">
			<div class="table-responsive">
				<table class="table table-hover mb-0 admin-table">
					<thead><tr><?php foreach ($report['columns'] as $col): ?><th><?= e($col) ?></th><?php endforeach; ?></tr></thead>
					<tbody>
					<?php foreach ($report['rows'] as $row): ?>
						<tr><?php foreach ($row as $cell): ?><td><?= e($cell) ?></td><?php endforeach; ?></tr>
					<?php endforeach; ?>
					<?php if ( ! $report['rows']): ?><tr><td colspan="<?= count($report['columns']) ?>" class="text-muted">Sin registros.</td></tr><?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
