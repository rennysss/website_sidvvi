<?php $types = Article_model::$types; ?>
<section class="section">
	<div class="container">
		<div class="row">
			<div class="col-lg-8 mb-5">
				<form class="filter-bar filter-inline" method="get" action="<?= current_url() ?>">
					<?php if ($f['q']): ?><input type="hidden" name="q" value="<?= e($f['q']) ?>"><?php endif; ?>
					<?php if ($f['tag']): ?><input type="hidden" name="tema" value="<?= e($f['tag']) ?>"><?php endif; ?>
					<select name="tipo" class="custom-select custom-select-sm" onchange="this.form.submit()" aria-label="Tipo">
						<option value="">Todos los tipos</option>
						<?php foreach ($types as $k => $v): ?><option value="<?= $k ?>"<?= $f['type'] === $k ? ' selected' : '' ?>><?= $v ?></option><?php endforeach; ?>
					</select>
					<select name="anio" class="custom-select custom-select-sm" onchange="this.form.submit()" aria-label="Año">
						<option value="">Todos los años</option>
						<?php foreach ($years as $y): ?><option<?= $f['year'] == $y ? ' selected' : '' ?>><?= $y ?></option><?php endforeach; ?>
					</select>
					<span class="results-count ml-auto mb-0"><strong><?= (int) $total ?></strong> resultado<?= $total == 1 ? '' : 's' ?></span>
				</form>
				<?php if ($f['q'] || $f['tag']): ?>
				<p class="small">Filtro: <strong><?= e($f['q'] ?: $f['tag']) ?></strong> · <a href="<?= site_url('biblioteca') ?>">Quitar filtros</a></p>
				<?php endif; ?>
				<div class="row">
					<?php foreach ($articles as $a): ?>
					<div class="col-md-6 mb-4"><?php $this->load->view('partials/card_article', array('a' => $a)); ?></div>
					<?php endforeach; ?>
					<?php if ( ! $articles): ?><div class="col-12"><div class="empty-state"><i class="fas fa-book-open"></i><p>No hay publicaciones con esos filtros.</p></div></div><?php endif; ?>
				</div>
				<?php if ($pages > 1): ?>
				<nav aria-label="Paginación"><ul class="pagination justify-content-center">
					<?php for ($i = 1; $i <= $pages; $i++): $qs = array_filter(array('q' => $f['q'], 'tema' => $f['tag'], 'tipo' => $f['type'], 'anio' => $f['year'], 'page' => $i)); ?>
					<li class="page-item<?= $i == $page ? ' active' : '' ?>"><a class="page-link" href="?<?= http_build_query($qs) ?>"><?= $i ?></a></li>
					<?php endfor; ?>
				</ul></nav>
				<?php endif; ?>
			</div>
			<div class="col-lg-4"><?php $this->load->view('public/library/_sidebar'); ?></div>
		</div>
	</div>
</section>
