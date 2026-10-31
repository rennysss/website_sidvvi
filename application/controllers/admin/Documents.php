<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documents extends Admin_Crud_Controller
{
	protected $table = 'documents';
	protected $route = 'documents';
	protected $title = 'Avisos y documentos';
	protected $singular = 'documento';
	protected $icon = 'fas fa-file-pdf';
	protected $order_by = 'sort_order ASC, published_at DESC';
	protected $search_fields = array('title');

	protected $fields = array(
		'title'        => array('label' => 'Título', 'type' => 'text', 'rules' => 'required|max_length[200]', 'list' => TRUE),
		'description'  => array('label' => 'Descripción', 'type' => 'text', 'rules' => 'max_length[300]'),
		'file_path'    => array('label' => 'Archivo', 'type' => 'file', 'list' => TRUE),
		'published_at' => array('label' => 'Fecha', 'type' => 'date', 'col' => 4, 'list' => TRUE),
		'sort_order'   => array('label' => 'Orden', 'type' => 'number', 'col' => 4),
		'is_active'    => array('label' => 'Visible', 'type' => 'checkbox', 'col' => 4, 'list' => TRUE),
	);
}
