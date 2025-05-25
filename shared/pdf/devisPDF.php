<?php

require_once PROJECT_ROOT . '/cli/vendor/setasign/fpdf/fpdf.php';

class DevisPDF extends FPDF
{
    function Header()
    {

        $numero = $_GET['numero'] ?? null;

        if (!$numero) {
            //die('Numéro de devis manquant');
            $this->SetFont('Arial', 'B', 15);
            $this->Cell(0, 10, 'NUMERO MANQUANT', 0, 1, 'C');
        } else {
            $this->SetFont('Arial', 'B', 15);
            $this->Cell(0, 10, 'Devis - Enooki', 0, 1, 'L');
            $this->SetFont('Arial', 'B', 12);
             $this->Cell(0, 10, 'Brouillon', 0, 1, 'L');
            $this->Ln(5);
            $this->SetFont('Arial', 'I', 12);
            $this->Cell(0, 10, convertPDF('Numéro de devis : ' . $numero), 0, 1, 'C');
            $this->Ln(5);
        }
    }

function Infos()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 10, 'Myinfos ', 0, 1, 'L');
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new DevisPDF();
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetAuthor('Enooki');
$pdf->SetCreator('Enooki PDF Generator');
$pdf->SetSubject('Devis PDF');
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(true, 15);
$pdf->SetDisplayMode('fullwidth', 'single');
$pdf->SetFont('Arial', '', 12);
if (isset($_GET['numero']) && !empty($_GET['numero'])) {
    $pdf->SetTitle('Devis - ' . htmlspecialchars($_GET['numero']));$pdf->Infos();
    $pdf->Cell(0, 10, "Ceci est un test de devis PDF.", 0, 1);
} else {
    $pdf->SetTitle(convertPDF('Erreur - Devis sans numéro - Enooki'));
}

$numero = $_GET['numero'] ?? 'devis';
$filename = 'devis_' . preg_replace('/[^A-Za-z0-9_-]/', '', $numero) . '.pdf';
$pdf->Output('I', $filename, true); // Affichage en popup avec nom dynamique
