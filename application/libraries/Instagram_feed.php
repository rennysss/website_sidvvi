<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Baja las publicaciones de Instagram (cuenta Business o Creator)
 * y las deja en instagram_cache. Si la API falla, el sitio sigue con la caché.
 */
class Instagram_feed
{
	protected $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->library('secrets');
	}

	/** @return array{ok:bool,count:int,message:string} */
	public function refresh()
	{
		$this->maybe_refresh_token();
		$token = $this->CI->secrets->get('instagram_token');
		if ($token === '') {
			return array('ok' => FALSE, 'count' => 0, 'message' => 'Aún no hay un token de Instagram. Lo configura el superadministrador.');
		}

		$url = 'https://graph.instagram.com/me/media?fields=id,caption,media_type,media_url,thumbnail_url,permalink,timestamp&limit=24&access_token='.rawurlencode($token);
		$res = $this->request($url);
		if ($res === NULL) {
			return array('ok' => FALSE, 'count' => 0, 'message' => 'Instagram no respondió. El sitio sigue mostrando lo último guardado.');
		}
		if ( ! empty($res['error']['message'])) {
			log_message('error', 'Instagram: '.$res['error']['message']);
			return array('ok' => FALSE, 'count' => 0, 'message' => 'Instagram rechazó la consulta. Revisa el token en Conexión Instagram.');
		}

		$n = 0;
		foreach ((array) ($res['data'] ?? array()) as $item) {
			if (empty($item['id']) || empty($item['permalink'])) continue;
			$image = ($item['media_type'] ?? '') === 'VIDEO'
				? ($item['thumbnail_url'] ?? '')
				: ($item['media_url'] ?? '');
			if ($image === '') continue;
			$row = array(
				'media_type' => substr((string) ($item['media_type'] ?? 'IMAGE'), 0, 30),
				'media_url'  => $image,
				'permalink'  => $item['permalink'],
				'caption'    => $item['caption'] ?? '',
				'posted_at'  => date('Y-m-d H:i:s', strtotime($item['timestamp'] ?? 'now')),
				'fetched_at' => date('Y-m-d H:i:s'),
			);
			$exists = $this->CI->db->get_where('instagram_cache', array('ig_media_id' => $item['id']))->row();
			if ($exists) {
				$this->CI->db->where('id', $exists->id)->update('instagram_cache', $row);
			} else {
				$row['ig_media_id'] = $item['id'];
				$this->CI->db->insert('instagram_cache', $row);
			}
			$n++;
		}
		return array('ok' => TRUE, 'count' => $n, 'message' => 'Se sincronizaron '.$n.' publicaciones.');
	}

	/** Renueva el token de larga duración si le quedan menos de 10 días */
	public function maybe_refresh_token()
	{
		$row = $this->CI->secrets->row('instagram_token');
		$token = $this->CI->secrets->get('instagram_token');
		if ($token === '' || ! $row || empty($row->expires_at)) return;
		if (strtotime($row->expires_at) - time() > 10 * 86400) return;

		$url = 'https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token='.rawurlencode($token);
		$res = $this->request($url);
		if (empty($res['access_token'])) {
			log_message('error', 'Instagram: no se pudo renovar el token.');
			return;
		}
		$seconds = (int) ($res['expires_in'] ?? (60 * 86400));
		$this->CI->secrets->set('instagram_token', $res['access_token'], date('Y-m-d H:i:s', time() + $seconds));
		log_message('info', 'Instagram: token renovado.');
	}

	protected function request($url)
	{
		$ch = curl_init($url);
		curl_setopt_array($ch, array(
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_TIMEOUT        => 10,
			CURLOPT_CONNECTTIMEOUT => 5,
			CURLOPT_FOLLOWLOCATION => FALSE,
			CURLOPT_PROTOCOLS      => CURLPROTO_HTTPS,
		));
		$body = curl_exec($ch);
		$code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$error = curl_error($ch);
		curl_close($ch);
		if ($body === FALSE || $code >= 500) {
			log_message('error', 'Instagram: HTTP '.$code.' '.$error);
			return NULL;
		}
		$json = json_decode($body, TRUE);
		return is_array($json) ? $json : NULL;
	}
}
