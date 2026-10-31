<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** /sitemap.xml dinámico */
class Sitemap extends MY_Controller
{
	public function index()
	{
		$this->load->model(array('Article_model', 'Congress_model', 'Member_model'));
		$urls = array('', 'nosotros', 'mesa-directiva', 'directorio', 'biblioteca', 'congresos', 'noticias', 'membresia', 'constancias', 'contacto');
		$items = array();
		foreach ($urls as $u) $items[] = array('loc' => site_url($u), 'priority' => $u === '' ? '1.0' : '0.8');
		foreach ($this->Article_model->latest(1000) as $a) $items[] = array('loc' => site_url('biblioteca/'.$a->slug), 'lastmod' => date('Y-m-d', strtotime($a->published_at)), 'priority' => '0.6');
		foreach (array_merge($this->Congress_model->upcoming(), $this->Congress_model->past()) as $c) $items[] = array('loc' => site_url('congresos/'.$c->slug), 'priority' => '0.7');
		list($members) = $this->Member_model->search(array(), 5000, 0);
		foreach ($members as $m) $items[] = array('loc' => site_url('directorio/'.$m->slug), 'priority' => '0.4');

		$xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
		foreach ($items as $i) {
			$xml .= '<url><loc>'.e($i['loc']).'</loc>'.(isset($i['lastmod']) ? '<lastmod>'.$i['lastmod'].'</lastmod>' : '').'<priority>'.$i['priority'].'</priority></url>'."\n";
		}
		$this->output->set_content_type('application/xml', 'utf-8')->set_output($xml.'</urlset>');
	}
}
