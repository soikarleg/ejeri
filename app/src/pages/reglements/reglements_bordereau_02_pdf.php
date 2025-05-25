<?php
session_start();
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$numbdx = $_GET['numbdx'];
$idcli = $_GET['idcli'];
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/vendor/setasign/fpdf/fpdf.php';
include $chemin . '/inc/function.php';
$conn = new connBase();
$reg = new Reglements($secteur);
$pdf = new FPDF;
$nums = explode('_', $num);

$client = $conn->askClient($idcli, 'nom');
$u_nom = str_replace(' ', '_', $client['nom']);
$u_nom = trim(strtoupper($u_nom));

$reg->askBordereau($numbdx);
//var_dump($reg);

foreach ($nums as $mykey => $numero) {
  $mypdf = new BordereauFPDF($secteur, $numbdx, $pdf);
  $mypdf->Header();
  // $mypdf->Adresse();
  $t =  $mypdf->Lignes($numbdx);
  // $mypdf->InfosReg();
  $mypdf->Footer();
}
// sortie

$datage = date('dmY');
$numbdx = str_replace('/', '', $numbdx);
$fichier = 'Remise_' . $numbdx . '_' . Dec_2($t);
if (!is_dir($chemin . '/documents/pdf/remises')) {
  mkdir($chemin . '/documents/pdf/remises');
}
if (!is_dir($chemin . '/documents/pdf/remises/' . $secteur)) {
  mkdir($chemin . '/documents/pdf/remises/' . $secteur);
}

$chemin . '/documents/pdf/remises/' . $secteur . '/' . $fichier . '.pdf';
$pdf->Output('D', $fichier . '.pdf');
$pdf->Output('F', $chemin . '/documents/pdf/remises/' . $secteur . '/' . $fichier . '.pdf');
$pdf->Output('I', $fichier);
