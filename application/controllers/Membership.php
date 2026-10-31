<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Membresía: /membresia (información + solicitud) */
class Membership extends Public_Controller
{
	public function index()
	{
		$this->load->model('Content_model');
		$this->seo->set('Membresía', 'Conoce los beneficios y requisitos para ser socio de SIDVVI y envía tu solicitud.');
		$this->render('membership', array(
			'active_menu' => 'membership',
			'intro' => $this->Content_model->block('membership_intro'),
			'types' => $this->db->get('member_types')->result(),
			'specialties' => $this->db->order_by('name')->get('specialties')->result(),
		), array('title' => 'Membresía', 'crumbs' => array(array('Membresía', NULL))));
	}

	/** POST Ajax */
	public function send()
	{
		$this->load->library('form_validation');
		$v = $this->form_validation;
		$v->set_rules('full_name', 'Nombre completo', 'trim|required|max_length[200]');
		$v->set_rules('email', 'Correo', 'trim|required|valid_email');
		$v->set_rules('phone', 'Teléfono', 'trim|required|max_length[30]');
		$v->set_rules('member_type_id', 'Tipo de membresía', 'required|is_natural_no_zero');
		$v->set_rules('specialty', 'Especialidad', 'trim|required|max_length[150]');
		$v->set_rules('institution', 'Institución', 'trim|max_length[200]');
		$v->set_rules('city', 'Ciudad', 'trim|max_length[100]');
		$v->set_rules('state', 'Estado', 'trim|max_length[100]');
		$v->set_rules('professional_id', 'Cédula profesional', 'trim|max_length[50]');
		$v->set_rules('comments', 'Comentarios', 'trim|max_length[3000]');
		$v->set_rules('privacy', 'Aviso de privacidad', 'required', array('required' => 'Debes aceptar el aviso de privacidad.'));

		if ($this->input->post('website')) return json_response(TRUE, 'Solicitud recibida.');
		if ( ! $v->run()) {
			return json_response(FALSE, 'Revisa los campos marcados.', array('errors' => $v->error_array()), 422);
		}

		$recent = $this->db->where('ip_address', $this->input->ip_address())->where('created_at >=', date('Y-m-d H:i:s', time() - 3600))->count_all_results('membership_requests');
		if ($recent >= 3) return json_response(FALSE, 'Ya recibimos varias solicitudes desde tu conexión. Intenta más tarde.', NULL, 429);
		$this->load->library('recaptcha');
		if ( ! $this->recaptcha->verify($this->input->post('recaptcha_token'), 'membresia')) {
			return json_response(FALSE, 'No pudimos confirmar el envío. Recarga la página e intenta de nuevo.', NULL, 422);
		}

		$fields = array('full_name', 'email', 'phone', 'member_type_id', 'specialty', 'institution', 'city', 'state', 'professional_id', 'comments');
		$data = array();
		foreach ($fields as $f) $data[$f] = $this->input->post($f, TRUE);
		$data['ip_address'] = $this->input->ip_address();
		$this->db->insert('membership_requests', $data);
		$request_id = $this->db->insert_id();

		$this->load->library('mailer');
		if ($to = Mailer::recipients('membership_recipients')) {
			$this->mailer->send($to, 'Nueva solicitud de membresía: '.$data['full_name'], 'membership_notification', $data, $data['email']);
		}
		$this->load->library('push');
		$this->push->send_to_role('all', 'Nueva solicitud de membresía', $data['full_name'], admin_url('memberships/edit/'.$request_id));
		json_response(TRUE, 'Recibimos tu solicitud. La mesa directiva la revisará y te contactará por correo.');
	}
}
