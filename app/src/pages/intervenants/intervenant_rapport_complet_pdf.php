<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$periode = $_GET['periode'];
$idinter = $_GET['u'];
$ca = $_GET['ca'];
$idinter = $_GET['u'] == null ? "" : $_GET['u'];
$p = explode('-', $periode);
$mois = $p[0];
$annee = $p[1];
session_start();
$secteur = $_SESSION['idcompte'];
$chemin = $_SERVER['DOCUMENT_ROOT'];
session_start();
include $chemin . '/vendor/setasign/fpdf/fpdf.php';

include $chemin . '/inc/function.php';

$conn = new connBase();

foreach ($_GET as $k => $v) {
  ${$k} = mb_convert_encoding($v, 'ISO-8859-1');
  //echo '$' . $k . ' = ' . $v . '</br>';
}

// if ($mois and $mois !== 'X') {
//   $rm = " and mois='$mois'";
// } else {
//   $rm = "";
// }

if ($annee and $annee !== 'X') {
  $ra = " and annee='$annee'";
} else {
  $ra = "";
}
$nominter = mb_convert_encoding(($idinter), 'ISO-8859-1');
$nomsecteur = mb_convert_encoding(NomSecteur($secteur), 'ISO-8859-1');
$titre = mb_convert_encoding("Rapport d'activité", 'ISO-8859-1');
$titre_periode = strEncoding('Mois');
$n = mb_convert_encoding('N°', 'ISO-8859-1');
$periode = $mois . '-' . $annee;
$e = strEncoding('€');



if ($ca == '1') {
  $ca = 48;
  $ca_titre = 'CA potentiel';
} else {
  $ca = 0;
  $ca_titre = '';
}

