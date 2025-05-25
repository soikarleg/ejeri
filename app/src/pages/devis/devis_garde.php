<?php
session_start();
$secteur = $_SESSION['idcompte'];
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$conn = new connBase();
$devis = new Devis($secteur);

?>
<?php
$reqclient = "select * from devisestimatif where cs='$secteur' order by time_maj desc limit 4";
$client = $conn->allRow($reqclient);
?>

<div class="row">

  <div class=" col-md-6 ">
    <p class=" titre_menu_item mb-2">Derniers devis</p>
    <div class="row">
      <?php
      foreach ($client as $c) {
      ?>
        <div class="col-md-12">
          <div class="border-dot mb-2">
            <p class="small text-primary pull-right pointer" onclick="ajaxData('numero_devis=<?= $c['numero'] ?>&idcli=<?= $c['id'] ?>', '../src/pages/devis/devis_faire.php', 'action', 'attente_target');">Modifier le devis <i class='bx bx-link-external icon-bar pointer text-primary '></i></p>
            <p class="small text-muted "><?= 'le ' . AffDate($c['time_maj']) ?></p>
            <!-- <p class="small text-success pull-right pointer" onclick="ajaxData('idcli=<?= $c['id'] ?>', '../src/pages/devis/devis_faire.php', 'action', 'attente_target');">Faire un devis <i class='bx bx-file icon-bar pointer text-success '></i></p> -->
            <p><span class="puce mr-1">N° <?= $c['id'] ?></span> <?= '<b>' . NomClient($c['id']) . '</b>' ?></p>
            <p><span class="pull-right"><?= Dec_2($c['totttc'], " €") ?></span></p>
            <p> <?= '<b>' . ($c['numero']) . '</b> - ' . $c['titre']  ?></p>
            <p></p>
          </div>
        </div>
      <?php
      }
      ?>
    </div>
  </div>
  <div class="col-md-6">
    <p class="titre_menu_item mb-4">Validité des devis</p>
    <?php
    $repartition = $devis->getSerieRepartitionDevis($secteur);
    //echo ($repartition);
    $data = json_decode($repartition, true);
    // Extraire le premier nombre ("value") du tableau
    $premierNombre = $data[0]['value'];
    // Extraire le dernier nombre ("value") du tableau
    $dernierNombre = $data[count($data) - 1]['value'];
    ?>
    <script src="../../assets/js/echarts.js"></script>
    <script src="../../src/graph/graph_devis_repartition.js"></script>
    <div class="center-graph">
      <div id="contacts_repartition" style="width: 600px;height:500px;margin-top:-30px"></div>
    </div>
    <script type="text/javascript">
      $(function() {
        repartitionDevis('contacts_repartition', <?= $repartition ?>, <?= $premierNombre ?>, <?= $dernierNombre ?>);
      });
    </script>
  </div>
</div>