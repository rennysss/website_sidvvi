<!DOCTYPE html>
<html lang="es-MX">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<?= $seo->render() ?>
	<meta name="theme-color" content="#23477A">
	<link rel="manifest" href="<?= base_url('manifest.json') ?>">
	<link rel="icon" href="<?= base_url('assets/img/favicon.ico') ?>">
	<link rel="apple-touch-icon" href="<?= base_url('assets/img/icon-192.png') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/vendor/bootstrap/bootstrap.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/vendor/fontawesome/css/all.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/sidvvi.css?v=1') ?>">
	<meta name="csrf-name" content="<?= $this->security->get_csrf_token_name() ?>">
	<meta name="csrf-hash" content="<?= $this->security->get_csrf_hash() ?>">
</head>
<body>
<a class="skip-link" href="#main">Saltar al contenido</a>

<?php $this->load->view('partials/header'); ?>

<main id="main">
	<?php if ( ! empty($page_header)) $this->load->view('partials/page_header'); ?>
	<?= $content ?>
</main>

<?php $this->load->view('partials/footer'); ?>

<a href="#" class="back-to-top" aria-label="Volver arriba"><i class="fas fa-arrow-up"></i></a>

<script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<?php if (env('RECAPTCHA_SITE_KEY') && env('RECAPTCHA_SECRET')): ?>
<script src="https://www.google.com/recaptcha/api.js?render=<?= e(env('RECAPTCHA_SITE_KEY')) ?>"></script>
<?php endif; ?>
<script>window.SIDVVI = { baseUrl: "<?= base_url() ?>", recaptcha: "<?= e(env('RECAPTCHA_SITE_KEY', '')) ?>" };</script>
<script src="<?= base_url('assets/js/app.js?v=1') ?>"></script>
</body>
</html>
