<?php
error_reporting(\E_ALL);
ini_set('display_errors', 'stdout');
session_start();
$secteur = $_SESSION['idcompte'];
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$conn = new connBase();
foreach ($_POST as $k => $v) {
  ${$k} = (trim($v));
  //echo '$' . $k . ' = ' . trim($v) . '</br>';
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
<hr>
<p class="text-bold text-success">Pointage effectué</p>
<?php
$nomc = addslashes($nomcli);
$insreg = "INSERT INTO reglements(id, client, jour, mois, annee, mode, factref, montant, partiel,acompte, commercial, cs, commentaire, bordereau,bank) VALUES ('$numcli','$nomc','$jour','$mois','$annee','$mode','$numfact','$montant','$partiel','$acompte','$iduser','$idcompte','$commentaire $mode $date','$bdx','$idrib')";
$conn->handleRow($insreg);

$factpaye = "UPDATE facturesentete SET paye='oui' WHERE numero = '$numfact'";
$conn->handleRow($factpaye);

$conn->insertLog('Pointage direct relevé', $iduser, $bdx . ' ' . $numfact . ' ' . $montant);
?>

<script type='text/javascript'>
  $('#appel<?= $numcli ?>').fadeOut();

  ajaxData('cs = maj', '../src/pages/reglements/reglements_montotal.php ', 'montotal', 'attente_target');
</script>