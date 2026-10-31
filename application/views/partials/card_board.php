<div class="card card-team h-100 text-center">
	<div class="team-photo">
		<img src="<?= img_url($b->photo, 'assets/img/placeholders/avatar-1.jpg') ?>" alt="<?= e($b->name) ?>" loading="lazy" width="400" height="400">
		<ul class="team-social list-inline mb-0">
			<?php if ($b->email): ?><li class="list-inline-item"><a href="mailto:<?= e($b->email) ?>" aria-label="Correo"><i class="fas fa-envelope"></i></a></li><?php endif; ?>
			<?php if ($b->linkedin): ?><li class="list-inline-item"><a href="<?= e($b->linkedin) ?>" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a></li><?php endif; ?>
			<?php if ($b->twitter): ?><li class="list-inline-item"><a href="<?= e($b->twitter) ?>" target="_blank" rel="noopener" aria-label="X"><i class="fab fa-x-twitter"></i></a></li><?php endif; ?>
		</ul>
	</div>
	<div class="card-body">
		<h3 class="h5 mb-1"><?= e($b->name) ?></h3>
		<p class="team-role mb-0"><?= e($b->position) ?></p>
		<?php if ($b->period): ?><small class="text-muted">Periodo <?= e($b->period) ?></small><?php endif; ?>
	</div>
</div>
