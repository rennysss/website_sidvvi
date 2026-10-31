<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Articles extends Admin_Crud_Controller
{
	protected $table = 'articles';
	protected $route = 'articles';
	protected $title = 'Biblioteca Virtual';
	protected $singular = 'publicación';
	protected $icon = 'fas fa-book-medical';
	protected $order_by = 'published_at DESC, id DESC';
	protected $soft_delete = TRUE;
	protected $search_fields = array('title', 'author_name', 'excerpt');
	protected $slug_field = 'slug';
	protected $slug_source = 'title';

	protected $fields = array(
		'cover_image'  => array('label' => 'Portada', 'type' => 'image', 'list' => TRUE, 'help' => '1200×780 px recomendado.'),
		'title'        => array('label' => 'Título', 'type' => 'text', 'rules' => 'required|max_length[255]', 'list' => TRUE),
		'slug'         => array('label' => 'URL (slug)', 'type' => 'text', 'help' => 'Se genera del título si lo dejas vacío.'),
		'type'         => array('label' => 'Tipo', 'type' => 'select', 'col' => 4, 'rules' => 'required', 'list' => TRUE,
			'options' => array('article' => 'Artículo', 'report' => 'Reporte', 'publication' => 'Publicación', 'guide' => 'Guía')),
		'category_id'  => array('label' => 'Categoría', 'type' => 'select', 'col' => 4, 'list' => TRUE, 'source' => array('table' => 'categories', 'label' => 'name', 'order' => 'sort_order')),
		'author_name'  => array('label' => 'Autor', 'type' => 'text', 'col' => 4),
		'excerpt'      => array('label' => 'Resumen', 'type' => 'textarea', 'rules' => 'max_length[500]'),
		'body'         => array('label' => 'Contenido', 'type' => 'html'),
		'attachment'   => array('label' => 'Documento adjunto (PDF)', 'type' => 'file'),
		'meta_description' => array('label' => 'Descripción SEO', 'type' => 'text', 'rules' => 'max_length[160]', 'help' => 'Máx. 160 caracteres. Si se deja vacío se usa el resumen.'),
		'status'       => array('label' => 'Estado', 'type' => 'select', 'col' => 6, 'rules' => 'required', 'list' => TRUE,
			'options' => array('draft' => 'Borrador', 'published' => 'Publicado', 'archived' => 'Archivado')),
		'published_at' => array('label' => 'Fecha de publicación', 'type' => 'datetime', 'col' => 6, 'list' => TRUE, 'help' => 'Si es futura, se publicará en esa fecha.'),
	);

	protected function before_save(array &$data, $row)
	{
		if ($data['status'] === 'published' && empty($data['published_at'])) $data['published_at'] = date('Y-m-d H:i:s');
	}

	protected function after_save($id, $row)
	{
		$this->db->where('article_id', $id)->delete('article_tags');
		foreach ((array) $this->input->post('tags') as $tag_id) {
			$this->db->insert('article_tags', array('article_id' => $id, 'tag_id' => (int) $tag_id));
		}
	}

	/** Selector de temas (etiquetas) dentro del formulario */
	protected function form_extra($row)
	{
		$selected = array();
		if ($row && ! empty($row->id)) {
			$selected = array_column($this->db->where('article_id', $row->id)->get('article_tags')->result_array(), 'tag_id');
		}
		$html = '<div class="form-group col-12"><label>Temas</label><div class="tag-checks">';
		foreach ($this->db->order_by('name')->get('tags')->result() as $t) {
			$chk = in_array($t->id, $selected) ? ' checked' : '';
			$html .= '<div class="custom-control custom-checkbox custom-control-inline"><input type="checkbox" class="custom-control-input" id="tag'.$t->id.'" name="tags[]" value="'.$t->id.'"'.$chk.'><label class="custom-control-label" for="tag'.$t->id.'">'.e($t->name).'</label></div>';
		}
		return $html.'</div><small class="form-text text-muted"><a href="'.admin_url('tags').'">Administrar temas</a></small></div>';
	}
}
