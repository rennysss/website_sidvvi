<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Tareas de consola: php index.php cli instagram | php index.php cli test_mail destino@correo.com */
class Cli extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if ( ! is_cli()) show_404();
		$this->db->query('SET time_zone = ?', array(date('P')));
	}

	public function instagram()
	{
		$this->load->library('instagram_feed');
		$result = $this->instagram_feed->refresh();
		echo ($result['ok'] ? 'OK' : 'ERROR').' '.$result['message'].PHP_EOL;
		exit($result['ok'] ? 0 : 1);
	}

	public function test_mail($to = '')
	{
		if ( ! filter_var($to, FILTER_VALIDATE_EMAIL)) {
			echo "Uso: php index.php cli test_mail destino@correo.com\n";
			exit(1);
		}
		$this->load->model('Setting_model');
		$this->load->library('mailer');
		$ok = $this->mailer->send($to, 'Prueba SMTP · SIDVVI', 'test', array(
			'name' => 'Administrador',
		));
		echo $ok ? "Correo enviado a $to\n" : "No se envió. Revisa SMTP_HOST en .env y application/logs.\n";
		exit($ok ? 0 : 1);
	}
}
