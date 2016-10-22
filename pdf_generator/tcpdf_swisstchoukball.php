<?php

require_once('../vendor/composer/tecnickcom/tcpdf/tcpdf.php');


// Extend the TCPDF class to create custom Header and Footer
class SwissTchoukballPDF extends TCPDF {

	//Page header
	public function Header() {
		// Logo
		$image_file = '../pictures/Logo-SwissTchoukball_848.jpg';
		$this->Image($image_file, 20, 10, 45, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
		$this->SetFont('florastd', 'B', 20);
		// Title
		$this->setCellMargins('', 10);
		$this->Cell(0, 15, $this->header_title, 0, false, 'R', 0, '', 0, false, 'M', 'M');
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('amasismtstd', '', 10);
		// Left text (empty)
		$this->MultiCell(25, 10, '', 0, 'L', 0, 0, '', '', true);
		// Center text
		$this->MultiCell(120, 10, 'Swiss Tchoukball - info@tchoukball.ch - www.tchoukball.ch', 0, 'C', 0, 0, '', '', true);
		// Right text (page number)
		$this->MultiCell(25, 10, $this->PageNo().'/'.$this->getNumPages(), 0, 'R', 0, 0, '', '', true);
	}
}
