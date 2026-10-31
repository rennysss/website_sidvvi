<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Messages extends Admin_Crud_Controller
{
	protected $table = 'contact_messages';
	protected $route = 'messages';
	protected $title = 'Mensajes de contacto';
	protected $singular = 'mensaje';
	protected $icon = 'fas fa-envelope';
	protected $order_by = 'created_at DESC';
	protected $can_create = FALSE;
	protected $search_fields = array('name', 'email', 'subject', 'message');
	protected $fields = array(
		'created_at' => array('label' => 'Fecha', 'type' => 'readonly', 'list' => TRUE),
		'name'       => array('label' => 'Nombre', 'type' => 'readonly', 'list' => TRUE),
		'email'      => array('label' => 'Correo', 'type' => 'readonly', 'list' => TRUE),
		'phone'      => array('label' => 'Teléfono', 'type' => 'readonly'),
		'subject'    => array('label' => 'Asunto', 'type' => 'readonly', 'list' => TRUE),
		'message'    => array('label' => 'Mensaje', 'type' => 'readonly'),
		'status'     => array('label' => 'Estado', 'type' => 'select', 'list' => TRUE, 'rules' => 'required',
			'options' => array('new' => 'Nuevo', 'read' => 'Leído', 'replied' => 'Respondido', 'archived' => 'Archivado', 'spam' => 'Spam')),
		'notes'      => array('label' => 'Notas internas', 'type' => 'textarea'),
	);

	protected function list_toolbar()
	{
		$status = (string) $this->input->get('status', TRUE);
		$from = (string) $this->input->get('from', TRUE);
		$to = (string) $this->input->get('to', TRUE);
		$opts = array('' => 'Todos', 'new' => 'Nuevos', 'read' => 'Leídos', 'replied' => 'Respondidos', 'archived' => 'Archivados', 'spam' => 'Spam');
		$html = '<form class="form-inline" method="get" action="'.admin_url('messages/export').'">';
		$html .= '<select name="status" class="custom-select custom-select-sm mr-2">';
		foreach ($opts as $k => $label) {
			$html .= '<option value="'.e($k).'"'.($status === $k ? ' selected' : '').'>'.e($label).'</option>';
		}
		$html .= '</select>';
		$html .= '<input type="date" name="from" class="form-control form-control-sm mr-2" value="'.e($from).'">';
		$html .= '<input type="date" name="to" class="form-control form-control-sm mr-2" value="'.e($to).'">';
		$html .= '<button class="btn btn-sm btn-outline-secondary"><i class="fas fa-file-csv"></i> Exportar CSV</button></form>';
		return $html;
	}

	public function export()
	{
		$status = (string) $this->input->get('status', TRUE);
		$from = (string) $this->input->get('from', TRUE);
		$to = (string) $this->input->get('to', TRUE);
		$allowed = array('new', 'read', 'replied', 'archived', 'spam');
		$this->db->from('contact_messages');
		if (in_array($status, $allowed, TRUE)) $this->db->where('status', $status);
		if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $from)) $this->db->where('created_at >=', $from.' 00:00:00');
		if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $to)) $this->db->where('created_at <=', $to.' 23:59:59');
		$rows = $this->db->order_by('created_at', 'DESC')->get()->result();

		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename="mensajes-sidvvi.csv"');
		$out = fopen('php://output', 'w');
		fwrite($out, "\xEF\xBB\xBF");
		fputcsv($out, array('Fecha', 'Nombre', 'Correo', 'Teléfono', 'Asunto', 'Mensaje', 'Estado'));
		foreach ($rows as $r) {
			fputcsv($out, array($r->created_at, $r->name, $r->email, $r->phone, $r->subject, $r->message, $r->status));
		}
		fclose($out);
		exit;
	}

	/** Al abrir un mensaje nuevo se marca como leído */
	public function edit($id = NULL)
	{
		if ($this->input->method() === 'get') {
			$this->db->where(array('id' => (int) $id, 'status' => 'new'))->update($this->table, array('status' => 'read'));
		}
		parent::edit($id);
	}

	protected function form_extra($row)
	{
		if ( ! $row) return '';
		return '<div class="col-12 mb-3"><a class="btn btn-outline-primary" href="mailto:'.e($row->email).'?subject='.rawurlencode('Re: '.$row->subject).'"><i class="fas fa-reply"></i> Responder por correo</a></div>';
	}
}
