<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** URL del panel de control: admin_url('banners') → https://sidvvi.com/admin-panel/banners */
function admin_url($path = '')
{
	$slug = get_instance()->config->item('admin_slug') ?: 'admin-panel';
	return base_url(trim($slug.'/'.ltrim($path, '/'), '/'));
}

/** Valor de configuración (tabla settings) */
function setting($key, $default = '')
{
	$CI = get_instance();
	return isset($CI->Setting_model) ? $CI->Setting_model->get($key, $default) : $default;
}

/** Escapa para HTML (atajo) */
function e($str)
{
	return html_escape((string) $str);
}

/** URL de una imagen guardada en BD, con imagen por defecto */
function img_url($path, $fallback = 'assets/img/placeholders/article-1.jpg')
{
	$path = trim((string) $path);
	if ($path === '') $path = $fallback;
	if (preg_match('#^https?://#', $path)) return $path;
	return base_url($path);
}

/** Convierte un enlace guardado en BD (relativo o absoluto) en URL completa */
function link_url($url)
{
	$url = trim((string) $url);
	if ($url === '' || $url === '#') return '#';
	if (preg_match('#^(https?:|mailto:|tel:|\#)#', $url)) return $url;
	return site_url($url);
}

/** Genera slug en español */
function slugify($text)
{
	$text = mb_strtolower(trim($text), 'UTF-8');
	$text = strtr($text, array('á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','ü'=>'u','ñ'=>'n','à'=>'a','è'=>'e','ì'=>'i','ò'=>'o','ù'=>'u'));
	$text = preg_replace('/[^a-z0-9]+/', '-', $text);
	return trim($text, '-');
}

/** Fecha en español: 18 de marzo de 2027 */
function fecha_es($date, $format = 'long')
{
	if (empty($date)) return '';
	$ts = is_numeric($date) ? (int) $date : strtotime($date);
	$months = array(1=>'enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre');
	$short  = array(1=>'Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic');
	$d = date('j', $ts); $m = (int) date('n', $ts); $y = date('Y', $ts);
	switch ($format) {
		case 'day':   return date('d', $ts);
		case 'month': return $short[$m];
		case 'short': return $d.' '.$short[$m].' '.$y;
		default:      return $d.' de '.$months[$m].' de '.$y;
	}
}

/** Rango de fechas de un evento: 18 al 20 de marzo de 2027 */
function rango_fechas_es($start, $end = NULL)
{
	if (empty($end) || $end === $start) return fecha_es($start);
	$s = strtotime($start); $e = strtotime($end);
	if (date('Y-m', $s) === date('Y-m', $e)) {
		return date('j', $s).' al '.fecha_es($end);
	}
	return fecha_es($start).' al '.fecha_es($end);
}

/** Etiqueta legible de estados */
function status_label($status)
{
	$map = array(
		'draft' => array('Borrador', 'secondary'), 'published' => array('Publicado', 'success'),
		'archived' => array('Archivado', 'dark'), 'new' => array('Nuevo', 'primary'),
		'read' => array('Leído', 'info'), 'replied' => array('Respondido', 'success'),
		'spam' => array('Spam', 'danger'), 'in_review' => array('En revisión', 'warning'),
		'approved' => array('Aprobada', 'success'), 'rejected' => array('Rechazada', 'danger'),
	);
	$s = isset($map[$status]) ? $map[$status] : array(ucfirst($status), 'secondary');
	return '<span class="badge badge-'.$s[1].'">'.$s[0].'</span>';
}

/** Responde JSON estándar { ok, message, data } */
function json_response($ok, $message = '', $data = NULL, $status = 200)
{
	$CI = get_instance();
	$CI->output->set_status_header($status)
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode(array(
			'ok' => (bool) $ok, 'message' => $message, 'data' => $data,
			'csrf' => array('name' => $CI->security->get_csrf_token_name(), 'hash' => $CI->security->get_csrf_hash()),
		), JSON_UNESCAPED_UNICODE));
}

/** Registra acción en bitácora */
function audit_log($action, $entity, $entity_id = NULL, $details = NULL)
{
	$CI = get_instance();
	$CI->db->insert('audit_logs', array(
		'user_id'   => $CI->session->userdata('admin_id'),
		'action'    => $action,
		'entity'    => $entity,
		'entity_id' => $entity_id,
		'details'   => is_array($details) ? json_encode($details, JSON_UNESCAPED_UNICODE) : $details,
		'ip_address'=> $CI->input->ip_address(),
	));
}

/**
 * Encabezado de sección (eyebrow + título + texto).
 * Se usa función en lugar de load->view directo porque CodeIgniter conserva
 * las variables de vistas anteriores y se "heredarían" textos entre secciones.
 */
function section_title(array $opts)
{
	$defaults = array('eyebrow' => '', 'title' => '', 'text' => '', 'center' => FALSE, 'light' => FALSE);
	get_instance()->load->view('partials/section_title', array_merge($defaults, $opts));
}

/** Recorta texto a ~$n caracteres respetando palabras (con «…» literal, apto para escapar) */
function str_limit($str, $n = 100)
{
	get_instance()->load->helper('text');
	return character_limiter((string) $str, $n, '…');
}
