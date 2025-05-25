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
 //echo '$' . $key . ' = ' . $value . '<br>';
}

if($numdev){

  $delete = "delete from devislignes where numdev = '$numdev' limit 1";
  $conn->handleRow($delete);
  echo 'success';
}else{
  echo 'no';
}

