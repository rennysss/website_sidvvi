<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Contacto: /contacto */
class Contact extends Public_Controller
{
	const MAX_PER_HOUR = 3;

	public function index()
	{
		$this->seo->set('Contacto', 'Ponte en contacto con SIDVVI.');
		$this->render('contact', array('active_menu' => 'contact'),
			array('title' => 'Contacto', 'crumbs' => array(array('Contacto', NULL))));
	}

	/** POST Ajax */
	public function send()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', 'Nombre', 'trim|required|max_length[150]');
		$this->form_validation->set_rules('email', 'Correo', 'trim|required|valid_email|max_length[191]');
		$this->form_validation->set_rules('phone', 'Teléfono', 'trim|max_length[30]');
		$this->form_validation->set_rules('subject', 'Asunto', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('message', 'Mensaje', 'trim|required|max_length[5000]');
		$this->form_validation->set_rules('privacy', 'Aviso de privacidad', 'required', array('required' => 'Debes aceptar el aviso de privacidad.'));

		// Honeypot anti-spam: el campo "website" está oculto, los humanos no lo llenan
		if ($this->input->post('website')) return json_response(TRUE, 'Gracias, recibimos tu mensaje.');

		if ( ! $this->form_validation->run()) {
			return json_response(FALSE, 'Revisa los campos marcados.', array('errors' => $this->form_validation->error_array()), 422);
		}

		$ip = $this->input->ip_address();
		$recent = $this->db->where('ip_address', $ip)->where('created_at >=', date('Y-m-d H:i:s', time() - 3600))->count_all_results('contact_messages');
		if ($recent >= self::MAX_PER_HOUR) {
			return json_response(FALSE, 'Has enviado varios mensajes en poco tiempo. Intenta más tarde.', NULL, 429);
		}
		$this->load->library('recaptcha');
		if ( ! $this->recaptcha->verify($this->input->post('recaptcha_token'), 'contacto')) {
			return json_response(FALSE, 'No pudimos confirmar el envío. Recarga la página e intenta de nuevo.', NULL, 422);
		}

		$data = array(
			'name'    => $this->input->post('name', TRUE),
			'email'   => $this->input->post('email', TRUE),
			'phone'   => $this->input->post('phone', TRUE),
			'subject' => $this->input->post('subject', TRUE),
			'message' => $this->input->post('message', TRUE),
			'ip_address' => $ip,
		);
		$this->db->insert('contact_messages', $data);
		$id = $this->db->insert_id();

		// Correo a la sociedad y acuse al remitente
		$this->load->library('mailer');
		$sent = FALSE;
		if ($to = Mailer::recipients('contact_recipients')) {
			$sent = $this->mailer->send($to, 'Nuevo mensaje de contacto: '.$data['subject'], 'contact_notification', $data, $data['email']);
		}
		$this->mailer->send($data['email'], 'Recibimos tu mensaje · SIDVVI', 'contact_ack', $data);
		$this->db->where('id', $id)->update('contact_messages', array('mail_sent' => $sent ? 1 : 0));
		$this->load->library('push');
		$this->push->send_to_role('all', 'Nuevo mensaje de contacto', $data['name'].': '.$data['subject'], admin_url('messages/edit/'.$id));

		json_response(TRUE, 'Gracias, '.$data['name'].'. Recibimos tu mensaje y te responderemos pronto.');
	}
}
