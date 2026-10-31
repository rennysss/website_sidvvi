<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Social_links extends Admin_Crud_Controller
{
	protected $table = 'social_links';
	protected $route = 'social_links';
	protected $title = 'Redes sociales';
	protected $singular = 'red social';
	protected $icon = 'fas fa-share-alt';
	protected $order_by = 'sort_order ASC';
	protected $help = 'Íconos del encabezado y pie de página. Ej. de ícono: fab fa-facebook-f, fab fa-instagram, fab fa-youtube, fab fa-tiktok.';
	protected $fields = array(
		'icon_class' => array('label' => 'Ícono', 'type' => 'icon', 'rules' => 'required', 'list' => TRUE),
		'network'    => array('label' => 'Red', 'type' => 'text', 'rules' => 'required|max_length[30]', 'list' => TRUE),
		'url'        => array('label' => 'URL del perfil', 'type' => 'url', 'rules' => 'required|max_length[255]', 'list' => TRUE),
		'sort_order' => array('label' => 'Orden', 'type' => 'number', 'col' => 6),
		'is_active'  => array('label' => 'Visible', 'type' => 'checkbox', 'col' => 6, 'list' => TRUE),
	);
}
