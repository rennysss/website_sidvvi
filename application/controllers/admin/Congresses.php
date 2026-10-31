<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Congresos: SOLO CONSULTA. Se administran en el subsistema de Congresos.
 */
class Congresses extends Admin_Controller
{
	public function index()
	{
		$this->load->model('Congress_model');
		$this->render('congresses', array(
			'page_title' => 'Congresos',
			'upcoming' => $this->Congress_model->upcoming(),
			'past' => $this->Congress_model->past(),
			'app_url' => $this->config->item('congress_app_url'),
		));
	}
}
