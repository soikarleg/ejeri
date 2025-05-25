<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$periode = $_GET['periode'];
$idinter = $_GET['u'];
$nombre_user = explode('-', $idinter);
$tab = count($nombre_user);
$p = explode('-', $periode);
$mois = $p[0];
$annee = $p[1];
session_start();
$secteur = $_SESSION['idcompte'];
session_start();
include '../../../vendor/setasign/fpdf/fpdf.php';
//include '../../../myclass/flxxx/src/Base.php';
include '../../../inc/function.php';

$conn = new connBase();

foreach ($_GET as $k => $v) {
  ${$k} = mb_convert_encoding($v, 'ISO-8859-1');
  //echo '$'.$k.' = '.$v.'</br>';
}
if ($mois and $mois !== 'X') {
  $rm = " and mois='$mois'";
} else {
  $rm = "";
}
if ($annee and $annee !== 'X') {
  $ra = " and annee='$annee'";
} else {
  $ra = "";
}
$nominter = mb_convert_encoding(NomColla($idinter), 'ISO-8859-1');
$nomsecteur = mb_convert_encoding(NomSecteur($secteur), 'ISO-8859-1');
$n = mb_convert_encoding('N°', 'ISO-8859-1');
$periode = $mois . '-' . $annee;
define('SAUT', 6);
define('NOMCOL', $nominter);
define('NOMSEC', $nomsecteur);
define('SEC', $secteur);
define('PERIODE', $periode);
define('ANNEE', $annee);
define('RM', $rm);
define('RA', $ra);
define('FONT', 9);
define('IDINTER', $idinter);
define('TAB', $tab);
class PDF extends FPDF
{
  function Header()
  {
    $this->SetFont('Nunito', 'B', 14);
    $this->Cell(50, SAUT + 4, NOMSEC, 'B', 0, 'L');
    $this->Cell(0, SAUT + 4, "VARIABLES " . ANNEE, 'B', 1, 'R');
    // $this->SetFont('Nunito', 'B', 14);
    // $this->Cell(0, SAUT + 4, IDINTER . ' - ' . NOMCOL, '', 1, 'R');
  }
  function Footer()
  {
    $this->SetY(-15);
    $this->SetFont('Nunito', '', 7);
    $this->Cell(0, SAUT, SEC . ' - Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
  }

  function titreParagraphe($titre)
  {
    $this->SetX(20);
    $this->SetFont('Nunito', 'B', FONT + 4);
    $this->Cell(170, SAUT, $titre, 'B', 1, 'L');
  }

  function corpSemaineAnnee($secteur, $idinter, $mois, $annee, $roll)
  {
    $this->SetFont('Nunito', 'B', 14);
    $colla = strEncoding(NomColla($idinter));
    $this->Cell(0, SAUT + 4, $idinter . ' - ' . $colla, '', 1, 'R');
    $conn = new connBase();
    $mois = str_pad($mois, 2, '0', STR_PAD_LEFT);
    $tourm = 0;
    $tourh = 0;
    $tour = 0;
    $tour_repas = 0;
    $trace = array();

    $reqidm = "select *, SUM(quant) as h from production where idcompte='$secteur' and idinter='$idinter' and mois='$mois' and annee='$annee'  group by sem ";
    $variasem = $conn->allRow($reqidm);
    if ($variasem) {
      $this->SetX(20);
      $this->SetFont('Nunito', 'B', FONT + 3);
      $this->Cell(170, SAUT, 'Semaines de ' . strEncoding(getMoisNom($mois)) . ' ' . $annee, 'B', 1, 'L');
      $this->SetFont('Nunito', '', FONT);
      foreach ($variasem as $v) {
        $ths = 0;
        $this->SetX(20);
        $this->Cell(57, SAUT, 'Semaine ' . ' ' . ($v['sem']), 'B', 0, 'L');
        $hs = Dec_2($v['h'] - 35);
        if ($hs > 0) {
          $ths = Dec_2($hs);
          $this->Cell(57, SAUT, 'HSupp. ' . $ths, 'B', 0, 'R');
        } else {
          $this->Cell(57, SAUT, '', 'B', 0, 'R');
        }
        $this->Cell(56, SAUT, $totm = Dec_2($v['h']), 'B', 1, 'R');
        $tourm = $tourm + $totm;
        $tourh = $tourh + $ths;
      }
      $this->SetX(20);
      $this->SetFont('Nunito', 'B', FONT);
      $this->Cell(57, SAUT, 'Total ', 'B', 0, 'L');
      $this->Cell(57, SAUT, 'HSupp. ' . Dec_2($tourh), 'B', 0, 'R');
      $this->Cell(56, SAUT, Dec_2($tourm), 'B', 1, 'R');
      $this->SetFont('Nunito', '', FONT);
    }

    $reqidj = "select *, SUM(quant) as h from production where idcompte='$secteur' and idinter='$idinter' and mois='$mois' and annee='$annee'  group by datetvx ";
    $variajour = $conn->allRow($reqidj);
    //var_dump($variajour);
    if ($variajour) {
      $this->Ln(5);
      $this->SetX(20);
      $this->SetFont('Nunito', 'B', FONT + 3);
      $this->Cell(170, SAUT, strEncoding('Journées de ') . strEncoding(getMoisNom($mois)) . ' ' . $annee, 'B', 1, 'L');
      $this->SetFont('Nunito', '', FONT);
      foreach ($variajour as $v) {
        $this->SetX(20);
        $this->Cell(28.3, SAUT, AffDate($v['datetvx']) . ' - S' . $v['sem'], 'B', 0, 'L');
        $this->Cell(28.3, SAUT, strEncoding($v['travaux'] . ' - ' . Tronque($v['dettvx'], 30)), 'B', 0, 'L');
        $repas = ($v['h'] > 4) ? 1 : 0;
        $this->Cell(56.6, SAUT, $tr = $repas, 'B', 0, 'R');
        $this->Cell(56.6, SAUT, $tot = Dec_2($v['h']), 'B', 1, 'R');
        $tour = $tour + $tot;
        $tour_repas = $tour_repas + $tr;
        $trace['trace'] = $tot;
      }
      $this->SetX(20);
      $this->SetFont('Nunito', 'B', FONT);
      $this->Cell(113.20, SAUT,  ' Repas = ' . $tour_repas, 'B', 0, 'R');
      $this->Cell(56.60, SAUT, Dec_2($tour), 'B', 1, 'R');
      $this->SetFont('Nunito', '', FONT);
    }

    return $trace;
  }
}
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddFont('Nunito', '', 'Nunito-Regular.php');
$pdf->AddFont('Nunito', 'B', 'Nunito-Bold.php');
$pdf->AddPage();
$pdf->SetFont('Nunito', '', FONT);
$reqidj = "select *, SUM(quant) as h from production where idcompte='$secteur' and idinter='$idinter' $rm $ra  group by datetvx ";
$variajour = $conn->allRow($reqidj);
$reqidm = "select *, SUM(quant) as h from production where idcompte='$secteur' and idinter='$idinter' $rm $ra  group by sem ";
$variasem = $conn->allRow($reqidm);
$tourm = 0;
$tourh = 0;
$ths = 0;
$tour = 0;
$tour_repas = 0;
$totm = 0;
$tr = 0;
$tot = 0;
$roll = 1;
if ($rm === "") {
  for ($i = 1; $i <= 12; $i++) {
    $donnee = $pdf->corpSemaineAnnee($secteur, $idinter, $i, $annee, $roll);
    if (isset($donnee) and $donnee['trace'] != null) {
      //$pdf->corpSemaineAnnee($secteur, $idinter, $i, $annee, $roll);
      $pdf->Ln(5);

      if ($roll < date('n') and $donnee['trace']) {
        $pdf->AddPage();
      }
    }

    $roll++;
  }
}


foreach ($nombre_user as $u) {
  $pdf->Ln(5);
  $pdf->corpSemaineAnnee($secteur, $u, $mois, $annee, $roll);
  if ($roll < $tab) {
    $pdf->AddPage();
  }
  $roll++;
}


//$pdf->corpSemaineAnnee($secteur, $idinter, $mois, $annee, $roll);

$nom_fichier = $tab < 2 ? SupAccX($nominter) : "global_" . $tab;


$fichier = 'Variables_' .  $nom_fichier . '_' . $periode . '_' . date('dmY_His');
if (!is_dir('/kunden/homepages/26/d529006740/htdocs/flxxx/documents/pdf/variables/' . $secteur)) {
  mkdir('/kunden/homepages/26/d529006740/htdocs/flxxx/documents/pdf/variables/' . $secteur);
}
$pdf->Output('I', $fichier);
$pdf->Output('F', '/kunden/homepages/26/d529006740/htdocs/flxxx/documents/pdf/variables/' . $secteur . '/' . $fichier . '.pdf');
