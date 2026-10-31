<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_types extends Admin_Crud_Controller
{
	protected $table = 'member_types';
	protected $route = 'member_types';
	protected $title = 'Tipos de socio';
	protected $singular = 'tipo de socio';
	protected $icon = 'fas fa-id-badge';
	protected $order_by = 'id ASC';
	protected $fields = array(
		'name' => array('label' => 'Nombre', 'type' => 'text', 'rules' => 'required|max_length[100]', 'list' => TRUE),
	);

	public function delete($id = NULL)
	{
		if ($this->db->where('member_type_id', (int) $id)->count_all_results('members')) {
			$this->session->set_flashdata('error', 'No se puede eliminar: hay socios con este tipo.');
			redirect(admin_url($this->route));
		}
		parent::delete($id);
	}
}
