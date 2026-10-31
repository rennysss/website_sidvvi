<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Biblioteca Virtual */
class Article_model extends MY_Model
{
	protected $table = 'articles';
	protected $soft_delete = TRUE;

	public static $types = array('article' => 'Artículo', 'report' => 'Reporte', 'publication' => 'Publicación', 'guide' => 'Guía');

	protected function published()
	{
		$this->db->select('articles.*, categories.name AS category, categories.slug AS category_slug')
			->from('articles')
			->join('categories', 'categories.id = articles.category_id', 'left')
			->where('articles.status', 'published')
			->where('articles.published_at <=', date('Y-m-d H:i:s'))
			->where('articles.deleted_at IS NULL', NULL, FALSE);
	}

	public function latest($limit = 3, $exclude_id = NULL, $category_id = NULL)
	{
		$this->published();
		if ($exclude_id) $this->db->where('articles.id !=', $exclude_id);
		if ($category_id) $this->db->where('articles.category_id', $category_id);
		return $this->db->order_by('articles.published_at', 'DESC')->limit($limit)->get()->result();
	}

	public function search($f, $limit, $offset)
	{
		$this->published();
		if ( ! empty($f['category'])) $this->db->where('categories.slug', $f['category']);
		if ( ! empty($f['type']))     $this->db->where('articles.type', $f['type']);
		if ( ! empty($f['year']))     $this->db->where('YEAR(articles.published_at)', (int) $f['year'], FALSE);
		if ( ! empty($f['tag'])) {
			$this->db->join('article_tags at', 'at.article_id = articles.id')->join('tags t', 't.id = at.tag_id')->where('t.slug', $f['tag']);
		}
		if ( ! empty($f['q'])) {
			$this->db->group_start()->like('articles.title', $f['q'])->or_like('articles.excerpt', $f['q'])->or_like('articles.author_name', $f['q'])->group_end();
		}
		$total = $this->db->count_all_results('', FALSE);
		$rows = $this->db->order_by('articles.published_at', 'DESC')->limit($limit, $offset)->get()->result();
		return array($rows, $total);
	}

	public function find_published($slug)
	{
		$this->published();
		return $this->db->where('articles.slug', $slug)->get()->row();
	}

	public function tags_for($article_id)
	{
		return $this->db->select('tags.*')->join('article_tags', 'article_tags.tag_id = tags.id')
			->where('article_tags.article_id', $article_id)->get('tags')->result();
	}

	public function categories_with_count()
	{
		return $this->db->select('categories.*, COUNT(a.id) AS total')
			->join('articles a', "a.category_id = categories.id AND a.status = 'published' AND a.deleted_at IS NULL", 'left')
			->group_by('categories.id')->order_by('categories.sort_order')->get('categories')->result();
	}

	public function years()
	{
		return array_column($this->db->query("SELECT DISTINCT YEAR(published_at) y FROM articles WHERE status='published' AND deleted_at IS NULL AND published_at IS NOT NULL ORDER BY y DESC")->result_array(), 'y');
	}

	public function all_tags()
	{
		return $this->db->order_by('name')->get('tags')->result();
	}

	public function add_view($id)
	{
		$this->db->set('views', 'views + 1', FALSE)->where('id', $id)->update('articles');
	}
}
