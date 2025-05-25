<?php
session_start();
error_reporting(\E_ALL);
ini_set('display_errors', 'stdout');
$num = $_GET['numero'];
$idcli = $_GET['idcli'];
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
$chemin = $_SERVER['DOCUMENT_ROOT'];

include $chemin . '/vendor/setasign/fpdf/fpdf.php';
include $chemin . '/inc/function.php';

$conn = new connBase();
$devis = new Factures($secteur);
$nums = explode('_', $num);

// Initialisation du PDF
$pdf = new MyFacture();
$pdf->AliasNbPages();
$pdf->AddFont('Nunito', '', 'Nunito-Regular.php');
$pdf->AddFont('Nunito', 'B', 'Nunito-Bold.php');
$pdf->SetAutoPageBreak(true, 55);

// Boucle sur chaque numéro de facture dans $nums
foreach ($nums as $numero) {
  // Récupérer les données de la facture
  $reqentete = $conn->askDevisNum($secteur, $numero);
  foreach ($reqentete as $key => $value) {
    ${$key} = mb_convert_encoding($value, 'ISO-8859-1');
  }

  // Autres requêtes pour récupérer les détails de la facture, client, etc.

  // Ajouter une nouvelle page pour chaque facture
  $pdf->AddPage();

  // Ajouter les informations de la facture à la page
  $pdf->Adresse();
  $pdf->Lignes($numero);

  // Vérifier si la facture est payée
  if ($f_paye === 'oui') {
    $pdf->afficheImage('../../../assets/img/payee_trans.png', 85, 80);
  }
}

// Définir le chemin de sauvegarde du fichier PDF
$datage = date('dmY');
$fichier = 'Factures_' . $u_nom . '_' . $idcli . '_' . $datage;
$dirPath = $chemin . '/documents/pdf/factures_group/' . $secteur;

if (!is_dir($dirPath)) {
  mkdir($dirPath, 0777, true);
}

// Sortie du fichier PDF (enregistrement et affichage)
$pdfPath = $dirPath . '/' . $fichier . '.pdf';
$pdf->Output('F', $pdfPath);
$pdf->Output('I', $fichier);
