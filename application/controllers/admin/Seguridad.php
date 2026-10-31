<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Equipos autorizados a entrar al panel sin contraseña */
class Seguridad extends Admin_Controller
{
	protected $min_role = 'viewer';

	public function index()
	{
		$to = $this->session->userdata('return_to');
		$this->session->unset_userdata('return_to');
		if ( ! $to || strpos($to, admin_url()) !== 0) $to = admin_url('dashboard');

		$devices = $this->db->where('user_id', $this->admin->id)->order_by('created_at', 'DESC')->get('webauthn_credentials')->result();
		$this->render('seguridad', array(
			'page_title' => 'Este equipo',
			'devices'    => $devices,
			'continue'   => $to,
			'extra_js'   => array(base_url('assets/js/webauthn.js?v=1')),
		));
	}

	public function revoke($id = NULL)
	{
		if ($this->input->method() !== 'post') show_404();
		$this->db->where(array('id' => (int) $id, 'user_id' => $this->admin->id))->delete('webauthn_credentials');
		audit_log('delete', 'webauthn_credentials', (int) $id);
		$this->session->set_flashdata('success', 'Se quitó ese equipo. Para volver a usarlo hay que registrarlo otra vez.');
		redirect(admin_url('seguridad'));
	}
}
