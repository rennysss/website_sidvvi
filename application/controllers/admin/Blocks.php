<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Textos institucionales (Quiénes somos, Misión, Visión, Mensaje de la presidencia, etc.) */
class Blocks extends Admin_Crud_Controller
{
	protected $table = 'page_blocks';
	protected $route = 'blocks';
	protected $title = 'Textos del sitio';
	protected $singular = 'texto';
	protected $icon = 'fas fa-align-left';
	protected $order_by = 'id ASC';
	protected $can_create = FALSE;
	protected $can_delete = FALSE;
	protected $search_fields = array('title', 'code');
	protected $help = 'Bloques de texto que aparecen en Inicio, Nosotros, Membresía y Aviso de privacidad.';

	protected $fields = array(
		'code'    => array('label' => 'Bloque', 'type' => 'readonly', 'list' => TRUE),
		'eyebrow' => array('label' => 'Texto superior', 'type' => 'text'),
		'title'   => array('label' => 'Título', 'type' => 'text', 'list' => TRUE),
		'body'    => array('label' => 'Contenido', 'type' => 'html'),
		'image'   => array('label' => 'Imagen', 'type' => 'image'),
		'extra'   => array('label' => 'Dato adicional', 'type' => 'text', 'help' => 'Firma: «Nombre|Cargo». Misión/Visión/Valores: clase de ícono (ej. fas fa-eye).'),
		'is_active' => array('label' => 'Activo', 'type' => 'checkbox', 'list' => TRUE),
	);
}
