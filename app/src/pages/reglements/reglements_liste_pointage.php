<?php
error_reporting(\E_ALL);
ini_set('display_errors', 'stdout');
session_start();
echo $secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';

$conn = new connBase();
echo $term = $_POST['term'];

// if (is_int($term)) {
//   echo "La variable est de type entier (int).";
// } elseif (is_string($term)) {
//   echo "La variable est de type chaîne de caractères (str).";
// } else {
//   echo "La variable n'est ni un entier ni une chaîne de caractères.";
// }

if (ctype_digit($term)) {
  echo "La chaîne est un INT";
} elseif (ctype_alpha($term)) {
  echo "La chaîne est un STR";
} else{
  echo "La chaine est STR et INT ou vide";
}

$reqreg = "select id,numero,totttc,nom,datefact,factav,paye from facturesentete where cs='$secteur' order by nom asc ";
//$reglements = $conn->allRow($reqreg);
