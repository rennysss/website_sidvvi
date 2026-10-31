<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Envío de correos con plantillas de views/emails/ */
class Mailer
{
	protected $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->library('email');
	}

	/**
	 * @param string|array $to
	 * @return bool  FALSE si no hay SMTP configurado o falla el envío (el mensaje ya quedó guardado en BD)
	 */
	public function send($to, $subject, $view, array $data = array(), $reply_to = NULL)
	{
		if ( ! env('SMTP_HOST')) {
			log_message('info', 'Mailer: SMTP no configurado, correo no enviado: '.$subject);
			return FALSE;
		}
		$data['subject'] = $subject;
		$body = $this->CI->load->view('emails/layout', array(
			'subject' => $subject,
			'content' => $this->CI->load->view('emails/'.$view, $data, TRUE),
		), TRUE);

		$this->CI->email->clear();
		$this->CI->email->from(env('MAIL_FROM', 'no-reply@sidvvi.com'), env('MAIL_FROM_NAME', 'SIDVVI'));
		$this->CI->email->to($to);
		if ($reply_to) $this->CI->email->reply_to($reply_to);
		$this->CI->email->subject($subject);
		$this->CI->email->message($body);
		$ok = $this->CI->email->send(FALSE);
		if ( ! $ok) log_message('error', 'Mailer: '.$this->CI->email->print_debugger(array('headers')));
		return $ok;
	}

	/** Lista de correos desde un setting separado por comas */
	public static function recipients($setting_key)
	{
		return array_filter(array_map('trim', explode(',', setting($setting_key))));
	}
}
