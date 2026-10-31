<footer class="site-footer">
	<div class="footer-top">
		<div class="container">
			<div class="row">
				<div class="col-lg-4 col-md-6 mb-4">
					<img src="<?= base_url('assets/img/logo-white.png') ?>" alt="SIDVVI" class="footer-logo mb-3" width="220" height="75">
					<p><?= e(setting('footer_about')) ?></p>
					<?php $this->load->view('partials/social_icons', array('class' => 'social-footer')); ?>
				</div>
				<div class="col-lg-2 col-md-6 col-6 mb-4">
					<h5>La sociedad</h5>
					<ul class="footer-links">
						<li><a href="<?= site_url('nosotros') ?>">Quiénes somos</a></li>
						<li><a href="<?= site_url('mesa-directiva') ?>">Mesa directiva</a></li>
						<li><a href="<?= site_url('membresia') ?>">Membresía</a></li>
						<li><a href="<?= site_url('directorio') ?>">Directorio</a></li>
					</ul>
				</div>
				<div class="col-lg-2 col-md-6 col-6 mb-4">
					<h5>Recursos</h5>
					<ul class="footer-links">
						<li><a href="<?= site_url('biblioteca') ?>">Biblioteca Virtual</a></li>
						<li><a href="<?= site_url('congresos') ?>">Congresos</a></li>
						<li><a href="<?= site_url('constancias') ?>">Constancias</a></li>
						<li><a href="<?= site_url('noticias') ?>">Noticias</a></li>
					</ul>
				</div>
				<div class="col-lg-4 col-md-6 mb-4">
					<h5>Contacto</h5>
					<ul class="footer-contact">
						<li><i class="fas fa-map-marker-alt"></i> <?= e(setting('address')) ?></li>
						<li><i class="fas fa-phone-alt"></i> <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', setting('phone'))) ?>"><?= e(setting('phone')) ?></a></li>
						<li><i class="fas fa-envelope"></i> <a href="mailto:<?= e(setting('email')) ?>"><?= e(setting('email')) ?></a></li>
						<li><i class="far fa-clock"></i> <?= e(setting('schedule')) ?></li>
					</ul>
					<a href="<?= site_url('contacto') ?>" class="btn btn-outline-light btn-sm mt-2">Escríbenos <i class="fas fa-arrow-right ml-1"></i></a>
				</div>
			</div>
		</div>
	</div>
	<div class="footer-bottom">
		<div class="container d-md-flex justify-content-between">
			<p class="mb-1 mb-md-0">&copy; <?= date('Y') ?> <?= e(setting('site_full_name')) ?>. Todos los derechos reservados.</p>
			<p class="mb-0"><a href="<?= site_url('aviso-de-privacidad') ?>">Aviso de privacidad</a></p>
		</div>
	</div>
</footer>
