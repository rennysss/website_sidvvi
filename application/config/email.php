<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['protocol']    = env('SMTP_HOST') ? 'smtp' : 'mail';
$config['smtp_host']   = env('SMTP_HOST', '');
$config['smtp_port']   = (int) env('SMTP_PORT', 587);
$config['smtp_user']   = env('SMTP_USER', '');
$config['smtp_pass']   = env('SMTP_PASS', '');
$config['smtp_crypto'] = env('SMTP_CRYPTO', 'tls');
$config['smtp_timeout']= 10;
$config['mailtype']    = 'html';
$config['charset']     = 'utf-8';
$config['newline']     = "\r\n";
$config['crlf']        = "\r\n";
