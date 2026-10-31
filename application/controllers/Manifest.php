<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** manifest.json con las URLs reales del entorno (raíz o /sidvvi en MAMP) */
class Manifest extends MY_Controller
{
	public function index()
	{
		$base = base_url();
		$data = array(
			'name'             => 'SIDVVI · Sociedad de Investigación y Docencia en Virología, Vacunas e Infectología',
			'short_name'       => 'SIDVVI',
			'start_url'        => $base,
			'scope'            => $base,
			'display'          => 'standalone',
			'background_color' => '#ffffff',
			'theme_color'      => '#23477A',
			'lang'             => 'es-MX',
			'icons'            => array(
				array('src' => $base.'assets/img/icon-192.png', 'sizes' => '192x192', 'type' => 'image/png'),
				array('src' => $base.'assets/img/icon-512.png', 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'any maskable'),
			),
		);
		$this->output->set_content_type('application/manifest+json', 'utf-8')
			->set_output(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}
}
