<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Public_Controller
{
	public function index()
	{
		$this->load->model(array('Content_model', 'Article_model', 'Congress_model'));
		$this->seo->set('');

		$this->render('home', array(
			'active_menu' => 'home',
			'banners'     => $this->Content_model->banners(),
			'blocks'      => $this->Content_model->blocks(array('president_message', 'cta_membership')),
			'areas'       => $this->Content_model->areas(),
			'stats'       => $this->Content_model->stats(),
			'congresses'  => $this->Congress_model->upcoming(3),
			'board'       => $this->Content_model->board(4),
			'quick_links' => $this->Content_model->quick_links(),
			'documents'   => $this->Content_model->documents(4),
			'articles'    => $this->Article_model->latest(3),
			'instagram'   => $this->Content_model->instagram(6),
		));
	}
}
