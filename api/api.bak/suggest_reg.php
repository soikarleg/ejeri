<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
session_start();
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];

$term = $_GET['term'];

$con = new connBase();

$select_fact = "select * from facturesentete where cs='$secteur' and paye = 'non' and nom like '%$term%' or cs='$secteur' and paye = 'non' and numero like '%$term%' or cs='$secteur' and paye = 'non' and totttc like '%$term%' order by nom asc";
$factures = $con->allRow($select_fact);


$res = '[';
$sep = " ";
foreach ($factures as $v) {
  $reg = "";
  $value = $v['totttc'];
  $idcli = $v['id'];
  $numero = $v['numero'];
  $date = $v['jour'] . '/' . $v['mois'] . '/' . $v['annee'];

  $select_reglement = "select SUM(montant) as reg from reglements where factref = '$numero' ";
  $reglement = $con->oneRow($select_reglement);
  $reg = $reglement['reg'];


  $label = (stripslashes(PrenomCli($v['id'])));
  $tev = array('label' => $label, 'value' => $value, 'idcli' => $idcli, 'numero' => $numero, 'reglement' => $reg, 'date' => $date);

  $res .= $sep . json_encode($tev);
  $sep = ',';
}
$res .= ']';
//}
echo $res;


// $host_name = 'db607797151.db.1and1.com';
// $database = 'db607797151';
// $user_name = 'dbo607797151';
// $password = 'FranLeg68FLGA';
// // Connexion à la base de données
// $link = new PDO("mysql:host=$host_name; dbname=$database;", $user_name, $password);

// // Récupération du terme de recherche envoyé par la requête AJAX
// $term = trim(strip_tags($_GET['term']));

// // Construction de la requête SELECT
// $sql = "SELECT * FROM client_chantier WHERE idcompte='" . $secteur . "' and nom LIKE :term or idcompte='" . $secteur . "' and idcli LIKE :term or idcompte='" . $secteur . "' and prenom LIKE :term order by nom asc";

// // Préparation de la requête
// $stmt = $link->prepare($sql);

// // Ligature du terme de recherche aux placeholders de la requête
// $stmt->bindValue(':term', '%' . $term . '%');

// // Exécution de la requête
// $stmt->execute();

// // Récupération des résultats
// $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
// //foreach ($results as $k) {
// //${$k}=$v;

// $res = '[';
// $sep = " ";
// foreach ($results as $v) {

//   $label = (stripslashes(strtoupper($v['idcli'] . ' - ' . $v['nom'])) . ' ' . stripslashes(($v['prenom'])) . ' à ' . strtoupper($v['ville']));

//   $value = $v['idcli'];
//   $tev = array('label' => $label, 'ncli' => $value);
//   $res .= $sep . json_encode($tev);
//   $sep = ',';
// }
// $res .= ']';
// //}
// echo $res;
// // Envoi des résultats sous forme de tableau JSON au script JavaScript
// //echo json_encode($results);