<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
session_start();

$conn = new connBase();
$reponse = false;
$secteur = $_POST['secteur'];

$requser = "SELECT COUNT(*) as count FROM users WHERE idcompte = '$secteur'";
$user = $conn->oneRow($requser);


$userCount = $user['count'];
if ($userCount > 0) {
  echo 'existe';
}
