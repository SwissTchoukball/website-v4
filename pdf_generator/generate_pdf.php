<?php

if ($_SESSION['__userLevel__'] > 10) {
	header($_SERVER["SERVER_PROTOCOL"]." 401 Unauthorized");
	include("../http_status_pages/401.html");
	exit;
}

if (!$department || !$filename || !$title || !$subject || !$keywords || !$content) {
	header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request");
	include("../http_status_pages/400.html");
	exit;
}

require_once('../lib/tcpdf/config/tcpdf_config.php');
require_once('tcpdf_swisstchoukball.php');

// create new PDF document
$pdf = new SwissTchoukballPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetTitle($title);
$pdf->SetSubject($subject);
$pdf->SetKeywords($keywords);

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $department, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('amasismtstd', '', 12);

// add a page
$pdf->AddPage();

$html = "<style>".file_get_contents('pdf.css')."</style>";

$html .= $content;

$pdf->writeHTML($html, true, false, true, false, '');

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output($filename . '.pdf', 'D');

//============================================================+
// END OF FILE
//============================================================+
