<?php
error_reporting(\E_ALL);
ini_set('display_errors', 'stdout');
session_start();
$secteur = $_SESSION['idcompte'];

$chemin = $_SERVER['DOCUMENT_ROOT'];

// $base = include $chemin . '/config/base_ionos.php';
// $host_name = $base['db_host'];
// $database = $base['db_name'];
// $user_name = $base['db_user'];
// $password = $base['db_password'];

$host_name = 'db607797151.db.1and1.com';
$database = 'db607797151';
$user_name = 'dbo607797151';
$password = 'FranLeg68FLGA-010268-!';

// Connexion à la base de données
$link = new PDO("mysql:host=$host_name; dbname=$database;", $user_name, $password);

// Récupération du terme de recherche envoyé par la requête AJAX
$term = trim(strip_tags($_GET['term']));

// Construction de la requête SELECT
$sql = "SELECT * FROM client_chantier WHERE   nom LIKE :term or  idcli LIKE :term or  prenom LIKE :term order by nom asc";

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

  $label = '<p class="btn btn-mag-n"><span class="mypills violet mr-2">' . (stripslashes(strtoupper($v['idcli'] . '</span>  ' . $v['nom'])) . ' ' . stripslashes(($v['prenom'])) . ' à ' . strtoupper($v['ville']) . ' ' . $v['cp'] . '</p>');

  $value = $v['idcli'];
  $tev = array('label' => $label, 'ncli' => $value);
  $res .= $sep . json_encode($tev, JSON_UNESCAPED_SLASHES);
  $sep = ',';
}
$res .= ']';
//}
echo $res;
// Envoi des résultats sous forme de tableau JSON au script JavaScript
//echo json_encode($results);