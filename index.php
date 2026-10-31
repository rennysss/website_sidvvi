<?php
/**
 * SIDVVI — Front controller (CodeIgniter 3)
 * https://sidvvi.com  ·  Panel: https://sidvvi.com/admin-panel
 */

// ---------------------------------------------------------------
// Cargar variables de entorno desde .env (sin dependencias)
// ---------------------------------------------------------------
$__env_file = __DIR__.'/.env';
if (is_file($__env_file)) {
	foreach (file($__env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $__line) {
		$__line = trim($__line);
		if ($__line === '' || $__line[0] === '#' || strpos($__line, '=') === FALSE) continue;
		list($__k, $__v) = array_map('trim', explode('=', $__line, 2));
		$__v = trim($__v, "\"'");
		if (getenv($__k) === FALSE) {
			putenv("$__k=$__v");
			$_ENV[$__k] = $__v;
		}
	}
}
unset($__env_file, $__line, $__k, $__v);

/** Lee una variable de entorno con valor por defecto */
function env($key, $default = NULL)
{
	$v = getenv($key);
	if ($v === FALSE) return $default;
	switch (strtolower($v)) {
		case 'true':  return TRUE;
		case 'false': return FALSE;
		case 'null':  return NULL;
	}
	return $v;
}

define('ENVIRONMENT', env('APP_ENV', 'development'));
date_default_timezone_set(env('APP_TIMEZONE', 'America/Mexico_City'));

switch (ENVIRONMENT)
{
	case 'development':
		// CI3 en PHP 8.2+ emite avisos de propiedades dinámicas: se ocultan los "deprecated"
		error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
		ini_set('display_errors', 1);
	break;
	case 'testing':
	case 'production':
		ini_set('display_errors', 0);
		error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
	break;
	default:
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'El entorno de la aplicación no está configurado correctamente (APP_ENV).';
		exit(1);
}

// CodeIgniter se instala con Composer:  composer install
$system_path = 'vendor/codeigniter/framework/system';
$application_folder = 'application';
$view_folder = '';

// --------------------------------------------------------------------
// NO EDITAR DEBAJO DE ESTA LÍNEA (bootstrap estándar de CodeIgniter 3)
// --------------------------------------------------------------------
if (defined('STDIN')) chdir(dirname(__FILE__));

if (($_temp = realpath($system_path)) !== FALSE) {
	$system_path = $_temp.DIRECTORY_SEPARATOR;
} else {
	$system_path = strtr(rtrim($system_path, '/\\'), '/\\', DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
}

if ( ! is_dir($system_path)) {
	header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
	echo 'No se encontró CodeIgniter. Ejecuta "composer install" en la raíz del proyecto.';
	exit(3);
}

define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('BASEPATH', $system_path);
define('FCPATH', dirname(__FILE__).DIRECTORY_SEPARATOR);
define('SYSDIR', basename(BASEPATH));

if (is_dir($application_folder)) {
	$application_folder = realpath($application_folder) ?: $application_folder;
} else {
	header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
	echo 'La carpeta application no existe.';
	exit(3);
}
define('APPPATH', $application_folder.DIRECTORY_SEPARATOR);

if ( ! isset($view_folder[0]) && is_dir(APPPATH.'views'.DIRECTORY_SEPARATOR)) {
	$view_folder = APPPATH.'views';
}
define('VIEWPATH', rtrim($view_folder, '/\\').DIRECTORY_SEPARATOR);

require_once BASEPATH.'core/CodeIgniter.php';
