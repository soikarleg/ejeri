<?php
session_start();
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$data = $_POST;
$conn = new connBase();
$ins = new FormValidation($data);
$devis = new Devis($secteur);
foreach ($_POST as $key => $value) {
  ${$key} = $ins->valFull($value);
  // echo '$' . $key . ' = ' . $value . '<br>';
}

if ($numero && $statut) {

  $update = "update devisestimatif SET validite='$statut' where numero='$numero' limit 1 ";
  $conn->handleRow($update);
  $conn->insertLog('Modification statut devis', $iduser, 'Passage du devis ' . $numero . ' en statut : ' . $statut);
  echo 'success';
} else {
  echo 'no';
}
