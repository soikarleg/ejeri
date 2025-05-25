<?php
session_start();
$secteur = $_SESSION['idcompte'];

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
  // '$' . $key . ' = ' . $value . '<br>';
}

$numero = $devis->genNumDoc();
?>
<p class="titre_menu_item mb-2">Enregistrement de la facture N° <?= $numero ?></p>