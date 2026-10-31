<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * App ID, App Secret y token de Instagram.
 * Se cifran en la base. El formulario no vuelve a mostrar el valor guardado.
 */
class Instagram_connect extends Admin_Controller
{
	protected $min_role = 'superadmin';

	protected $keys = array(
		'instagram_app_id'     => 'App ID',
		'instagram_app_secret' => 'App Secret',
		'instagram_token'      => 'Token de larga duración',
	);

	public function index()
	{
		$this->load->library('secrets');
		if ($this->input->method() === 'post') return $this->save();

		$state = array();
		foreach ($this->keys as $key => $label) {
			$row = $this->secrets->row($key);
			$state[$key] = array(
				'label'   => $label,
				'filled'  => (bool) ($row && $row->value_enc),
				'expires' => $row->expires_at ?? NULL,
				'updated' => $row->updated_at ?? NULL,
			);
		}
		$this->render('instagram_connect', array(
			'page_title' => 'Conexión Instagram',
			'state'      => $state,
			'ig_user'    => setting('instagram_user', ''),
		));
	}

	protected function save()
	{
		foreach ($this->keys as $key => $label) {
			if ($this->input->post('clear_'.$key)) {
				$this->secrets->set($key, '');
				continue;
			}
			$value = trim((string) $this->input->post($key, FALSE));
			if ($value === '') continue;
			if ( ! preg_match('/^[A-Za-z0-9._\-]{6,500}$/', $value)) {
				$this->session->set_flashdata('error', $label.' no tiene un formato válido. No se guardó.');
				redirect(admin_url('instagram_connect'));
			}
			$expires = $key === 'instagram_token' ? date('Y-m-d H:i:s', time() + 50 * 86400) : NULL;
			$this->secrets->set($key, $value, $expires);
		}

		$user = ltrim(trim(strip_tags((string) $this->input->post('instagram_user'))), '@');
		if ($user !== '' && preg_match('/^[A-Za-z0-9._]{1,30}$/', $user)) {
			$this->Setting_model->save(array('instagram_user' => $user));
		}
		audit_log('update', 'integration_secrets', NULL, 'instagram');
		$this->session->set_flashdata('success', 'Credenciales guardadas. El token queda cifrado en la base.');
		redirect(admin_url('instagram_connect'));
	}
}
