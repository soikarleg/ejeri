<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
session_start();
$secteur = $_SESSION['idcompte'];
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';

$conn = new connBase();
$facture = new Factures($secteur);

foreach ($_POST as $k => $v) {
  ${$k} = trim($v);
  // echo '$' . $k . ' = ' . trim($v) . '</br>';
}

$en_attente = $facture->askFacturesAttente();
echo Dec_2($en_attente['tot'], ' €');
?>
<!-- <span>Encours = <?= Dec_2($en_attente['tot'], ' €') ?></span> -->