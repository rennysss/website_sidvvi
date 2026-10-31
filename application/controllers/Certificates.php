<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Constancias: /constancias
 * La emisión de constancias vive en el subsistema de Congresos; esta página
 * es el punto de acceso desde el sitio y envía al subsistema.
 */
class Certificates extends Public_Controller
{
	public function index()
	{
		$this->load->model('Congress_model');
		$this->seo->set('Constancias', 'Descarga tus constancias de participación en congresos y cursos de SIDVVI.');
		$this->render('certificates', array(
			'active_menu' => 'certificates',
			'congresses' => $this->Congress_model->past(),
			'app_url' => rtrim($this->config->item('congress_app_url'), '/').'/',
		), array('title' => 'Constancias', 'crumbs' => array(array('Constancias', NULL))));
	}
}
