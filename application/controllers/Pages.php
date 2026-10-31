<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Páginas institucionales: Nosotros, Mesa directiva, Aviso de privacidad */
class Pages extends Public_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Content_model');
	}

	public function about()
	{
		$blocks = $this->Content_model->blocks(array('about', 'mission', 'vision', 'values', 'president_message', 'cta_membership'));
		$this->seo->set('Nosotros', $blocks['about']->body);
		$this->render('about', array(
			'active_menu' => 'about',
			'blocks' => $blocks,
			'areas'  => $this->Content_model->areas(),
			'stats'  => $this->Content_model->stats(),
			'board'  => $this->Content_model->board(4),
		), array('title' => 'Nosotros', 'crumbs' => array(array('Nosotros', NULL))));
	}

	public function board()
	{
		$this->seo->set('Mesa directiva', 'Conoce a la mesa directiva de SIDVVI.');
		$this->render('board', array(
			'active_menu' => 'about',
			'board' => $this->Content_model->board(),
		), array('title' => 'Mesa directiva', 'crumbs' => array(array('Nosotros', 'nosotros'), array('Mesa directiva', NULL))));
	}

	public function privacy()
	{
		$b = $this->Content_model->block('privacy');
		$this->seo->set('Aviso de privacidad');
		$this->render('simple', array('block' => $b),
			array('title' => 'Aviso de privacidad', 'crumbs' => array(array('Aviso de privacidad', NULL))));
	}
}
