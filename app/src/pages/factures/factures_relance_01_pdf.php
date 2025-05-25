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
$devis = new Factures($secteur);
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
$reqlignes = $conn->askFactureLigne($secteur, $numero);
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
$reqcompte = $conn->askClientAdresse($idcli);
foreach ($reqcompte as $key => $value) {
  ${'u_' . $key} = mb_convert_encoding($value, 'ISO-8859-1');
  //echo $key.' '.$value .'<br>';
}
$reqcompte = $conn->askClient($idcli);
foreach ($reqcompte as $key => $value) {
  ${'u_' . $key} = mb_convert_encoding($value, 'ISO-8859-1');
  //echo $key.' '.$value .'<br>';
}

$adresse_chantier = trim(strtolower($u_adresse));
$adresse_adresse = trim(strtolower($u_adressfact));

$compare = strcmp($adresse_adresse, $adresse_chantier);
$adresse_chantier = null;
if ($compare != '0') {
  $adresse_chantier = 'Chantier : ' . $u_adresse . ' ' . $u_cp . ' ' . $u_ville;
}


$reqfact = $devis->askFacturesEntete($numero);
foreach ($reqfact as $key => $value) {
  ${'f_' . $key} = mb_convert_encoding($value, 'ISO-8859-1');
  //echo $key.' '.$value .'<br>';
}

$reqbank = $conn->askBankDefault($secteur);
foreach ($reqbank as $key => $value) {
  // ${'bank_' . $key} = mb_convert_encoding($value, 'ISO-8859-1');
  //echo $key.' '.$value['nom_bank'];
  $compte_bancaire = mb_convert_encoding('IBAN : ' . $value['iban'] . ' BIC : ' . $value['bic'] . ' / ' . $value['nom_bank'], 'ISO-8859-1');
}


//var_dump($fact_relance);

$factav = $f_factav;
$nomsecteur = mb_convert_encoding(NomSecteur($secteur), 'ISO-8859-1');
$n = mb_convert_encoding('N°', 'ISO-8859-1');
$agrement = mb_convert_encoding('Agrément : ' . $c_agre, 'ISO-8859-1');
$datefacture = $f_jour . '/' . $f_mois . '/' . $f_annee;
define('SAUT', 4);
define('NOMSEC', strtoupper($nomsecteur . ' ' . $c_statut));
define('DENOMINATION', $c_denomination);
define('ADRESSE', $c_adresse);
define('CPVILLE', $c_cp . ' ' . ($c_ville));
define('LIEUDATE', ('A ' . $c_ville . ', le ' . date('d/m/Y')));
define('EDITION', ('Edition du ' . date('d/m/Y')));
define('SIRET', 'SIRET : ' . $c_siret . ' ' . $c_rcs);
define('AGRE', $agrement);
define('TELEPHONE', $c_telephone);
define('EMAIL', $c_email);
define('SEC', $secteur);
define('PIECE', $numero);
define('FACTAV', mb_convert_encoding('Information règlements', 'ISO-8859-1'));
define('RETRAITADR', 110);
define('NOMCLI', $u_civilite . ' ' . $u_prenom . ' ' . $u_nom);
define('ADRCLI', $u_adressfact);
define('CPVILLECLI', $u_cpfact . ' ' . $u_villefact);
define('ADRESSCHANTIER', $adresse_chantier);
define('N', $n);
define('FONT', 9);
define('VOTRECONTACT', 'Votre contact');
define('COMMERCIAL',  mb_convert_encoding(NomColla($iduser), 'ISO-8859-1'));
define('TELCOMM', $c_telephone);
define('TVA', $c_t7);
define('SAP', $c_sap);
if ($f_commentaire === "") {
  define('COMMENTAIRE', '');
} else {
  define('COMMENTAIRE', ' - ' . $f_commentaire);
}
define('EPOQUE', $epoque);
define('VALIDITE', mb_convert_encoding('Validité du devis : ' . $c_valdev, 'ISO-8859-1'));
define('MODEREG', $c_modreg . ', ' . $c_delpai . '.');
define('NUMCLIENT', $u_idcli);
define('EURO', '€');
define('BANK', $compte_bancaire);

