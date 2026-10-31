<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Congresos — SOLO LECTURA.
 * Los datos pertenecen al subsistema de Congresos. Mientras se integra, se usa la
 * tabla demo cg_congresses. Para conectar el subsistema real, ajusta
 * application/config/congress.php (tabla y nombres de columnas).
 */
class Congress_model extends CI_Model
{
	protected $t;
	protected $c;

	public function __construct()
	{
		parent::__construct();
		$this->config->load('congress', TRUE);
		$cfg = $this->config->item('congress');
		$this->t = $cfg['table'];
		$this->c = $cfg['columns'];
	}

	/** Selecciona columnas mapeadas con nombres estándar */
	protected function select()
	{
		$parts = array();
		foreach ($this->c as $alias => $col) {
			$parts[] = $col ? $this->t.'.'.$col.' AS '.$alias : 'NULL AS '.$alias;
		}
		$this->db->select(implode(', ', $parts), FALSE)->from($this->t);
		if ($this->c['is_published']) $this->db->where($this->t.'.'.$this->c['is_published'], 1);
	}

	public function upcoming($limit = NULL)
	{
		$this->select();
		$this->db->where($this->t.'.'.$this->c['starts_at'].' >=', date('Y-m-d'))->order_by($this->c['starts_at'], 'ASC');
		if ($limit) $this->db->limit($limit);
		return $this->decorate($this->db->get()->result());
	}

	public function past($year = NULL)
	{
		$this->select();
		$this->db->where($this->t.'.'.$this->c['starts_at'].' <', date('Y-m-d'));
		if ($year) $this->db->where('YEAR('.$this->t.'.'.$this->c['starts_at'].')', (int) $year, FALSE);
		return $this->decorate($this->db->order_by($this->c['starts_at'], 'DESC')->get()->result());
	}

	public function find($slug)
	{
		$this->select();
		$r = $this->db->where($this->t.'.'.$this->c['slug'], $slug)->get()->row();
		return $r ? $this->decorate(array($r))[0] : NULL;
	}

	public function count_all()
	{
		$this->select();
		return $this->db->count_all_results();
	}

	/** Enlaces de registro/constancias: si el registro no trae URL, se arma con la del subsistema */
	protected function decorate(array $rows)
	{
		$app = rtrim($this->config->item('congress_app_url'), '/').'/';
		foreach ($rows as $r) {
			if (empty($r->registration_url)) $r->registration_url = $app.'registro/'.$r->slug;
			if (empty($r->certificates_url)) $r->certificates_url = $app.'constancias/'.$r->slug;
			$r->is_upcoming = $r->starts_at >= date('Y-m-d');
		}
		return $rows;
	}
}
