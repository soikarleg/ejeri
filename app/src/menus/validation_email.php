<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';


$conn = new authBase();
$reponse = false;
$email = $_POST['email'];
$requser = "SELECT COUNT(*) as count FROM `users` WHERE emailcollabo = '$email'";
$user = $conn->oneRowAuth($requser);
$userCount = $user['count'];
if ($userCount > 0) {
  echo 'true';
}
?>
