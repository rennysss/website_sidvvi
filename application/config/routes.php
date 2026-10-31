<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| SIDVVI · Rutas
| Sitio público:   https://sidvvi.com
| Panel:           https://sidvvi.com/admin-panel   (controladores en controllers/admin/)
*/
$route['default_controller']   = 'home';
$route['404_override']         = 'errors/not_found';
$route['translate_uri_dashes'] = FALSE;

// ---------- Sitio público ----------
$route['nosotros']                       = 'pages/about';
$route['mesa-directiva']                 = 'pages/board';
$route['aviso-de-privacidad']            = 'pages/privacy';

$route['directorio']                     = 'directory_members/index';
$route['directorio/buscar']              = 'directory_members/search';
$route['directorio/(:any)']              = 'directory_members/show/$1';

$route['biblioteca']                     = 'library/index';
$route['biblioteca/categoria/(:any)']    = 'library/index/$1';
$route['biblioteca/descargar/(:num)']    = 'library/download/$1';
$route['biblioteca/(:any)']              = 'library/show/$1';

$route['congresos']                      = 'congresses/index';
$route['congresos/(:any)']               = 'congresses/show/$1';

$route['noticias']                       = 'news/index';

$route['membresia']                      = 'membership/index';
$route['membresia/enviar']['post']       = 'membership/send';

$route['constancias']                    = 'certificates/index';

$route['contacto']                       = 'contact/index';
$route['contacto/enviar']['post']        = 'contact/send';

$route['sitemap\.xml']                   = 'sitemap/index';
$route['manifest\.json']                 = 'manifest/index';
$route['verificar/(:any)']               = 'verify/show/$1';

// ---------- Panel de control (/admin-panel) ----------
$route['admin-panel']                    = 'admin/access/login';
$route['admin-panel/login']['post']      = 'admin/access/do_login';
$route['admin-panel/logout']             = 'admin/access/logout';
$route['admin-panel/recuperar']          = 'admin/access/forgot';
$route['admin-panel/restablecer/(:any)'] = 'admin/access/reset/$1';
$route['admin-panel/(.+)']               = 'admin/$1';

// El acceso directo a /admin queda bloqueado
$route['admin']                          = 'errors/not_found';
$route['admin/(.+)']                     = 'errors/not_found';
