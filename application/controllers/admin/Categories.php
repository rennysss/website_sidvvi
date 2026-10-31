<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends Admin_Crud_Controller
{
	protected $table = 'categories';
	protected $route = 'categories';
	protected $title = 'Categorías de la biblioteca';
	protected $singular = 'categoría';
	protected $icon = 'fas fa-folder';
	protected $order_by = 'sort_order ASC';
	protected $slug_field = 'slug';
	protected $slug_source = 'name';
	protected $fields = array(
		'name'        => array('label' => 'Nombre', 'type' => 'text', 'rules' => 'required|max_length[150]', 'list' => TRUE),
		'slug'        => array('label' => 'URL (slug)', 'type' => 'text', 'list' => TRUE, 'help' => 'Se genera solo si lo dejas vacío.'),
		'description' => array('label' => 'Descripción', 'type' => 'textarea'),
		'sort_order'  => array('label' => 'Orden', 'type' => 'number', 'list' => TRUE),
	);

	public function delete($id = NULL)
	{
		if ($this->db->where('category_id', (int) $id)->where('deleted_at IS NULL', NULL, FALSE)->count_all_results('articles')) {
			$this->session->set_flashdata('error', 'No se puede eliminar: hay publicaciones en esta categoría.');
			redirect(admin_url($this->route));
		}
		parent::delete($id);
	}
}
