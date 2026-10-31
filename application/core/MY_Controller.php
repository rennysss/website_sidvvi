<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controlador base de SIDVVI.
 * Jerarquía:  MY_Controller → Public_Controller (sitio)
 *                          → Admin_Controller  (panel /admin-panel) → Admin_Crud_Controller
 */
class MY_Controller extends CI_Controller
{
	/** Datos compartidos con las vistas */
	protected $data = array();

	public function __construct()
	{
		parent::__construct();
		// Misma zona horaria en PHP y MySQL (fechas de publicación, límites de envío, etc.)
		$this->db->query('SET time_zone = ?', array(date('P')));
		$this->data['settings'] = $this->Setting_model->all();
	}
}

require_once APPPATH.'core/Public_Controller.php';
require_once APPPATH.'core/Admin_Controller.php';
require_once APPPATH.'core/Admin_Crud_Controller.php';
