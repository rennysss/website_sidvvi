<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CRUD genérico del panel. Cada módulo solo declara su tabla y sus campos.
 *
 * Tipos de campo: text, email, url, number, textarea, html, image, file, select,
 *                 checkbox, date, datetime, password, icon, readonly
 *
 * Ejemplo de campo:
 *   'title' => ['label' => 'Título', 'type' => 'text', 'rules' => 'required|max_length[200]', 'list' => TRUE]
 *   'category_id' => ['label' => 'Categoría', 'type' => 'select', 'source' => ['table' => 'categories', 'label' => 'name']]
 */
abstract class Admin_Crud_Controller extends Admin_Controller
{
	protected $table;
	protected $route;               // ej. 'banners'  → /admin-panel/banners
	protected $title;               // plural (Banners)
	protected $singular;            // singular (banner)
	protected $icon = 'fas fa-list';
	protected $fields = array();
	protected $order_by = 'id DESC';
	protected $search_fields = array();
	protected $soft_delete = FALSE;
	protected $slug_field = NULL;   // ej. 'slug'
	protected $slug_source = NULL;  // ej. 'title'
	protected $can_create = TRUE;
	protected $can_delete = TRUE;
	protected $edit_role = 'editor';
	protected $per_page = 20;
	protected $help = '';           // texto de ayuda arriba del listado

