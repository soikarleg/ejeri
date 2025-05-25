<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
session_start();
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/vendor/autoload.php';
include $chemin . '/inc/function.php';
$conn = new connBase();
$synchro = new Bridge($secteur, $iduser);

$banks = $conn->askBank($secteur);
//var_dump($_POST);
foreach ($_POST as $key => $value) {
  echo ${$key} = $value;
}

?>

<div class="card card-body">
  <h5>Encaissement</h5>
  <?php
  $transac = $conn->askTransac($secteur);
  $transac_nbr = count($transac);
  $start = max(0, $transac_nbr - 50);

  for ($i = $transac_nbr - 1; $i >= $start; $i--) {
    foreach ($transac[$i] as $k => $v) {
      ${$k} = $v;
      //echo $k . ' ' . $v . '</br>';
    }
    if ($amount > 0) {
      echo   Tronque($clean_description, '30', '3') . ' ' . $amount . '</br>';
    }
  }


  ?>
</div>