<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Contenido general administrable del sitio (Inicio, Nosotros, etc.) */
class Content_model extends CI_Model
{
	public function banners()
	{
		$now = date('Y-m-d H:i:s');
		return $this->db->where('status', 'published')->where('deleted_at IS NULL', NULL, FALSE)
			->group_start()->where('starts_at IS NULL', NULL, FALSE)->or_where('starts_at <=', $now)->group_end()
			->group_start()->where('ends_at IS NULL', NULL, FALSE)->or_where('ends_at >=', $now)->group_end()
			->order_by('sort_order')->get('banners')->result();
	}

	/** Bloque de texto por código (about, mission, vision, president_message, ...) */
	public function block($code)
	{
		$b = $this->db->where(array('code' => $code, 'is_active' => 1))->get('page_blocks')->row();
		return $b ?: (object) array('code' => $code, 'eyebrow' => '', 'title' => '', 'body' => '', 'image' => '', 'extra' => '');
	}

	public function blocks(array $codes)
	{
		$out = array();
		foreach ($codes as $c) $out[$c] = $this->block($c);
		return $out;
	}

	public function areas()       { return $this->active('areas'); }
	public function quick_links() { return $this->active('quick_links'); }
	public function board($limit = NULL) { return $this->active('board_members', $limit); }

	public function documents($limit = NULL)
	{
		return $this->active('documents', $limit, 'sort_order, published_at DESC');
	}

	public function instagram($limit = 8)
	{
		return $this->db->order_by('posted_at', 'DESC')->limit($limit)->get('instagram_cache')->result();
	}

	public function stats()
	{
		$founded = (int) setting('founded_year', date('Y'));
		$this->load->model('Congress_model');
		return array(
			array('icon' => 'fas fa-user-md',     'value' => $this->db->where(array('is_active' => 1))->where('deleted_at IS NULL', NULL, FALSE)->count_all_results('members'), 'label' => 'Socios activos'),
			array('icon' => 'fas fa-calendar-check', 'value' => $this->Congress_model->count_all(), 'label' => 'Congresos y eventos'),
			array('icon' => 'fas fa-book-medical', 'value' => $this->db->where('status', 'published')->where('deleted_at IS NULL', NULL, FALSE)->count_all_results('articles'), 'label' => 'Publicaciones'),
			array('icon' => 'fas fa-award',       'value' => max(1, (int) date('Y') - $founded), 'label' => 'Años de trayectoria'),
		);
	}

	protected function active($table, $limit = NULL, $order = 'sort_order')
	{
		$this->db->where('is_active', 1)->order_by($order);
		if ($limit) $this->db->limit($limit);
		return $this->db->get($table)->result();
	}
}
