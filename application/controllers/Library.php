<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Biblioteca Virtual: /biblioteca */
class Library extends Public_Controller
{
	const PER_PAGE = 9;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Article_model');
	}

	public function index($category = NULL)
	{
		$f = array(
			'category' => $category ?: $this->input->get('categoria', TRUE),
			'tag'  => $this->input->get('tema', TRUE),
			'type' => $this->input->get('tipo', TRUE),
			'year' => $this->input->get('anio', TRUE),
			'q'    => trim((string) $this->input->get('q', TRUE)),
		);
		$page = max(1, (int) $this->input->get('page'));
		list($articles, $total) = $this->Article_model->search($f, self::PER_PAGE, ($page - 1) * self::PER_PAGE);

		$cat_name = NULL;
		if ($f['category']) {
			$cat = $this->db->where('slug', $f['category'])->get('categories')->row();
			if ( ! $cat) return $this->not_found();
			$cat_name = $cat->name;
		}
		$title = $cat_name ? $cat_name : 'Biblioteca Virtual';
		$this->seo->set($title, 'Artículos, guías, reportes y publicaciones de SIDVVI.');

		$crumbs = array(array('Biblioteca Virtual', $cat_name ? 'biblioteca' : NULL));
		if ($cat_name) $crumbs[] = array($cat_name, NULL);

		$this->render('library/index', array(
			'active_menu' => 'library', 'f' => $f, 'articles' => $articles, 'total' => $total,
			'page' => $page, 'pages' => (int) ceil($total / self::PER_PAGE),
			'categories' => $this->Article_model->categories_with_count(),
			'tags' => $this->Article_model->all_tags(),
			'years' => $this->Article_model->years(),
			'recent' => $this->Article_model->latest(3),
		), array('title' => $title, 'crumbs' => $crumbs));
	}

	public function show($slug = '')
	{
		$a = $this->Article_model->find_published($slug);
		if ( ! $a) return $this->not_found();
		$this->Article_model->add_view($a->id);

		$this->seo->set($a->title, $a->meta_description ?: $a->excerpt, $a->cover_image);
		$this->seo->type = 'article';
		$this->seo->add_jsonld(array(
			'@context' => 'https://schema.org', '@type' => 'Article', 'headline' => $a->title,
			'author' => array('@type' => 'Person', 'name' => $a->author_name),
			'datePublished' => date('c', strtotime($a->published_at)),
			'image' => img_url($a->cover_image), 'publisher' => array('@type' => 'Organization', 'name' => 'SIDVVI'),
		));

		$this->render('library/show', array(
			'active_menu' => 'library', 'a' => $a,
			'tags' => $this->Article_model->tags_for($a->id),
			'related' => $this->Article_model->latest(3, $a->id, $a->category_id),
			'categories' => $this->Article_model->categories_with_count(),
			'recent' => $this->Article_model->latest(3, $a->id),
		), array('title' => 'Biblioteca Virtual', 'crumbs' => array(array('Biblioteca Virtual', 'biblioteca'), array(str_limit($a->title, 40), NULL))));
	}

	/** Descarga controlada del adjunto (cuenta descargas) */
	public function download($id = 0)
	{
		$a = $this->Article_model->get((int) $id);
		if ( ! $a || $a->status !== 'published' || empty($a->attachment) || ! is_file(FCPATH.$a->attachment)) return $this->not_found();
		$this->db->set('downloads', 'downloads + 1', FALSE)->where('id', $a->id)->update('articles');
		$this->load->helper('download');
		force_download(slugify($a->title).'.'.pathinfo($a->attachment, PATHINFO_EXTENSION), file_get_contents(FCPATH.$a->attachment));
	}
}
