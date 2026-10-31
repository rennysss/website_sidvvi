<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** reCAPTCHA v3. Si no hay llaves en .env, no bloquea el formulario. */
class Recaptcha
{
	protected $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
	}

	public function enabled()
	{
		return (bool) (env('RECAPTCHA_SITE_KEY') && env('RECAPTCHA_SECRET'));
	}

	public function verify($token, $action = NULL)
	{
		if ( ! $this->enabled()) return TRUE;
		if ( ! $token) return FALSE;

		$ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
		curl_setopt_array($ch, array(
			CURLOPT_POST           => TRUE,
			CURLOPT_POSTFIELDS     => http_build_query(array(
				'secret'   => env('RECAPTCHA_SECRET'),
				'response' => $token,
				'remoteip' => $this->CI->input->ip_address(),
			)),
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_TIMEOUT        => 8,
		));
		$raw = curl_exec($ch);
		curl_close($ch);
		$json = json_decode((string) $raw, TRUE);
		if (empty($json['success'])) return FALSE;
		if (isset($json['score']) && (float) $json['score'] < 0.5) return FALSE;
		if ($action && isset($json['action']) && $json['action'] !== $action) return FALSE;
		return TRUE;
	}
}
