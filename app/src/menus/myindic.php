<?php
//session_start();
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
//include $chemin . '/inc/error.php';
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
$conn = new connBase();
$devis = new Devis($secteur);
$production = new Production($secteur);
$facture = new Factures($secteur);
$reglements = new Reglements($secteur);
$bridge = new Bridge();
$annee = isset($_GET['annee']) ? $_GET['annee'] : date('Y');
$username = $_POST['user'] ?? '';
/* #devis */
$nbr_devis = $devis->askNbrDevis();
$nbr_tot = Dec_0($nbr_devis['t'], ' u.');
/* #enddevis */
//echo $data_verifie = verifData($secteur, $username);
$data_verifie = false;
if (!$data_verifie) {
?>
  <div class="mt-3">
    <div class="row">
      <!-- //*DEVIS*/ -->
      <div class="col-md-3">
        <div class="card card-body">
          <h5 class="text-right valign"><i class='bx bxs-folder-open bx-lg mr-2 text-primary'></i> Devis en attente </h5>
          <div class="row align-items-center mb-4">
            <?php
            $moisref = date('m');
            $attente = $devis->askNbrDevis();
            ?>
            <div class="col-md-7  d-flex">
              <p class="small text-left my-auto">Devis à pointer</p>
            </div>
            <div class="col-md-5 d-flex justify-content-end">
              <h1 class="text-right my-auto"><?= Dec_0($attente['a'], ' u.') ?></h1>

            </div>
            <p>
              <small>
                <?php
                //include $chemin . '/inc/comparatif.php';
                ?>
              </small>
            </p>
          </div>
          <div class="graph-container">
            <?php
            for ($i = 1; $i < 13; $i++) {
              if (strlen($i) == 1) {
                $i = '0' . $i;
              } else {
                $i = $i;
              }
              $nbr_devis = $devis->askNbrDevisMois($i);
              $affmois = $nbr_devis > 0 ? $i : '';
              $nbr_devis = $nbr_devis == '0' ? 0.4 : $nbr_devis;
            ?>
              <div class="bardevis text-center pointer" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="<?= Dec_0($nbr_devis) ?> devis réalisés." style="height: <?= $nbr_devis * 4 ?>px;"><small class="text-xs"><?= $affmois ?></small></div>
            <?php
            }
            ?>
          </div>
          <p class="small text-center text-muted mb-2">Répartition des devis en <?= $annee ?></p>
          <div class="row align-items-center mb-4">
            <div class="col-md-7  d-flex">
              <p class="text-left my-auto small">Devis réalisés en <?= $annee ?></p>
            </div>
            <div class="col-md-5  d-flex justify-content-end">
              <h5 class="text-right my-auto"><?= $nbr_tot ?></h5>
            </div>
          </div>
          <p class="btn btn-mag-n" onclick="ajaxData('pass=devis', '../src/menus/menu_devis.php', 'target-one', 'attente_target');"><i class='bx bx-link bx-flxxx icon-bar'></i>Pointer vos devis</p>
        </div>
      </div>
      <!-- //*******/ -->
      <!-- //*PROD*/ -->
      <div class="col-md-3">
        <?php
        $moisref = date('m');
        $hrs_ann_encours = $production->getHeures($moisref, $annee);
        $hrs_ann_passee = $production->getHeures($moisref, $annee - 1);
        if ($hrs_ann_encours['hmo'] > $hrs_ann_passee['hmo']) {
          $indicateur_tendance = "<i class='bx bxs-up-arrow-circle icon-bar-p text-success mr-1'></i>";
        } else {
          $indicateur_tendance = "<i class='bx bxs-down-arrow-circle icon-bar-p text-danger mr-1'></i>";
        }
        ?>
        <div class="card card-body">
          <h5 class="text-right valign"><i class='bx bx-qr bx-lg mr-4 text-warning'></i> Production <?= strtolower(moisAbrege(date('m'))) . ' ' . date('Y')  ?> </h5>
          <div class="row align-items-center mb-4">
            <div class="col-md-4  d-flex">
              <p data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Heures de <?= strtolower(moisLettre($moisref)) . ' ' . $annee - 1 ?>" class="small text-left my-auto"><?= Dec_0($hrs_ann_passee['hmo'], ' hrs') ?> <?= $indicateur_tendance ?></p>
            </div>
            <div class="col-md-8 d-flex justify-content-end">
              <h1 class="text-right my-auto"><?= Dec_0($hrs_ann_encours['hmo'], ' hrs') ?></h1>
            </div>
            <p>
              <small>
                <?php
                // include $chemin . '/inc/comparatif.php';
                ?>
              </small>
            </p>
          </div>
          <div class="graph-container">
            <?php
            $heures_facturable_annee = $production->getHeures("", $annee);
            for ($i = 1; $i < 13; $i++) {
              if (strlen($i) == 1) {
                $i = '0' . $i;
              } else {
                $i = $i;
              }
              $heures_facturable_mois = $production->getHeures($i, $annee);
              $affmois = $heures_facturable_mois['hmo'] > 0 ? $i : '';
              $heures_facturable_mois['hmo'] = $heures_facturable_mois['hmo'] == '0' ? 20 : $heures_facturable_mois['hmo'];
            ?>
              <div class="barprod text-center pointer" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="<?= Dec_0($heures_facturable_mois['hmo']) ?> heures facturables réalisées." style="height: <?= $heures_facturable_mois['hmo'] / 5  ?>px;"><small class="text-xs"><?= $affmois ?></small></div>
            <?php
            }
            ?>
          </div>
          <p class="small text-center text-muted mb-2">Répartition production en <?= $annee ?></p>
          <div class="row align-items-center mb-4">
            <div class="col-md-7  d-flex">
              <p class="text-left my-auto small">Production de <?= $annee ?></p>
            </div>
            <div class="col-md-5  d-flex justify-content-end">
              <h5 class="text-right my-auto"><?= Dec_0($heures_facturable_annee['hmo'], ' hrs') ?></h5>
            </div>
          </div>
          <p class="btn btn-mag-n" onclick="ajaxData('pass=null', '../src/menus/menu_productions.php', 'target-one', 'attente_target');"><i class='bx bx-link bx-flxxx icon-bar'></i>Noter vos productions</p>
        </div>
      </div>
      <!-- //*******/ -->
      <!-- //*AFACT*/ -->
      <div class="col-md-3">
        <?php
        $production_afacturer = $production->getTotNonFacture();
        $taux_horaire = $production->getTauxHoraire();
        ?>
        <div class="card card-body">
          <h5 class="text-right valign"><i class='bx bxs-file-export bx-lg mr-4 text-success'></i> Factures à établir</h5>
          <div class="row align-items-center mb-4">
            <div class="col-md-4  d-flex">
              <p class="small text-left my-auto">Reste <?= Dec_0($production_afacturer, ' hrs') ?></p>
            </div>
            <div class="col-md-8 d-flex justify-content-end">
              <h1 class="text-right my-auto"><?= Dec_0($production_afacturer * $taux_horaire, ' €') ?></h1>
            </div>
            <p>
              <small>
                <?php
                //  include $chemin . '/inc/comparatif.php';
                ?>
              </small>
            </p>
          </div>
          <div class="graph-container">
            <?php
            $heures_facturable_annee = $production->getHeures("", $annee);
            $facture_cumul_annee = $facture->askEtatFactures("", $annee);
            for ($i = 1; $i < 13; $i++) {
              if (strlen($i) == 1) {
                $i = '0' . $i;
              } else {
                $i = $i;
              }
              $facture_mois = $facture->askFacturesMois($i, $annee);
              $facture->$heures_facturable_mois = $production->getHeures($i, $annee);
              $affmois = $facture_mois['tot'] > 0 ? $i : '';
              $heures_facturable_mois['hmo'] = $heures_facturable_mois['hmo'] == '0' ? 1 : $heures_facturable_mois['hmo'];
            ?>
              <div class="barfact text-center pointer" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="<?= Dec_0($facture_mois['tot']) ?> € de facturation." style="height: <?= $facture_mois['tot'] / 300  ?>px;"><small class="text-xs"><?= $affmois ?></small></div>
            <?php
            }
            ?>
          </div>
          <p class=" small text-center text-muted mb-2">Répartition factures en <?= $annee ?></p>
          <div class="row align-items-center mb-4">
            <div class="col-md-7  d-flex">
              <p class="text-left my-auto small">Total de <?= $annee ?></p>
            </div>
            <div class="col-md-5  d-flex justify-content-end">
              <h5 class="text-right my-auto"><?= Dec_0($facture_cumul_annee['tot'], ' €') ?></h5>
            </div>
          </div>
          <p class="btn btn-mag-n" onclick="ajaxData('pass=null', '../src/menus/menu_factures.php', 'target-one', 'attente_target');"><i class='bx bx-link bx-flxxx icon-bar'></i>Faites vos factures</p>
        </div>
      </div>
      <!-- //********/ -->
      <!-- //*APOINTER*/ -->
      <div class="col-md-3">
        <?php
        $factures_attente = $facture->askFacturesAttente();
        $encaissement = $reglements->askTotalBordereau("", $annee);
        ?>
        <div class="card card-body">
          <h5 class="text-right valign"><i class='bx bxs-file-import bx-lg mr-4 text-danger'></i>Règlements en attente</h5>
          <div class="row align-items-center mb-4">
            <div class="col-md-4  d-flex">
              <p class="small text-left my-auto">Nbr : <?= Dec_0($factures_attente['nbr'], ' u.') ?></p>
            </div>
            <div class="col-md-8 d-flex justify-content-end">
              <h1 class="text-right my-auto"><?= Dec_0($factures_attente['tot'], ' €') ?></h1>
            </div>

            <p><small>
                <?php
                // include $chemin . '/inc/comparatif.php';
                ?>

              </small></p>
          </div>
          <div class="graph-container">
            <?php
            $heures_facturable_annee = $production->getHeures("", $annee);
            $facture_cumul_mois = $facture->askEtatFacturesAttente("", $annee);
            for ($i = 1; $i < 13; $i++) {
              if (strlen($i) == 1) {
                $i = '0' . $i;
              } else {
                $i = $i;
              }
              $facture_attente_mois = $facture->askEtatFacturesAttente($i, $annee);
              $affmois = $facture_attente_mois['tot'] > 0 ? $i : '';
              $heures_facturable_mois['hmo'] = $heures_facturable_mois['hmo'] == '0' ? 1 : $heures_facturable_mois['hmo'];
            ?>
              <div onclick="ajaxData('pass=reglement&moisref=<?= $i ?>&annref=<?= $annee ?>','../src/menus/menu_factures.php','target-one','attente_target')" class="barreg text-center pointer" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="<?= moisLettre($i) . ', ' . Dec_0($facture_attente_mois['tot']) ?> € en attente." style="height: <?= $facture_attente_mois['tot'] / 100  ?>px;"><small class="text-xs"><?= $affmois ?></small></div>
            <?php
            }
            ?>
          </div>
          <p class=" small text-center text-muted mb-2">Répartition impayés en <?= $annee ?></p>
          <div class="row align-items-center mb-4">
            <div class="col-md-7  d-flex">
              <p class="text-left my-auto small">Encaissements <?= $annee ?></p>
            </div>
            <div class="col-md-5  d-flex justify-content-end">
              <h5 class="text-right my-auto"><?= Dec_0($encaissement, ' €') ?></h5>
            </div>
          </div>
          <p class="btn btn-mag-n" onclick="ajaxData('pass=null', '../src/menus/menu_reglements.php', 'target-one', 'attente_target');"><i class='bx bx-link bx-flxxx icon-bar'></i>Pointer vos factures</p>
        </div>
      </div>
      <!-- //********/ -->
      <div class="col-md-6">
        <div class="card card-body mt-4 scroll-s">
          <h5 class="text-right valign"><i class='bx bx-qr bx-lg mr-4 text-success'></i> Production du <?= date('d/m/Y') ?></h5>
          <?php
          $dates = $production->derniersJours(1);
          foreach ($dates as $date) {
            $prodjour = $production->getProdJour($secteur, $date);
            if (!empty($prodjour)) {
          ?>
              <p><b class="small text-primary text-bold"><?= $date ?></b></p>
              <div class="d-flex flex-wrap gap-1">

                <?php
                // pretty($prodjour);


                foreach ($prodjour as $p) {
                  $oui = $p['factok'] == 'oui' ? 'text-muted' : '';
                ?>
                  <p class="small d-inline <?= $oui ?> mt-1"><?= NomCli($p['idcli']) ?>
                  <p class="small puce-mag d-inline" style="margin-right:1px "><?= ($p['tot']) ?></p>
                  </p>
                <?php
                }

                ?>
              </div>
          <?php
            }
          }




          ?>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card card-body mt-4 scroll-s">
          <h5 class="text-right valign"><i class='bx bxs-bank bx-lg mr-4 text-success'></i> Virements reçus / <?= strtolower(moisAbrege(date('m'))) . ' ' . date('Y')  ?> </h5>
        </div>
      </div>
      <script>
        $(function() {
          $('[data-bs-toggle="tooltip"]').tooltip();
        });
      </script>
    <?php
  } else {

    ?>
      <div class="">
        <div class="">
          <p class="text-warning">Pas de données pour <?= $annee ?></p>
          <!-- <p><?=pretty($myenv)?></p> -->
          <p>
            <?php
            //echo $pageref;
            foreach ($_ENV as $k => $v) {
              ${$k} = $v;
              //echo 'Variable $ENV - $' . $k . ' = ' . $v . '</br>';
            }
            ?>

          </p>
        </div>

      </div>


    <?php
  }
    ?>
    </div>