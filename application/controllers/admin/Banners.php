<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banners extends Admin_Crud_Controller
{
	protected $table = 'banners';
	protected $route = 'banners';
	protected $title = 'Banners del inicio';
	protected $singular = 'banner';
	protected $icon = 'fas fa-images';
	protected $order_by = 'sort_order ASC, id DESC';
	protected $soft_delete = TRUE;
	protected $search_fields = array('title', 'subtitle');
	protected $help = 'Imágenes del carrusel principal. Tamaño recomendado: 1920×900 px (escritorio) y 800×1000 px (móvil).';

	protected $fields = array(
		'image_desktop' => array('label' => 'Imagen escritorio', 'type' => 'image', 'list' => TRUE, 'required' => TRUE),
		'image_mobile'  => array('label' => 'Imagen móvil (opcional)', 'type' => 'image'),
		'eyebrow'       => array('label' => 'Texto superior', 'type' => 'text', 'rules' => 'max_length[100]'),
		'title'         => array('label' => 'Título', 'type' => 'text', 'rules' => 'required|max_length[200]', 'list' => TRUE),
		'subtitle'      => array('label' => 'Subtítulo', 'type' => 'textarea', 'rules' => 'max_length[300]'),
		'button_text'   => array('label' => 'Botón 1 · texto', 'type' => 'text', 'col' => 6),
		'button_url'    => array('label' => 'Botón 1 · enlace', 'type' => 'text', 'col' => 6, 'help' => 'Ej. congresos o https://...'),
		'button2_text'  => array('label' => 'Botón 2 · texto', 'type' => 'text', 'col' => 6),
		'button2_url'   => array('label' => 'Botón 2 · enlace', 'type' => 'text', 'col' => 6),
		'starts_at'     => array('label' => 'Mostrar desde', 'type' => 'datetime', 'col' => 6),
		'ends_at'       => array('label' => 'Mostrar hasta', 'type' => 'datetime', 'col' => 6),
		'sort_order'    => array('label' => 'Orden', 'type' => 'number', 'col' => 6, 'list' => TRUE),
		'status'        => array('label' => 'Estado', 'type' => 'select', 'col' => 6, 'list' => TRUE, 'rules' => 'required',
			'options' => array('published' => 'Publicado', 'draft' => 'Borrador', 'archived' => 'Archivado')),
	);
}
