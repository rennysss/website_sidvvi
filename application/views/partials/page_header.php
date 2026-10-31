<section class="page-header" style="background-image:url('<?= base_url('assets/img/placeholders/page-header.jpg') ?>')">
	<div class="container">
		<h1><?= e($page_header['title']) ?></h1>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?= site_url() ?>"><i class="fas fa-home"></i> Inicio</a></li>
				<?php foreach ($page_header['crumbs'] as $c): ?>
					<?php if ($c[1]): ?>
					<li class="breadcrumb-item"><a href="<?= site_url($c[1]) ?>"><?= e($c[0]) ?></a></li>
					<?php else: ?>
					<li class="breadcrumb-item active" aria-current="page"><?= e($c[0]) ?></li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ol>
		</nav>
	</div>
</section>
