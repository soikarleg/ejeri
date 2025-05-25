<?php
class FactureListeFPDF extends FPDF
{
    private $numero;
    private $secteur;
    private $pdf;



    public function __construct($secteur, $numero, $pdf)
    {

        $this->numero = $numero;
        $this->pdf = $pdf;
        $this->secteur = $secteur;

        $this->pdf->AliasNbPages();
        $this->pdf->AddFont('Nunito', '', 'Nunito-Regular.php');
        $this->pdf->AddFont('Nunito', 'B', 'Nunito-Bold.php');
        $this->pdf->SetAutoPageBreak(true, 10);
        $this->pdf->AcceptPageBreak();
        // // Ajouter une page pour cette facture
        $this->pdf->AddPage('L', 'A4');

        // Générer les lignes de la facture
        //$this->pdf->Lignes($numero);
    }



    function Header()
    {
        $this->pdf->SetFont('Nunito', 'B', 20);
        $this->pdf->Cell(0, 9,  strEncoding('Factures en attente'), '', 1, 'R');
        $this->pdf->SetFont('Nunito', 'B', 9);
        $this->pdf->Cell(0, 5, 'Edition du ' . date('d/m/Y'), '', 1, 'R');
        $this->pdf->Ln(14);
    }

    function Footer()
    {
        $this->pdf->SetY(185);
        $this->pdf->SetFont('Nunito', '', 8);
        $this->pdf->Cell(0, 4, strEncoding('Règlements en attente - ' . NomSecteur($this->secteur)), 0, 1, 'C');
        $this->pdf->SetFont('Nunito', 'B', 7);
        $this->pdf->Cell(0, 7, 'Page ' . $this->pdf->PageNo() . '/{nb}', 0, 1, 'C');
    }



    public function Lignes($numero)
    {

        $this->pdf->SetFont('Nunito', '', 9);
        $total = 0;
        $tour = 0;
        $fill = true;
        foreach ($numero as  $value) {
            $this->pdf->SetFillColor(224, 235, 255);
            $factures = new Factures($this->secteur);
            $entete = $factures->askFacturesEntete($value);

            $reglements = new Reglements($this->secteur);
            $deja_regle = $reglements->askReglementsFact($value, $entete['id']);


            $date = AffDate($entete['datefact']);

            $this->pdf->SetFont('Nunito', 'B', 9);
            $this->pdf->Cell(70, 10,  strEncoding(Nomclient($entete['id'])), 'B', 0, 'L', $fill);
            $this->pdf->SetFont('Nunito', '', 9);
            $this->pdf->Cell(60, 10,  $value . ' du ' . $date, 'B', 0, 'L', $fill);
            $this->pdf->Cell(40, 10,  $entete['titre'], 'B', 0, 'L', $fill);
            $this->pdf->Cell(41, 10,  'limite : ' . AffDate($entete['dateche']), 'B', 0, 'R', $fill);
            $this->pdf->Cell(22, 10,  $tour = Dec_2($entete['totttc']), 'B', 0, 'R', $fill);
            $this->pdf->Cell(22, 10,  $tot = Dec_2($deja_regle['tot']), 'B', 0, 'R', $fill);
            $this->pdf->SetFont('Nunito', 'B', 9);
            $this->pdf->Cell(22, 10,  $tour = Dec_2($entete['totttc'] - $tot), 'B', 1, 'R', $fill);
            $this->pdf->SetFont('Nunito', '', 9);
            $total = $tour + $total;
            if ($this->pdf->GetY() > 170) { // Ajustez la valeur selon votre mise en page

                $this->pdf->AddPage('L', 'A4');
            }
            $fill = !$fill;
        }
        $this->pdf->Ln(10);
        $this->pdf->SetFont('Nunito', 'B', 9);
        $this->pdf->Cell(0, 10, 'Total         ' . Dec_2($total), 'B', 1, 'R', $fill);
        $this->pdf->SetFont('Nunito', '', 9);



        $this->pdf->Ln(8);
    }
}
