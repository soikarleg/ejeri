<?php
session_start();

use Delight\Auth\Auth;

$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
$conn = new connBase();
$bridge = new Bridge();

$annref = isset($_POST['annref']) ? $_POST['annref'] : date('Y');

// $annref = date('Y');
$username = ucfirst($_POST['user'] . '.');
//Contacts
$reqcontacts = "SELECT
COUNT(DISTINCT client_chantier.idcli) AS total_clients,
COUNT(DISTINCT CASE WHEN facturesentete.id IS NOT NULL THEN client_chantier.idcli END) AS clients_fact
FROM
client_chantier
LEFT JOIN
facturesentete  ON client_chantier.idcli = facturesentete.id
WHERE
client_chantier.idcompte = '$secteur'
/* AND facturesentete.annee = '$annref'  */
AND client_chantier.datecrea LIKE '%$annref%'
";
$contacts = $conn->oneRow($reqcontacts);
//var_dump($contacts);
// Toutes factures
$reqfacturesall = "SELECT COUNT(id) AS tot FROM facturesentete WHERE cs='$secteur' AND paye ='oui' AND annee LIKE '%$annref%'  ";
$nbrfact = $conn->oneRow($reqfacturesall);
// Production
// $reqheuresAI = "SELECT
// SUM(CASE WHEN codeprod IN ('MO', 'NF') THEN quant ELSE 0 END) AS heures,
// SUM(CASE WHEN codeprod = 'MO' THEN quant ELSE 0 END) AS heures_mo,
// SUM(CASE WHEN codeprod = 'NF' THEN quant ELSE 0 END) AS heures_nf
// FROM
// production
// WHERE
// idcompte = '$secteur'
// AND annee = '$annref'";
$reqheuresAI = "
SELECT
    SUM(CASE WHEN codeprod IN ('MO', 'NF') THEN quant ELSE 0 END) AS heures,
    SUM(CASE WHEN codeprod = 'MO' THEN quant ELSE 0 END) AS heures_mo,
    SUM(CASE WHEN codeprod = 'NF' THEN quant ELSE 0 END) AS heures_nf
FROM
    production
WHERE
    idcompte = '$secteur'
    AND annee = '$annref'
    AND codeprod IN ('MO', 'NF')
";

$heures = $conn->oneRow($reqheuresAI);
// Factures
// $reqfact = "SELECT
// SUM(CASE WHEN paye IN ('oui', 'non') THEN totttc ELSE 0 END) AS fact,
// SUM(CASE WHEN paye = 'oui' THEN totttc ELSE 0 END) AS fact_ok,
// SUM(CASE WHEN paye = 'non' THEN totttc ELSE 0 END) AS fact_at,
// (
//         SELECT COUNT(*)
//         FROM facturesentete
//         WHERE cs = '$secteur' AND annee = '$annref' AND paye IN ('oui', 'non')
//     ) AS fact_count,
//     (
//         SELECT COUNT(*)
//         FROM facturesentete
//         WHERE cs = '$secteur' AND annee = '$annref' AND paye = 'oui'
//     ) AS fact_ok_count,
//     (
//         SELECT COUNT(*)
//         FROM facturesentete
//         WHERE cs = '$secteur' AND annee = '$annref' AND paye = 'non'
//     ) AS fact_at_count
// FROM
// facturesentete
// WHERE
// cs = '$secteur'
// AND annee = '$annref'";
$reqfact = "
SELECT
    SUM(CASE WHEN paye IN ('oui', 'non') THEN totttc ELSE 0 END) AS fact,
    SUM(CASE WHEN paye = 'oui' THEN totttc ELSE 0 END) AS fact_ok,
    SUM(CASE WHEN paye = 'non' THEN totttc ELSE 0 END) AS fact_at,
    COUNT(*) AS fact_count,
    SUM(CASE WHEN paye = 'oui' THEN 1 ELSE 0 END) AS fact_ok_count,
    SUM(CASE WHEN paye = 'non' THEN 1 ELSE 0 END) AS fact_at_count
FROM
    facturesentete
WHERE
    cs = '$secteur'
    AND annee = '$annref'
    AND paye IN ('oui', 'non')
";

$factures = $conn->oneRow($reqfact);
// Règlements
$reqreg = "SELECT
*,
SUM(montant) AS mont
FROM
reglements
WHERE
cs = '$secteur'
AND annee = '$annref'";
$reglements = $conn->oneRow($reqreg);

$reqintervenant = "select * from users_infos where idcompte='$secteur' and actif='1'";
$intervenant = $conn->allRow($reqintervenant);
$nbrintervenant = count($intervenant)
?>
<script src="../../assets/js/echarts.js"></script>
<script src="../../src/graph/graph_production_home.js"></script>
<script src="../../src/graph/graph_facture_home.js"></script>
<script src="../../src/graph/graph_reglement_home.js"></script>