define('SAUT', 8);
define('NOMCOL', $nominter);
define('NOMSEC', $nomsecteur);
define('SEC', $secteur);
define('PERIODE', $periode);
define('ANNEE', $annee);
define('TITRE', $titre);
define('FONT', 9);
define('IDINTER', $idinter);
class PDF extends FPDF
{
  function Header()
  {
    $this->SetFont('Nunito', 'B', 14);
    $this->Cell(50, SAUT + 4, NOMSEC, 'B', 0, 'L');
    $this->Cell(0, SAUT + 4, TITRE . ' ' . ANNEE, 'B', 1, 'R');
    $this->SetFont('Nunito', 'B', 14);
    $this->Cell(0, SAUT + 4, '', '', 1, 'R');
  }
  function Footer()
  {
    $this->SetY(-15);
    $this->SetFont('Nunito', '', 7);
    $this->Cell(0, SAUT, SEC . ' - Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
  }
}
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddFont('Nunito', '', 'Nunito-Regular.php');
$pdf->AddFont('Nunito', 'B', 'Nunito-Bold.php');
$pdf->AddPage();
$pdf->SetFont('Nunito', '', FONT);


// TOUR DES USERS
$requsers = "select * from users where idcompte='$secteur' and actif='1'";
$users = $conn->allRow($requsers);



// Variable semaine
$pdf->SetXY(20, 40);
// $pdf->SetFont('Nunito', 'B', FONT + 2);
// $pdf->Cell(170, SAUT, 'Semaine', 'B', 1, 'L');

foreach ($users as $v) {
  $pdf->SetFont('Nunito', 'B', FONT + 4);
  $id = $v['idusers'];
  $colla = ($v['idusers']);
  $user = mb_convert_encoding(NomColla($colla), 'ISO-8859-1');

  $pdf->SetX(20);
  $pdf->Cell(57, SAUT, $user, 'B', 0, 'L');
  // $hs = $hs = Dec_2($v['h'] - 35);
  $pdf->Cell(57, SAUT, '', 'B', 0, 'R');
  $pdf->Cell(56, SAUT, $n . ($v['idusers']), 'B', 1, 'R');


  $pdf->Ln(1);

  // MOYENNE
  $pdf->SetX(20);
  $pdf->SetFont('Nunito', 'B', FONT);
  $pdf->Cell(30, SAUT, 'Mois', 'B', 0, 'L');
  $pdf->Cell(28, SAUT, strEncoding('Facturée'), 'B', 0, 'R');
  $pdf->Cell(28, SAUT, $ca_titre, 'B', 0, 'R');

  $pdf->Cell(28, SAUT, strEncoding('Non facturée'), 'B', 0, 'R');
  $pdf->Cell(28, SAUT, '%', 'B', 0, 'R');
  $pdf->Cell(28, SAUT, 'Total heures', 'B', 1, 'R');
  // TOUR MOIS
  $tour_mo = 0;
  $tour_nf = 0;
  $tour_tot = 0;
  $roll = 0;



  for ($i = 1; $i <= 12; $i++) {
    $mois_lisible = getMoisNom($i);
    $mois_lisible = mb_convert_encoding($mois_lisible, 'ISO-8859-1');
    $mois = str_pad($i, 2, '0', STR_PAD_LEFT);

    //TOUR DES HEURES
    $reqheuresAI = "SELECT SUM(CASE WHEN codeprod IN ('MO', 'NF') THEN quant ELSE 0 END) AS heures, SUM(CASE WHEN codeprod = 'MO' THEN quant ELSE 0 END) AS heures_mo, SUM(CASE WHEN codeprod = 'NF' THEN quant ELSE 0 END) AS heures_nf FROM production WHERE idinter = '$id' $ra and mois='$mois' ";
    $heures = $conn->oneRow($reqheuresAI);

    $h_mo = $heures['heures_mo'];
    $h_nf = $heures['heures_nf'];
    $h_tot = $heures['heures'];

    if ($h_tot == 0) {
      $h_tot = 1;
    } else {
      $h_tot = $h_tot;
    }


    $pdf->SetX(20);
    $pdf->SetFont('Nunito', '', FONT);
    $pdf->Cell(30, SAUT, $mois_lisible, 'B', 0, 'L');
    $pdf->Cell(28, SAUT, Dec_2($h_mo), 'B', 0, 'R');
    $pdf->Cell(28, SAUT, Dec_2($h_mo * $ca), 'B', 0, 'R');
    $pdf->Cell(28, SAUT, Dec_2($h_nf), 'B', 0, 'R');
    $pdf->Cell(28, SAUT, Dec_0($h_nf * 100 / $h_tot, '%'), 'B', 0, 'R');
    if ($h_tot == 1) {
      $h_tot = 0;
    } else {
      $h_tot = $h_tot;
      $roll++;
    }


    $pdf->Cell(28, SAUT, Dec_2($h_tot), 'B', 1, 'R');

    $tour_mo = $tour_mo + $h_mo;
    $tour_nf = $tour_nf + $h_nf;
    $tour_tot = $tour_tot + $h_tot;


    // foreach ($heures as $h) {
    //   $pdf->SetX(20);
    //   $pdf->SetFont('Nunito', 'B', FONT);
    //   $pdf->Cell(57, SAUT, 'Total ', 'B', 0, 'L');
    //   $pdf->Cell(57, SAUT, Dec_2($h['heures']), 'B', 0, 'R');
    //   $pdf->Cell(56, SAUT, $tourm, 'B', 1, 'R');
    //   $pdf->SetFont('Nunito', '', FONT);
    // }

  }


  if ($tour_tot == 0) {
    $tour_tot = 1;
  } else {
    $tour_tot = $tour_tot;
  }
  // TOTAL
  $pdf->SetX(20);
  $pdf->SetFont('Nunito', 'B', FONT);
  $pdf->Cell(30, SAUT, 'Total' . $roll, 'B', 0, 'L');
  $pdf->Cell(28, SAUT, Dec_2($tour_mo), 'B', 0, 'R');
  $pdf->Cell(28, SAUT, Dec_2($tour_mo * $ca), 'B', 0, 'R');
  $pdf->Cell(28, SAUT, Dec_2($tour_nf), 'B', 0, 'R');
  $pdf->Cell(28, SAUT, Dec_0($tour_nf * 100 / $tour_tot, '%'), 'B', 0, 'R');
  if ($tour_tot == 1) {
    $tour_tot = 0;
  } else {
    $tour_tot = $tour_tot;
    $roll++;
  }

  if ($roll == 0) {
    $roll = 1;
  } else {
    $roll = $roll;
    $roll++;
  }


  $pdf->Cell(28, SAUT, Dec_2($tour_tot), 'B', 1, 'R');
  // MOYENNE
  $pdf->SetX(20);
  $pdf->SetFont('Nunito', 'B', FONT);
  $pdf->Cell(30, SAUT, 'Moyenne mensuelle des HF', 'B', 0, 'L');
  $pdf->Cell(28, SAUT, '', 'B', 0, 'R');
  $pdf->Cell(28, SAUT, '', 'B', 0, 'R');
  $pdf->Cell(28, SAUT, '', 'B', 0, 'R');
  $pdf->Cell(28, SAUT, '', 'B', 0, 'R');
  $pdf->Cell(28, SAUT,  Dec_0($tour_mo / $roll, '.00 h'), 'B', 1, 'R');
  // CA POTENTIEL
  if ($ca !== 1) {
    $pdf->SetX(20);
    $pdf->SetFont('Nunito', 'B', FONT);
    $pdf->Cell(30, SAUT, 'CA potentiel', 'B', 0, 'L');
    $pdf->Cell(28, SAUT, '', 'B', 0, 'R');
    $pdf->Cell(28, SAUT, '', 'B', 0, 'R');
    $pdf->Cell(28, SAUT, '', 'B', 0, 'R');
    $pdf->Cell(28, SAUT, '', 'B', 0, 'R');
    $pdf->Cell(28, SAUT, Dec_0($tour_mo * $ca, '.00'), 'B', 1, 'R');
  }



  $pdf->Ln(10);
  $pdf->AddPage();
}



// Fin variable semaine


$fichier = 'Rapport_'  . $annee . '_' . date('dmY_His');
if (!is_dir('/kunden/homepages/26/d529006740/htdocs/flxxx/documents/pdf/variables/' . $secteur)) {
  mkdir('/kunden/homepages/26/d529006740/htdocs/flxxx/documents/pdf/variables/' . $secteur);
}
$pdf->Output('I', $fichier);
$pdf->Output('F', '/kunden/homepages/26/d529006740/htdocs/flxxx/documents/pdf/variables/' . $secteur . '/' . $fichier . '.pdf');
