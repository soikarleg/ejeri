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
  //echo $key . ' ' . $value . '<br>';
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

$reqacompte = $devis->askFacturesEntete($numero);
foreach ($reqacompte as $key => $value) {
  ${'ac_' . $key} = mb_convert_encoding($value, 'ISO-8859-1');
  //echo $key.' '.$value .'<br>';
}

$verif_acompte = $devis->askAcompte($ac_devref, $idcli);
foreach ($verif_acompte as $key => $value) {
  ${'a_' . $key} = mb_convert_encoding($value, 'ISO-8859-1');
  // echo $key . ' ' . $value . '<br>';
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
  //echo $key . ' ' . $value . '<br>';
}
$reqbank = $conn->askBankDefault($secteur);
foreach ($reqbank as $key => $value) {
  $compte_bancaire = mb_convert_encoding('IBAN : ' . $value['iban'] . ' BIC : ' . $value['bic'] . ' / ' . $value['nom_bank'], 'ISO-8859-1');
}
$factav = $f_factav;
$nomsecteur = mb_convert_encoding(NomSecteur($secteur), 'ISO-8859-1');
$n = mb_convert_encoding('N°', 'ISO-8859-1');
$agrement = mb_convert_encoding('Agrément : ' . $c_agre, 'ISO-8859-1');
$datefacture = $reqfact['jour'] . '/' . $f_mois . '/' . $f_annee;
define('SAUT', 4);
define('NOMSEC', strtoupper($nomsecteur . ' ' . $c_statut));
define('DENOMINATION', $c_denomination);
define('ADRESSE', $c_adresse);
define('CPVILLE', $c_cp . ' ' . ($c_ville));
define('LIEUDATE', ('A ' . $c_ville . ', le ' . $datefacture));
define('EDITION', ('Edition du ' . date('d/m/Y')));
define('SIRET', 'SIRET : ' . $c_siret . ' ' . $c_rcs);
define('AGRE', $agrement);
define('TELEPHONE', $c_telephone);
define('EMAIL', $c_email);
define('SEC', $secteur);
define('PIECE', $numero);
define('FACTAV', $factav);
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
      $path_image = '../../../documents/img/' . SEC . '/logo.png';
      $h = definirHauteurImageProgressive($path_image);
      $this->Image($path_image, 8, 8, $h);
    }
    $this->SetFont('Nunito', 'B', 10);
    $this->Cell(0, SAUT + 5,  LIEUDATE, '', 1, 'R');
    $this->SetFont('Nunito', 'B', 20);
    $this->Cell(0, SAUT + 5,  FACTAV, '', 1, 'R');
    $this->SetFont('Nunito', 'B', 15);
    $this->Cell(0, SAUT + 1,   N . ' ' . PIECE, '', 1, 'R');
    $this->SetFont('Nunito', 'B', 9);
    $this->Cell(0, SAUT + 1,   EDITION, '', 1, 'R');
    $this->Ln(28);
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
    $this->Cell(190, SAUT, NOMSEC, 0, 1, 'C');
    $this->SetFont('Nunito', '', 8);
    $this->Cell(190, SAUT, ADRESSE . ' ' . CPVILLE, 0, 1, 'C');
    $this->Cell(190, SAUT, TELEPHONE . ' ' . EMAIL . '   ' . SIRET . ' ' . AGRE, 0, 1, 'C');
    $this->SetFont('Nunito', 'B', 7);
    $this->Cell(193, SAUT + 3, 'Page ' . $this->PageNo() . '/{nb}', 0, 1, 'C');
  }
  function Adresse()
  {
    $this->SetY(Y);
    $this->SetFont('Nunito', 'B', 10);
    $this->Cell(100, SAUT + 1, VOTRECONTACT, 0, 0, 'L');
    $this->Cell(95, SAUT + 1, NOMCLI, 0, 1, 'L');
    $this->SetFont('Nunito', '', 10);
    $this->Cell(100, SAUT + 1, COMMERCIAL, 0, 0, 'L');
    $this->Cell(95, SAUT + 1, ADRCLI, 0, 1, 'L');
    $this->Cell(100, SAUT + 1, TELCOMM, 0, 0, 'L');
    $this->Cell(95, SAUT + 1, CPVILLECLI, 0, 1, 'L');
    $this->SetFont('Nunito', 'B', 8);
    $this->Cell(100, SAUT + 1, ADRESSCHANTIER, 0, 0, 'L');
    $this->Ln(8);
  }
  function Infos()
  {
    $reglement = mb_convert_encoding('Règlement : ', 'ISO-8859-1');
    $this->SetFont('Nunito', '', 10);
    $this->Cell(100, SAUT + 2,  $reglement . MODEREG, 0, 1, 'L');
    $notation = strEncoding('Merci de noter le numéro de facture lors de vos virements ou au dos votre chèque.');
    $this->SetFont('Nunito', 'B', 10);
    $this->Cell(25, SAUT + 2, $notation, 0, 1, 'L');
    $adrenvoi = strEncoding('Envoyez vos chèques au 3, place de l`Eglise 45740 Lailly-en-Val.');
    if (SEC == 'C4X') {
      $this->SetFont('Nunito', 'B', 10);
      $this->Cell(25, SAUT + 2, $adrenvoi, 0, 0, 'L');
    }
  }
  function Bank()
  {
    $reglement = mb_convert_encoding('Références bancaires : ', 'ISO-8859-1');
    $this->SetFont('Nunito', '', 10);
    // $this->Cell(100, SAUT + 1, 'Execution : ' . EPOQUE, 0, 1, 'L');
    $this->Cell(100, SAUT + 2,  $reglement . BANK, 0, 1, 'L');
    //$this->Cell(100, SAUT + 1,  VALIDITE, 0, 1, 'L');
  }
  function Lignes($numero)
  {
    $devis = new Factures(SEC);
    $lignes = $devis->askFacturesLignes($numero);
    $entete = $devis->askFacturesEntete($numero);
    $acompte = $devis->askAcompte($entete['devref'], NUMCLIENT);
    //echo $entete['devref'];
    //var_dump($acompte);
    if ($acompte['montant']) {
      $acompte_client = $acompte['montant'];
    } else {
      $acompte_client = 0;
    }

    $desi = mb_convert_encoding('Désignation', 'ISO-8859-1');
    $titre = mb_convert_encoding($entete['titre'], 'ISO-8859-1');
    $titre = $titre === '' ? 'Prestation' : $titre;
    $this->SetFont('Nunito', 'B', 10);
    $this->Cell(190, SAUT + 2, $titre . COMMENTAIRE, 'B', 1, 'L');
    $this->Ln(4);
    $this->Cell(115, SAUT + 1, $desi, 0, 0, 'L');
    $this->Cell(25, SAUT + 1, 'Q.', 0, 0, 'R');
    $this->Cell(25, SAUT + 1, 'PU', 0, 0, 'R');
    $this->Cell(25, SAUT + 1, 'PTTC', 0, 1, 'R');
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
      $this->SetX(125);
      $q = $l['q'] === 0.00 ? "" : Dec_2($l['q']);
      $this->Cell(25, SAUT + 1, $q, 0, 0, 'R');
      $pu = $l['puttc'] === 0.00 ? "" : Dec_2($l['puttc']);
      $this->Cell(25, SAUT + 1, $pu, 0, 0, 'R');
      $tot = $l['ptttc'] === 0.00 ? "" : Dec_2($l['ptttc']);
      $this->Cell(25, SAUT + 1, $tot, 0, 1, 'R');
      $this->Ln(3);
    }
    $this->SetFont('Nunito', '', 10);
    $montant_tva =  mb_convert_encoding('Montant TVA à ' . TVA . '%', 'ISO-8859-1');
    $this->Cell(190, SAUT + 1, '', 'T', 1, 'L');
    $y = $this->GetY();
    $this->SetY($y + 36);
    $this->SetFont('Nunito', '', 10);
    $this->SetY($y + 4);
    $this->SetX(125);
    $this->Cell(25, SAUT + 1, 'Montant HT', 0, 0, 'L');
    $this->Cell(25, SAUT + 1, '', 0, 0, 'L');
    $this->Cell(25, SAUT + 1, $ht = Dec_2(($entete['totttc'] + $acompte_client) / 1.2), 0, 1, 'R');
    $this->Cell(115, SAUT + 1, '', 0, 0, 'R');
    $this->Cell(25, SAUT + 1, $montant_tva, 0, 0, 'L');
    $this->Cell(25, SAUT + 1, '', 0, 0, 'L');
    $this->Cell(25, SAUT + 1, Dec_2($entete['totttc'] + $acompte_client - $ht), 0, 1, 'R');
    $this->Cell(115, SAUT + 1, '', 0, 0, 'R');
    $this->SetFont('Nunito', 'B', 10);
    $montant_a_payer = mb_convert_encoding('Montant à payer TTC', 'ISO-8859-1');
    $this->Cell(25, SAUT + 1, $montant_a_payer, 0, 0, 'L');
    $this->Cell(25, SAUT + 1, '', 0, 0, 'L');
    $this->Cell(25, SAUT + 1, Dec_2($entete['totttc'] + $acompte_client), 0, 1, 'R');
    if ($acompte_client > 0) {
      $this->Cell(115, SAUT + 1, '', 0, 0, 'R');
      $this->SetFont('Nunito', '', 10);
      $acompte_verse = mb_convert_encoding('Acompte versé', 'ISO-8859-1');
      $this->Cell(25, SAUT + 1, $acompte_verse, 0, 0, 'L');
      $this->Cell(25, SAUT + 1, '', 0, 0, 'L');
      $this->Cell(25, SAUT + 1, Dec_2($acompte_client), 0, 1, 'R');

      $this->Cell(115, SAUT + 1, '', 0, 0, 'R');
      $this->SetFont('Nunito', 'B', 10);
      $acompte_verse = mb_convert_encoding('Solde', 'ISO-8859-1');
      $this->Cell(25, SAUT + 1, $acompte_verse, 0, 0, 'L');
      $this->Cell(25, SAUT + 1, '', 0, 0, 'L');
      $this->Cell(25, SAUT + 1, Dec_2($entete['totttc']), 0, 1, 'R');
    }
    if (SAP != null) {

      $montant_reduction = Dec_2(($entete['totttc'] + $acompte_client) / 2);
      $reduc = $montant_reduction > 5000 ? 5000 : $montant_reduction;
      $reduction = mb_convert_encoding('Réduction d\'impôts envisagée', 'ISO-8859-1');

      //$this->Cell(115, SAUT + 1, '', 0, 0, 'R');
      $this->SetFont('Nunito', '', 10);
      $this->Cell(25, SAUT + 1, $reduction . ' : ' . Dec_2($reduc) . ' euros', 0, 1, 'L');


      // $this->Cell(25, SAUT + 1, '', 0, 0, 'L');

      // $this->Cell(25, SAUT + 1, Dec_2($reduc), 0, 1, 'R');
    }
    $this->Ln(10);
    $this->Bank();
    $this->Infos();
    $this->Ln(45);
    // $this->Infos();
    //$this->Ln(15);
  }
  function afficheImage($path, $x, $a)
  {
    $y = $this->GetY();
    $this->Image($path, $x, $y - $a - 14, 30);
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
$pdf->Lignes($numero);

if ($f_paye === 'oui') {
  $pdf->afficheImage('../../../assets/img/payee_trans.png', 85, 80);
}
$fichier = 'Facture_' .  $numero;
if (!is_dir($chemin . '/documents/pdf/factures')) {
  mkdir($chemin . '/documents/pdf/factures');
}
if (!is_dir($chemin . '/documents/pdf/factures/' . $secteur)) {
  mkdir($chemin . '/documents/pdf/factures/' . $secteur);
}

$chemin . '/documents/pdf/factures/' . $secteur . '/' . $fichier . '.pdf';
$pdf->Output('I', $fichier);
$pdf->Output('F', $chemin . '/documents/pdf/factures/' . $secteur . '/' . $fichier . '.pdf');
