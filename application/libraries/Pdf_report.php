<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** PDF de reportes: logo, título y código QR de verificación arriba */
class Pdf_report extends TCPDF
{
	public $report_title = '';
	public $folio = '';
	public $qr_url = '';

	public function __construct()
	{
		parent::__construct('P', 'mm', 'LETTER', TRUE, 'UTF-8', FALSE);
		$this->SetCreator('SIDVVI');
		$this->SetAuthor('SIDVVI');
		$this->setPrintHeader(TRUE);
		$this->setPrintFooter(TRUE);
		$this->SetMargins(15, 42, 15);
		$this->SetHeaderMargin(6);
		$this->SetFooterMargin(10);
		$this->SetAutoPageBreak(TRUE, 18);
		$this->setHeaderFont(array('dejavusans', '', 9));
		$this->setFooterFont(array('dejavusans', '', 8));
		$this->SetFont('dejavusans', '', 9);
	}

	public function prepare($title, $folio)
	{
		$this->report_title = $title;
		$this->folio = $folio;
		$this->qr_url = site_url('verificar/'.$folio);
		$this->SetTitle($title.' '.$folio);
		$this->AddPage();
		return $this;
	}

	public function Header()
	{
		$logo = FCPATH.'assets/img/logo.png';
		if (is_file($logo)) $this->Image($logo, 15, 8, 46, 0, 'PNG');
		if ($this->qr_url) $this->write2DBarcode($this->qr_url, 'QRCODE,M', 178, 8, 22, 22);
		$this->SetY(30);
		$this->SetFont('dejavusans', 'B', 13);
		$this->Cell(0, 6, $this->report_title, 0, 1, 'L');
		$this->SetFont('dejavusans', '', 8);
		$this->Cell(0, 4, 'Folio '.$this->folio.'  ·  '.fecha_es(date('Y-m-d')), 0, 1, 'L');
	}

	public function Footer()
	{
		$this->SetY(-12);
		$this->SetFont('dejavusans', '', 8);
		$this->Cell(0, 8, 'SIDVVI  ·  Página '.$this->getAliasNumPage().' de '.$this->getAliasNbPages(), 0, 0, 'C');
	}

	public function table(array $columns, array $rows)
	{
		$html = '<table border="0.4" cellpadding="4"><thead><tr>';
		foreach ($columns as $col) $html .= '<th><b>'.htmlspecialchars($col, ENT_QUOTES, 'UTF-8').'</b></th>';
		$html .= '</tr></thead><tbody>';
		if ( ! $rows) {
			$html .= '<tr><td colspan="'.count($columns).'">Sin registros en este periodo.</td></tr>';
		}
		foreach ($rows as $row) {
			$html .= '<tr>';
			foreach ($row as $cell) $html .= '<td>'.htmlspecialchars((string) $cell, ENT_QUOTES, 'UTF-8').'</td>';
			$html .= '</tr>';
		}
		$html .= '</tbody></table>';
		$this->SetFont('dejavusans', '', 9);
		$this->writeHTML($html, TRUE, FALSE, TRUE, FALSE, '');
	}
}
