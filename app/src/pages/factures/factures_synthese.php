<?php
session_start();
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$secteur = $_SESSION['idcompte'];
$conn = new connBase();
$fact = new Factures($secteur);
$annref = !isset($_POST['annref']) ? date('Y') : $_POST['annref'];
?>
<script src="https://app.enooki.com/assets/js/echarts.js"></script>
<div class="">

  <div class="">
    <script src="https://app.enooki.com/src/graph/graph_facturation_synthese.js?=<?= time(); ?>"></script>
    <p class="puce-mag pull-right">
      <span class="mr-2"><i class='bx bx-history bx-flxxx icon-bar text-primary'></i></span>
      <?php
      for ($i = date('Y') - 4; $i < date('Y') + 1; $i++) {
      ?>
        <span class="pointer mr-2" onclick="ajaxData('erreur=<?= $erreur ?>&user=<?= $user ?>&annref=<?= $i ?>', '../src/pages/factures/factures_synthese.php', 'action', 'attente_target');"><?= $i ?></span>
      <?php
      }
      ?>

    </p>
    <p class="titre_menu_item mb-2">Synthèse - <?= $annref ?></p>

    <?php

    $ca_mois = $fact->getSerieAnnee();
    $ca_serie = $fact->getSerieCA($annref);
    $ca_serien = $fact->getSerieCAn($annref);



    ?>
    <div class="center-graph">
      <div id="facture_annee_synthese" style="width: 100%;height:500px;"></div>
    </div>
    <script type="text/javascript">
      $(function() {
        factureSynthese('facture_annee_synthese', <?= $ca_serie ?>, <?= $ca_serien ?>, <?= $ca_mois ?>);
      });
    </script>
  </div>
</div>