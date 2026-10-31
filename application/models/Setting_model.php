<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Configuración general del sitio (tabla settings), cacheada por petición */
class Setting_model extends CI_Model
{
	protected $cache = NULL;

	public function all()
	{
		if ($this->cache === NULL) {
			$this->cache = array();
			foreach ($this->db->get('settings')->result() as $row) {
				$this->cache[$row->key] = $row->value;
			}
		}
		return $this->cache;
	}

	public function get($key, $default = '')
	{
		$all = $this->all();
		return (isset($all[$key]) && $all[$key] !== '') ? $all[$key] : $default;
	}

	public function grouped()
	{
		$rows = $this->db->order_by('sort_order')->get('settings')->result();
		$out = array();
		foreach ($rows as $r) $out[$r->group][] = $r;
		return $out;
	}

	public function save(array $data)
	{
		foreach ($data as $key => $value) {
			$this->db->where('key', $key)->update('settings', array('value' => $value));
		}
		$this->cache = NULL;
	}
}
