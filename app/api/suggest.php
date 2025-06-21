<?php
error_reporting(\E_ALL);
ini_set('display_errors', 'stdout');
session_start();
$secteur = $_SESSION['idcompte'];

$chemin = $_SERVER['DOCUMENT_ROOT'];

$base = include $chemin . '/config/base_ionos.php';
$host_name = $base['db_host'];
$database = $base['db_name'];
$user_name = $base['db_user'];
$password = $base['db_password'];

$link = new PDO("mysql:host=$host_name; dbname=$database;", $user_name, $password);

// Récupération du terme de recherche envoyé par la requête AJAX
$term = trim(strip_tags($_GET['term']));

// Construction de la requête SELECT
// $sql = "SELECT * FROM client WHERE
// ville LIKE :term and idcompte = :secteur
// or cp LIKE :term  and idcompte = :secteur
// or nom LIKE :term  and idcompte = :secteur
// or idcli LIKE :term  and idcompte = :secteur
// or prenom LIKE :term and idcompte = :secteur
// order by nom asc";
$sql = "SELECT * FROM client c
JOIN client_correspondance cc ON c.idcli = cc.idcli
WHERE (cc.ville LIKE :term AND c.idcompte = :secteur)
OR (cc.cp LIKE :term AND c.idcompte = :secteur)
OR (c.nom LIKE :term AND c.idcompte = :secteur)
OR (c.idcli LIKE :term AND c.idcompte = :secteur)
OR (c.prenom LIKE :term AND c.idcompte = :secteur)
ORDER BY c.nom ASC";

// Préparation de la requête
$stmt = $link->prepare($sql);

// Ligature du terme de recherche aux placeholders de la requête
$stmt->bindValue(':term', '%' . $term . '%');
$stmt->bindValue(':secteur', $secteur);

// Exécution de la requête
$stmt->execute();

// Récupération des résultats
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
//foreach ($results as $k) {
//${$k}=$v;

if (empty($results)) {
  $res = json_encode(['message' => '<p>Aucun résultat pour : ' . $term . '</p>']);
} else {
  $res = '[';
  $sep = " ";
  foreach ($results as $v) {
    $prenom = $v['prenom'] == '' ? '' : $v['prenom'];
    $label = '<p class=""><span style="margin-top:-2px" class="mypills bleu mr-2 px-3 py-0">' . (stripslashes(($v['idcli'] . '</span>  ' .$v['civilite'].' '. $v['nom'])) . ' ' . stripslashes(($prenom)) . ' - ' . ($v['ville']) . ' ' . $v['cp'] . '</p>');

    $value = $v['idcli'];
    $tev = array('label' => $label, 'ncli' => $value);
    $res .= $sep . json_encode($tev, JSON_UNESCAPED_SLASHES);
    $sep = ',';
  }
  $res .= ']';
}
echo $res;
// Envoi des résultats sous forme de tableau JSON au script JavaScript
//echo json_encode($results);