<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| Integración con el subsistema de Congresos (solo lectura).
| Cambia 'table' y el mapeo de 'columns' para apuntar a las tablas reales del
| subsistema. Una columna en NULL se devuelve vacía.
*/
$config['table'] = 'cg_congresses';   // TABLA DEMO
$config['columns'] = array(
	'id'               => 'id',
	'name'             => 'name',
	'slug'             => 'slug',
	'edition'          => 'edition',
	'starts_at'        => 'starts_at',
	'ends_at'          => 'ends_at',
	'venue'            => 'venue',
	'city'             => 'city',
	'modality'         => 'modality',
	'image'            => 'image',
	'summary'          => 'summary',
	'description'      => 'description',
	'registration_url' => 'registration_url',
	'certificates_url' => 'certificates_url',
	'is_published'     => 'is_published',
);
