<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Publicaciones de Instagram mostradas en «Noticias».
 * Mientras no se conecte la API (fase 6) se pueden capturar a mano.
 */
class Instagram extends Admin_Crud_Controller
{
	protected $table = 'instagram_cache';
	protected $route = 'instagram';
	protected $title = 'Noticias (Instagram)';
	protected $singular = 'publicación';
	protected $icon = 'fab fa-instagram';
	protected $order_by = 'posted_at DESC';
	protected $search_fields = array('caption');
	protected $help = 'La lista se llena desde la API cada hora. Si Instagram no responde, se conserva lo último guardado. También puedes agregar una publicación a mano.';
	protected $fields = array(
		'media_url' => array('label' => 'Imagen', 'type' => 'image', 'list' => TRUE, 'required' => TRUE),
		'caption'   => array('label' => 'Texto', 'type' => 'textarea', 'list' => TRUE),
		'permalink' => array('label' => 'Enlace a la publicación', 'type' => 'url', 'rules' => 'required'),
		'posted_at' => array('label' => 'Fecha', 'type' => 'datetime', 'rules' => 'required', 'list' => TRUE),
	);

	protected function before_save(array &$data, $row)
	{
		if ( ! $row) $data['ig_media_id'] = 'manual-'.uniqid();
	}

	protected function list_toolbar()
	{
		$html = '';
		if ($this->can('editor')) {
			$html .= form_open(admin_url('instagram/sincronizar'), array('class' => 'd-inline'));
			$html .= '<button class="btn btn-sm btn-outline-primary"><i class="fas fa-sync-alt"></i> Sincronizar ahora</button>';
			$html .= form_close();
		}
		if ($this->can('superadmin')) {
			$html .= ' <a class="btn btn-sm btn-outline-secondary" href="'.admin_url('instagram_connect').'"><i class="fas fa-key"></i> Credenciales</a>';
		}
		return $html;
	}

	public function sincronizar()
	{
		$this->require_role('editor');
		if ($this->input->method() !== 'post') show_404();
		$this->load->library('instagram_feed');
		$result = $this->instagram_feed->refresh();
		audit_log('update', 'instagram_cache', NULL, $result['message']);
		$this->session->set_flashdata($result['ok'] ? 'success' : 'error', $result['message']);
		redirect(admin_url('instagram'));
	}
}
