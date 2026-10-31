<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quick_links extends Admin_Crud_Controller
{
	protected $table = 'quick_links';
	protected $route = 'quick_links';
	protected $title = 'Accesos rápidos';
	protected $singular = 'acceso';
	protected $icon = 'fas fa-link';
	protected $order_by = 'sort_order ASC';

	protected $fields = array(
		'icon'       => array('label' => 'Ícono', 'type' => 'icon', 'list' => TRUE, 'rules' => 'required'),
		'title'      => array('label' => 'Texto', 'type' => 'text', 'rules' => 'required|max_length[150]', 'list' => TRUE),
		'url'        => array('label' => 'Enlace', 'type' => 'text', 'rules' => 'required', 'list' => TRUE),
		'sort_order' => array('label' => 'Orden', 'type' => 'number', 'col' => 6),
		'is_active'  => array('label' => 'Activo', 'type' => 'checkbox', 'col' => 6, 'list' => TRUE),
	);
}
