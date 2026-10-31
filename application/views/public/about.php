<?php $ab = $blocks['about']; ?>
<section class="section">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-6 mb-4 mb-lg-0">
				<img src="<?= img_url($ab->image, 'assets/img/placeholders/about.jpg') ?>" alt="" class="img-fluid rounded-lg shadow-soft" loading="lazy">
			</div>
			<div class="col-lg-6 pl-lg-5">
				<?php section_title(array('eyebrow' => $ab->eyebrow, 'title' => $ab->title)); ?>
				<div class="rich-text"><?= $ab->body ?></div>
			</div>
		</div>
	</div>
</section>

<section class="section bg-soft">
	<div class="container">
		<div class="row">
			<?php foreach (array('mission', 'vision', 'values') as $k): $b = $blocks[$k]; ?>
			<div class="col-md-4 mb-4">
				<div class="card card-service h-100"><div class="card-body">
					<span class="icon-circle"><i class="<?= e($b->extra ?: 'fas fa-star') ?>"></i></span>
					<h3 class="h5"><?= e($b->title) ?></h3>
					<div class="rich-text"><?= $b->body ?></div>
				</div></div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php $this->load->view('partials/stats'); ?>

<section class="section">
	<div class="container">
		<?php section_title(array('eyebrow' => 'Lo que hacemos', 'title' => 'Áreas de trabajo', 'center' => TRUE)); ?>
		<div class="row">
			<?php foreach ($areas as $a): ?>
			<div class="col-md-6 col-lg-4 mb-4">
				<div class="feature-inline">
					<span class="icon-circle icon-sm"><i class="<?= e($a->icon) ?>"></i></span>
					<div><h3 class="h6 mb-1"><?= e($a->title) ?></h3><p class="mb-0 small text-muted"><?= e($a->description) ?></p></div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section bg-soft">
	<div class="container">
		<?php section_title(array('eyebrow' => 'Nuestro equipo', 'title' => 'Mesa directiva', 'center' => TRUE)); ?>
		<div class="row">
			<?php foreach ($board as $b): ?>
			<div class="col-sm-6 col-lg-3 mb-4"><?php $this->load->view('partials/card_board', array('b' => $b)); ?></div>
			<?php endforeach; ?>
		</div>
		<div class="text-center"><a href="<?= site_url('mesa-directiva') ?>" class="btn btn-outline-primary">Ver mesa directiva completa</a></div>
	</div>
</section>

<?php $this->load->view('partials/cta_membership', array('cta' => $blocks['cta_membership'])); ?>
