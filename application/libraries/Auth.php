<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Autenticación del panel de control (/admin-panel) */
class Auth
{
	const MAX_ATTEMPTS = 5;
	const LOCK_MINUTES = 15;

	protected $CI;
	protected $user = NULL;

	public function __construct()
	{
		$this->CI =& get_instance();
	}

	public function user()
	{
		if ($this->user === NULL) {
			$id = $this->CI->session->userdata('admin_id');
			$this->user = $id ? $this->CI->db->where(array('id' => $id, 'is_active' => 1))
				->where('deleted_at IS NULL', NULL, FALSE)->get('users')->row() : FALSE;
		}
		return $this->user ?: NULL;
	}

	public function is_locked($email)
	{
		$since = date('Y-m-d H:i:s', time() - self::LOCK_MINUTES * 60);
		$n = $this->CI->db->where('email', $email)->where('ip_address', $this->CI->input->ip_address())
			->where('attempted_at >=', $since)->count_all_results('login_attempts');
		return $n >= self::MAX_ATTEMPTS;
	}

	/** @return TRUE | string mensaje de error */
	public function login($email, $password)
	{
		$email = strtolower(trim($email));
		if ($this->is_locked($email)) {
			return 'Demasiados intentos. Intenta de nuevo en '.self::LOCK_MINUTES.' minutos.';
		}
		$user = $this->CI->db->where('email', $email)->where('deleted_at IS NULL', NULL, FALSE)->get('users')->row();
		if ( ! $user || ! password_verify($password, $user->password_hash)) {
			$this->CI->db->insert('login_attempts', array('email' => $email, 'ip_address' => $this->CI->input->ip_address()));
			return 'Correo o contraseña incorrectos.';
		}
		if ( ! $user->is_active) return 'Tu usuario está desactivado.';

		return $this->start_session($user);
	}

	/** Abre la sesión del panel. $detail distingue contraseña y passkey en la bitácora. */
	public function start_session($user, $detail = NULL)
	{
		$this->CI->db->where('email', $user->email)->delete('login_attempts');
		$this->CI->db->where('id', $user->id)->update('users', array('last_login_at' => date('Y-m-d H:i:s')));
		$this->CI->session->sess_regenerate(TRUE);
		$this->CI->session->set_userdata('admin_id', (int) $user->id);
		$this->user = $user;
		audit_log('login', 'users', $user->id, $detail);
		return TRUE;
	}

	public function logout()
	{
		$this->CI->session->unset_userdata('admin_id');
		$this->CI->session->sess_destroy();
	}

	/** Genera token de recuperación (válido 1 hora). Devuelve el usuario o NULL */
	public function create_reset_token($email)
	{
		$user = $this->CI->db->where('email', strtolower(trim($email)))->where('is_active', 1)->get('users')->row();
		if ( ! $user) return NULL;
		$user->reset_token = bin2hex(random_bytes(24));
		$this->CI->db->where('id', $user->id)->update('users', array(
			'reset_token' => hash('sha256', $user->reset_token),
			'reset_expires_at' => date('Y-m-d H:i:s', time() + 3600),
		));
		return $user;
	}

	public function find_by_token($token)
	{
		return $this->CI->db->where('reset_token', hash('sha256', $token))
			->where('reset_expires_at >=', date('Y-m-d H:i:s'))->get('users')->row();
	}
}
