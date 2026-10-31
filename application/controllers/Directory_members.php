<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Directorio de socios: /directorio */
class Directory_members extends Public_Controller
{
	const PER_PAGE = 9;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Member_model');
	}

	protected function filters()
	{
		return array(
			'q'         => trim((string) $this->input->get('q', TRUE)),
			'specialty' => (int) $this->input->get('specialty'),
			'type'      => (int) $this->input->get('type'),
			'state'     => trim((string) $this->input->get('state', TRUE)),
			'page'      => max(1, (int) $this->input->get('page')),
		);
	}

	public function index()
	{
		$f = $this->filters();
		list($members, $total) = $this->Member_model->search($f, self::PER_PAGE, ($f['page'] - 1) * self::PER_PAGE);
		$this->seo->set('Directorio de socios', 'Consulta el directorio de socios de SIDVVI por especialidad, estado y tipo de socio.');

		$this->render('directory/index', array(
			'active_menu' => 'directory',
			'f' => $f, 'members' => $members, 'total' => $total,
			'pages' => (int) ceil($total / self::PER_PAGE),
			'specialties' => $this->db->order_by('name')->get('specialties')->result(),
			'types'  => $this->db->get('member_types')->result(),
			'states' => $this->Member_model->states(),
		), array('title' => 'Directorio de socios', 'crumbs' => array(array('Directorio', NULL))));
	}

	/** Ajax: devuelve el HTML del listado filtrado */
	public function search()
	{
		$f = $this->filters();
		list($members, $total) = $this->Member_model->search($f, self::PER_PAGE, ($f['page'] - 1) * self::PER_PAGE);
		$html = $this->load->view('public/directory/_list', array(
			'members' => $members, 'total' => $total, 'f' => $f, 'pages' => (int) ceil($total / self::PER_PAGE),
		), TRUE);
		json_response(TRUE, '', array('html' => $html, 'total' => $total));
	}

	public function show($slug = '')
	{
		$m = $this->Member_model->find_public($slug);
		if ( ! $m) return $this->not_found();
		$name = trim($m->title_prefix.' '.$m->first_name.' '.$m->last_name);
		$this->seo->set($name, $m->position.' · '.$m->institution, $m->photo);
		$this->render('directory/show', array('active_menu' => 'directory', 'm' => $m, 'name' => $name),
			array('title' => $name, 'crumbs' => array(array('Directorio', 'directorio'), array($name, NULL))));
	}
}
