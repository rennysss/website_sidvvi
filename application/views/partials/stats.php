<section class="stats-band">
	<div class="container">
		<div class="row">
			<?php foreach ($stats as $s): ?>
			<div class="col-6 col-lg-3 stat-item">
				<i class="<?= $s['icon'] ?>"></i>
				<div><span class="stat-value" data-count="<?= (int) $s['value'] ?>"><?= (int) $s['value'] ?></span>+</div>
				<p><?= e($s['label']) ?></p>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
