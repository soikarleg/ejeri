<?php
session_start();
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
foreach ($_POST as $k => $v) {
  ${$k} = $v;
  // echo '$' . $k . ' = ' . $v . '</br>';

}
$conn = new connBase();
$prod = new Production($secteur);
if ($mod === "ok") {
  $upprod = "update production set dettvx = '$text' where numero='$numero' limit 1";
  $conn->handleRow($upprod);
  $err = "1";
?>
  <script>
    pushSuccess('Modification', 'Mise à jour réussie');
  </script>
<?php
}
if ($efface === "ok") {
  $delprod = "delete from production where numero='$numero' limit 1";
  $conn->handleRow($delprod);
  $conn->insertLog('Effacement production', $iduser, 'N° ' . $numero);
  $err = "1";
  $delinter = "delete from INTERVENTION where numero='$numero' limit 1";
  $conn->handleRow($delinter);
  $err = "1";
?>
  <script>
    pushWarning('Effacement', 'Effacement réussie');
  </script>
<?php
}
if ($date === "" || $idcli === "" || $idinter === "" || $titre === "" || $travaux === "" || $quant === "") {
  $err = "1";
?>
  <p class="text-danger">Heures vides</p>
  <script>
    pushError('Erreur', 'Ne pas laisser de champs vide.</br>Merci.');
  </script>
  <?php
} else {
  $err = null;
}
if ($err === null and $validation === "ok") {
  $reqtitre = "select titre from devistitres where cs='$secteur' and titre='$travaux'";
  $titre = $conn->oneRow($reqtitre);
  if ($titre === false) {
    $institre = "INSERT INTO devistitres(cs, titre, type) VALUES ('$secteur','$travaux','$codeprod')";
    $conn->handleRow($institre);
    $conn->insertLog('Nouveau titre', $iduser, 'Nouveau : ' . $travaux . ' ' . $codeprod);
    $err = "1";
  ?>
    <script>
      pushSuccess('Modification', 'Insertion d\'un nouveau titre');
    </script>
  <?php
  }
  $adaptdate = explode('/', $date);
  $datebd = $adaptdate[2] . '-' . $adaptdate[1] . '-' . $adaptdate[0];
  $jour = $adaptdate[0];
  $mois = $adaptdate[1];
  $annee = $adaptdate[2];
  if (strlen($semaine) == 1) {
    $semaine = '0' . $semaine;
  } else {
    $semaine = $semaine;
  }
  if ($codeprod === 'NF') {
    $monhnf = '---' . $nomcli;
    $reqidcli = "select numero from CLIENT where CS='$secteur' and nom like '%---%' limit 1";
    $cli = $conn->oneRow($reqidcli);
    $idcli = $cli['numero'];
  } else {
    $monhnf = $nomcli;
    $idcli = $idcli;
  }
  $travaux = addslashes($travaux);
  $dettvx = addslashes($dettvx);
  $monhnf = addslashes($monhnf);
  $nomcli = addslashes($nomcli);

  $reqprod = "INSERT INTO INTERVENTION(id, nom, CS, datetvx, jour, mois, annee, sem, travaux, dettvx, tot, cout, factok, idinter) VALUES ('$idcli','$monhnf','$secteur','$datebd','$jour','$mois','$annee','$semaine','$travaux','$dettvx','$quant','0.00','$factok','$idinter')";
  $nbr =  $conn->handleRow($reqprod);
  $insprod = "INSERT INTO production (numero,datetvx, sem, idcli, nomcli, idinter, travaux, dettvx, quant,  codeprod, factok,idcompte,jour,mois,annee)VALUES ('$nbr','$datebd','$semaine','$idcli', '$nomcli','$idinter', '$travaux', '$dettvx','$quant','$codeprod', '$factok','$secteur','$jour','$mois','$annee')";
  $conn->handleRow($insprod);
  $conn->insertLog('Insertion production', $iduser, $codeprod . ' ' . $nomcli . ' ' . $quant);
  //LogA('Ajout production', $idconn, 'Production ' . $nomcli . ' ' . ($travaux) . ' ' . $quant);
  $err = "1";
  ?>
  <script>
    var push = pushSuccess('Notation', 'Insertion effectuée');
    console.log(push);
  </script>
<?php
}
$adaptdate = explode('/', $date);
$datebd = $adaptdate[2] . '-' . $adaptdate[1] . '-' . $adaptdate[0];
$reqprod = "select * from production where idcompte='$secteur' and datetvx='$datebd' and codeprod='MO' and nomcli not like '---%' ";
$production = $conn->allRow($reqprod);
$reqtotprod = "select SUM(quant) as tot from production where idcompte='$secteur' and codeprod='MO' and datetvx='$datebd' and nomcli not like '---%' ";
$totprod = $conn->oneRow($reqtotprod);
$reqhnf = "select * from production where idcompte='$secteur' and codeprod='NF' and datetvx='$datebd' or idcompte='$secteur' and nomcli like '---%' and datetvx='$datebd'";
$hnf = $conn->allRow($reqhnf);
$reqtothnf = "select SUM(quant) as tot  from production where idcompte='$secteur' and codeprod='NF' and datetvx='$datebd' or idcompte='$secteur' and nomcli like '---%' and datetvx='$datebd'";
$tothnf = $conn->oneRow($reqtothnf);
?>
<div class="">
  <?php
  if ($totprod['tot'] !== null || $tothnf['tot'] !== null) {
    $tprod = $totprod['tot'];
    $tp = Dec_2($totprod['tot'], ' hrs');
    $thnf = $tothnf['tot'];
    $th = Dec_2($tothnf['tot'], ' hrs');
    $tpth = $tprod + $thnf;
    $tph = Dec_2($tpth, ' hrs');
    $tpth = $tpth == 0 ? 0.0001 : $tpth;
    $thnf = $thnf == 0 ? 0.0001 : $thnf;
    $pourth = $thnf * 100 / $tpth;
    $pth = Dec_2($pourth, ' %');
    $thor = $prod->getTauxHoraire();
  ?>
    <div class="row">
      <p class="titre_menu_item mb-2">Journée du <?= $date ?></p>
      <div class="col-md-6">

        <div class="border-dot mb-2">
          <p>Heures facturables <span class="pull-right"><?= $tp ?></span> </p>
          <p>Heures non facturable <span class="pull-right"><?= $th ?></span> </p>
          <p class="text-bold">Total <span class="text-bold pull-right"><?= $tph ?></span> </p>
          <p class="text-bold">CA potentiel <span class="text-bold pull-right"><?= Dec_2($tprod * $thor, ' €TTC') ?></span> </p>
          <?php
          $chnf = ($pourth > 15) ? "text-danger text-bold" : "text-success text-bold";
          ?>
          <p class="<?= $chnf ?>">Pourcentage HNF <span class="<?= $chnf ?> pull-right"><?= $pth ?></span> </p>
        </div>

      </div>
      <div class="col-md-6">
        <?php
        $colhnf = ($pourth > 15) ? "#dc3545" : "#3a9d23";
        ?>
        <script src="https://app.enooki.com/assets/js/echarts.js"></script>
        <script src="https://app.enooki.com/src/graph/graph_production_jour.js?=<?= time(); ?>"></script>

        <div class="border-dot" style="height:155px">Répartition <span id="production_jour" class="pull-right" style="width: 150px;height: 150px;top:-18px!important"></span></div>

        <script type="text/javascript">
          $(function() {
            productionJour('production_jour', <?= $thnf ?>, <?= $tprod ?>, '<?= $colhnf ?>');
          });
        </script>
      </div>
    </div>
    <!-- <p class="titre_menu_item mb-2">Détails</p> -->
    <div class="">
      <div class="row">
        <div class="col-md-6">
          <p class="text-bold text-left mb-2">Heures facturables</p>
          <div class="border-dot-infos p-4">
            <div class="">
              <?php
              foreach ($production as $t) {
                $idcli = $t['idcli'];
                $numero = $t['numero'];
                $reqclient = "select * from client_chantier where idcli = '$idcli' ";
                $client = $conn->oneRow($reqclient);
                $client['prenom'];
                $fok = ($t['factok'] === "oui") ? "<i class='bx bxs-check-circle icon-bar text-success'></i>" : "<i class='bx bxs-error-circle icon-bar icon_red'></i>";
                $trait = ($t['factok'] === "oui") ? ";border-left: 6px solid #198754;margin-left: 5px" : ";border-left: 6px solid #dc3545;margin-left: 5px";
                $eff = ($t['factok'] === "non") ? "<i id=\"eff\" title=\"Effacer la production\" class='bx bx-x-circle icon-bar text-danger' onclick=\"ajaxData('efface=ok&numero=$numero&date=$date','../src/pages/production/production_liste.php','sub-target','attente_target');\"></i>" : "";
              ?>
                <p class="pointer" onmouseover="Montre<?= $t['numero'] ?>('#des<?= $t['numero'] ?>');" onmouseout="Cache<?= $t['numero'] ?>('#des<?= $t['numero'] ?>');">
                  <?= $eff ?><?= $fok ?>
                  <?= $t['nomcli']  ?>
                  <span class="small text-muted text-bold ml-2"><i class='bx bxs-right-arrow-square icon-bar'></i><?= NomConn($t['idinter']) ?></span>

                  <span class="pull-right"><?= Dec_2($t['quant'], ' hrs')  ?></span>
                </p>
                <div id="des<?= $t['numero'] ?>" class="p-4 mb-2 tip" style="display:none <?= $trait ?>">
                  <!-- <p class="text-primary text-bold"><?= $t['nomcli'] . ' ' . $client['prenom'] ?></p> -->
                  <p class="text-bold"><?= $t['travaux'] ?></p>
                  <p class="pull-right"><?= Dec_2($t['quant'], ' hrs') ?></p>
                  <p class=""><?= $t['dettvx'] ?></p>
                  <p class=""><?= $t['numfact'] ?></p>
                </div>
                <script>
                  // $(function() {
                  function Montre<?= $t['numero'] ?>(fen) {
                    $(fen).show('fade', 10);
                  }

                  function Cache<?= $t['numero'] ?>(fen) {
                    $(fen).hide('fade', 10);
                  }
                  // });
                </script>
              <?php
              }
              ?>

            </div>
          </div>
          <p class="border-dot text-bold mt-1">Total <span class="text-bold pull-right"><?= Dec_2($tprod, ' hrs')  ?></span></p>
        </div>
        <div class="col-md-6">
          <p class="text-bold text-left text-danger mb-2">Heures non facturable</p>
          <div class="border-dot-infos p-4">
            <div class="">
              <?php
              foreach ($hnf as $t) {
                $idcli = $t['idcli'];
                $numeronf = $t['numero'];
                $reqclient = "select * from client_chantier where idcli = '$idcli' ";
                $client = $conn->oneRow($reqclient);
                $client['prenom'];
                $fok = ($t['factok'] === "oui") ? "<i class='bx bxs-check-circle icon-bar text-success'></i>" : "<i class='bx bxs-error-circle icon-bar icon_red'></i>";
                $trait = ($t['factok'] === "oui") ? ";border-left: 6px solid #198754;margin-left: 5px" : ";border-left: 6px solid #dc3545;margin-left: 5px";
                $eff = ($t['factok'] === "oui") ? "<i id=\"eff\" title=\"Effacer la production\" class='bx bx-x-circle icon-bar text-danger' onclick=\"ajaxData('efface=ok&numero=$numeronf&date=$date','../src/pages/production/production_liste.php','sub-target','attente_target');\"></i>" : "";
              ?>
                <p class="pointer" onmouseover="Montre<?= $t['numero'] ?>('#des<?= $t['numero'] ?>');" onmouseout="Cache<?= $t['numero'] ?>('#des<?= $t['numero'] ?>');">
                  <?= $eff ?><?= $fok ?>
                  <?= $t['travaux']  ?>
                  <span class="small text-muted text-bold ml-2"><i class='bx bxs-right-arrow-square icon-bar'></i><?= NomConn($t['idinter']) ?></span>

                  <span class="pull-right"><?= Dec_2($t['quant'], ' hrs')  ?></span>
                </p>
                <div id="des<?= $t['numero'] ?>" class="p-4 mb-2 tip" style="display:none <?= $trait ?>; ">
                  <!-- <p class="text-primary text-bold"><?= $t['nomcli'] . ' ' . $client['prenom'] ?></p> -->
                  <p class="text-bold"><?= $t['travaux'] ?></p>
                  <p class="pull-right"><?= Dec_2($t['quant'], ' hrs') ?></p>
                  <p class=""><?= $t['dettvx'] ?></p>
                  <p class=""><?= $t['numfact'] ?></p>
                </div>
                <script>
                  // $(function() {
                  function Montre<?= $t['numero'] ?>(fen) {
                    $(fen).show('fade', 10);
                  }

                  function Cache<?= $t['numero'] ?>(fen) {
                    $(fen).hide('fade', 10);
                  }
                  // });
                </script>
              <?php
              }
              ?>
            </div>
          </div>
          <p class="border-dot text-bold text-danger mt-1">Total <span class="text-bold text-danger pull-right"><?= Dec_2($thnf, ' hrs')  ?></span></p>
        </div>
      </div>

    </div>
  <?php
  } else {
  ?>
    <div class="col-md12">
      <p class="titre_menu_item text-bold mb-2">Aucune données pour le <?= $date ?></p>
    </div>
  <?php
  }
  ?>
</div>
<script>
  $(function() {
    $('.bx,#eff').tooltip();
  });
</script>