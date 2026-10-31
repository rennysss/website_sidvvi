<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tags extends Admin_Crud_Controller
{
	protected $table = 'tags';
	protected $route = 'tags';
	protected $title = 'Temas (etiquetas)';
	protected $singular = 'tema';
	protected $icon = 'fas fa-tags';
	protected $order_by = 'name ASC';
	protected $slug_field = 'slug';
	protected $slug_source = 'name';
	protected $fields = array(
		'name' => array('label' => 'Nombre', 'type' => 'text', 'rules' => 'required|max_length[100]', 'list' => TRUE),
		'slug' => array('label' => 'URL (slug)', 'type' => 'text', 'list' => TRUE),
	);
}
