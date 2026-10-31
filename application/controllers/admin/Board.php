<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Board extends Admin_Crud_Controller
{
	protected $table = 'board_members';
	protected $route = 'board';
	protected $title = 'Mesa directiva';
	protected $singular = 'integrante';
	protected $icon = 'fas fa-user-tie';
	protected $order_by = 'sort_order ASC';
	protected $search_fields = array('name', 'position');
	protected $help = 'Foto cuadrada recomendada: 600×600 px.';

	protected $fields = array(
		'photo'      => array('label' => 'Foto', 'type' => 'image', 'list' => TRUE),
		'name'       => array('label' => 'Nombre', 'type' => 'text', 'rules' => 'required|max_length[150]', 'list' => TRUE, 'col' => 6),
		'position'   => array('label' => 'Cargo', 'type' => 'text', 'rules' => 'required|max_length[150]', 'list' => TRUE, 'col' => 6),
		'period'     => array('label' => 'Periodo', 'type' => 'text', 'col' => 6, 'help' => 'Ej. 2025-2027'),
		'email'      => array('label' => 'Correo', 'type' => 'email', 'col' => 6),
		'linkedin'   => array('label' => 'LinkedIn (URL)', 'type' => 'text', 'col' => 6),
		'twitter'    => array('label' => 'X / Twitter (URL)', 'type' => 'text', 'col' => 6),
		'bio'        => array('label' => 'Semblanza', 'type' => 'textarea'),
		'sort_order' => array('label' => 'Orden', 'type' => 'number', 'col' => 6, 'list' => TRUE),
		'is_active'  => array('label' => 'Visible', 'type' => 'checkbox', 'col' => 6, 'list' => TRUE),
	);
}
