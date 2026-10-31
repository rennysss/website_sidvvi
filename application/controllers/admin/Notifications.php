<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Envío manual de avisos a quienes activaron notificaciones en el panel */
class Notifications extends Admin_Controller
{
	protected $min_role = 'viewer';

	public function index()
	{
		if ($this->input->method() === 'post') return $this->send();
		$rows = $this->db->order_by('created_at', 'DESC')->limit(30)->get('notifications')->result();
		$this->render('notifications', array('page_title' => 'Notificaciones', 'rows' => $rows));
	}

	protected function send()
	{
		$this->require_role('editor');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', 'Título', 'trim|required|max_length[150]');
		$this->form_validation->set_rules('body', 'Mensaje', 'trim|required|max_length[500]');
		$this->form_validation->set_rules('url', 'Enlace', 'trim|max_length[255]');
		$this->form_validation->set_rules('audience', 'Destino', 'required|in_list[all,superadmin,editor,viewer]');
		if ( ! $this->form_validation->run()) {
			$this->session->set_flashdata('error', validation_errors(' ', ' '));
			redirect(admin_url('notifications'));
		}

		$title = $this->input->post('title', TRUE);
		$body = $this->input->post('body', TRUE);
		$url = trim((string) $this->input->post('url', TRUE));
		$audience = $this->input->post('audience', TRUE);
		if ($url !== '' && ! preg_match('#^https?://#', $url)) $url = site_url($url);

		$this->load->library('push');
		$sent = $this->push->send_to_role($audience, $title, $body, $url ?: admin_url('dashboard'));
		$this->db->insert('notifications', array(
			'title'      => $title,
			'body'       => $body,
			'url'        => $url,
			'sent_count' => $sent,
			'created_by' => $this->admin->id,
		));
		audit_log('create', 'notifications', $this->db->insert_id(), $audience);
		$this->session->set_flashdata('success', 'Aviso enviado a '.$sent.' navegador'.($sent === 1 ? '' : 'es').'.');
		redirect(admin_url('notifications'));
	}
}
