<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends Admin_Controller
{
	protected $min_role = 'superadmin';

	public static $groups = array(
		'general' => array('General', 'fas fa-sliders-h'),
		'contact' => array('Contacto', 'fas fa-address-card'),
		'seo'     => array('SEO', 'fas fa-search'),
		'social'  => array('Redes', 'fas fa-share-alt'),
	);

	public function index()
	{
		if ($this->input->method() === 'post') {
			$valid = array_column($this->db->select('key')->get('settings')->result_array(), 'key');
			$data = array();
			foreach ((array) $this->input->post('s') as $k => $v) {
				if (in_array($k, $valid, TRUE)) $data[$k] = trim(strip_tags($v));
			}
			$this->Setting_model->save($data);
			audit_log('update', 'settings', NULL);
			$this->session->set_flashdata('success', 'Configuración guardada.');
			redirect(admin_url('settings'));
		}
		$this->render('settings', array('page_title' => 'Configuración', 'grouped' => $this->Setting_model->grouped()));
	}
}
