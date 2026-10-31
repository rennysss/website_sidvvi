<section class="section">
	<div class="container">
		<form id="directoryFilters" class="filter-bar" action="<?= site_url('directorio') ?>" method="get" data-ajax="<?= site_url('directorio/buscar') ?>">
			<div class="form-row">
				<div class="col-md-4 mb-2">
					<label class="sr-only" for="dq">Buscar</label>
					<div class="input-icon"><i class="fas fa-search"></i><input type="search" class="form-control" id="dq" name="q" value="<?= e($f['q']) ?>" placeholder="Nombre, institución o ciudad"></div>
				</div>
				<div class="col-md-3 col-sm-4 mb-2">
					<label class="sr-only" for="ds">Especialidad</label>
					<select class="custom-select" id="ds" name="specialty">
						<option value="">Todas las especialidades</option>
						<?php foreach ($specialties as $s): ?><option value="<?= $s->id ?>"<?= $f['specialty'] == $s->id ? ' selected' : '' ?>><?= e($s->name) ?></option><?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-3 col-sm-4 mb-2">
					<label class="sr-only" for="dst">Estado</label>
					<select class="custom-select" id="dst" name="state">
						<option value="">Todos los estados</option>
						<?php foreach ($states as $s): ?><option<?= $f['state'] === $s ? ' selected' : '' ?>><?= e($s) ?></option><?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-2 col-sm-4 mb-2">
					<label class="sr-only" for="dt">Tipo</label>
					<select class="custom-select" id="dt" name="type">
						<option value="">Tipo de socio</option>
						<?php foreach ($types as $t): ?><option value="<?= $t->id ?>"<?= $f['type'] == $t->id ? ' selected' : '' ?>><?= e($t->name) ?></option><?php endforeach; ?>
					</select>
				</div>
			</div>
		</form>
		<div id="directoryResults" aria-live="polite">
			<?php $this->load->view('public/directory/_list'); ?>
		</div>
	</div>
</section>