// Vérification existance logo secteur.
$affiche_logo = null;
$chemin_logo = '../../../documents/img/' . SEC . '/logo.png';
if (file_exists($chemin_logo)) {
  define('ISLOGO', 'ok');
} else {
  define('ISLOGO', null);
}
class MyFacture extends FPDF
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
    // $this->SetFont('Nunito', 'B', 15);
    // // $this->Cell(50, SAUT + 4, '', '', 0, 'L');
    // $this->Cell(0, SAUT + 1,   N . ' ' . PIECE, '', 1, 'R');
    // $this->SetFont('Nunito', 'B', 9);
    // $this->Cell(0, SAUT + 1,   EDITION, '', 1, 'R');
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
    //$this->Cell(100, SAUT + 1, N . ' client : ' . NUMCLIENT, 0, 0, 'L');
    $this->SetFont('Nunito', 'B', 8);
    $this->Cell(100, SAUT + 1, ADRESSCHANTIER, 0, 0, 'L');


    $this->Ln(8);
  }
  function Infos()
  {
    $reglement = mb_convert_encoding('Règlement : ', 'ISO-8859-1');
    $this->SetFont('Nunito', '', 10);
    // $this->Cell(100, SAUT + 1, 'Execution : ' . EPOQUE, 0, 1, 'L');
    $this->Cell(100, SAUT + 1,  $reglement . MODEREG, 0, 1, 'L');
    //$this->Cell(100, SAUT + 1,  VALIDITE, 0, 1, 'L');
  }
  function Bank()
  {
    $reglement = mb_convert_encoding('Références bancaires : ', 'ISO-8859-1');
    $this->SetFont('Nunito', '', 10);
    // $this->Cell(100, SAUT + 1, 'Execution : ' . EPOQUE, 0, 1, 'L');
    $this->Cell(100, SAUT + 1,  $reglement . BANK, 0, 1, 'L');
    //$this->Cell(100, SAUT + 1,  VALIDITE, 0, 1, 'L');
  }
  function Lignes($secteur, $idcli)
  {
    $conn = new connBase();
    $fact_relance = $conn->askRelance($secteur, $idcli);
    $devis = new Factures(SEC);
    // $lignes = $devis->askFacturesLignes($numero);
    // $entete = $devis->askFacturesEntete($numero);
    $desi = mb_convert_encoding('Numéro facture', 'ISO-8859-1');
    $titre = mb_convert_encoding($fact_relance['numero'], 'ISO-8859-1');
    $titre = $titre === '' ? 'Facture(s) en attente ' : $titre;
    $this->SetFont('Nunito', 'B', 10);
    $this->Cell(190, SAUT + 2, $titre, 'B', 1, 'L');
    $this->Ln(4);
    $this->Cell(115, SAUT + 1, $desi, 0, 0, 'L');
    $this->Cell(25, SAUT + 1, 'Travaux', 0, 0, 'L');
    $this->Cell(25, SAUT + 1, '', 0, 0, 'R');
    $this->Cell(25, SAUT + 1, 'Montant', 0, 1, 'R');
    $this->Ln(5);
    $total = 0;
    $tot = 0;
    foreach ($fact_relance as $f) {
      
      $this->SetFont('Nunito', '', 10);
      // $this->Cell(190, SAUT + 2, $f['numero'].' du '.$f['jour'] . '/' . $f['mois'] . '/' . $f['annee'], '', 0, 'L');
      // $this->Ln(4);
      $this->Cell(115, SAUT + 1, $f['numero'] . ' du ' . $f['jour'] . '/' . $f['mois'] . '/' . $f['annee'], 0, 0, 'L');
      $this->Cell(25, SAUT + 1, $f['titre'], 0, 0, 'L');
      $this->Cell(25, SAUT + 1, '', 0, 0, 'R');
      $this->Cell(25, SAUT + 1, Dec_2($f['totttc'], ' euros'), 0, 1, 'R');
      //$this->Ln(5);
      $tot = Dec_2($f['totttc']);
$total = $total + $tot;


    }
    $this->Ln(5);
    $this->Cell(115, SAUT + 1, 'Total', 0, 0, 'L');
    $this->Cell(25, SAUT + 1, '', 0, 0, 'L');
    $this->Cell(25, SAUT + 1, '', 0, 0, 'R');
    $this->Cell(25, SAUT + 1, Dec_2($total, ' euros'), 0, 1, 'R');


    // $this->SetFont('Nunito', '', 10);
    // $montant_tva =  mb_convert_encoding('Montant TVA à ' . TVA . '%', 'ISO-8859-1');
    // $this->Cell(190, SAUT + 1, '', 'T', 1, 'L');
    // $this->Bank();
    // $this->Infos();
    //$this->Cell(190, SAUT + 1, 'Bon pour accord, signature du client.', '', 1, 'L');
    $y = $this->GetY();
    // $this->SetFillColor(250);
    // $this->RoundedRect(10, $y, 105, 35, 2, 'DF');
    $this->SetY($y + 36);
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
    // $montant_a_payer = mb_convert_encoding('Montant à payer TTC', 'ISO-8859-1');
    // $this->Cell(25, SAUT + 1, $montant_a_payer, 0, 0, 'L');
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
    $this->Ln(15);
    $this->Bank();
   // $this->Infos();
    $this->Ln(45);
    // $this->Infos();
    //$this->Ln(15);
  }
  function afficheImage($path, $x, $a)
  {
    $y = $this->GetY();
    $this->Image($path, $x, $y - $a, 30);
  }
}
$pdf = new MyFacture();
$pdf->AliasNbPages();
$pdf->AddFont('Nunito', '', 'Nunito-Regular.php');
$pdf->AddFont('Nunito', 'B', 'Nunito-Bold.php');
$pdf->SetAutoPageBreak(true, 55);
$pdf->AcceptPageBreak();
$pdf->AddPage();
$pdf->Adresse();
$pdf->Lignes($secteur, $idcli);

if ($f_paye === 'oui') {
  $pdf->afficheImage('../../../documents/img/' . $secteur . '/payee_trans.png', 85, 100);
}
$date_relance = date('dmY');
$fichier = 'Relance_' .  $u_idcli;
// if (!is_dir($chemin . '/documents/pdf/relances')) {
//   mkdir($chemin . '/documents/pdf/relances');
// }
// if (!is_dir($chemin . '/documents/pdf/relances/' . $secteur)) {
//   mkdir($chemin . '/documents/pdf/relances/' . $secteur);
// }
$path_relance = $devis->getCheminRelance($u_idcli);
$path_relance . '/' . $fichier . '.pdf';

$pdf->Output('I', $fichier);
$pdf->Output('F', $path_relance . '/' . $fichier . '.pdf');