	protected $model;

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('form_validation', 'upload'));
		$this->model = new MY_Model($this->table, $this->soft_delete);
	}

	// ------------------------------------------------------------------ Listado
	public function index()
	{
		$q    = trim((string) $this->input->get('q', TRUE));
		$page = max(1, (int) $this->input->get('page'));

		$this->list_query($q);
		$total = $this->db->count_all_results('', FALSE);
		$rows  = $this->db->order_by($this->order_by)->limit($this->per_page, ($page - 1) * $this->per_page)->get()->result();

		$this->render('crud/index', array(
			'page_title' => $this->title,
			'c'          => $this->meta(),
			'rows'       => $rows,
			'columns'    => $this->list_columns(),
			'options'    => $this->all_options(),
			'q'          => $q,
			'page'       => $page,
			'pages'      => (int) ceil($total / $this->per_page),
			'total'      => $total,
			'toolbar'    => $this->list_toolbar(),
		));
	}

	/** HTML opcional bajo el encabezado del listado (botones de exportación o sincronización) */
	protected function list_toolbar()
	{
		return '';
	}

	/** Consulta base del listado (sobrescribir para joins o filtros) */
	protected function list_query($q)
	{
		$this->db->from($this->table);
		if ($this->soft_delete) $this->db->where($this->table.'.deleted_at IS NULL', NULL, FALSE);
		if ($q !== '' && $this->search_fields) {
			$this->db->group_start();
			foreach ($this->search_fields as $f) $this->db->or_like($this->table.'.'.$f, $q);
			$this->db->group_end();
		}
	}

	// ------------------------------------------------------------------ Alta / edición
	public function create()
	{
		if ( ! $this->can_create) show_404();
		$this->require_role($this->edit_role);
		$this->form(NULL);
	}

	public function edit($id = NULL)
	{
		$row = $this->model->get((int) $id);
		if ( ! $row) show_404();
		if ($this->input->method() === 'post') $this->require_role($this->edit_role);
		$this->form($row);
	}

	protected function form($row)
	{
		if ($this->input->method() === 'post') {
			$this->set_rules($row);
			if ($this->form_validation->run()) {
				$data = $this->collect($row);
				if ($data !== FALSE) {
					$this->before_save($data, $row);
					if ($row) {
						$this->model->update($row->id, $data);
						$id = $row->id;
						audit_log('update', $this->table, $id);
					} else {
						$id = $this->model->insert($data);
						audit_log('create', $this->table, $id);
					}
					$this->after_save($id, $row);
					$this->session->set_flashdata('success', ucfirst($this->singular).' guardado correctamente.');
					redirect(admin_url($this->route));
				}
			}
			// Repoblar con lo enviado
			$row = (object) array_merge((array) $row, $this->input->post(NULL, TRUE) ?: array());
		}

		$this->render('crud/form', array(
			'page_title' => ($row && isset($row->id) ? 'Editar ' : 'Nuevo ').$this->singular,
			'c'       => $this->meta(),
			'row'     => $row,
			'fields'  => $this->fields,
			'options' => $this->all_options(),
			'extra'   => $this->form_extra($row),
		));
	}

	public function delete($id = NULL)
	{
		if ( ! $this->can_delete || $this->input->method() !== 'post') show_404();
		$this->require_role($this->edit_role);
		if ($this->model->get((int) $id)) {
			$this->model->delete((int) $id);
			audit_log('delete', $this->table, (int) $id);
			$this->session->set_flashdata('success', ucfirst($this->singular).' eliminado.');
		}
		redirect(admin_url($this->route));
	}

	// ------------------------------------------------------------------ Hooks
	protected function before_save(array &$data, $row) {}
	protected function after_save($id, $row) {}
	protected function form_extra($row) { return ''; }

	// ------------------------------------------------------------------ Internos
	protected function meta()
	{
		return (object) array(
			'route' => $this->route, 'title' => $this->title, 'singular' => $this->singular,
			'icon' => $this->icon, 'can_create' => $this->can_create && $this->can($this->edit_role),
			'can_delete' => $this->can_delete && $this->can($this->edit_role),
			'can_edit' => $this->can($this->edit_role), 'help' => $this->help,
			'searchable' => ! empty($this->search_fields),
		);
	}

	protected function list_columns()
	{
		$cols = array();
		foreach ($this->fields as $k => $f) if ( ! empty($f['list'])) $cols[$k] = $f;
		return $cols;
	}

	protected function all_options()
	{
		$out = array();
		foreach ($this->fields as $k => $f) {
			if (($f['type'] ?? '') !== 'select') continue;
			if (isset($f['options'])) { $out[$k] = $f['options']; continue; }
			$s = $f['source'];
			$rows = $this->db->select('id, '.$s['label'].' AS label')->order_by($s['order'] ?? $s['label'])->get($s['table'])->result();
			$out[$k] = array();
			foreach ($rows as $r) $out[$k][$r->id] = $r->label;
		}
		return $out;
	}

	protected function set_rules($row)
	{
		foreach ($this->fields as $k => $f) {
			$type = $f['type'] ?? 'text';
			if (in_array($type, array('image', 'file', 'checkbox', 'readonly'))) continue;
			$rules = $f['rules'] ?? '';
			if ($type === 'password' && $row) $rules = str_replace('required|', '', $rules);
			if ($type === 'email') $rules = trim($rules.'|valid_email', '|');
			$this->form_validation->set_rules($k, $f['label'], $rules ?: 'trim');
		}
	}

	/** Recolecta y normaliza los datos del POST */
	protected function collect($row)
	{
		$data = array();
		foreach ($this->fields as $k => $f) {
			$type = $f['type'] ?? 'text';
			switch ($type) {
				case 'readonly':
					break;
				case 'checkbox':
					$data[$k] = $this->input->post($k) ? 1 : 0;
					break;
				case 'html':
					$data[$k] = $this->input->post($k); // HTML del editor (solo usuarios del panel)
					break;
				case 'password':
					$v = (string) $this->input->post($k);
					if ($v !== '') $data[$f['column'] ?? $k] = password_hash($v, PASSWORD_BCRYPT);
					break;
				case 'image':
				case 'file':
					if ( ! empty($_FILES[$k]['name'])) {
						$path = $this->do_upload($k, $type);
						if ($path === FALSE) return FALSE;
						$data[$k] = $path;
					} elseif ($this->input->post($k.'_remove') && empty($f['required'])) {
						$data[$k] = NULL;
					} elseif ( ! empty($f['required']) && empty($row->$k)) {
						$this->data['upload_error'] = 'Debes subir: '.$f['label'].'.';
						return FALSE;
					}
					break;
				case 'select':
				case 'date':
				case 'datetime':
				case 'number':
					$v = $this->input->post($k, TRUE);
					$data[$k] = ($v === '' || $v === NULL) ? NULL : str_replace('T', ' ', $v);
					break;
				default:
					$data[$k] = $this->input->post($k, TRUE);
			}
		}
		if ($this->slug_field) {
			$src = $this->input->post($this->slug_field, TRUE) ?: $this->input->post($this->slug_source, TRUE);
			$data[$this->slug_field] = $this->model->unique_slug($src, $row->id ?? NULL, $this->slug_field);
		}
		return $data;
	}

	protected function do_upload($field, $type)
	{
		$dir = 'uploads/'.$this->route.'/'.date('Y/m').'/';
		if ( ! is_dir(FCPATH.$dir)) mkdir(FCPATH.$dir, 0755, TRUE);

		$this->upload->initialize(array(
			'upload_path'   => FCPATH.$dir,
			'allowed_types' => $type === 'image' ? 'jpg|jpeg|png|webp|gif' : 'pdf|doc|docx|xls|xlsx|ppt|pptx|jpg|jpeg|png',
			'max_size'      => $this->config->item('upload_max_kb'),
			'encrypt_name'  => TRUE,
		));
		if ( ! $this->upload->do_upload($field)) {
			$this->data['upload_error'] = $this->fields[$field]['label'].': '.strip_tags($this->upload->display_errors());
			return FALSE;
		}
		return $dir.$this->upload->data('file_name');
	}
}
