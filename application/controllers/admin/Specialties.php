<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Specialties extends Admin_Crud_Controller
{
	protected $table = 'specialties';
	protected $route = 'specialties';
	protected $title = 'Especialidades';
	protected $singular = 'especialidad';
	protected $icon = 'fas fa-stethoscope';
	protected $order_by = 'name ASC';
	protected $fields = array(
		'name' => array('label' => 'Nombre', 'type' => 'text', 'rules' => 'required|max_length[150]', 'list' => TRUE),
	);

	public function delete($id = NULL)
	{
		if ($this->db->where('specialty_id', (int) $id)->count_all_results('members')) {
			$this->session->set_flashdata('error', 'No se puede eliminar: hay socios con esta especialidad.');
			redirect(admin_url($this->route));
		}
		parent::delete($id);
	}
}
