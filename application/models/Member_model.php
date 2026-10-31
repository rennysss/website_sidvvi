<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Directorio de socios */
class Member_model extends MY_Model
{
	protected $table = 'members';
	protected $soft_delete = TRUE;

	protected function base($filters)
	{
		$this->db->select('members.*, specialties.name AS specialty, member_types.name AS member_type')
			->from('members')
			->join('specialties', 'specialties.id = members.specialty_id', 'left')
			->join('member_types', 'member_types.id = members.member_type_id', 'left')
			->where(array('members.is_active' => 1, 'members.is_public' => 1))
			->where('members.deleted_at IS NULL', NULL, FALSE);

		if ( ! empty($filters['q'])) {
			$this->db->group_start()
				->like('members.first_name', $filters['q'])->or_like('members.last_name', $filters['q'])
				->or_like('members.institution', $filters['q'])->or_like('members.city', $filters['q'])
				->group_end();
		}
		if ( ! empty($filters['specialty'])) $this->db->where('members.specialty_id', (int) $filters['specialty']);
		if ( ! empty($filters['type']))      $this->db->where('members.member_type_id', (int) $filters['type']);
		if ( ! empty($filters['state']))     $this->db->where('members.state', $filters['state']);
	}

	public function search($filters, $limit, $offset)
	{
		$this->base($filters);
		$total = $this->db->count_all_results('', FALSE);
		$rows = $this->db->order_by('members.last_name, members.first_name')->limit($limit, $offset)->get()->result();
		return array($rows, $total);
	}

	public function find_public($slug)
	{
		$this->base(array());
		return $this->db->where('members.slug', $slug)->get()->row();
	}

	public function states()
	{
		return array_column($this->db->distinct()->select('state')->where('is_active', 1)->where('is_public', 1)
			->where('state IS NOT NULL', NULL, FALSE)->order_by('state')->get('members')->result_array(), 'state');
	}
}
