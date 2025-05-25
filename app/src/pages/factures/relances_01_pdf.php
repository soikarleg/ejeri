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
$datedition = strEncoding('Liste des appels édité le ' . date('d/m/Y'));
define('SAUT', 6);
define('NOMSEC', strtoupper($nomsecteur . ' ' . $c_statut));
define('DENOMINATION', $c_denomination);
define('ADRESSE', $c_adresse);
define('CPVILLE', $c_cp . ' ' . ($c_ville));
define('LIEUDATE',  $datedition);
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



class MyFacture extends FPDF
{
  function Header()
  {
    $this->SetFont('Nunito', 'B', 10);
    $this->Cell(0, SAUT + 5,  LIEUDATE, '', 1, 'R');
    $y = $this->GetY();
    define('Y', $y);
    $this->SetFont('Nunito', 'B', 20);
    $this->SetY($y - 7);
    $this->Cell(100, SAUT, 'RELANCE TELEPHONIQUE', 0, 0, 'L');
    $this->Ln(15);
  }
  function Footer()
  {
    $this->SetY(-15);
    $this->SetFont('Nunito', 'B', 8);
    $this->Cell(0, SAUT, NOMSEC . ' - ' . TELEPHONE, 0, 1, 'C');
    $this->SetFont('Nunito', 'B', 7);
    $this->Cell(0, SAUT + 3, 'Page ' . $this->PageNo() . '/{nb}', 0, 1, 'C');
  }

  function Lignes($numero)
  {
    $factures = new Factures(SEC);
    $attente = $factures->askFacturesAttente();
    $relance = $factures->askFacturesRelance();

    //var_dump($acompte);

    $attente_montant = 'Montant total en attente : ' . Dec_2($attente['tot'], ' euros');
    $telephone = strEncoding('Téléphone');

    $this->SetFont('Nunito', 'B', 10);
    $this->Cell(0, SAUT + 2,  $attente_montant, 'B', 1, 'R');
    $this->Ln(4);

    $this->Ln(5);

    $total = 0;
    $touravant = '-';
    foreach ($relance as $l) {
      $conn = new connBase();
      $client = $conn->askClient($l['id']);
      $attente = $factures->askFacturesAttenteClient(trim($l['id']));
      $this->SetFont('Nunito', 'B', 10);
      $this->Cell(0, SAUT, trim($l['id']) . '-' . $touravant . ' - ' . NomClient($l['id']) . ' / ' . strEncoding($client['ville'])  . ' ' . $attente['tot'], 'B', 1, 'L');

      $this->Ln(3);
      foreach ($attente as $l) {
        $totalimp = $factures->askFacturesRelanceClientTotal(trim($l['id']));
        $total = $total + $l['totttc'];


        $this->SetFont('Nunito', '', 10);
        $this->Cell(15, SAUT, '', 0, 0, 'L');
        $this->Cell(145, SAUT,   $l['numero'] . ' du ' . AffDate($l['datefact']) . ' / ' . ($l['titre']), 0, 0, 'L');
        $this->Cell(35, SAUT,    Dec_2($l['totttc']), 0, 0, 'R');
        $this->Cell(35, SAUT, '', 0, 0, 'L');
        $this->Cell(41, SAUT, Tel($client['telephone']), 0, 0, 'C');
        $this->Cell(6, SAUT, '', 1, 1, 'C');
        $this->Ln(3);
        $touravant = !empty($touravant) ? $touravant : trim($l['id']);
        if (trim($l['id']) == $touravant) {
          $this->SetFont('Nunito', 'B', 10);

          $this->Cell(160, SAUT, 'Sous-total ', 0, 0, 'L');
          $this->Cell(35, SAUT, Dec_2($totalimp['tot']), 0, 0, 'R');
          $this->Cell(35, SAUT, '', 0, 0, 'R');
          $this->Cell(41, SAUT, '', 0, 0, 'R');
          $this->Cell(5, SAUT, '', 0, 1, 'C');
          $this->Ln(13);
        }
        $touravant = trim($l['id']);
      }
    }
  }
}




$pdf = new MyFacture();
$pdf->AliasNbPages();
$pdf->AddFont('Nunito', '', 'Nunito-Regular.php');
$pdf->AddFont('Nunito', 'B', 'Nunito-Bold.php');
$pdf->SetAutoPageBreak(true, 30);
$pdf->AcceptPageBreak();
$pdf->AddPage('L', 'A4');
$pdf->Lignes($numero);


//? *****************************************************
//? Création et utilisation repertoire stockage .pdf - DEBUT
$fichier = 'Relance_tel_' .  date('dmY_His');
if (!is_dir($chemin . '/documents/pdf/relances_tel')) {
  mkdir($chemin . '/documents/pdf/relances_tel');
}
if (!is_dir($chemin . '/documents/pdf/relances_tel/' . $secteur)) {
  mkdir($chemin . '/documents/pdf/relances_tel/' . $secteur);
}
//? Création et utilisation repertoire stockage .pdf - FIN
//? *****************************************************


$chemin . '/documents/pdf/relances_tel/' . $secteur . '/' . $fichier . '.pdf';
$pdf->Output('I', $fichier);
$pdf->Output('F', $chemin . '/documents/pdf/relances_tel/' . $secteur . '/' . $fichier . '.pdf');
