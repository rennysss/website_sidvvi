<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends Admin_Crud_Controller
{
	protected $table = 'users';
	protected $route = 'users';
	protected $title = 'Usuarios del panel';
	protected $singular = 'usuario';
	protected $icon = 'fas fa-users-cog';
	protected $min_role = 'superadmin';
	protected $edit_role = 'superadmin';
	protected $soft_delete = TRUE;
	protected $order_by = 'name ASC';
	protected $search_fields = array('name', 'email');
	protected $help = 'Roles: Superadministrador (todo), Editor (contenido), Consulta (solo lectura).';
	protected $fields = array(
		'name'     => array('label' => 'Nombre', 'type' => 'text', 'rules' => 'required|max_length[150]', 'list' => TRUE),
		'email'    => array('label' => 'Correo', 'type' => 'email', 'rules' => 'required|max_length[191]', 'list' => TRUE),
		'role'     => array('label' => 'Rol', 'type' => 'select', 'rules' => 'required', 'list' => TRUE, 'col' => 6,
			'options' => array('superadmin' => 'Superadministrador', 'editor' => 'Editor', 'viewer' => 'Consulta')),
		'is_active' => array('label' => 'Activo', 'type' => 'checkbox', 'list' => TRUE, 'col' => 6),
		'password' => array('label' => 'Contraseña', 'type' => 'password', 'column' => 'password_hash', 'rules' => 'required|min_length[8]', 'help' => 'Mínimo 8 caracteres. En edición, déjala vacía para no cambiarla.'),
		'last_login_at' => array('label' => 'Último acceso', 'type' => 'readonly', 'list' => TRUE),
	);

	protected function set_rules($row)
	{
		parent::set_rules($row);
		$this->form_validation->set_rules('email', 'Correo', 'trim|required|valid_email|callback__unique_email['.($row->id ?? 0).']');
	}

	public function _unique_email($email, $id)
	{
		$exists = $this->db->where('email', strtolower($email))->where('id !=', (int) $id)->count_all_results('users');
		if ($exists) { $this->form_validation->set_message('_unique_email', 'Ese correo ya está registrado.'); return FALSE; }
		return TRUE;
	}

	protected function before_save(array &$data, $row)
	{
		$data['email'] = strtolower($data['email']);
		unset($data['password']);
		// Evitar que el usuario se quite a sí mismo el rol o se desactive
		if ($row && $row->id == $this->admin->id) { $data['role'] = 'superadmin'; $data['is_active'] = 1; }
	}

	public function delete($id = NULL)
	{
		if ((int) $id === (int) $this->admin->id) {
			$this->session->set_flashdata('error', 'No puedes eliminar tu propio usuario.');
			redirect(admin_url($this->route));
		}
		parent::delete($id);
	}
}
