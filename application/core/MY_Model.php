<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo base con CRUD genérico.
 * Los modelos hijos definen $table y, si la tabla tiene deleted_at, $soft_delete = TRUE.
 */
class MY_Model extends CI_Model
{
	protected $table = '';
	protected $primary_key = 'id';
	protected $soft_delete = FALSE;

	public function __construct($table = NULL, $soft_delete = NULL)
	{
		parent::__construct();
		if ($table !== NULL) $this->table = $table;
		if ($soft_delete !== NULL) $this->soft_delete = $soft_delete;
	}

	public function table() { return $this->table; }

	protected function scope()
	{
		if ($this->soft_delete) $this->db->where($this->table.'.deleted_at IS NULL', NULL, FALSE);
	}

	public function get($id)
	{
		$this->scope();
		return $this->db->where($this->table.'.'.$this->primary_key, $id)->get($this->table)->row();
	}

	public function get_by($field, $value)
	{
		$this->scope();
		return $this->db->where($field, $value)->get($this->table)->row();
	}

	public function get_all($where = array(), $order_by = NULL, $limit = NULL, $offset = 0)
	{
		$this->scope();
		if ($where) $this->db->where($where);
		if ($order_by) $this->db->order_by($order_by);
		if ($limit) $this->db->limit($limit, $offset);
		return $this->db->get($this->table)->result();
	}

	public function count($where = array())
	{
		$this->scope();
		if ($where) $this->db->where($where);
		return $this->db->count_all_results($this->table);
	}

	public function insert(array $data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($id, array $data)
	{
		return $this->db->where($this->primary_key, $id)->update($this->table, $data);
	}

	public function delete($id)
	{
		if ($this->soft_delete) {
			return $this->update($id, array('deleted_at' => date('Y-m-d H:i:s')));
		}
		return $this->db->where($this->primary_key, $id)->delete($this->table);
	}

	/** Genera un slug único para la tabla */
	public function unique_slug($text, $ignore_id = NULL, $field = 'slug')
	{
		$base = slugify($text) ?: 'item';
		$slug = $base; $i = 2;
		while (TRUE) {
			$this->db->where($field, $slug);
			if ($ignore_id) $this->db->where($this->primary_key.' !=', $ignore_id);
			if ( ! $this->db->count_all_results($this->table)) return $slug;
			$slug = $base.'-'.$i++;
		}
	}
}
