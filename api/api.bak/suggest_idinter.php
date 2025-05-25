<?php
session_start();
//require_once('../config/config.php');
foreach ($_SESSION as $k => $v) {
  ${$k} = $v;
  // echo '$' . $k. ' = ' . $v. '</br>';
}
$host_name = 'db607797151.db.1and1.com';
$database = 'db607797151';
$user_name = 'dbo607797151';
$password = 'FranLeg68FLGA';
$secteur = $_SESSION['idcompte'];
// Connexion à la base de données
$link = new PDO("mysql:host=$host_name; dbname=$database;", $user_name, $password);

// Récupération du terme de recherche envoyé par la requête AJAX
$term = trim(strip_tags($_GET['term']));

// Construction de la requête SELECT
$sql = "SELECT * FROM users WHERE idcompte='" . $secteur . "' and nom LIKE :term or idcompte='" . $secteur . "' and prenom LIKE :term and actif='1'";
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

  $label = stripslashes(strtoupper($v['nom'])) . ' ' . stripslashes(($v['prenom'])) ;
  $value = $v['idusers'];
  $tev = array('label' => $label, 'ncli' => $value);
  $res .= $sep . json_encode($tev);
  $sep = ',';
}
$res .= ']';
//}
echo $res;
// Envoi des résultats sous forme de tableau JSON au script JavaScript
//echo json_encode($results);