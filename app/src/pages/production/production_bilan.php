<?php
session_start();
error_reporting(\E_ALL);
ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$secteur = $_SESSION['idcompte'];
$conn = new connBase();
$prod = new Production($secteur);
?>
<script src="https://app.enooki.com/assets/js/echarts.js"></script>
<div class="">

  <div class="">
    <script src="https://app.enooki.com/src/graph/graph_production_synthese_annee.js"></script>
    <?php
    $annref = date('Y');
    $moisref = date('m');
    $heures = $prod->getHeures($moisref, $annref);
    $ratio_annee = $prod->getRatio();
    $getSerieAnnee = $prod->getSerieAnnee();
    $getSerieHNF = $prod->getSerieHNF();
    $getSerieHMO = $prod->getSerieHMO();
    $getSerieMoyenne = $prod->getSerieMoyenne();
    $getTauxHoraire = $prod->getTauxHoraire();
    ?>
    <p class="titre_menu_item mb-2">Production <?= date('Y') ?></p>
    <div class="center-graph">
      <div id="production_annee_synthese" style="width:100%;height:450px;"></div>
    </div>
    <script type="text/javascript">
      $(function() {
        productionSyntheseAnnee('production_annee_synthese', <?= $getSerieAnnee ?>, <?= $getSerieHNF ?>, <?= $getSerieHMO ?>, <?= $getSerieMoyenne ?>, <?= $getTauxHoraire ?>);
      });
    </script>
  </div>
</div>