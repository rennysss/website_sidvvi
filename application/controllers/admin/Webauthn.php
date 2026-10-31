<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Registro y acceso del panel con el autenticador del equipo.
 * Estas rutas son JSON. El alta exige sesión; el acceso, no.
 */
class Webauthn extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('auth', 'passkey'));
	}

	public function login_options()
	{
		if ($this->input->method() !== 'post') show_404();
		$wa = $this->passkey->engine();
		$args = $wa->getGetArgs(array(), 60, FALSE, FALSE, FALSE, FALSE, TRUE, 'required');
		$this->passkey->store_challenge($wa->getChallenge()->getBinaryString());
		json_response(TRUE, '', array('options' => $args));
	}

	public function login_verify()
	{
		if ($this->input->method() !== 'post') show_404();
		$payload = json_decode($this->input->post('credential', FALSE));
		$challenge = $this->passkey->take_challenge();
		if ( ! $payload || ! $challenge || empty($payload->id) || empty($payload->response)) {
			return json_response(FALSE, 'La verificación expiró. Vuelve a intentarlo.', NULL, 400);
		}

		$row = $this->db->get_where('webauthn_credentials', array(
			'credential_id' => $this->passkey->encode_id($this->passkey->decode($payload->id)),
		))->row();
		if ( ! $row) $row = $this->db->get_where('webauthn_credentials', array('credential_id' => $payload->id))->row();
		if ( ! $row) {
			return json_response(FALSE, 'Este equipo no está registrado. Entra con tu contraseña y regístralo.', NULL, 401);
		}

		try {
			$wa = $this->passkey->engine();
			$wa->processGet(
				$this->passkey->decode($payload->response->clientDataJSON),
				$this->passkey->decode($payload->response->authenticatorData),
				$this->passkey->decode($payload->response->signature),
				$row->public_key,
				$challenge,
				(int) $row->sign_count,
				TRUE,
				TRUE
			);
		} catch (\Throwable $e) {
			log_message('error', 'WebAuthn login: '.$e->getMessage());
			return json_response(FALSE, 'No se pudo verificar este equipo. Intenta de nuevo o usa tu contraseña.', NULL, 401);
		}

		$user = $this->db->where('id', $row->user_id)->where('deleted_at IS NULL', NULL, FALSE)->get('users')->row();
		if ( ! $user || ! $user->is_active) {
			return json_response(FALSE, 'Tu usuario está desactivado.', NULL, 403);
		}
		if ( ! empty($payload->response->userHandle)) {
			$handle = $this->passkey->decode($payload->response->userHandle);
			if ( ! hash_equals($this->passkey->user_handle($user->id), $handle)) {
				return json_response(FALSE, 'No se pudo verificar este equipo.', NULL, 401);
			}
		}

		$count = $wa->getSignatureCounter();
		$this->db->where('id', $row->id)->update('webauthn_credentials', array(
			'sign_count'   => $count === NULL ? $row->sign_count : $count,
			'last_used_at' => date('Y-m-d H:i:s'),
		));
		$this->auth->start_session($user, 'passkey');

		$to = $this->session->userdata('return_to');
		$this->session->unset_userdata('return_to');
		$redirect = ($to && strpos($to, admin_url()) === 0) ? $to : admin_url('dashboard');
		json_response(TRUE, 'Listo.', array('redirect' => $redirect));
	}

	public function register_options()
	{
		if ($this->input->method() !== 'post') show_404();
		$user = $this->auth->user();
		if ( ! $user) return json_response(FALSE, 'Inicia sesión para registrar este equipo.', NULL, 401);

		$exclude = array();
		foreach ($this->db->select('credential_id')->get_where('webauthn_credentials', array('user_id' => $user->id))->result() as $row) {
			$exclude[] = $this->passkey->decode($row->credential_id);
		}
		$wa = $this->passkey->engine();
		$args = $wa->getCreateArgs(
			$this->passkey->user_handle($user->id),
			$user->email,
			$user->name,
			60,
			TRUE,
			'required',
			FALSE,
			$exclude
		);
		$this->passkey->store_challenge($wa->getChallenge()->getBinaryString());
		json_response(TRUE, '', array('options' => $args));
	}

	public function register_verify()
	{
		if ($this->input->method() !== 'post') show_404();
		$user = $this->auth->user();
		if ( ! $user) return json_response(FALSE, 'Inicia sesión para registrar este equipo.', NULL, 401);

		$payload = json_decode($this->input->post('credential', FALSE));
		$challenge = $this->passkey->take_challenge();
		if ( ! $payload || ! $challenge || empty($payload->response)) {
			return json_response(FALSE, 'La verificación expiró. Vuelve a intentarlo.', NULL, 400);
		}

		try {
			$wa = $this->passkey->engine();
			$data = $wa->processCreate(
				$this->passkey->decode($payload->response->clientDataJSON),
				$this->passkey->decode($payload->response->attestationObject),
				$challenge,
				TRUE,
				TRUE,
				FALSE,
				FALSE
			);
		} catch (\Throwable $e) {
			log_message('error', 'WebAuthn registro: '.$e->getMessage());
			return json_response(FALSE, 'No se pudo registrar este equipo. Revisa que Face ID, Touch ID o Windows Hello estén activos.', NULL, 400);
		}

		$credential_id = $this->passkey->encode_id($data->credentialId);
		if ($this->db->get_where('webauthn_credentials', array('credential_id' => $credential_id))->row()) {
			return json_response(TRUE, 'Este equipo ya estaba registrado.');
		}
		$this->db->insert('webauthn_credentials', array(
			'user_id'       => $user->id,
			'credential_id' => $credential_id,
			'public_key'    => $data->credentialPublicKey,
			'sign_count'    => (int) $data->signatureCounter,
			'label'         => $this->device_label(),
		));
		audit_log('create', 'webauthn_credentials', $this->db->insert_id(), $this->device_label());
		json_response(TRUE, 'Equipo registrado. La próxima vez puedes entrar sin contraseña.');
	}

	protected function device_label()
	{
		$ua = (string) $this->input->user_agent();
		if (stripos($ua, 'iPhone') !== FALSE) return 'iPhone';
		if (stripos($ua, 'iPad') !== FALSE) return 'iPad';
		if (stripos($ua, 'Android') !== FALSE) return 'Android';
		if (stripos($ua, 'Macintosh') !== FALSE) return 'Mac';
		if (stripos($ua, 'Windows') !== FALSE) return 'Windows';
		if (stripos($ua, 'Linux') !== FALSE) return 'Linux';
		return 'Este equipo';
	}
}
