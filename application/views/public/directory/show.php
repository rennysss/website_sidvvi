<section class="section">
	<div class="container">
		<div class="row">
			<div class="col-lg-4 mb-4">
				<div class="card profile-card text-center">
					<div class="card-body">
						<img src="<?= img_url($m->photo, 'assets/img/placeholders/avatar-1.jpg') ?>" alt="<?= e($name) ?>" class="profile-avatar mb-3" width="180" height="180">
						<h2 class="h4 mb-1"><?= e($name) ?></h2>
						<p class="text-accent mb-2"><?= e($m->position) ?></p>
						<?php if ($m->specialty): ?><span class="badge badge-soft"><?= e($m->specialty) ?></span><?php endif; ?>
						<?php if ($m->member_type): ?><span class="badge badge-soft">Socio <?= e(mb_strtolower($m->member_type)) ?></span><?php endif; ?>
					</div>
					<ul class="list-group list-group-flush text-left small">
						<?php if ($m->institution): ?><li class="list-group-item"><i class="fas fa-hospital fa-fw text-primary"></i> <?= e($m->institution) ?></li><?php endif; ?>
						<li class="list-group-item"><i class="fas fa-map-marker-alt fa-fw text-primary"></i> <?= e($m->city) ?>, <?= e($m->state) ?></li>
						<?php if ($m->email_public): ?><li class="list-group-item"><i class="fas fa-envelope fa-fw text-primary"></i> <a href="mailto:<?= e($m->email_public) ?>"><?= e($m->email_public) ?></a></li><?php endif; ?>
						<?php if ($m->phone_public): ?><li class="list-group-item"><i class="fas fa-phone fa-fw text-primary"></i> <?= e($m->phone_public) ?></li><?php endif; ?>
						<?php if ($m->website): ?><li class="list-group-item"><i class="fas fa-globe fa-fw text-primary"></i> <a href="<?= e($m->website) ?>" target="_blank" rel="noopener">Sitio web</a></li><?php endif; ?>
						<?php if ($m->member_number): ?><li class="list-group-item"><i class="fas fa-id-card fa-fw text-primary"></i> No. de socio: <?= e($m->member_number) ?></li><?php endif; ?>
					</ul>
				</div>
			</div>
			<div class="col-lg-8">
				<div class="content-box">
					<h3 class="h5">Semblanza</h3>
					<p><?= nl2br(e($m->bio ?: 'Información pendiente.')) ?></p>
					<?php if ($m->joined_at): ?><p class="small text-muted mb-0">Socio desde <?= fecha_es($m->joined_at) ?></p><?php endif; ?>
				</div>
				<a href="<?= site_url('directorio') ?>" class="btn btn-outline-primary mt-3"><i class="fas fa-arrow-left"></i> Volver al directorio</a>
			</div>
		</div>
	</div>
</section>
