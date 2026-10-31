<?php $modes = array('presencial' => 'Presencial', 'virtual' => 'Virtual', 'hibrido' => 'Híbrido'); ?>
<article class="card card-event h-100">
	<a href="<?= site_url('congresos/'.$c->slug) ?>" class="card-img-wrap">
		<img src="<?= img_url($c->image) ?>" class="card-img-top" alt="<?= e($c->name) ?>" loading="lazy" width="800" height="520">
		<span class="date-badge"><strong><?= fecha_es($c->starts_at, 'day') ?></strong><?= fecha_es($c->starts_at, 'month') ?> <?= date('Y', strtotime($c->starts_at)) ?></span>
	</a>
	<div class="card-body d-flex flex-column">
		<h3 class="card-title h5"><a href="<?= site_url('congresos/'.$c->slug) ?>"><?= e($c->name) ?></a></h3>
		<ul class="event-meta list-unstyled">
			<li><i class="fas fa-map-marker-alt"></i> <?= e(trim(($c->venue ? $c->venue.', ' : '').$c->city, ', ')) ?></li>
			<li><i class="far fa-calendar-alt"></i> <?= rango_fechas_es($c->starts_at, $c->ends_at) ?></li>
			<li><i class="fas fa-laptop-house"></i> <?= $modes[$c->modality] ?? e($c->modality) ?></li>
		</ul>
		<div class="mt-auto">
			<?php if ($c->is_upcoming): ?>
			<a href="<?= e($c->registration_url) ?>" class="btn btn-primary btn-sm">Registrarme</a>
			<?php else: ?>
			<a href="<?= e($c->certificates_url) ?>" class="btn btn-outline-primary btn-sm"><i class="fas fa-certificate"></i> Constancias</a>
			<?php endif; ?>
			<a href="<?= site_url('congresos/'.$c->slug) ?>" class="link-more ml-2">Detalles <i class="fas fa-arrow-right"></i></a>
		</div>
	</div>
</article>
