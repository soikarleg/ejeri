<?php
require_once __DIR__ . '/../../../vendor/fpdf/fpdf.php';

class BonTravauxPDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, 'Bon de travaux - Enooki', 0, 1, 'C');
        $this->Ln(5);
    }
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new BonTravauxPDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, "Ceci est un test de bon de travaux PDF.", 0, 1);
$pdf->Output();
