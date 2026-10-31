<?php /* Encabezado de sección: $eyebrow, $title, $text, $center (bool), $light (bool) */ ?>
<div class="section-title<?= ! empty($center) ? ' text-center mx-auto' : '' ?><?= ! empty($light) ? ' section-title-light' : '' ?>">
	<?php if ( ! empty($eyebrow)): ?><span class="eyebrow"><?= e($eyebrow) ?></span><?php endif; ?>
	<h2><?= e($title) ?></h2>
	<?php if ( ! empty($text)): ?><p class="lead-text"><?= e($text) ?></p><?php endif; ?>
</div>
