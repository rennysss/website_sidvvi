<?php
$uri = $this->uri->segment(2) ?: 'dashboard';
$nav = array(
	array('header', 'General'),
	array('dashboard', 'Panel', 'fas fa-tachometer-alt'),
	array('header', 'Inicio'),
	array('banners', 'Banners', 'fas fa-images'),
	array('blocks', 'Textos del sitio', 'fas fa-align-left'),
	array('areas', 'Áreas de trabajo', 'fas fa-th-large'),
	array('quick_links', 'Accesos rápidos', 'fas fa-link'),
	array('documents', 'Documentos', 'fas fa-file-pdf'),
	array('header', 'Sociedad'),
	array('board', 'Mesa directiva', 'fas fa-user-tie'),
	array('members', 'Directorio de socios', 'fas fa-address-book'),
	array('specialties', 'Especialidades', 'fas fa-stethoscope'),
	array('member_types', 'Tipos de socio', 'fas fa-id-badge'),
	array('memberships', 'Solicitudes de membresía', 'fas fa-user-plus', 'memberships'),
	array('header', 'Contenido'),
	array('articles', 'Biblioteca Virtual', 'fas fa-book-medical'),
	array('categories', 'Categorías', 'fas fa-folder'),
	array('tags', 'Temas', 'fas fa-tags'),
	array('instagram', 'Noticias (Instagram)', 'fab fa-instagram'),
	array('congresses', 'Congresos', 'fas fa-calendar-alt'),
	array('header', 'Comunicación'),
	array('messages', 'Mensajes de contacto', 'fas fa-envelope', 'messages'),
	array('notifications', 'Notificaciones', 'fas fa-bell'),
	array('reports', 'Reportes', 'fas fa-chart-bar'),
	array('social_links', 'Redes sociales', 'fas fa-share-alt'),
);
if ($is_super) {
	$nav[] = array('header', 'Administración');
	$nav[] = array('users', 'Usuarios', 'fas fa-users-cog');
	$nav[] = array('instagram_connect', 'Conexión Instagram', 'fab fa-instagram');
	$nav[] = array('settings', 'Configuración', 'fas fa-cog');
}
$roles = array('superadmin' => 'Superadministrador', 'editor' => 'Editor', 'viewer' => 'Consulta');
?>
<!DOCTYPE html>
<html lang="es-MX">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex,nofollow">
	<meta name="csrf-name" content="<?= $this->security->get_csrf_token_name() ?>">
	<meta name="csrf-hash" content="<?= $this->security->get_csrf_hash() ?>">
	<meta name="vapid-public" content="<?= e(env('VAPID_PUBLIC', '')) ?>">
	<meta name="push-subscribe" content="<?= admin_url('push/subscribe') ?>">
	<meta name="sw-url" content="<?= base_url('service-worker.js') ?>">
	<meta name="sw-scope" content="<?= admin_url() ?>/">
	<title><?= e($page_title ?? 'Panel') ?> · Panel SIDVVI</title>
	<link rel="icon" href="<?= base_url('assets/img/favicon.ico') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/vendor/bootstrap/bootstrap.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/vendor/fontawesome/css/all.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/vendor/summernote/summernote-bs4.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/admin.css?v=1') ?>">
</head>
<body class="admin-body">
<aside class="admin-sidebar" id="adminSidebar">
	<a href="<?= admin_url('dashboard') ?>" class="sidebar-brand"><img src="<?= base_url('assets/img/logo-white.png') ?>" alt="SIDVVI"></a>
	<nav class="sidebar-nav">
		<?php foreach ($nav as $n): ?>
			<?php if ($n[0] === 'header'): ?>
			<div class="nav-header"><?= $n[1] ?></div>
			<?php else: ?>
			<a href="<?= admin_url($n[0]) ?>" class="<?= $uri === $n[0] ? 'active' : '' ?>">
				<i class="<?= $n[2] ?> fa-fw"></i> <span><?= $n[1] ?></span>
				<?php if ( ! empty($n[3]) && $nav_counts[$n[3]]): ?><span class="badge badge-pill badge-danger ml-auto"><?= $nav_counts[$n[3]] ?></span><?php endif; ?>
			</a>
			<?php endif; ?>
		<?php endforeach; ?>
	</nav>
</aside>
<div class="sidebar-backdrop" data-toggle-sidebar></div>

<div class="admin-main">
	<header class="admin-topbar">
		<button class="btn btn-link text-dark d-lg-none mr-2 p-0" data-toggle-sidebar aria-label="Menú"><i class="fas fa-bars fa-lg"></i></button>
		<h1 class="h5 mb-0"><?= e($page_title ?? '') ?></h1>
		<div class="ml-auto d-flex align-items-center">
			<button type="button" class="btn btn-sm btn-light mr-2" id="pushToggle" hidden title="Activar notificaciones"><i class="fas fa-bell"></i></button>
			<a href="<?= site_url() ?>" target="_blank" class="btn btn-sm btn-light mr-2 d-none d-sm-inline-block"><i class="fas fa-external-link-alt"></i> Ver sitio</a>
			<div class="dropdown">
				<a href="#" class="dropdown-toggle text-dark" data-toggle="dropdown"><span class="avatar-sm"><?= e(mb_substr($admin->name, 0, 1)) ?></span> <span class="d-none d-md-inline"><?= e($admin->name) ?></span></a>
				<div class="dropdown-menu dropdown-menu-right">
					<span class="dropdown-item-text small text-muted"><?= e($admin->email) ?><br><?= $roles[$admin->role] ?></span>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="<?= admin_url('seguridad') ?>"><i class="fas fa-fingerprint"></i> Este equipo</a>
					<a class="dropdown-item" href="<?= admin_url('logout') ?>"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
				</div>
			</div>
		</div>
	</header>
	<main class="admin-content">
		<?php foreach (array('success', 'error') as $t): if ($m = $this->session->flashdata($t)): ?>
		<div class="alert alert-<?= $t === 'error' ? 'danger' : 'success' ?> alert-dismissible fade show"><?= e($m) ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>
		<?php endif; endforeach; ?>
		<?= $content ?>
	</main>
</div>

<script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/summernote/summernote-bs4.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/summernote/summernote-es-ES.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/chartjs/chart.min.js') ?>"></script>
<script src="<?= base_url('assets/js/admin.js?v=2') ?>"></script>
<?php if ( ! empty($extra_js)): foreach ((array) $extra_js as $src): ?>
<script src="<?= $src ?>"></script>
<?php endforeach; endif; ?>
</body>
</html>
