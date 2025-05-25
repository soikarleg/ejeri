<?php
session_start();
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
$conn = new connBase();
$devis = new Devis($secteur);
$myindics = new MyIndics($secteur, $iduser);
$moisref = $myindics->getMois();
$fact = $myindics->MyFact("", "2024");
?>
<p>Test comparatif</p>
<p><?= var_dump($fact); ?></p>