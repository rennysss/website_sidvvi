<?php
/**
 * INICIO — estructura inspirada en la plantilla Cityvile:
 * 1 Hero/banners · 2 Mensaje de la presidencia · 3 Áreas · 4 Cifras · 5 Próximos congresos
 * 6 Mesa directiva · 7 Accesos rápidos + Documentos · 8 Biblioteca · 9 Instagram · 10 CTA membresía
 */
$pm = $blocks['president_message'];
list($sign_name, $sign_role) = array_pad(explode('|', (string) $pm->extra), 2, '');
?>

<!-- 1. HERO -->
<section class="hero">
	<div id="heroCarousel" class="carousel slide carousel-fade" data-ride="carousel" data-interval="6500">
		<?php if (count($banners) > 1): ?>
		<ol class="carousel-indicators">
			<?php foreach ($banners as $i => $b): ?>
			<li data-target="#heroCarousel" data-slide-to="<?= $i ?>"<?= $i === 0 ? ' class="active"' : '' ?>></li>
			<?php endforeach; ?>
		</ol>
		<?php endif; ?>
		<div class="carousel-inner">
			<?php foreach ($banners as $i => $b): ?>
			<div class="carousel-item<?= $i === 0 ? ' active' : '' ?>">
				<picture>
					<?php if ($b->image_mobile): ?><source media="(max-width: 767px)" srcset="<?= img_url($b->image_mobile) ?>"><?php endif; ?>
					<img src="<?= img_url($b->image_desktop) ?>" alt="" class="hero-img"<?= $i ? ' loading="lazy"' : '' ?>>
				</picture>
				<div class="hero-overlay"></div>
				<div class="hero-content">
					<div class="container">
						<div class="hero-text">
							<?php if ($b->eyebrow): ?><span class="eyebrow eyebrow-light"><?= e($b->eyebrow) ?></span><?php endif; ?>
							<?php if ($i === 0): ?><h1><?= e($b->title) ?></h1><?php else: ?><h2 class="h1"><?= e($b->title) ?></h2><?php endif; ?>
							<?php if ($b->subtitle): ?><p><?= e($b->subtitle) ?></p><?php endif; ?>
							<div class="hero-buttons">
								<?php if ($b->button_text): ?><a href="<?= link_url($b->button_url) ?>" class="btn btn-accent btn-lg"><?= e($b->button_text) ?></a><?php endif; ?>
								<?php if ($b->button2_text): ?><a href="<?= link_url($b->button2_url) ?>" class="btn btn-outline-light btn-lg"><?= e($b->button2_text) ?></a><?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
		<?php if (count($banners) > 1): ?>
		<a class="carousel-control-prev" href="#heroCarousel" role="button" data-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="sr-only">Anterior</span></a>
		<a class="carousel-control-next" href="#heroCarousel" role="button" data-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="sr-only">Siguiente</span></a>
		<?php endif; ?>
	</div>
</section>

<!-- 2. MENSAJE DE LA PRESIDENCIA -->
<section class="section">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-5 mb-5 mb-lg-0">
				<div class="about-media">
					<img src="<?= img_url($pm->image, 'assets/img/placeholders/about.jpg') ?>" alt="<?= e($sign_name) ?>" class="img-fluid rounded-lg" loading="lazy" width="900" height="1000">
					<div class="about-badge">
						<strong><?= max(1, (int) date('Y') - (int) setting('founded_year', date('Y'))) ?>+</strong>
						<span>años impulsando<br>la ciencia</span>
					</div>
				</div>
			</div>
			<div class="col-lg-7 pl-lg-5">
				<?php section_title(array('eyebrow' => $pm->eyebrow, 'title' => $pm->title)); ?>
				<div class="rich-text check-list"><?= $pm->body ?></div>
				<div class="signature">
					<div>
						<strong><?= e($sign_name) ?></strong>
						<span><?= e($sign_role) ?></span>
					</div>
					<a href="<?= site_url('nosotros') ?>" class="btn btn-primary ml-auto">Conócenos</a>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- 3. ÁREAS -->
<section class="section bg-soft">
	<div class="container">
		<div class="d-md-flex justify-content-between align-items-end mb-4">
			<?php section_title(array('eyebrow' => 'Lo que hacemos', 'title' => 'Áreas de investigación y docencia')); ?>
			<a href="<?= site_url('nosotros') ?>" class="btn btn-outline-primary mb-4">Ver todo</a>
		</div>
		<div class="row">
			<?php foreach ($areas as $a): ?>
			<div class="col-md-6 col-lg-4 mb-4">
				<a href="<?= link_url($a->url) ?>" class="card card-service h-100">
					<div class="card-body">
						<span class="icon-circle"><i class="<?= e($a->icon) ?>"></i></span>
						<h3 class="h5"><?= e($a->title) ?></h3>
						<p><?= e($a->description) ?></p>
						<span class="link-more">Saber más <i class="fas fa-arrow-right"></i></span>
					</div>
				</a>
			</div>
			<?php endforeach; ?>
		</div>
		<div class="info-strip">
			<div class="info-strip-icon"><i class="fas fa-user-md"></i></div>
			<div class="info-strip-text">
				<strong>¿Eres profesional de la salud?</strong>
				<span>Únete a SIDVVI y forma parte del directorio de especialistas.</span>
			</div>
			<a href="<?= site_url('membresia') ?>" class="btn btn-accent">Hazte socio</a>
		</div>
	</div>
