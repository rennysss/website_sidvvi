<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Página pública que abre el QR de un reporte PDF */
class Verify extends Public_Controller
{
	protected $labels = array(
		'socios_especialidad' => 'Socios por especialidad',
		'socios_estado'       => 'Socios por estado',
		'biblioteca'          => 'Biblioteca y descargas',
		'mensajes'            => 'Mensajes de contacto',
		'membresias'          => 'Solicitudes de membresía',
	);

	public function show($folio = '')
	{
		$folio = strtolower(preg_replace('/[^a-f0-9]/', '', (string) $folio));
		$row = $this->db->select('r.*, u.name AS author')
			->from('report_logs r')
			->join('users u', 'u.id = r.generated_by', 'left')
			->where('r.folio', $folio)
			->get()->row();
		$this->seo->robots = 'noindex,nofollow';
		if ( ! $row) return $this->not_found();
		$this->seo->set('Verificación de reporte', 'Comprobante del reporte '.$folio.'.');
		$this->render('verify', array(
			'report' => $row,
			'label'  => $this->labels[$row->report_type] ?? $row->report_type,
		), array('title' => 'Verificación de reporte', 'crumbs' => array(array('Verificación', NULL))));
	}
}
