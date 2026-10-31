<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Suscripción del navegador del administrador a los avisos del panel */
class Push extends Admin_Controller
{
	protected $min_role = 'viewer';

	public function subscribe()
	{
		if ($this->input->method() !== 'post') show_404();
		$endpoint = trim((string) $this->input->post('endpoint', FALSE));
		$p256dh = trim((string) $this->input->post('p256dh', FALSE));
		$auth = trim((string) $this->input->post('auth', FALSE));
		if (parse_url($endpoint, PHP_URL_SCHEME) !== 'https' || strlen($endpoint) > 2000
			|| ! preg_match('/^[A-Za-z0-9_\-]{16,255}$/', $p256dh)
			|| ! preg_match('/^[A-Za-z0-9_\-]{8,255}$/', $auth)) {
			return json_response(FALSE, 'El navegador no entregó una suscripción válida.', NULL, 422);
		}

		$data = array(
			'user_id'       => $this->admin->id,
			'endpoint'      => $endpoint,
			'endpoint_hash' => hash('sha256', $endpoint),
			'p256dh'        => $p256dh,
			'auth'          => $auth,
		);
		$row = $this->db->get_where('push_subscriptions', array('endpoint_hash' => $data['endpoint_hash']))->row();
		if ($row) $this->db->where('id', $row->id)->update('push_subscriptions', $data);
		else $this->db->insert('push_subscriptions', $data);
		json_response(TRUE, 'Notificaciones activadas en este navegador.');
	}
}
