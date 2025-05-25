<?php
session_start();
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$numero = $_GET['numero'];
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/vendor/setasign/fpdf/fpdf.php';
include $chemin . '/inc/function.php';
$conn = new connBase();
$devis = new Devis($secteur);
foreach ($_GET as $k => $v) {
  ${$k} = mb_convert_encoding($v, 'ISO-8859-1');
  // echo '$'.$k.' = '.$v.'</br>';
}
// On recupère les éléments de la facture avec N° et secteur.
$reqentete = $conn->askDevisNum($secteur, $numero);
foreach ($reqentete as $key => $value) {
  ${$key} = mb_convert_encoding($value, 'ISO-8859-1');
  //echo $key.' '.$value .'<br>';
}
$reqlignes = $conn->askDevisLigne($numero);
foreach ($reqlignes as $key => $value) {
  ${'l_' . $key} = mb_convert_encoding($value, 'ISO-8859-1');
  //echo $key.' '.$value .'<br>';
}
// On récupère les éléments de l'entreprise.
$reqcompte = $conn->askIdcompte($secteur);
foreach ($reqcompte as $key => $value) {
  ${'c_' . $key} = mb_convert_encoding($value, 'ISO-8859-1');
  //echo $key.' '.$value .'<br>';
}
// On récupère les éléments du client.
$reqcompte = $conn->askClient($id);
foreach ($reqcompte as $key => $value) {
  ${'u_' . $key} = mb_convert_encoding($value, 'ISO-8859-1');
  //echo $key.' '.$value .'<br>';
}
$devisestimatif = "BON DE TRAVAUX";
$nomsecteur = mb_convert_encoding(NomSecteur($secteur), 'ISO-8859-1');
$n = mb_convert_encoding('N°', 'ISO-8859-1');
$agrement = mb_convert_encoding('Agrément : ' . $c_agre, 'ISO-8859-1');
define('SAUT', 5);
define('NOMSEC', strtoupper($nomsecteur . ' ' . $c_statut));
define('DENOMINATION', $c_denomination);
define('ADRESSE', $c_adresse);
define('CPVILLE', $c_cp . ' ' . ($c_ville));
define('LIEUDATE', ('A ' . $c_ville . ', le ' . date('d/m/Y')));
define('SIRET', 'SIRET : ' . $c_siret . ' ' . $c_rcs);
define('AGRE', $agrement);
define('TELEPHONE', $c_telephone);
define('EMAIL', $c_email);
define('TELEPHONECLI', $u_telephone);
define('PORTABLECLI', $u_portable);
define('EMAILCLI', $u_email);
define('SEC', $secteur);
define('PIECE', $numero);
define('FACTAV', $devisestimatif);
define('RETRAITADR', 110);
define('NOMCLI', $u_civilite . ' ' . $u_prenom . ' ' . $u_nom);
define('ADRCLI', $u_adresse);
define('CPVILLECLI', $u_cp . ' ' . $u_ville);
define('N', $n);
define('FONT', 9);
define('VOTRECONTACT', 'Votre contact');
define('COMMERCIAL',  mb_convert_encoding(NomColla($iduser), 'ISO-8859-1'));
define('TELCOMM', $c_telephone);
define('TVA', $c_t7);
define('SAP', $c_sap);
if ($commentaire === "") {
  define('COMMENTAIRE', '');
} else {
  define('COMMENTAIRE', ' - ' . $commentaire);
}
define('EPOQUE', $epoque);
define('VALIDITE', mb_convert_encoding('Validité du devis : ' . $c_valdev, 'ISO-8859-1'));
define('MODEREG', $c_delpai . '. ' . $c_modreg);
define('NUMCLIENT', $u_idcli);
define('EURO', '€');
// Vérification existance logo secteur.
$affiche_logo = null;
$chemin_logo = '../../../documents/img/' . SEC . '/logo.png';
if (file_exists($chemin_logo)) {
  define('ISLOGO', 'ok');
} else {
  define('ISLOGO', null);
}
class MyDevis extends FPDF
{
  function Header()
  {
    if (!is_null(ISLOGO)) {
      //$hauteur_image = 80;
      $path_image = '../../../documents/img/' . SEC . '/logo.png';
      $h = definirHauteurImageProgressive($path_image);
      //var_dump($h);
      $this->Image($path_image, 8, 8, $h);
    }
    $this->SetFont('Nunito', 'B', 10);
    // $this->Cell(50, SAUT, '', '', 0, 'L');
    $this->Cell(0, SAUT + 5,  LIEUDATE, '', 1, 'R');
    $this->SetFont('Nunito', 'B', 20);
    // $this->Cell(50, SAUT, '', '', 0, 'L');
    $this->Cell(0, SAUT + 5,  FACTAV, '', 1, 'R');
    $this->SetFont('Nunito', 'B', 15);
    // $this->Cell(50, SAUT + 4, '', '', 0, 'L');
    $this->Cell(0, SAUT,   N . ' ' . PIECE, '', 1, 'R');
    $this->Ln(28);
    // $this->SetFont('Nunito', 'B', 14);
    // $this->Cell(0, SAUT + 4, '', '', 1, 'R');
    $y = $this->GetY();
    define('Y', $y);
    $this->SetFont('Nunito', 'B', 10);
    $this->SetY($y - 5);
    $this->Cell(100, SAUT + 1, N . ' client : ' . NUMCLIENT, 0, 0, 'L');
    $this->Ln(25);
  }
  function Footer()
  {
    $this->SetY(-25);
    $this->SetFont('Nunito', 'B', 8);
    $this->Cell(190, SAUT - 1, NOMSEC, 0, 1, 'C');
    $this->SetFont('Nunito', '', 8);
    $this->Cell(190, SAUT - 1, ADRESSE . ' ' . CPVILLE, 0, 1, 'C');
    $this->Cell(190, SAUT - 1, TELEPHONE . ' ' . EMAIL . '   ' . SIRET . ' ' . AGRE, 0, 1, 'C');
    //$this->Cell(190, SAUT - 1, , 0, 1, 'C');
    // Quatrième ligne
    $this->SetFont('Nunito', 'B', 7);
    $this->Cell(193, SAUT + 3, 'Page ' . $this->PageNo() . '/{nb}', 0, 1, 'C');
  }
  function Adresse()
  {
    $this->SetY(Y);
    $this->SetFont('Nunito', 'B', 10);
    //$this->SetX(RETRAITADR);
    $this->Cell(100, SAUT + 1, VOTRECONTACT, 0, 0, 'L');
    $this->Cell(95, SAUT + 1, NOMCLI, 0, 1, 'L');
    $this->SetFont('Nunito', '', 10);
    //$this->SetX(RETRAITADR);
    $this->Cell(100, SAUT + 1, COMMERCIAL, 0, 0, 'L');
    $this->Cell(95, SAUT + 1, ADRCLI, 0, 1, 'L');
    // $this->SetX(RETRAITADR);
    $this->Cell(100, SAUT + 1, TELCOMM, 0, 0, 'L');
    $this->Cell(95, SAUT + 1, CPVILLECLI, 0, 1, 'L');

    $this->Cell(100, SAUT + 1, '', 0, 0, 'L');
    $this->Cell(95, SAUT + 1, afficherTiret(TELEPHONECLI, PORTABLECLI), 0, 1, 'L');

    $this->Cell(100, SAUT + 1, '', 0, 0, 'L');
    $this->Cell(95, SAUT + 1, EMAILCLI, 0, 1, 'L');
    //$this->Cell(100, SAUT + 1, N . ' client : ' . NUMCLIENT, 0, 0, 'L');
    $this->Ln(8);
  }
  function Infos()
  {
    $reglement = mb_convert_encoding('Règlement : ', 'ISO-8859-1');
    $this->SetFont('Nunito', '', 10);
    $this->Cell(100, SAUT + 1, 'Execution : ' . EPOQUE, 0, 1, 'L');
    $this->Cell(100, SAUT + 1,  $reglement . MODEREG, 0, 1, 'L');
    $this->Cell(100, SAUT + 1,  VALIDITE, 0, 1, 'L');
  }
  function Lignes($numero)
  {
    $devis = new Devis(SEC);
    $lignes = $devis->askDevisLignes($numero);
    $entete = $devis->askDevisEntete($numero);
    $desi = mb_convert_encoding('Désignation', 'ISO-8859-1');
    $titre = mb_convert_encoding($entete['titre'], 'ISO-8859-1');
    $this->SetFont('Nunito', 'B', 10);
    $this->Cell(190, SAUT + 2, $titre . COMMENTAIRE, 'B', 1, 'L');
    $this->Ln(4);
    $this->Cell(165, SAUT + 1, $desi, 0, 0, 'L');
    $this->Cell(25, SAUT + 1, 'Q.', 0, 0, 'R');
    // $this->Cell(25, SAUT + 1, 'PU', 0, 0, 'R');
    // $this->Cell(25, SAUT + 1, 'PTTC', 0, 1, 'R');
    $this->Ln(5);
    foreach ($lignes as $l) {
      $this->SetFont('Nunito', '', 10);
      $desi = mb_convert_encoding($l['designation'], 'ISO-8859-1');
      //$this->Cell(95, SAUT + 1, $desi, 0, 0, 'L');
      if ($l['q'] === 0.00 || $l['puttc'] === 0.00 || $l['ptttc'] === 0.00) {
        $largeurWW = 195;
      } else {
        $largeurWW = 105;
      }
      $d = $this->WordWrap($desi, $largeurWW);
      //$this->MultiCell(115,SAUT,$d,0,'L',0);
      //$this->Cell(95, SAUT + 1, $d, 0, 0, 'L');
      $this->Write(SAUT + 1, $d);
      $this->SetX(175);
      $q = $l['q'] === 0.00 ? "" : Dec_2($l['q']);
      $this->Cell(25, SAUT + 1, $q, 0, 0, 'R');
      // $pu = $l['puttc'] === 0.00 ? "" : Dec_2($l['puttc']);
      // $this->Cell(25, SAUT + 1, $pu, 0, 0, 'R');
      // $tot = $l['ptttc'] === 0.00 ? "" : Dec_2($l['ptttc']);
      // $this->Cell(25, SAUT + 1, $tot, 0, 1, 'R');
      $this->Ln(8);
    }
    // $this->SetFont('Nunito', '', 10);
    // $montant_tva =  mb_convert_encoding('Montant TVA à ' . TVA . '%', 'ISO-8859-1');
    // $this->Cell(190, SAUT + 1, '', 'T', 1, 'L');
    // $this->Cell(190, SAUT + 1, 'Bon pour accord, signature du client.', '', 1, 'L');
    // $y = $this->GetY();
    // $this->SetFillColor(250);
    // $this->RoundedRect(10, $y, 105, 35, 2, 'DF');
    // $this->SetY($y + 36);
    // $sans_acompte = mb_convert_encoding('Aucun acompte demandé.', 'ISO-8859-1');
    // $avec_acompte = mb_convert_encoding('Acompte de ' . $entete['acompte_tx'] . '% à la signature, soit ' . Dec_2($entete['acompte']) . ' euros.', 'ISO-8859-1');
    // $acompte = $entete['acompte_tx'] != 0 ? $avec_acompte : $sans_acompte;
    // $this->SetFont('Nunito', 'B', 10);
    // $this->Cell(115, SAUT + 1, $acompte, '', 0, 'L');
    // $this->SetFont('Nunito', '', 10);
    // $this->SetY($y + 4);
    // $this->SetX(125);
    // $this->Cell(25, SAUT + 1, 'Montant HT', 0, 0, 'L');
    // $this->Cell(25, SAUT + 1, '', 0, 0, 'L');
    // $this->Cell(25, SAUT + 1, $ht = Dec_2($entete['totttc'] / 1.2), 0, 1, 'R');
    // $this->Cell(115, SAUT + 1, '', 0, 0, 'R');
    // $this->Cell(25, SAUT + 1, $montant_tva, 0, 0, 'L');
    // $this->Cell(25, SAUT + 1, '', 0, 0, 'L');
    // $this->Cell(25, SAUT + 1, Dec_2($entete['totttc'] - $ht), 0, 1, 'R');
    // $this->Cell(115, SAUT + 1, '', 0, 0, 'R');
    // $this->SetFont('Nunito', 'B', 10);
    // $this->Cell(25, SAUT + 1, 'A payer TTC', 0, 0, 'L');
    // $this->Cell(25, SAUT + 1, '', 0, 0, 'L');
    // $this->Cell(25, SAUT + 1, Dec_2($entete['totttc']), 0, 1, 'R');
    // if (SAP == null) {
    //   $reduction = mb_convert_encoding('Réduction d\'impôts envisagée', 'ISO-8859-1');
    //   $this->Cell(115, SAUT + 1, '', 0, 0, 'R');
    //   $this->SetFont('Nunito', 'B', 10);
    //   $this->Cell(25, SAUT + 1, $reduction, 0, 0, 'L');
    //   $this->Cell(25, SAUT + 1, '', 0, 0, 'L');
    //   $montant_reduction = Dec_2($entete['totttc'] / 2);
    //   $reduc = $montant_reduction > 5000 ? 5000 : $montant_reduction;
    //   $this->Cell(25, SAUT + 1, Dec_2($reduc), 0, 1, 'R');
    // }
    $this->Ln(8);
    $this->Cell(190, SAUT + 2, 'Horaires d\'intervention', 'B', 1, 'L');
    $this->Cell(190, SAUT + 2, 'Notes sur le chantier', '', 1, 'L');
    // $this->Infos();
    // $this->Ln(15);
  }
  function afficheImage($path, $x, $a)
  {
    $y = $this->GetY();
    $this->Image($path, $x, $y - $a, 30);
  }
}
$pdf = new MyDevis();
$pdf->AliasNbPages();
$pdf->AddFont('Nunito', '', 'Nunito-Regular.php');
$pdf->AddFont('Nunito', 'B', 'Nunito-Bold.php');
$pdf->SetAutoPageBreak(true, 55);
$pdf->AcceptPageBreak();
$pdf->AddPage();
$pdf->Adresse();
$pdf->Lignes($numero);
//$pdf->afficheImage('../../../documents/img/' . $secteur . '/payee_trans.png', 105, 50);
$fichier = 'BT_' .  $numero;
if (!is_dir($chemin . '/documents/pdf/devis')) {
  mkdir($chemin . '/documents/pdf/devis');
}
if (!is_dir($chemin . '/documents/pdf/devis/' . $secteur)) {
  mkdir($chemin . '/documents/pdf/devis/' . $secteur);
}
$pdf->Output('I', $fichier);
$pdf->Output('F', $chemin . '/documents/pdf/devis/' . $secteur . '/' . $fichier . '.pdf');
