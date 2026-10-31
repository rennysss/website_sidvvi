<ul class="social-icons list-inline mb-0 <?= isset($class) ? $class : '' ?>">
	<?php foreach ($social_links as $s): ?>
	<li class="list-inline-item"><a href="<?= e($s->url) ?>" target="_blank" rel="noopener" aria-label="<?= e($s->network) ?>"><i class="<?= e($s->icon_class) ?>"></i></a></li>
	<?php endforeach; ?>
</ul>
