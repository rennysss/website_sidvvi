<!DOCTYPE html>
<html lang="es-MX">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex,nofollow">
	<meta name="csrf-name" content="<?= $this->security->get_csrf_token_name() ?>">
	<meta name="csrf-hash" content="<?= $this->security->get_csrf_hash() ?>">
	<title><?= e($title) ?> · Panel SIDVVI</title>
	<link rel="icon" href="<?= base_url('assets/img/favicon.ico') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/vendor/bootstrap/bootstrap.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/vendor/fontawesome/css/all.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/admin.css?v=1') ?>">
</head>
<body class="auth-body">
	<div class="auth-wrap">
		<div class="auth-card">
			<div class="text-center mb-4">
				<img src="<?= base_url('assets/img/logo.png') ?>" alt="SIDVVI" class="auth-logo">
				<p class="text-muted small mt-2 mb-0">Panel de control</p>
			</div>
			<?= $content ?>
		</div>
		<p class="text-center small mt-3"><a href="<?= site_url() ?>" class="text-white-50"><i class="fas fa-arrow-left"></i> Volver a sidvvi.com</a></p>
	</div>
	<?php if ( ! empty($extra_js)): foreach ((array) $extra_js as $src): ?>
	<script src="<?= $src ?>"></script>
	<?php endforeach; endif; ?>
</body>
</html>
