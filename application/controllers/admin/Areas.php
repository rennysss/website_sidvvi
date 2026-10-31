<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Areas extends Admin_Crud_Controller
{
	protected $table = 'areas';
	protected $route = 'areas';
	protected $title = 'Áreas de trabajo';
	protected $singular = 'área';
	protected $icon = 'fas fa-th-large';
	protected $order_by = 'sort_order ASC';
	protected $help = 'Tarjetas de la sección «Áreas de investigación y docencia». Íconos: https://fontawesome.com/search?ic=free';

	protected $fields = array(
		'icon'        => array('label' => 'Ícono', 'type' => 'icon', 'list' => TRUE, 'rules' => 'required'),
		'title'       => array('label' => 'Título', 'type' => 'text', 'rules' => 'required|max_length[150]', 'list' => TRUE),
		'description' => array('label' => 'Descripción', 'type' => 'textarea', 'rules' => 'max_length[400]'),
		'url'         => array('label' => 'Enlace', 'type' => 'text', 'col' => 6),
		'sort_order'  => array('label' => 'Orden', 'type' => 'number', 'col' => 3, 'list' => TRUE),
		'is_active'   => array('label' => 'Activo', 'type' => 'checkbox', 'col' => 3, 'list' => TRUE),
	);
}
