<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Congresos (datos del subsistema de Congresos): /congresos */
class Congresses extends Public_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Congress_model');
	}

	public function index()
	{
		$year = (int) $this->input->get('anio');
		$this->seo->set('Congresos', 'Próximos congresos, cursos y simposios de SIDVVI e histórico de ediciones anteriores.');
		$past = $this->Congress_model->past();
		$years = array_values(array_unique(array_map(function ($c) { return date('Y', strtotime($c->starts_at)); }, $past)));

		$this->render('congresses/index', array(
			'active_menu' => 'congresses',
			'upcoming' => $this->Congress_model->upcoming(),
			'past'     => $year ? $this->Congress_model->past($year) : $past,
			'years'    => $years, 'year' => $year,
		), array('title' => 'Congresos', 'crumbs' => array(array('Congresos', NULL))));
	}

	public function show($slug = '')
	{
		$c = $this->Congress_model->find($slug);
		if ( ! $c) return $this->not_found();
		$this->seo->set($c->name, $c->summary, $c->image);
		$this->seo->add_jsonld(array(
			'@context' => 'https://schema.org', '@type' => 'Event', 'name' => $c->name,
			'startDate' => $c->starts_at, 'endDate' => $c->ends_at ?: $c->starts_at,
			'eventAttendanceMode' => $c->modality === 'virtual' ? 'https://schema.org/OnlineEventAttendanceMode' : 'https://schema.org/OfflineEventAttendanceMode',
			'location' => array('@type' => 'Place', 'name' => $c->venue ?: $c->city, 'address' => $c->city),
			'image' => img_url($c->image), 'organizer' => array('@type' => 'Organization', 'name' => 'SIDVVI', 'url' => base_url()),
		));
		$this->render('congresses/show', array(
			'active_menu' => 'congresses', 'c' => $c,
			'others' => array_filter($this->Congress_model->upcoming(4), function ($o) use ($c) { return $o->id != $c->id; }),
		), array('title' => $c->name, 'crumbs' => array(array('Congresos', 'congresos'), array(str_limit($c->name, 40), NULL))));
	}
}
