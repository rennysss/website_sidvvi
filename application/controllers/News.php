<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Noticias: publicaciones de Instagram.
 * Hoy lee la tabla instagram_cache (datos dummy). En la fase 6 la librería
 * Instagram_feed llenará esa tabla desde la API de Instagram cada hora.
 */
class News extends Public_Controller
{
	public function index()
	{
		$this->load->model('Content_model');
		$this->seo->set('Noticias', 'Las publicaciones más recientes de SIDVVI en Instagram.');
		$this->render('news', array(
			'active_menu' => 'news',
			'posts' => $this->Content_model->instagram(24),
		), array('title' => 'Noticias', 'crumbs' => array(array('Noticias', NULL))));
	}
}
