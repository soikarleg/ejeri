<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
session_start();
$secteur = $_SESSION['idcompte'];
$conn = new connBase();
$devis = new Devis($secteur);
$d = $devis->askNbrDevis();
$devis_total = $d['a'] + $d['v'] + $d['r'];

foreach ($_POST as $k => $v) {
  ${$k} = $v;
}

$users = $conn->askDossierIntervenant($idusers);

?>

<p class="titre_menu_item mb-2 mt-4"></p>
<div class="row">
  <div class="col-md-3">
    <div class="border-dot" style="height:550px;">
      <!-- <p class="titre_menu_item mb-2">Devis <span class="pull-right text-muted small"><?= $devis_total ?> u.</span></p>
      <p>En attente <span class="pull-right"><?= $d['a']; ?> u.</span></p>
      <p>Validé <span class="pull-right"><?= $d['v']; ?> u.</span></p>
      <p>Refusé <span class="pull-right"><?= $d['r']; ?> u.</span></p>
      <p>Montant devis validé <span class="pull-right"><?= Dec_0($d['m'] / 1000, ' K€'); ?></span></p>
      <p>Moyenne <span class="pull-right"><?= Dec_2($d['m'] / $d['v'], ' €'); ?></span></p> -->


      <p class="">CAHT annuel <?= date('Y') ?></p>
      <p class="mb-4 text-right text-grand"> 152560 €</p>
      <p class="">CA mensuel <?= date('m/Y') ?></p>
      <p class="mb-4 text-right text-grand"> 13560 €</p>


    </div>
  </div>
  <div class="col-md-9">
    <div class="border-dot" style="height:550px;">

      <div id="action_prod"></div>
    </div>

  </div>

</div>
<script>
  ajaxData('cs=cs', '../src/pages/production/production_bilan.php', 'action_prod', 'attente_target');
</script>