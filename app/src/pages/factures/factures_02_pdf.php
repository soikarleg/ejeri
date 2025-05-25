<?php
session_start();
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$num = $_GET['numero'];
$idcli = $_GET['idcli'];
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/vendor/setasign/fpdf/fpdf.php';
include $chemin . '/inc/function.php';
$conn = new connBase();
$facture = new Factures($secteur);
$pdf = new FPDF;
$nums = explode('_', $num);

$liste = $_GET['liste'];

if ($liste != 'liste') {

  $client = $conn->askClient($idcli, 'nom');
  $u_nom = str_replace(' ', '_', $client['nom']);
  $u_nom = trim(strtoupper($u_nom));

  if ($u_nom == '') {
    $u_nom = 'group';
    $mont = 'x';
  } else {
    $u_nom = $u_nom;
    $mont = $facture->askFacturesEntete($nums[0]);
    $mont = Dec_2($mont['totttc']);
  }

  if ($idcli == '') {
    $idcli = count($nums);
  } else {
    $idcli = $idcli;
  }

  foreach ($nums as $mykey => $numero) {
    $mypdf = new FactureFPDF($secteur, $numero, $pdf);
    $mypdf->Header();
    $mypdf->Adresse();
    $mypdf->Lignes($numero);
    $mypdf->InfosReg();
    $mypdf->Footer();
  }

  // sortie

  $datage = date('dmY');
  $fichier = 'Facture_' . $u_nom . '_' .  $idcli . '_' . $num . '_' . $mont;
  if (!is_dir($chemin . '/documents/pdf/factures')) {
    mkdir($chemin . '/documents/pdf/factures');
  }
  if (!is_dir($chemin . '/documents/pdf/factures/' . $secteur)) {
    mkdir($chemin . '/documents/pdf/factures/' . $secteur);
  }

  $chemin . '/documents/pdf/factures/' . $secteur . '/' . $fichier . '.pdf';
  $pdf->Output('D', $fichier . '.pdf');
  $pdf->Output('F', $chemin . '/documents/pdf/factures/' . $secteur . '/' . $fichier . '.pdf');

  $pdf->Output('I', $fichier);
} else {


  $mypdf = new FactureListeFPDF($secteur, $nums, $pdf);
  $mypdf->Header();
  $mypdf->Lignes($nums);
  $mypdf->Footer();
  // Générer les lignes
  $pdf->Output('I', 'Liste_facture_attente_' . date('dmY') . '.pdf');
}
