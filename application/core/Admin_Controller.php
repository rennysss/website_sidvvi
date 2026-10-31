<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base del panel de control: https://sidvvi.com/admin-panel
 * Exige sesión iniciada. Roles: superadmin (todo), editor (contenido), viewer (solo lectura).
 */
class Admin_Controller extends MY_Controller
{
	protected $admin;
	/** Rol mínimo para entrar al controlador: viewer | editor | superadmin */
	protected $min_role = 'viewer';

	private static $levels = array('viewer' => 1, 'editor' => 2, 'superadmin' => 3);

	public function __construct()
	{
		parent::__construct();
		$this->load->library('auth');
		$this->admin = $this->auth->user();

		if ( ! $this->admin) {
			$this->session->set_flashdata('return_to', current_url());
			redirect(admin_url());
		}
		$this->require_role($this->min_role);

		$this->data['admin'] = $this->admin;
		$this->data['nav_counts'] = array(
			'messages'    => $this->db->where('status', 'new')->count_all_results('contact_messages'),
			'memberships' => $this->db->where('status', 'new')->count_all_results('membership_requests'),
		);
	}

	protected function can($role)
	{
		return self::$levels[$this->admin->role] >= self::$levels[$role];
	}

	protected function require_role($role)
	{
		if ( ! $this->can($role)) {
			$this->session->set_flashdata('error', 'No tienes permiso para acceder a esa sección.');
			redirect(admin_url('dashboard'));
		}
	}

	protected function render($view, $data = array())
	{
		$data = array_merge($this->data, $data);
		$data['can_edit'] = $this->can('editor');
		$data['is_super'] = $this->can('superadmin');
		$data['content'] = $this->load->view('admin/'.$view, $data, TRUE);
		$this->load->view('layouts/admin', $data);
	}
}
