<section class="cta-band" style="background-image:url('<?= img_url($cta->image, 'assets/img/placeholders/page-header.jpg') ?>')">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-8">
				<span class="eyebrow eyebrow-light"><?= e($cta->eyebrow) ?></span>
				<h2><?= e($cta->title) ?></h2>
				<div class="cta-text"><?= $cta->body ?></div>
			</div>
			<div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
				<a href="<?= site_url('membresia') ?>" class="btn btn-accent btn-lg">Solicitar membresía</a>
			</div>
		</div>
	</div>
</section>
