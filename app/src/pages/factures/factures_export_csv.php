<?php
session_start();
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$secteur = $_SESSION['idcompte'];
$conn = new connBase();

$data =  json_encode($_POST['numero']);
$data = trim($data, '"');
$factures_numero = explode('_', $data);
//var_dump($factures_numero);
$nb_factures = count($factures_numero);
//var_dump($factures_numero);
$ligne_csv0 = array(
  'Journal',
  'Date',
  'Numero',
  'Compte',
  'Libelle ecriture',
  'Debit',
  'Credit'
);
$lignes = [];
foreach ($factures_numero as $f) {

  $facture = $conn->askFactureNum($secteur, $f);
  //var_dump($facture);
  $id_client_modifie = strlen($facture['id']) <= 4 ? '0' . $facture['id'] : $facture['id'];
  $nomshort = Tronque($facture['nom'], 8, 0);

  $compte_client = '411' . $facture['id'] . $nomshort;
  $ttc = $facture['totttc'];
  $ht = $ttc / 1.20;
  $tva = $ttc - $ht;


  //Ligne client
  // echo "'VTE', " . AffDate($facture['datefact']) . "," . $facture['numero'] . "," . $compte_client . "," . $facture['factav'] . "," . $facture['numero'] . "," . $facture['totttc'] . ", ''";

  if ($facture['factav'] == 'FACTURE') {


    $ligne_csv0 =  array('VTE', AffDate($facture['datefact']), $facture['numero'], $compte_client, $facture['factav'] . ' TTC ' . $facture['numero'] . ' ' . $nomshort, Dec_2($facture['totttc']), '0.00');
    $lignes_csv[] = $ligne_csv0;
    // Ligne TVA collectée
    $ligne_csv1 =  array('VTE', AffDate($facture['datefact']), $facture['numero'], '44576000', $facture['factav'] . ' TVA ' . $facture['numero'] . ' ' . $nomshort, '0.00', Dec_2($tva));
    $lignes_csv[] = $ligne_csv1;
    // Ligne Vente HT
    $ligne_csv2 =  array('VTE', AffDate($facture['datefact']), $facture['numero'], '70650000', $facture['factav'] . ' HT ' . $facture['numero'] . ' ' . $nomshort, '0.00', Dec_2($ht));
    $lignes_csv[] = $ligne_csv2;
  } else {
    $ligne_csv0 =  array('VTE', AffDate($facture['datefact']), $facture['numero'], $compte_client, $facture['factav'] . ' TTC ' . $facture['numero'] . ' ' . $nomshort, '0.00', abs($facture['totttc']));
    $lignes_csv[] = $ligne_csv0;
    // Ligne TVA collectée
    $ligne_csv1 =  array('VTE', AffDate($facture['datefact']), $facture['numero'], '44576000', $facture['factav'] . ' TVA ' . $facture['numero'] . ' ' . $nomshort, abs(Dec_2($tva)), '0.00');
    $lignes_csv[] = $ligne_csv1;
    // Ligne Vente HT
    $ligne_csv2 =  array('VTE', AffDate($facture['datefact']), $facture['numero'], '70650000', $facture['factav'] . ' HT ' . $facture['numero'] . ' ' . $nomshort, abs(Dec_2($ht)), '0.00');
    $lignes_csv[] = $ligne_csv2;
  }
}

$horodatage =  date('dmY_His');
$nom_fichier = $secteur . '_factures_' . $horodatage . '.csv';
$handle = fopen($nom_fichier, 'w');
foreach ($lignes_csv as $ligne) {
  fputcsv($handle, $ligne);
}
fclose($handle);
?>


<a class="text-warning" href="/src/pages/factures/<?= $secteur ?>_factures_<?= $horodatage ?>.csv" target="_blank">Téléchargez votre fichier : <?= $secteur ?>_factures.csv</a>