<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Reportes del panel: gráfica en pantalla, CSV y PDF con QR */
class Reports extends Admin_Controller
{
	protected $min_role = 'viewer';

	protected $types = array(
		'socios_especialidad' => 'Socios por especialidad',
		'socios_estado'       => 'Socios por estado',
		'biblioteca'          => 'Biblioteca y descargas',
		'mensajes'            => 'Mensajes de contacto',
		'membresias'          => 'Solicitudes de membresía',
	);

	public function index()
	{
		$filters = $this->filters();
		$report = $filters['type'] ? $this->build($filters) : NULL;
		$this->render('reports', array(
			'page_title' => 'Reportes',
			'types'      => $this->types,
			'filters'    => $filters,
			'report'     => $report,
		));
	}

	public function pdf()
	{
		$filters = $this->filters();
		if ( ! $filters['type']) show_404();
		$report = $this->build($filters);
		$folio = $this->folio($filters);

		$this->load->library('pdf_report');
		$this->pdf_report->prepare($report['title'], $folio);
		$this->pdf_report->writeHTML('<p>'.htmlspecialchars($report['note'], ENT_QUOTES, 'UTF-8').'</p>');
		$this->pdf_report->table($report['columns'], $report['rows']);
		$this->pdf_report->Output('sidvvi-'.$folio.'.pdf', 'I');
		exit;
	}

	public function csv()
	{
		$filters = $this->filters();
		if ( ! $filters['type']) show_404();
		$report = $this->build($filters);
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename="sidvvi-'.$filters['type'].'.csv"');
		$out = fopen('php://output', 'w');
		fwrite($out, "\xEF\xBB\xBF");
		fputcsv($out, $report['columns']);
		foreach ($report['rows'] as $row) fputcsv($out, $row);
		fclose($out);
		exit;
	}

	protected function filters()
	{
		$type = (string) $this->input->get('type', TRUE);
		if ( ! isset($this->types[$type])) $type = '';
		$from = (string) $this->input->get('from', TRUE);
		$to = (string) $this->input->get('to', TRUE);
		if ( ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $from)) $from = date('Y-m-d', strtotime('-11 months'));
		if ( ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $to)) $to = date('Y-m-d');
		if ($from > $to) { $swap = $from; $from = $to; $to = $swap; }
		return array('type' => $type, 'from' => $from, 'to' => $to);
	}

	protected function folio(array $filters)
	{
		do {
			$folio = bin2hex(random_bytes(6));
		} while ($this->db->where('folio', $folio)->count_all_results('report_logs'));
		$this->db->insert('report_logs', array(
			'folio'        => $folio,
			'report_type'  => $filters['type'],
			'params'       => json_encode(array('from' => $filters['from'], 'to' => $filters['to'])),
			'generated_by' => $this->admin->id,
		));
		audit_log('create', 'report_logs', $this->db->insert_id(), $folio);
		return $folio;
	}

	protected function build(array $filters)
	{
		$note = 'Periodo del '.fecha_es($filters['from']).' al '.fecha_es($filters['to']).'.';
		switch ($filters['type']) {
			case 'socios_especialidad':
				$rows = $this->db->select('IFNULL(s.name, "Sin especialidad") AS label, COUNT(*) AS total', FALSE)
					->from('members m')->join('specialties s', 's.id = m.specialty_id', 'left')
					->where('m.deleted_at IS NULL', NULL, FALSE)->where('m.is_active', 1)
					->group_by('label')->order_by('total', 'DESC')->get()->result();
				return $this->pack($filters, 'Corte actual de socios activos. No usa el rango de fechas.', 'Especialidad', 'Socios', $rows);
			case 'socios_estado':
				$rows = $this->db->select('IFNULL(NULLIF(state, ""), "Sin estado") AS label, COUNT(*) AS total', FALSE)
					->from('members')->where('deleted_at IS NULL', NULL, FALSE)->where('is_active', 1)
					->group_by('label')->order_by('total', 'DESC')->get()->result();
				return $this->pack($filters, 'Corte actual de socios activos. No usa el rango de fechas.', 'Estado', 'Socios', $rows);
			case 'biblioteca':
				$rows = $this->db->select('DATE_FORMAT(published_at, "%Y-%m") AS label, COUNT(*) AS total, SUM(downloads) AS extra', FALSE)
					->where('deleted_at IS NULL', NULL, FALSE)->where('status', 'published')
					->where('published_at >=', $filters['from'].' 00:00:00')->where('published_at <=', $filters['to'].' 23:59:59')
					->group_by('label')->order_by('label')->get('articles')->result();
				$out = array();
				foreach ($rows as $r) $out[] = array($r->label, (int) $r->total, (int) $r->extra);
				return array(
					'title' => $this->types['biblioteca'], 'note' => $note,
					'columns' => array('Mes', 'Publicaciones', 'Descargas'),
					'rows' => $out, 'labels' => array_column($out, 0), 'values' => array_column($out, 2),
				);
			case 'mensajes':
				$rows = $this->db->select('status AS label, COUNT(*) AS total', FALSE)
					->where('created_at >=', $filters['from'].' 00:00:00')->where('created_at <=', $filters['to'].' 23:59:59')
					->group_by('status')->order_by('total', 'DESC')->get('contact_messages')->result();
				return $this->pack($filters, $note, 'Estado', 'Mensajes', $rows, TRUE);
			default:
				$rows = $this->db->select('status AS label, COUNT(*) AS total', FALSE)
					->where('created_at >=', $filters['from'].' 00:00:00')->where('created_at <=', $filters['to'].' 23:59:59')
					->group_by('status')->order_by('total', 'DESC')->get('membership_requests')->result();
				return $this->pack($filters, $note, 'Estatus', 'Solicitudes', $rows, TRUE);
		}
	}

	protected function pack(array $filters, $note, $col_a, $col_b, array $rows, $status = FALSE)
	{
		$labels = array(); $values = array(); $table = array();
		$map = array(
			'new' => 'Nuevo', 'read' => 'Leído', 'replied' => 'Respondido', 'archived' => 'Archivado', 'spam' => 'Spam',
			'in_review' => 'En revisión', 'approved' => 'Aprobada', 'rejected' => 'Rechazada',
		);
		foreach ($rows as $r) {
			$label = $status ? ($map[$r->label] ?? $r->label) : $r->label;
			$labels[] = $label;
			$values[] = (int) $r->total;
			$table[] = array($label, (int) $r->total);
		}
		return array(
			'title' => $this->types[$filters['type']], 'note' => $note,
			'columns' => array($col_a, $col_b), 'rows' => $table,
			'labels' => $labels, 'values' => $values,
		);
	}
}
