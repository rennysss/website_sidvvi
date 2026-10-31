<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| SIDVVI · Configuración principal
| Los valores sensibles vienen de .env (ver .env.example)
*/
$config['base_url']   = env('BASE_URL', 'http://localhost:8080/');
$config['index_page'] = '';
$config['uri_protocol'] = 'REQUEST_URI';
$config['url_suffix'] = '';
$config['language']   = 'spanish';
$config['charset']    = 'UTF-8';
$config['enable_hooks'] = FALSE;
$config['subclass_prefix'] = 'MY_';
$config['composer_autoload'] = is_file(FCPATH.'vendor/autoload.php') ? FCPATH.'vendor/autoload.php' : FALSE;
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';
$config['enable_query_strings'] = FALSE;
$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';
$config['directory_trigger'] = 'd';
$config['allow_get_array'] = TRUE;

$config['log_threshold'] = ENVIRONMENT === 'production' ? 1 : 2;
$config['log_path'] = '';
$config['log_file_extension'] = '';
$config['log_file_permissions'] = 0644;
$config['log_date_format'] = 'Y-m-d H:i:s';
$config['error_views_path'] = '';
$config['cache_path'] = '';
$config['cache_query_string'] = FALSE;

$config['encryption_key'] = env('ENCRYPTION_KEY', '');

// Sesiones en base de datos (tabla ci_sessions)
$config['sess_driver'] = 'database';
$config['sess_cookie_name'] = 'sidvvi_session';
$config['sess_expiration'] = 7200;
$config['sess_save_path'] = 'ci_sessions';
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = TRUE;

$config['cookie_prefix']   = '';
$config['cookie_domain']   = '';
$config['cookie_path']     = '/';
$config['cookie_secure']   = ENVIRONMENT === 'production';
$config['cookie_httponly'] = TRUE;

$config['standardize_newlines'] = FALSE;
$config['global_xss_filtering'] = FALSE;

// CSRF (el token se envía también en peticiones Ajax, ver assets/js/app.js)
$config['csrf_protection'] = TRUE;
$config['csrf_token_name'] = 'csrf_sidvvi';
$config['csrf_cookie_name'] = 'csrf_sidvvi_cookie';
$config['csrf_expire'] = 7200;
$config['csrf_regenerate'] = FALSE;
$config['csrf_exclude_uris'] = array();

$config['compress_output'] = FALSE;
$config['time_reference'] = 'local';
$config['rewrite_short_tags'] = FALSE;
$config['proxy_ips'] = '';

// ---- Propias de SIDVVI ----
$config['admin_slug'] = 'admin-panel';
$config['congress_app_url'] = env('CONGRESS_APP_URL', 'https://sidvvi.com/congresos-app/');
$config['upload_max_kb'] = 8192;
