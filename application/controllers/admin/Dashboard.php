<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller
{
	public function index()
	{
		$this->load->model('Congress_model');
		$db = $this->db;
		$cards = array(
			array('Socios activos', $db->where('is_active', 1)->where('deleted_at IS NULL', NULL, FALSE)->count_all_results('members'), 'fas fa-user-md', 'members', 'primary'),
			array('Publicaciones', $db->where('status', 'published')->where('deleted_at IS NULL', NULL, FALSE)->count_all_results('articles'), 'fas fa-book-medical', 'articles', 'info'),
			array('Mensajes nuevos', $this->data['nav_counts']['messages'], 'fas fa-envelope', 'messages', 'danger'),
			array('Solicitudes de membresía', $this->data['nav_counts']['memberships'], 'fas fa-user-plus', 'memberships', 'warning'),
		);

		// Socios por especialidad (gráfica)
		$by_spec = $db->select('COALESCE(s.name, "Sin especialidad") AS label, COUNT(m.id) AS total', FALSE)
			->from('members m')->join('specialties s', 's.id = m.specialty_id', 'left')
			->where('m.is_active', 1)->where('m.deleted_at IS NULL', NULL, FALSE)
			->group_by('label')->order_by('total', 'DESC')->get()->result();

		// Mensajes por mes (últimos 6 meses)
		$months = array();
		for ($i = 5; $i >= 0; $i--) $months[date('Y-m', strtotime("-$i month"))] = 0;
		foreach ($db->query("SELECT DATE_FORMAT(created_at,'%Y-%m') ym, COUNT(*) n FROM contact_messages WHERE created_at >= ? GROUP BY ym", array(date('Y-m-01', strtotime('-5 month'))))->result() as $r) {
			if (isset($months[$r->ym])) $months[$r->ym] = (int) $r->n;
		}

		$this->render('dashboard', array(
			'page_title' => 'Panel de control',
			'cards' => $cards,
			'by_spec' => $by_spec,
			'months' => $months,
			'upcoming' => $this->Congress_model->upcoming(3),
			'last_messages' => $db->order_by('created_at', 'DESC')->limit(5)->get('contact_messages')->result(),
			'last_articles' => $db->where('deleted_at IS NULL', NULL, FALSE)->order_by('updated_at DESC, created_at DESC')->limit(5)->get('articles')->result(),
		));
	}
}
