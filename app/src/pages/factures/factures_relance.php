<?php
session_start();
error_reporting(\E_ALL);
ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$secteur = $_SESSION['idcompte'];
$conn = new connBase();
$facture = new Factures($secteur);

$annref = 'and annee = ' . date('Y');
?>
<p class="titre_menu_item mb-2 text-warning">Relance des factures en attente</p>
<div class="scroll">
  <div class="row">
    <?php
    $boucle_client = $conn->askAllClient($secteur);
    //var_dump($boucle_client);
    $nbrfact = 0;
    $montants_attente = 0;
    foreach ($boucle_client as $client) {
      $idcli = $client['idcli'];
      $factures_attente = $conn->askRelance($secteur, $idcli);
      $nbrfact += count($factures_attente);
      if ($factures_attente) {
        $path_relance = $facture->getCheminRelance($idcli);
        $path = $path_relance . '/Relance_' . $idcli . '.pdf';
        $relance_existe = file_exists($path);

    ?><div class="col-md-4 mt-2 mb-2">
          <div class="border-dot" style="height: auto">
            <?php
            $relances = new Relances($secteur, $idcli);
            $n = $relances->setNombreRelance();

            if ($relance_existe) {
            ?>
              <p class="pull-right mb-2"><i title="Relance faite" class='bx bxs-file icon-bar bx-flxxx text-warning'></i><?= $n ?></p>
            <?php
            }
            ?>
            <p class="text-bold mr-1"><?= $client['civilite'] . ' ' . $client['prenom'] . ' ' . $client['nom']; ?> </p>
            <?php
            $montant_client = 0;
            $infos = '';
            $infos .= '<small>';
            foreach ($factures_attente as $f) {
              $montants_attente += $f['totttc'];
              $montant_client += $f['totttc'];

              $infos .= $f['numero'] . ' du ' . AffDate($f['datefact']) . ' de ' . Dec_2($f['totttc']) . ' euros';
              $infos .= '<br>';

              $in = nl2br($infos);

              $ema = $client['email'] == '' ? 'Pas d\'email' : $client['email'];
            }
            ?>
            <p class="bg-pale-o puce pull-right l-5 text-center small">N° <?= $client['idcli'] ?></p>
            <p class="text-muted"> Montant total en attente : <?= $montant_client ?> € <i title="<?= $infos ?>" class='bx bxs-info-circle icon-bar bx-flxxx text-primary'></i></p>
            <p class="text-muted small"><?= $ema ?></p>
            <p class="mt-4 text-right pointer small" onclick="ajaxData('idcli=<?= $idcli ?>&infos=<?= $infos ?>','../src/pages/factures/factures_envoi_relance.php','action','attente_target');">Editer la relance n° <?= $n + 1 ?></p>
          </div>
        </div>
      <?php
      }
      ?>

    <?php
    }
    ?>

  </div>
  <div class="col-md-9">
    <div id="relance"></div>
  </div>
</div>
<div class="border-dot mt-2 mb-2">
  <span><?= $nbrfact ?> factures en attente pour un montant total de <?= Dec_2($montants_attente, ' €TTC') ?></span>
</div>
<script>
  $(function() {
    $('.bx').tooltip({
      html: true
    });
  });
</script>