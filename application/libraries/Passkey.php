<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Face ID, Touch ID y Windows Hello vía WebAuthn.
 * La llave privada no sale del equipo: aquí solo se guarda la llave pública.
 */
class Passkey
{
	protected $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
	}

	public function engine()
	{
		$host = parse_url(base_url(), PHP_URL_HOST) ?: 'localhost';
		return new \lbuchs\WebAuthn\WebAuthn(
			'SIDVVI',
			$host,
			array('none', 'packed', 'apple', 'android-key', 'android-safetynet', 'tpm', 'fido-u2f'),
			TRUE
		);
	}

	/** Identificador estable del usuario dentro del autenticador */
	public function user_handle($user_id)
	{
		return hash('sha256', 'sidvvi-admin-'.$user_id, TRUE);
	}

	public function store_challenge($binary)
	{
		$this->CI->session->set_userdata(array(
			'wa_challenge'     => base64_encode($binary),
			'wa_challenge_exp' => time() + 120,
		));
	}

	/** Lee el reto una sola vez. NULL si ya venció o no existe. */
	public function take_challenge()
	{
		$exp = (int) $this->CI->session->userdata('wa_challenge_exp');
		$raw = $this->CI->session->userdata('wa_challenge');
		$this->CI->session->unset_userdata(array('wa_challenge', 'wa_challenge_exp'));
		if ( ! $raw || $exp < time()) return NULL;
		$bin = base64_decode($raw, TRUE);
		return $bin === FALSE ? NULL : $bin;
	}

	public function decode($b64url)
	{
		return \lbuchs\WebAuthn\Binary\ByteBuffer::fromBase64Url($b64url)->getBinaryString();
	}

	public function encode_id($binary)
	{
		if ($binary instanceof \lbuchs\WebAuthn\Binary\ByteBuffer) {
			$binary = $binary->getBinaryString();
		}
		return rtrim(strtr(base64_encode($binary), '+/', '-_'), '=');
	}
}