</section>

<!-- 4. CIFRAS -->
<?php $this->load->view('partials/stats'); ?>

<!-- 5. PRÓXIMOS CONGRESOS -->
<section class="section">
	<div class="container">
		<?php section_title(array('eyebrow' => 'Agenda', 'title' => 'Próximos congresos y eventos', 'center' => TRUE, 'text' => 'Participa en nuestros congresos, cursos y simposios con valor curricular.')); ?>
		<div class="row">
			<?php foreach ($congresses as $c): ?>
			<div class="col-md-6 col-lg-4 mb-4"><?php $this->load->view('partials/card_congress', array('c' => $c)); ?></div>
			<?php endforeach; ?>
			<?php if ( ! $congresses): ?><div class="col-12 text-center text-muted">Muy pronto anunciaremos nuevos eventos.</div><?php endif; ?>
		</div>
		<div class="text-center mt-2"><a href="<?= site_url('congresos') ?>" class="btn btn-primary">Ver todos los congresos</a></div>
	</div>
</section>

<!-- 6. MESA DIRECTIVA -->
<section class="section bg-soft">
	<div class="container">
		<?php section_title(array('eyebrow' => 'Nuestro equipo', 'title' => 'Mesa directiva', 'center' => TRUE)); ?>
		<div class="row">
			<?php foreach ($board as $b): ?>
			<div class="col-sm-6 col-lg-3 mb-4"><?php $this->load->view('partials/card_board', array('b' => $b)); ?></div>
			<?php endforeach; ?>
		</div>
		<div class="text-center"><a href="<?= site_url('mesa-directiva') ?>" class="btn btn-outline-primary">Conoce a toda la mesa directiva</a></div>
	</div>
</section>

<!-- 7. ACCESOS RÁPIDOS + DOCUMENTOS -->
<section class="section">
	<div class="container">
		<div class="row">
			<div class="col-lg-6 mb-5 mb-lg-0">
				<?php section_title(array('eyebrow' => 'Accesos rápidos', 'title' => '¿Qué necesitas hoy?')); ?>
				<ul class="quick-links list-unstyled">
					<?php foreach ($quick_links as $q): ?>
					<li><a href="<?= link_url($q->url) ?>"><i class="<?= e($q->icon) ?>"></i> <span><?= e($q->title) ?></span> <i class="fas fa-chevron-right arrow"></i></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="col-lg-6">
				<?php section_title(array('eyebrow' => 'Documentos', 'title' => 'Avisos y documentos')); ?>
				<div class="doc-list">
					<?php foreach ($documents as $d): ?>
					<div class="doc-item">
						<span class="doc-icon"><i class="far fa-file-pdf"></i></span>
						<div class="doc-body">
							<strong><?= e($d->title) ?></strong>
							<small><?= e($d->description) ?><?= $d->published_at ? ' · '.fecha_es($d->published_at, 'short') : '' ?></small>
						</div>
						<?php if ($d->file_path): ?>
						<a href="<?= base_url($d->file_path) ?>" class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener" download>Descargar</a>
						<?php else: ?>
						<span class="btn btn-sm btn-outline-secondary disabled" title="Archivo pendiente">Próximamente</span>
						<?php endif; ?>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- 8. BIBLIOTECA -->
<section class="section bg-soft">
	<div class="container">
		<div class="d-md-flex justify-content-between align-items-end mb-4">
			<?php section_title(array('eyebrow' => 'Biblioteca Virtual', 'title' => 'Publicaciones recientes')); ?>
			<a href="<?= site_url('biblioteca') ?>" class="btn btn-outline-primary mb-4">Ir a la biblioteca</a>
		</div>
		<div class="row">
			<?php foreach ($articles as $a): ?>
			<div class="col-md-6 col-lg-4 mb-4"><?php $this->load->view('partials/card_article', array('a' => $a)); ?></div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- 9. INSTAGRAM -->
<section class="section">
	<div class="container">
		<?php section_title(array('eyebrow' => 'Noticias', 'title' => 'Síguenos en Instagram', 'center' => TRUE, 'text' => '@'.setting('instagram_user', 'sidvvi'))); ?>
		<div class="row no-gutters insta-grid">
			<?php foreach ($instagram as $p): ?>
			<div class="col-4 col-md-2">
				<a href="<?= e($p->permalink) ?>" target="_blank" rel="noopener" class="insta-item" title="<?= e(str_limit($p->caption, 80)) ?>">
					<img src="<?= img_url($p->media_url) ?>" alt="<?= e(str_limit($p->caption, 80)) ?>" loading="lazy" width="600" height="600">
					<span><i class="fab fa-instagram"></i></span>
				</a>
			</div>
			<?php endforeach; ?>
		</div>
		<div class="text-center mt-4"><a href="<?= site_url('noticias') ?>" class="btn btn-primary">Ver todas las noticias</a></div>
	</div>
</section>

<!-- 10. CTA -->
<?php $this->load->view('partials/cta_membership', array('cta' => $blocks['cta_membership'])); ?>
