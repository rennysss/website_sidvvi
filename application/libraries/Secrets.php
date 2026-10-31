<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Credenciales de integraciones cifradas en integration_secrets.
 * El valor en claro no se imprime en el panel: solo se indica si ya hay uno guardado.
 */
class Secrets
{
	protected $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->library('encryption');
	}

	/** Texto descifrado, o cadena vacía si no hay valor o no se puede leer */
	public function get($key)
	{
		$row = $this->row($key);
		if ( ! $row || $row->value_enc === NULL || $row->value_enc === '') return '';
		$plain = $this->CI->encryption->decrypt($row->value_enc);
		return $plain === FALSE ? '' : $plain;
	}

	public function filled($key)
	{
		$row = $this->row($key);
		return (bool) ($row && $row->value_enc);
	}

	public function row($key)
	{
		return $this->CI->db->get_where('integration_secrets', array('key' => $key))->row();
	}

	/**
	 * Guarda un valor cifrado. Cadena vacía borra la credencial.
	 * $expires_at solo aplica al token de Instagram (cuándo hay que renovarlo).
	 */
	public function set($key, $plain, $expires_at = NULL)
	{
		$uid = $this->CI->session->userdata('admin_id');
		if ($plain === '' || $plain === NULL) {
			$this->CI->db->where('key', $key)->delete('integration_secrets');
			return;
		}
		$data = array(
			'value_enc'  => $this->CI->encryption->encrypt($plain),
			'expires_at' => $expires_at,
			'updated_at' => date('Y-m-d H:i:s'),
			'updated_by' => $uid ? (int) $uid : NULL,
		);
		if ($this->row($key)) {
			$this->CI->db->where('key', $key)->update('integration_secrets', $data);
		} else {
			$data['key'] = $key;
			$this->CI->db->insert('integration_secrets', $data);
		}
	}
}
