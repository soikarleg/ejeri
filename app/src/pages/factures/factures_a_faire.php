<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$conn = new connBase();
session_start();
$secteur = $_SESSION['idcompte'];
// Ancienne prod
$reqprod = "select *,SUM(quant) as afact from production  where idcompte='$secteur' and factok='non' and codeprod='MO' group by idcli ";
// $prod = $conn->allRow($reqprod);

// Prod avec devis validé
// $reqprod = "SELECT p.*, SUM(p.quant) AS afact, d.*
// FROM production p
// LEFT JOIN devisestimatif d ON p.idcompte = d.cs
// WHERE p.idcompte = '$secteur' AND p.factok = 'non' AND p.codeprod = 'MO' AND d.validite like 'Vali%'
// GROUP BY p.idcli";
$prod = $conn->allRow($reqprod);

$nbrprod = count($prod);
$reqtotal = "select SUM(quant) as heures_totale from production where idcompte='$secteur' and factok='non' and codeprod='MO' group by idcompte";
$total = $conn->oneRow($reqtotal);


$infos_idcompte = $conn->askIdcompte($secteur, 'tr');
foreach ($infos_idcompte as $key => $value) {
  ${$key} = $value;
}
?>
<p class="titre_menu_item">Facturation des productions</p>
<div class="scroll">
  <div class="row">
    <?php
    foreach ($prod as $p) {
      $nomcli = $p['nomcli'];
      if (strpos($nomcli, '---') === false or strpos($nomcli, '***') === false) {
    ?>
        <div class="col-md-3 mt-2 mb-2">
          <div class="border-dot">
            <p class="pull-right pointer text-primary" onclick="ajaxData('idcli=<?= $p['idcli'] ?>&avoir=&facture=&source=I', '../src/pages/contacts/contacts_fiche.php', 'target-one', 'attente_target');">Voir le dossier <i class='bx bx-link-external'></i> </p>
            <p class="text-bold mb-2 pointer" onclick="ajaxData('idcli=<?= $p['idcli'] ?>&avoir=&facture=&source=I', '../src/pages/factures/factures_faire.php', 'action', 'attente_target');"> <?= (NomClient($p['idcli'])) ?></p>
            <p class="bg-pale-o puce pull-right l-5 text-center small">N° <?= $idinter = $p['idcli'] ?></p>
            <p class="text-bold puce l-5 text-center"> <?= Dec_2($p['afact'], ' hrs') ?></p>
          </div>
        </div>
    <?php
      }
    }
    ?>
  </div>
</div>
<div class="border-dot mt-2 mb-2">

  <span>Heures non facturées : <?= Dec_2($total['heures_totale'], ' hrs') ?> - (<?= $nbrprod ?> clients)</span>

  <span class="pull-right text-bold">Facturation potentielle : <?= Dec_2($total['heures_totale'] * $tr, ' €'); ?></span>


</div>
<?php
include '../../../inc/foot.php';
?>
<script src="../../../assets/js/script.js?=<?= time(); ?>"></script>