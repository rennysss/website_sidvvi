<?php
/**
 * Router SOLO para desarrollo con el servidor embebido de PHP:
 *   php -S localhost:8080 router.php
 * En producción (Apache) se usa .htaccess y este archivo no se usa.
 */
$uri  = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$file = __DIR__.$uri;

if (preg_match('#^/(application|vendor|database|\.env)#', $uri)) {
	http_response_code(403);
	exit('Prohibido');
}
if ($uri !== '/' && is_file($file)) {
	return FALSE; // servir archivo estático
}
$_SERVER['SCRIPT_NAME'] = '/index.php';
require __DIR__.'/index.php';
