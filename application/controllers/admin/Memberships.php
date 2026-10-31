<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Memberships extends Admin_Crud_Controller
{
	protected $table = 'membership_requests';
	protected $route = 'memberships';
	protected $title = 'Solicitudes de membresía';
	protected $singular = 'solicitud';
	protected $icon = 'fas fa-user-plus';
	protected $order_by = 'created_at DESC';
	protected $can_create = FALSE;
	protected $search_fields = array('full_name', 'email', 'specialty', 'institution');
	protected $fields = array(
		'created_at'      => array('label' => 'Fecha', 'type' => 'readonly', 'list' => TRUE),
		'full_name'       => array('label' => 'Nombre', 'type' => 'readonly', 'list' => TRUE),
		'email'           => array('label' => 'Correo', 'type' => 'readonly', 'list' => TRUE),
		'phone'           => array('label' => 'Teléfono', 'type' => 'readonly'),
		'member_type_id'  => array('label' => 'Tipo solicitado', 'type' => 'select', 'source' => array('table' => 'member_types', 'label' => 'name', 'order' => 'id')),
		'specialty'       => array('label' => 'Especialidad', 'type' => 'readonly', 'list' => TRUE),
		'institution'     => array('label' => 'Institución', 'type' => 'readonly'),
		'professional_id' => array('label' => 'Cédula', 'type' => 'readonly'),
		'city'            => array('label' => 'Ciudad', 'type' => 'readonly'),
		'state'           => array('label' => 'Estado', 'type' => 'readonly'),
		'comments'        => array('label' => 'Comentarios', 'type' => 'readonly'),
		'status'          => array('label' => 'Estatus', 'type' => 'select', 'list' => TRUE, 'rules' => 'required',
			'options' => array('new' => 'Nueva', 'in_review' => 'En revisión', 'approved' => 'Aprobada', 'rejected' => 'Rechazada')),
		'notes'           => array('label' => 'Notas internas', 'type' => 'textarea'),
	);

	protected function form_extra($row)
	{
		if ( ! $row || $row->status !== 'approved') return '';
		return '<div class="col-12 mb-3"><div class="alert alert-info mb-0">Solicitud aprobada. Da de alta al socio en <a href="'.admin_url('members/create').'">Directorio de socios</a>.</div></div>';
	}
}
