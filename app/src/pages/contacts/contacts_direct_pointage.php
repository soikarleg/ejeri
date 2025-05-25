<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
session_start();
$secteur = $_SESSION['idcompte'];
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$conn = new connBase();
foreach ($_POST as $k => $v) {
  ${$k} = (trim($v));
  //echo '$' . $k . ' = ' . trim($v) . '</br>';
  if ($v == '') $no = 'no';
}
$d = explode('/', $date);
$jour =  $d[0];
$mois = $d[1];
$annee = $d[2];

if (!isset($acompte)) {
  $acompte = "0";
}
if (!isset($partiel)) {
  $partiel = "non";
}
?>
<br>

<?php
if ($no == 'no') {
?>
  <p class="text-bold text-danger text-center">Il manque une valeur dans votre demande... mode ?</p>
<?php

} else {
?>
  <p class="text-bold text-success text-center">Pointage effectué</p>
<?php
  $numfact = $numero;
  $nomc = addslashes($nomcli);
  $insreg = "INSERT INTO reglements(id, client, jour, mois, annee, mode, factref, montant, partiel,acompte, commercial, cs, commentaire, bordereau,bank) VALUES ('$numcli','$nomc','$jour','$mois','$annee','$mode','$numfact','$montant','$partiel','$acompte','$iduser','$idcompte','$commentaire $mode $date','$bdx','$idrib')";
  $conn->handleRow($insreg);

  $factpaye = "UPDATE facturesentete SET paye='oui' WHERE numero = '$numfact'";
  
  $conn->handleRow($factpaye);

  $conn->insertLog('Pointage direct fiche client', $iduser, $bdx . ' ' . $numfact . ' ' . $montant);
}
?>

<!-- <script type='text/javascript'>
  $('#appel<?= $numcli ?>').fadeOut();

  ajaxData('cs = maj', '../src/pages/reglements/reglements_montotal.php ', 'montotal', 'attente_target');
</script> -->