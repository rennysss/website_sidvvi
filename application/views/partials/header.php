<?php
$menu = array(
	array('home', 'Inicio', ''),
	array('about', 'Nosotros', 'nosotros', array(array('Quiénes somos', 'nosotros'), array('Mesa directiva', 'mesa-directiva'))),
	array('directory', 'Directorio', 'directorio'),
	array('library', 'Biblioteca', 'biblioteca'),
	array('congresses', 'Congresos', 'congresos'),
	array('news', 'Noticias', 'noticias'),
	array('membership', 'Membresía', 'membresia'),
	array('contact', 'Contacto', 'contacto'),
);
?>
<div class="topbar d-none d-lg-block">
	<div class="container d-flex justify-content-between align-items-center">
		<ul class="topbar-info list-inline mb-0">
			<li class="list-inline-item"><i class="fas fa-phone-alt"></i> <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', setting('phone'))) ?>"><?= e(setting('phone')) ?></a></li>
			<li class="list-inline-item"><i class="fas fa-envelope"></i> <a href="mailto:<?= e(setting('email')) ?>"><?= e(setting('email')) ?></a></li>
			<li class="list-inline-item"><i class="far fa-clock"></i> <?= e(setting('schedule')) ?></li>
		</ul>
		<?php $this->load->view('partials/social_icons', array('class' => 'social-sm')); ?>
	</div>
</div>

<header class="site-header" id="siteHeader">
	<nav class="navbar navbar-expand-lg navbar-light">
		<div class="container">
			<a class="navbar-brand" href="<?= site_url() ?>">
				<img src="<?= base_url('assets/img/logo.png') ?>" alt="<?= e(setting('site_full_name')) ?>" width="200" height="68">
			</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Abrir menú">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="mainNav">
				<ul class="navbar-nav ml-auto">
					<?php foreach ($menu as $item): $active = $active_menu === $item[0]; ?>
						<?php if ( ! empty($item[3])): ?>
						<li class="nav-item dropdown<?= $active ? ' active' : '' ?>">
							<a class="nav-link dropdown-toggle" href="<?= site_url($item[2]) ?>" id="dd-<?= $item[0] ?>" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?= $item[1] ?></a>
							<div class="dropdown-menu" aria-labelledby="dd-<?= $item[0] ?>">
								<?php foreach ($item[3] as $sub): ?>
								<a class="dropdown-item" href="<?= site_url($sub[1]) ?>"><?= $sub[0] ?></a>
								<?php endforeach; ?>
							</div>
						</li>
						<?php else: ?>
						<li class="nav-item<?= $active ? ' active' : '' ?>"><a class="nav-link" href="<?= site_url($item[2]) ?>"<?= $active ? ' aria-current="page"' : '' ?>><?= $item[1] ?></a></li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
				<a href="<?= link_url(setting('header_cta_url', 'constancias')) ?>" class="btn btn-accent btn-header ml-lg-3"><i class="fas fa-certificate mr-1"></i> <?= e(setting('header_cta_text', 'Constancias')) ?></a>
			</div>
		</div>
	</nav>
</header>
