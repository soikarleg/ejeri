<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$conn = new connBase();
session_start();
$secteur = $_SESSION['idcompte'];
foreach ($_POST as $k => $v) {
  ${$k} = $v;
  //echo '$'.$k.'= '.$v.'<br class=""> ';
};
$reqdevis = "select * from devisestimatif where id='$idcli' and cs='$secteur'";
$devis = $conn->allRow($reqdevis);

$client = $conn->askClient($idcli, 'email');
?>
<p class="titre_menu_item">Devis</p>
<ul class="nav justify-content-end">
  <li class="nav-item">
    <span class="btn btn-mag-n pull-right" onclick="ajaxData('idcli=<?= $idcli ?>', '../src/pages/devis/devis_faire.php', 'action', 'attente_target');">Nouveau devis</span>
  </li>
</ul>
<div class="scroll-m">
  <?php
  if (!empty($devis)) {
  ?>
    <table class="table">
      <thead>
        <tr>

          <td>Numero</td>
          <td>Date</td>
          <td>Titre</td>
          <td>Commentaire</td>
          <td>Statut</td>
          <td class="text-right">Montant</td>
          <td class="text-right">Options</td>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($devis as $d) {
          $chemin_doc = 'https://app.enooki.com/documents/pdf/devis/' . $secteur . '/Devis_' . $d['numero'] . '.pdf';
          $chemin_bt = 'https://app.enooki.com/src/pages/devis/devis_bt_pdf.php?numero=' . $d['numero'];


        ?>
          <!--  -->
          <tr>

            <td><?= $d['numero'] ?></td>
            <td><?= $d['jour'] . '/' . $d['mois'] . '/' . $d['annee'] ?></td>
            <td><?= $d['titre'] ?></td>
            <td><?= $d['commentaire'] ?></td>
            <td><?= $d['validite'] ?></td>
            <td class="text-right"><?= Dec_2($d['totttc'], '') ?></td>
            <td class="text-right">
              <i class='bx bxs-hard-hat text-muted mr-1 bx-sm icon-bar pointer' title="Bon de travaux" onclick="getDocPDF('<?= $chemin_bt ?>','_blank')"></i>
              <i class='bx bxs-printer text-danger mr1 bx-sm icon-bar pointer' title="Imprimer" onclick="getDocPDF('<?= $chemin_doc ?>','_blank')"></i>
              <i class='bx bxs-edit text-success mr-1 bx-sm icon-bar pointer' onclick="ajaxData('idcli=<?= $idcli ?>&numero_devis=<?= $d['numero'] ?>', '../src/pages/devis/devis_faire.php', 'action', 'attente_target');" title="Modifier le devis"></i>

              <!-- <?php
                    if ($client['email']) {
                    ?>
                <i class='bx bx-mail-send text-primary mr-1 bx-sm icon-bar pointer' title="Envoi simple du devis sur <?= $client['email'] ?>"></i>
              <?php
                    }
              ?> -->
            </td>
          </tr>
        <?php
        }  ?>
      </tbody>
      <tfoot>
      </tfoot>
    </table>

  <?php
  } else {
  ?>
    <p class="text-bold text-danger mt-2">Aucun devis</p>
  <?php
  }
  ?>
</div>
<script>
  $(function() {
    $('.bx').tooltip();
  });
</script>