<p class="puce-mag pull-right">
  <span class="mr-2"><i class='bx bx-history bx-flxxx icon-bar text-primary'></i></span>
  <?php
  for ($i = date('Y') - 4; $i < date('Y') + 1; $i++) {
  ?>
    <span class="pointer mr-2" onclick="ajaxData('erreur=<?= $erreur ?>&user=<?= $user ?>&annref=<?= $i ?>', '../src/menus/synthese.php', 'target-one', 'attente_target');"><?= $i ?></span>
  <?php
  }
  ?>

</p>
<p class="titre_menu_item">Synthèse de l'activité <?= $annref ?></p>
<!--<p class="text-muted"><?= $show_user = $conn->showUser($iduser); ?></p>
 <p class="px-0 py-2"><span class="puce-mag mr-1">N° 61</span><span class="text-muted mr-1">M.</span><span class="text-muted mr-1">Jean</span><span class="text-muted">Bon</span></p> -->
<?php
echo $data_verifie = verifData($secteur, $username);

?>
<div class="col-md-12">
  <?php


  ?>
</div>
<?php



if (!$data_verifie) {
?>
  <div class="row">
    <?php
    $nbr_fact = ($nbrfact['tot'] == 0) ? 1 : $nbrfact['tot'];
    ?>
    <div class="col-md-4 bg-bilan">
      <p class="titre_menu_item mb-2">Clients</p>
      <p class="pull-right"><?= ($contacts['total_clients']) ?> u.</p>
      <p class="text-bold">Nouveau contacts <?= $annref ?> </p>
      <p class="pull-right"><?= $nbrfact['tot'] ?> u.</p>
      <p class="text-bold">Nombre factures clients payées</p>
      <p class="pull-right"><?= Dec_0(($factures['fact']) / $nbr_fact) ?> €/u.</p>
      <p class="text-bold">Panier moyen </p>
    </div>
    <div class="col-md-4 bg-bilan">
      <p class="titre_menu_item mb-2">Intervenants</p>
      <p class="pull-right"><?= ($nbrintervenant) ?> pers.</p>
      <p class="text-bold">Intervenants actifs </p>

      <?php
      $m = (date('m') == 01) ? 2 : date('m');
      $month = (date('m') == 01) ? 2 : date('m');
      $compare_annee =  strcmp($annref, date('Y'));
      if ($compare_annee === -1) {
        $month = 12;
      }
      ?>
      <p class="pull-right"><?= $etp = Dec_2(($heures['heures'] / $month) / 134) ?> pers.</p>
      <p class="text-bold">Equivalent Temps Plein (ETP)</p>
      <?php
      $nbrintervenant = ($nbrintervenant == 0) ? 0.001 : $nbrintervenant;
      $facture = ($factures['fact'] == 0) ? 0.001 : $factures['fact'];

      ?>
      <p class="pull-right"><?= Dec_0(($facture / 1.2) / $nbrintervenant / ($m - 1)) ?> €/pers.</p>
      <p class="text-bold">Facturation HT moyenne par inter.</p>
    </div>

    <div class="col-md-4 bg-bilan">
      <?php
      $rappels = $conn->askNotesSecteur($secteur);
      $notification = empty($rappels) ? "" : '<span class="pointer small" onclick="ajaxData(\'secteur=' . $secteur . '\', \'../src/menus/menu_notes.php\', \'target-one\', \'attente_target\');">Voir les rappels <i class="bx bxs-star icon-bar text-warning" ></i></span>';
      ?>
      <p class="pull-right"><?= $notification ?></p>
      <p class="titre_menu_item mb-2">Rappels</p>
      <div class="scroll-xs">
        <?php
        if ($rappels) {
          foreach ($rappels as $value) {
            if ($value['rappel'] != "0000-00-00 00:00:00") {

        ?>
              <p class=""><?= AffDate($value['rappel']) . ' ' . NomClient($value['idcli']) ?></p>

            <?php
            }
            ?>

          <?php
          }
        } else {
          ?>


          <p>Pas de rappel</p>
          <p class="pointer" onclick="ajaxData('secteur=<?= $secteur ?>' , '../src/menus/menu_notes.php', 'target-one', 'attente_target');">Définir un rappel</p>
        <?php
        }
        ?>
      </div>
    </div>


    <div class="col-md-4 bg-bilan">
      <p class="titre_menu_item mb-2">Productions
        <span class=" pull-right text-bold"><?= Dec_0($heures['heures']) ?></span>
      </p>
      <p class="pull-right"><?= $hmo = Dec_0($heures['heures_mo']) ?></p>
      <p class="text-bold">Heures facturées </p>
      <p class="pull-right"><?= $hnf = Dec_0($heures['heures_nf']) ?></p>
      <p class="text-bold">Heures non facturables </p>
      <p class="pull-right"><?= Dec_0($heures['heures']) ?></p>
      <p class="text-bold">Heures totales </p>
      <?php
      $nbr_hrs = ($heures['heures'] == 0) ? 1 : $heures['heures'];
      ?>
      <p class="pull-right"><?= $pourth = Dec_0($heures['heures_nf'] * 100 / $nbr_hrs) ?></p>
      <p class="text-bold">% HNF </p>
      <div class="center-graph">
        <div id="production_home" style="width: 250px;height:250px;"></div>
      </div>
      <?php
      $colhnf = ($pourth > 18) ? "#dc3545" : "#3a9d23";
      ?>
      <script type="text/javascript">
        $(function() {
          productionHome('production_home', <?= "'" . $colhnf . "'" ?>, <?= "'" . Dec_2($heures['heures_mo']) . "'" ?>,
            <?= "'" . Dec_2($heures['heures_nf']) . "'" ?>);
        });
      </script>
      <div class="center-graph">
        <p class="btn btn-mag-n" onclick="ajaxData('cs=cs', '../src/menus/menu_productions.php', 'target-one', 'attente_target');"><i class='bx bx-qr bx-flxxx icon-bar'></i>Saisir une
          production</p>
      </div>
    </div>
    <div class="col-md-4 bg-bilan">
      <p class="titre_menu_item mb-2">Facturations HT<span class=" pull-right text-bold"><?= Dec_0($factures['fact'] / 1.2) ?></span></p>
      <p class="pull-right text-muted"><?= Dec_0($heures['heures_mo'] * 48 / 1.2) ?></p>
      <p class="text-bold text-muted">Potentiel/heures en €HT </p>
      <p class="pull-right text-muted"><?= Dec_0($factures['fact'] / 1.2 - $heures['heures_mo'] * 48 / 1.2) ?></p>
      <p class="text-bold text-muted">Ecarts en €HT</p>
      <p class="pull-right"><?= Dec_0($factures['fact_count']) ?></p>
      <p class="text-bold">Nombre de factures </p>
      <p class="pull-right"><?= Dec_0($factures['fact_at_count']) ?></p>
      <p class="text-bold">Nombre attente </p>
      <div class="center-graph">
        <div id="facture_home" style="width: 250px;height:250px;"></div>
      </div>
      <?php
      $colfact = ($factures['fact_at_count'] > 50) ? "#dc3545" : "#3a9d23";
      ?>
      <script type="text/javascript">
        $(function() {
          factureHome('facture_home', <?= "'" . $colfact . "'" ?>, <?= "'" . Dec_2($factures['fact_ok_count']) . "'" ?>,
            <?= "'" . Dec_2($factures['fact_at_count']) . "'" ?>);
        });
      </script>
      <div class="center-graph">
        <p class="btn btn-mag-n" onclick="ajaxData('cs=cs', '../src/menus/menu_factures.php', 'target-one', 'attente_target');"><i class='bx bxs-file-export bx-flxxx icon-bar'></i>
          Etablir une facture</p>
      </div>
    </div>
    <?php
    $fact_fact = ($factures['fact'] == 0) ? 1 : $factures['fact'];
    ?>
    <div class="col-md-4 bg-bilan">
      <p class="titre_menu_item mb-2">Règlements TTC<span class=" pull-right text-bold"><?= Dec_0($reglements['mont']) ?></span></p>
      <p class="pull-right "><?= Dec_0($factures['fact']) ?></p>
      <p class="text-bold">Total attendu en €</p>
      <p class="pull-right "><?= Dec_0($reglements['mont']) ?></p>
      <p class="text-bold">Règlements pointés en €</p>
      <p class="pull-right "><?= $att = Dec_0($factures['fact'] - $reglements['mont']) ?></p>
      <p class="text-bold">En attente, non pointé</p>
      <p class="pull-right "><?= Dec_0(($factures['fact'] - $reglements['mont']) * 100 / $fact_fact) ?></p>
      <p class="text-bold">% attente</p>
      <div class="center-graph">
        <div id="reglement_home" style="width: 250px;height:250px;"></div>
      </div>
      <?php
      $colfact = ($factures['fact_at'] > 5000) ? "#dc3545" : "#3a9d23";
      ?>
      <script type="text/javascript">
        $(function() {
          reglementHome('reglement_home', <?= "'" . $colfact . "'" ?>, <?= "'" . Dec_2($reglements['mont']) . "'" ?>,
            <?= "'" . Dec_2($factures['fact'] - $reglements['mont']) . "'" ?>);
        });
      </script>
      <div class="center-graph">
        <p class="btn btn-mag-n" onclick="ajaxData('cs=cs', '../src/menus/menu_reglements.php', 'target-one', 'attente_target');"><i class='bx bxs-file-import bx-flxxx icon-bar'></i> Pointer un règlement
        </p>
      </div>
    </div>

    <div class="col-md-12">
      <?php
      $chemin = $_SERVER['DOCUMENT_ROOT'];
      $base = include $chemin . '/config/.base_ionos.php';
      pretty($base);
      ?>
    </div>
  </div>
<?php
}
?>
</div>