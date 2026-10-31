<?php $modes = array('presencial' => 'Presencial', 'virtual' => 'Virtual', 'hibrido' => 'Híbrido'); ?>
<section class="section">
	<div class="container">
		<div class="row">
			<div class="col-lg-8 mb-5">
				<img src="<?= img_url($c->image) ?>" alt="<?= e($c->name) ?>" class="img-fluid rounded-lg mb-4" width="800" height="520">
				<?php if ($c->edition): ?><span class="eyebrow"><?= e($c->edition) ?> edición</span><?php endif; ?>
				<h2 class="post-title"><?= e($c->name) ?></h2>
				<?php if ($c->summary): ?><p class="lead"><?= e($c->summary) ?></p><?php endif; ?>
				<div class="rich-text"><?= $c->description ?></div>
			</div>
			<div class="col-lg-4">
				<aside class="sidebar">
					<div class="widget event-box">
						<h4 class="widget-title">Información del evento</h4>
						<ul class="event-meta list-unstyled">
							<li><i class="far fa-calendar-alt"></i> <?= rango_fechas_es($c->starts_at, $c->ends_at) ?></li>
							<li><i class="fas fa-map-marker-alt"></i> <?= e(trim(($c->venue ? $c->venue.', ' : '').$c->city, ', ')) ?></li>
							<li><i class="fas fa-laptop-house"></i> <?= $modes[$c->modality] ?? e($c->modality) ?></li>
						</ul>
						<?php if ($c->is_upcoming): ?>
						<a href="<?= e($c->registration_url) ?>" class="btn btn-accent btn-block">Registrarme</a>
						<?php endif; ?>
						<a href="<?= e($c->certificates_url) ?>" class="btn btn-outline-primary btn-block"><i class="fas fa-certificate"></i> Constancias</a>
					</div>
					<?php if ($others): ?>
					<div class="widget">
						<h4 class="widget-title">Otros eventos</h4>
						<?php foreach ($others as $o): ?>
						<a class="mini-post" href="<?= site_url('congresos/'.$o->slug) ?>">
							<img src="<?= img_url($o->image) ?>" alt="" loading="lazy" width="80" height="60">
							<span><strong><?= e(str_limit($o->name, 50)) ?></strong><small><?= fecha_es($o->starts_at, 'short') ?></small></span>
						</a>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>
				</aside>
			</div>
		</div>
	</div>
</section>
