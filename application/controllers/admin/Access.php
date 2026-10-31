<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Login del panel: https://sidvvi.com/admin-panel
 * (Se llama Access y no Auth para no chocar con la librería Auth)
 * (no extiende Admin_Controller porque estas pantallas son públicas)
 */
class Access extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('auth', 'form_validation'));
	}

	public function login()
	{
		if ($this->auth->user()) redirect(admin_url('dashboard'));
		if ($r = $this->session->flashdata('return_to')) $this->session->set_userdata('return_to', $r);
		$this->view('login', array('title' => 'Iniciar sesión'));
	}

	public function do_login()
	{
		$this->form_validation->set_rules('email', 'Correo', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Contraseña', 'required');
		if ( ! $this->form_validation->run()) {
			return $this->view('login', array('title' => 'Iniciar sesión', 'error' => validation_errors(' ', ' ')));
		}
		$result = $this->auth->login($this->input->post('email', TRUE), $this->input->post('password'));
		if ($result !== TRUE) {
			return $this->view('login', array('title' => 'Iniciar sesión', 'error' => $result));
		}
		$to = $this->session->userdata('return_to');
		$dest = ($to && strpos($to, admin_url()) === 0) ? $to : admin_url('dashboard');
		$has_device = $this->db->where('user_id', $this->auth->user()->id)->count_all_results('webauthn_credentials');
		if ($has_device === 0) {
			$this->session->set_flashdata('success', 'Contraseña correcta. Registra este equipo para entrar después con Face ID, Touch ID o Windows Hello.');
			redirect(admin_url('seguridad'));
		}
		$this->session->unset_userdata('return_to');
		redirect($dest);
	}

	public function logout()
	{
		$this->auth->logout();
		redirect(admin_url());
	}

	public function forgot()
	{
		$data = array('title' => 'Recuperar contraseña');
		if ($this->input->method() === 'post') {
			$this->form_validation->set_rules('email', 'Correo', 'trim|required|valid_email');
			if ($this->form_validation->run()) {
				if ($user = $this->auth->create_reset_token($this->input->post('email', TRUE))) {
					$this->load->library('mailer');
					$this->mailer->send($user->email, 'Restablecer contraseña · SIDVVI', 'password_reset', array(
						'name' => $user->name, 'link' => admin_url('restablecer/'.$user->reset_token),
					));
				}
				// Mismo mensaje exista o no el correo (no revelar usuarios)
				$data['success'] = 'Si el correo está registrado, recibirás un enlace para restablecer tu contraseña.';
			} else {
				$data['error'] = validation_errors(' ', ' ');
			}
		}
		$this->view('forgot', $data);
	}

	public function reset($token = '')
	{
		$user = $this->auth->find_by_token($token);
		if ( ! $user) {
			return $this->view('forgot', array('title' => 'Recuperar contraseña', 'error' => 'El enlace no es válido o ya venció. Solicita uno nuevo.'));
		}
		$data = array('title' => 'Nueva contraseña', 'token' => $token);
		if ($this->input->method() === 'post') {
			$this->form_validation->set_rules('password', 'Contraseña', 'required|min_length[8]');
			$this->form_validation->set_rules('password_confirm', 'Confirmación', 'required|matches[password]');
			if ($this->form_validation->run()) {
				$this->db->where('id', $user->id)->update('users', array(
					'password_hash' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
					'reset_token' => NULL, 'reset_expires_at' => NULL,
				));
				$this->session->set_flashdata('success', 'Contraseña actualizada. Ya puedes iniciar sesión.');
				redirect(admin_url());
			}
			$data['error'] = validation_errors(' ', ' ');
		}
		$this->view('reset', $data);
	}

	protected function view($name, $data)
	{
		if ($name === 'login') $data['extra_js'] = array(base_url('assets/js/webauthn.js?v=1'));
		$data['content'] = $this->load->view('admin/auth/'.$name, $data, TRUE);
		$this->load->view('layouts/auth', $data);
	}
}
