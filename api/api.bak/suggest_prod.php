<?php
session_start();
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
//require_once('../config/config.php');
foreach ($_SESSION as $k => $v) {
  ${$k} = $v;
  //echo '$' . $k. ' = ' . $v. '</br>';
}
$secteur = $_SESSION['idcompte'];
$host_name = 'db607797151.db.1and1.com';
$database = 'db607797151';
$user_name = 'dbo607797151';
$password = 'FranLeg68FLGA';
// Connexion à la base de données
$link = new PDO("mysql:host=$host_name; dbname=$database;", $user_name, $password);
// Récupération du terme de recherche envoyé par la requête AJAX
$term = trim(strip_tags($_GET['term']));
// Construction de la requête SELECT
$sql = "SELECT * FROM client_chantier WHERE idcompte='" . $secteur . "' and nom LIKE :term";
// Préparation de la requête
$stmt = $link->prepare($sql);
// Ligature du terme de recherche aux placeholders de la requête
$stmt->bindValue(':term', '%' . $term . '%');
// Exécution de la requête
$stmt->execute();
// Récupération des résultats
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
//foreach ($results as $k) {
//${$k}=$v;
$res = '[';
$sep = " ";
foreach ($results as $v) {
  $label = stripslashes(($v['nom'])) . ' ' . stripslashes(($v['prenom']));
  $value = $v['idcli'];
  $tev = array('label' => $label, 'ncli' => $value);
  $res .= $sep . json_encode($tev);
  $sep = ',';
}
$res .= ']';
//}
echo $res;
// Envoi des résultats sous forme de tableau JSON au script JavaScript
//echo json_encode($results);