<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Base de todas las páginas públicas de https://sidvvi.com */
class Public_Controller extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('seo');
		$this->data['social_links'] = $this->db->where('is_active', 1)->order_by('sort_order')->get('social_links')->result();
		$this->data['active_menu'] = '';
	}

	/** Muestra la página 404 con el diseño del sitio y termina */
	protected function not_found()
	{
		$this->output->set_status_header(404);
		$this->seo->set('Página no encontrada');
		$this->render('404', array(), array('title' => 'Página no encontrada', 'crumbs' => array(array('404', NULL))));
		$this->output->_display();
		exit;
	}

	/**
	 * Renderiza una vista dentro del layout público.
	 * @param string $view   vista dentro de views/public/
	 * @param array  $data
	 * @param array  $header ['title' => ..., 'crumbs' => [['Texto','url'],...]]  (encabezado de página interna)
	 */
	protected function render($view, $data = array(), $header = NULL)
	{
		$data = array_merge($this->data, $data);
		$data['page_header'] = $header;
		$data['seo'] = $this->seo;
		$data['content'] = $this->load->view('public/'.$view, $data, TRUE);
		$this->load->view('layouts/public', $data);
	}
}
