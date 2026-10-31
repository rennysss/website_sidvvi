<section class="section">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-8">
				<div class="content-box text-center certificate-search">
					<span class="icon-circle icon-lg mb-3"><i class="fas fa-certificate"></i></span>
					<h2 class="h3">Descarga tu constancia</h2>
					<p class="text-muted">Ingresa el correo con el que te registraste al congreso. Tus constancias se generan en el sistema de congresos de SIDVVI.</p>
					<form action="<?= e($app_url) ?>constancias" method="get" class="form-row justify-content-center mt-3">
						<div class="col-md-8 mb-2"><input type="email" name="email" class="form-control form-control-lg" placeholder="tu@correo.com" required aria-label="Correo de registro"></div>
						<div class="col-md-4 mb-2"><button type="submit" class="btn btn-accent btn-lg btn-block">Buscar</button></div>
					</form>
				</div>
			</div>
		</div>
		<h3 class="h4 mt-5 mb-4 text-center">Constancias por evento</h3>
		<div class="row">
			<?php foreach ($congresses as $c): ?>
			<div class="col-md-6 col-lg-4 mb-3">
				<div class="doc-item">
					<span class="doc-icon"><i class="fas fa-award"></i></span>
					<div class="doc-body"><strong><?= e($c->name) ?></strong><small><?= rango_fechas_es($c->starts_at, $c->ends_at) ?></small></div>
					<a href="<?= e($c->certificates_url) ?>" class="btn btn-sm btn-outline-primary">Ir</a>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
