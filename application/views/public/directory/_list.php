<p class="results-count"><strong><?= (int) $total ?></strong> socio<?= $total == 1 ? '' : 's' ?> encontrado<?= $total == 1 ? '' : 's' ?></p>
<div class="row">
	<?php foreach ($members as $m): $name = trim($m->title_prefix.' '.$m->first_name.' '.$m->last_name); ?>
	<div class="col-sm-6 col-lg-4 mb-4">
		<a class="card card-member h-100" href="<?= site_url('directorio/'.$m->slug) ?>">
			<div class="card-body d-flex align-items-center">
				<img src="<?= img_url($m->photo, 'assets/img/placeholders/avatar-1.jpg') ?>" alt="<?= e($name) ?>" class="member-avatar" loading="lazy" width="80" height="80">
				<div class="ml-3">
					<h3 class="h6 mb-1"><?= e($name) ?></h3>
					<?php if ($m->specialty): ?><span class="badge badge-soft"><?= e($m->specialty) ?></span><?php endif; ?>
					<p class="small text-muted mb-0 mt-1"><i class="fas fa-hospital"></i> <?= e(str_limit($m->institution, 34)) ?></p>
					<p class="small text-muted mb-0"><i class="fas fa-map-marker-alt"></i> <?= e($m->city) ?>, <?= e($m->state) ?></p>
				</div>
			</div>
		</a>
	</div>
	<?php endforeach; ?>
	<?php if ( ! $members): ?>
	<div class="col-12"><div class="empty-state"><i class="fas fa-user-slash"></i><p>No encontramos socios con esos filtros.</p></div></div>
	<?php endif; ?>
</div>
<?php if ($pages > 1): ?>
<nav aria-label="Paginación del directorio">
	<ul class="pagination justify-content-center">
		<?php for ($i = 1; $i <= $pages; $i++): ?>
		<li class="page-item<?= $i == $f['page'] ? ' active' : '' ?>"><a class="page-link" href="?<?= http_build_query(array_merge(array_filter($f), array('page' => $i))) ?>" data-page="<?= $i ?>"><?= $i ?></a></li>
		<?php endfor; ?>
	</ul>
</nav>
<?php endif; ?>
