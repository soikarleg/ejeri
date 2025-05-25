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
$access = new Access();
foreach ($_GET as $k => $v) {
  ${$k} = mb_convert_encoding($v, 'ISO-8859-1');
  //echo '$' . $k . ' = ' . $v . '</br>';
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
$datefacture = strEncoding('31 décembre ' . $annref);
define('SAUT', 6);
define('ANNREF', $annref);
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
define('FACTAV', 'Attestation fiscale ' . $annref);
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
    $this->Cell(100, SAUT - 1, VOTRECONTACT, 0, 0, 'L');
    $this->Cell(95, SAUT - 1, NOMCLI, 0, 1, 'L');
    $this->SetFont('Nunito', '', 10);
    $this->Cell(100, SAUT - 1, COMMERCIAL . ' ' . SEC, 0, 0, 'L');
    $this->Cell(95, SAUT - 1, ADRCLI, 0, 1, 'L');
    $this->Cell(100, SAUT - 1, TELCOMM, 0, 0, 'L');
    $this->Cell(95, SAUT - 1, CPVILLECLI, 0, 1, 'L');
    $this->SetFont('Nunito', 'B', 8);
    $this->Cell(100, SAUT - 1, ADRESSCHANTIER, 0, 0, 'L');
    $this->Ln(8);
  }
  function Lignes($idcli)
  {
    $attestation = new Pieces($idcli);
    $titre = strEncoding('Attestation établie suivant les règlements effectués jusqu\'au 31 décembre ' . ANNREF);
    $titre = $titre === '' ? 'Prestation' : $titre;
    $this->SetFont('Nunito', '', 10);
    $this->Cell(190, SAUT + 1, $titre, 0, 1, 'L');
    $this->Ln(10);

    //? *******************************
    $this->SetFont('Nunito', 'B', 12);
    $this->Cell(15, SAUT + 1, '', 0, 0, 'L');
    $this->SetTextColor(0, 0, 139);
    $this->Cell(160, SAUT + 1, '1 - NATURE DES SERVICES', 0, 0, 'L');
    $this->SetTextColor(0, 0, 0);
    $this->Cell(15, SAUT + 1, '', 0, 1, 'L');
    $this->Ln(2);
    $this->SetFont('Nunito', '', 10);
    $this->Cell(15, SAUT + 1, '', 0, 0, 'L');
    $this->Cell(160, SAUT + 1, 'Entretien de jardins.', 0, 0, 'L');
    $this->Cell(15, SAUT + 1, '', 0, 1, 'L');
    $this->Ln(8);
    //? ******************************

    //? ******************************
    $this->SetFont('Nunito', 'B', 12);
    $this->Cell(15, SAUT + 1, '', 0, 0, 'L');
    $this->SetTextColor(0, 0, 139);
    $this->Cell(160, SAUT + 1, '2 - INTERVENANTS', 0, 0, 'L');
    $this->SetTextColor(0, 0, 0);
    $this->Cell(15, SAUT + 1, '', 0, 1, 'L');
    $this->Ln(2);
    $this->SetFont('Nunito', '', 10);
    $intervenants = $attestation->attesIntervenants(ANNREF, SEC);
    foreach ($intervenants as $inter) {
      $this->Cell(15, SAUT + 1, '', 0, 0, 'L');
      $this->Cell(80, SAUT + 1, strEncoding(NomColla($inter['idinter']) . ' - N° ' . SEC . '-' . $inter['idinter']), 0, 0, 'L');
      $this->Cell(80, SAUT + 1,  Dec_2($inter['hrs'], ' hrs'), 0, 0, 'R');
      $this->Cell(15, SAUT + 1, '', 0, 1, 'L');
    }
    $this->Ln(8);
    //? ******************************

    //? ******************************
    $this->SetFont('Nunito', 'B', 12);
    $this->Cell(15, SAUT + 1, '', 0, 0, 'L');
    $this->SetTextColor(0, 0, 139);
    $this->Cell(160, SAUT + 1, '3 - DUREE ANNUELLE DES INTERVENTIONS', 0, 0, 'L');
    $this->SetTextColor(0, 0, 0);
    $this->Cell(15, SAUT + 1, '', 0, 1, 'L');
    $this->Ln(2);
    $this->SetFont('Nunito', '', 10);
    $intervenants = $attestation->attesHeures(ANNREF, SEC);

    $this->Cell(15, SAUT + 1, '', 0, 0, 'L');
    $this->Cell(80, SAUT + 1, strEncoding('Total des heures d\'intervention en base 100.'), 0, 0, 'L');
    $this->Cell(80, SAUT + 1,  Dec_2($intervenants['hrs'], ' hrs'), 0, 0, 'R');
    $this->Cell(15, SAUT + 1, '', 0, 1, 'L');

    $this->Ln(8);
    //? ******************************

    //? ******************************
    $this->SetFont('Nunito', 'B', 12);
    $this->Cell(15, SAUT + 1, '', 0, 0, 'L');
    $this->SetTextColor(0, 0, 139);
    $this->Cell(160, SAUT + 1, '4 - MODALITE ET MONTANT DES PAIEMENTS', 0, 0, 'L');
    $this->SetTextColor(0, 0, 0);
    $this->Cell(15, SAUT + 1, '', 0, 1, 'L');
    $this->Ln(2);
    $this->SetFont('Nunito', '', 10);
    $modes = $attestation->attesMode(ANNREF, SEC);
    $total = 0;
    foreach ($modes as $mode) {
      $report = $mode['mode'] != 'CESU' ? ($mode['tot']) : ($mode['tot']);
      $this->Cell(15, SAUT + 1, '', 0, 0, 'L');
      $this->Cell(80, SAUT + 1, strEncoding($mode['mode']), 0, 0, 'L');
      $this->Cell(80, SAUT + 1,   Dec_2($mode['tot'], ' euros'), 0, 0, 'R');
      $this->Cell(15, SAUT + 1, '', 0, 1, 'L');
      $total = $total + $report;
    }

    $this->Cell(15, SAUT + 1, '', 0, 0, 'L');
    $this->Cell(80, SAUT + 1, 'Total', 0, 0, 'L');
    $this->SetFont('Nunito', 'B', 10);
    $this->Cell(80, SAUT + 1,   Dec_2($total, ' euros'), 0, 0, 'R');
    $this->Cell(15, SAUT + 1, '', 0, 1, 'L');
    $this->SetFont('Nunito', '', 10);
    $this->Ln(8);
    //? ******************************

    //? ******************************
    $this->SetFont('Nunito', 'B', 12);
    $this->Cell(15, SAUT + 1, '', 0, 0, 'L');
    $this->SetTextColor(0, 0, 139);
    $this->Cell(160, SAUT + 1, '5 - MONTANT A REPORTER SUR VOTRE DECLARATION', 0, 0, 'L');
    $this->SetTextColor(0, 0, 0);
    $this->Cell(15, SAUT + 1, '', 0, 1, 'L');
    $this->Ln(2);
    $this->SetFont('Nunito', '', 10);
    $report = $attestation->attesReport(ANNREF, SEC);

    $this->Cell(15, SAUT + 1, '', 0, 0, 'L');
    $this->Cell(80, SAUT + 1, strEncoding('Montant à reporter à la ligne DB ou DF.'), 0, 0, 'L');
    $this->SetFont('Nunito', 'B', 10);
    $this->Cell(80, SAUT + 1,  Dec_2($report['tot'], ' euros'), 0, 0, 'R');
    $this->Cell(15, SAUT + 1, '', 0, 1, 'L');
    $this->SetFont('Nunito', '', 10);
    $this->Ln(8);


    //? ******************************


    $this->Ln(5);

    $this->Ln(45);
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
$pdf->Lignes($idcli);
$fichier = 'Attestation_' . $secteur . '_' . $idcli . '_' . $annref;
$access->putLog($idcli, 'Edition attestation ' . $fichier);
if (!is_dir($chemin . '/documents/pdf/attestations')) {
  mkdir($chemin . '/documents/pdf/attestations');
}
if (!is_dir($chemin . '/documents/pdf/attestations/' . $secteur)) {
  mkdir($chemin . '/documents/pdf/attestations/' . $secteur);
}
$chemin . '/documents/pdf/attestations/' . $secteur . '/' . $fichier . '.pdf';
$pdf->Output('I', $fichier);
$pdf->Output('F', $chemin . '/documents/pdf/attestations/' . $secteur . '/' . $fichier . '.pdf');
