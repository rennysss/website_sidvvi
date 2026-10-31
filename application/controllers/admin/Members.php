<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Members extends Admin_Crud_Controller
{
	protected $table = 'members';
	protected $route = 'members';
	protected $title = 'Directorio de socios';
	protected $singular = 'socio';
	protected $icon = 'fas fa-address-book';
	protected $order_by = 'last_name ASC, first_name ASC';
	protected $soft_delete = TRUE;
	protected $search_fields = array('first_name', 'last_name', 'institution', 'city', 'member_number');
	protected $slug_field = 'slug';
	protected $slug_source = 'full_name_for_slug';

	protected $fields = array(
		'photo'          => array('label' => 'Foto', 'type' => 'image', 'list' => TRUE),
		'title_prefix'   => array('label' => 'Título', 'type' => 'text', 'col' => 2, 'help' => 'Dr., Dra.'),
		'first_name'     => array('label' => 'Nombre(s)', 'type' => 'text', 'rules' => 'required|max_length[100]', 'col' => 5, 'list' => TRUE),
		'last_name'      => array('label' => 'Apellidos', 'type' => 'text', 'rules' => 'required|max_length[150]', 'col' => 5, 'list' => TRUE),
		'member_number'  => array('label' => 'No. de socio', 'type' => 'text', 'col' => 4, 'list' => TRUE),
		'member_type_id' => array('label' => 'Tipo de socio', 'type' => 'select', 'col' => 4, 'source' => array('table' => 'member_types', 'label' => 'name', 'order' => 'id')),
		'specialty_id'   => array('label' => 'Especialidad', 'type' => 'select', 'col' => 4, 'list' => TRUE, 'source' => array('table' => 'specialties', 'label' => 'name')),
		'position'       => array('label' => 'Cargo', 'type' => 'text', 'col' => 6),
		'institution'    => array('label' => 'Institución', 'type' => 'text', 'col' => 6),
		'city'           => array('label' => 'Ciudad', 'type' => 'text', 'col' => 6),
		'state'          => array('label' => 'Estado', 'type' => 'text', 'col' => 6, 'list' => TRUE),
		'email_public'   => array('label' => 'Correo público', 'type' => 'email', 'col' => 4),
		'phone_public'   => array('label' => 'Teléfono público', 'type' => 'text', 'col' => 4),
		'website'        => array('label' => 'Sitio web', 'type' => 'text', 'col' => 4),
		'bio'            => array('label' => 'Semblanza', 'type' => 'textarea'),
		'joined_at'      => array('label' => 'Socio desde', 'type' => 'date', 'col' => 4),
		'is_public'      => array('label' => 'Mostrar en el directorio', 'type' => 'checkbox', 'col' => 4),
		'is_active'      => array('label' => 'Activo', 'type' => 'checkbox', 'col' => 4, 'list' => TRUE),
	);

	protected function collect($row)
	{
		$_POST['full_name_for_slug'] = $this->input->post('first_name').' '.$this->input->post('last_name');
		return parent::collect($row);
	}
}
