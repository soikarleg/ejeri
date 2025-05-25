<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
session_start();
$secteur = $_SESSION['idcompte'];
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$conn = new connBase();
$remises = new Factures($secteur);
$regl = new Reglements($secteur);
$remise = Factures::askRemises($secteur);
foreach ($_POST as $k => $v) {
  ${$k} = $v;
}
$data = "";
$type = isset($_POST['type']) ? $_POST['type'] : null;
if ($type) {
  $r_type = "AND mode like '$type' ";
  $data .= $r_type;
} else {
  $r_type = "";
  $data .= $r_type;
}
$idcli = isset($_POST['idcli']) || $_POST['idcli'] === "" ? $_POST['idcli'] : null;
if ($idcli) {
  $r_idcli = "AND id like '$idcli' ";
  $data .= $r_idcli;
} else {
  $r_idcli = "";
  $data .= $r_idcli;
}
$annee = isset($_POST['annee']) ? $_POST['annee'] : null;
if ($annee) {
  $r_annee = "AND annee like '$annee' ";
  $data .= $r_annee;
} else {
  $r_annee = "";
  $data .= $r_annee;
}
$mois = isset($_POST['mois']) || $_POST['mois'] === "" ? $_POST['mois'] : null;
if ($mois) {
  $r_mois = "AND mois like '$mois' ";
  $data .= $r_mois;
} else {
  $r_mois = "";
  $data .= $r_mois;
}
$data_send = $data;
$total_remise = $regl->remiseTotalBordereau($data);
?>

<div>
  <span id="affcsv" class="pull-right"></span>
</div>
<form id="datacsv">
  <input type="hidden" id="lignes_coche" name="datacsv" class="form-control" value="ii">
</form>

<p class="pull-right puce-titre">Sélection : <span id="nbr_coche" class="ml-2">0</span> bordereau(x) d'un montant de <span id="total" class="ml-1">0.00</span> euros</p>
<p class="m-1 "><input type="checkbox" name="" id="checkmaster">
  <span id="coche" class="ml-2 mr-1">Tout cocher</span>
  <span class="mr-2 " id="optioncsv" style="display:none">
    <span class="pointer text-success py-0 px-2  mr-1" onclick="ajaxForm('#datacsv','../src/pages/factures/factures_export_csv.php','affcsv','attente_target')">Export .csv</span>
    <span class="text-danger py-0 px-2 pointer">Impression <img src="../../../assets/img/svg/pdf_2.svg" alt=".pdf" width="17px"></span>
  </span>
</p>





<div class="scroll">
  <?php
  if ($idcli != "") {
    $remises_unitaire = $regl->remiseBordereauUnitaire($data);
    $infos_cli = $conn->askClient($idcli);
  ?>
    <p>Remise de <?= $infos_cli['prenom'] . ' ' . $infos_cli['nom'] ?></p>
    <table class="table100 table-hover">
      <?php
      foreach ($remises_unitaire as $ru) {
        //var_dump($ru);
        switch ($ru['mode']) {
          case 'CHEQUE':
            $puce_bg = "bg-primary";
            break;
          case 'CESU':
            $puce_bg = "bg-warning";
            break;
          case 'VIREMENT':
            $puce_bg = "bg-success";
            break;
          case 'XXX ':
            $puce_bg = "bg-danger";
            break;
          default:
            $puce_bg = "";
            break;
        }
      ?>
        <tr>
          <td>
            <input type="checkbox" name="" class="checkslave_bdx" onclick="updateCount();calculateTotal();ligneCoche();">
            <input type="hidden" name="nom_client" id="clientcsv" value="<?= $infos_cli['idcli'] ?>">
          </td>
          <td><?= $ru['jour'] . '/' . $ru['mois'] . '/' . $ru['annee'] ?></td>
          <td><?= $ru['factref'] ?></td>
          <td><?= $ru['mode'] ?></td>
          <td><span class="puce <?= $puce_bg ?> numero"><?= $ru['bordereau'] ?></span> / <span class="puce-mag"><?= $ru['bank'] ?></span>
          </td>
          <td class="text-right tot"><?= Dec_2($ru['montant']) ?></td>
        </tr>
      <?php
      }
      ?>
    </table>
  <?php
  } else {
    $remises = $regl->remiseBordereau($data);
  ?>
    <table class="table100">
      <?php
      foreach ($remises as $r) {
        switch ($r['mode']) {
          case 'CHEQUE':
            $puce_bg = "bg-primary";
            break;
          case 'CESU':
            $puce_bg = "bg-warning";
            break;
          case 'VIREMENT':
            $puce_bg = "bg-success";
            break;
          case 'XXX ':
            $puce_bg = "bg-danger";
            break;
          default:
            $puce_bg = "";
            break;
        }
        $bordereau = $r['bordereau'];
        $clients = $regl->remiseClientBordereau($bordereau);

      ?>
        <tr>

          <td valign="top"><input type="checkbox" name="" class="checkslave_bdx" onclick="updateCount();calculateTotal();ligneCoche();">

          </td>
          <td valign="top" style="width:450px">
            <div class="border-dot py-2 px-3" style="height:110px">
              <p class="pull-right"><a href="../src/pages/reglements/reglements_bordereau_02_pdf.php?numbdx=<?= $r['bordereau'] ?>" target="_blank"><i class='bx bxs-file-pdf bx-flxxx icon-bar text-danger pointer'></i></a></p><span class=" text-bold"><?= AffDate($r['time_maj']) ?></span> - <span class="small"><?= $regl->getNomBank($r['bank']) ?></span><br><span class="puce <?= $puce_bg ?>"><?= $r['mode'] ?></span> - <span class="puce-mag numero"><?= $r['bordereau'] ?></span><br><span class="small"><?= NomColla($r['commercial']) ?></span>
              <p class="pull-right tot"><?= Dec_2($r['total'], ' € ') ?></p>
            </div>
          </td>
          <td valign="top">
            <div class=" py-2 px-3">
              <!-- <p class="pull-right"><i class='bx bxs-file-pdf bx-flxxx icon-bar text-danger pointer'></i></p> -->
              <p class="small text-bold">Factures réglées :
              </p>
              <?php
              foreach ($clients as $c) {
                $partiel = $c['partiel'] == 'oui' ? "<i class='bx bxs-circle-three-quarter bx-flxxx icon-bar text-warning' ></i>" : "<i class='bx bxs-circle bx-flxxx icon-bar text-success' ></i>"
              ?>
                <p class="text-muted small"><?= NomClient($c['id']) . ', facture <b class="small">' . $c['factref'] . '</b> de ' . Dec_2($c['montant'], ' €') . ' ' . $partiel ?></p>
              <?php
              }
              ?>

            </div>
          </td>

        </tr>

      <?php
      }
      ?>
    </table>
  <?php
  }
  ?>
</div>
<div class="border-dot mt-4">
  <p>Total des remises <?= Dec_2($total_remise, ' €') ?></p>
</div>
<script>
  $(document).ready(function() {
    var selectAll = $("#checkmaster");
    var checkboxes = $(".checkslave_bdx");
    var nbrCoche = $("#nbr_coche");
    var optionCSV = $('#optioncsv');
    var clientCSV = $('#clientcsv').val();
    console.log('Client ' + clientCSV);

    function updateCount() {
      var count = checkboxes.filter(':checked').length;
      console.log(count);
      nbrCoche.html(count);
      if (count > 0) {
        optionCSV.show('fade', 500);
      } else {
        optionCSV.hide('fade', 500);
      }
    }

    function calculateTotal() {
      var total = 0;
      checkboxes.filter(':checked').each(function() {
        total += parseFloat($(this).closest('tr').find('.tot').text());
      });
      $('#total').html(total.toFixed(2));
    }

    function ligneCoche() {
      var ligneCochee = "";
      if (clientCSV) {
        ligneCochee += '0!' + clientCSV + '!';
      } else {
        ligneCochee += '1!';
      };
      var count = checkboxes.filter(':checked').length;
      console.log('Compte ligne +' + count);
      checkboxes.filter(':checked').each(function() {
        ligneCochee += $(this).closest('tr').find('.numero').text() + "!";
      });
      ligneCochee = ligneCochee.slice(0, -1);
      $('#lignes_coche').val(ligneCochee);
      if (count == 0) {
        $('#lignes_coche').val('');
      }
    }
    selectAll.change(function() {
      checkboxes.prop('checked', selectAll.prop('checked'));
      updateCount();
      if (selectAll.prop('checked')) {
        $('#coche').html('Tout décocher');
        $('#optioncsv').show();
      } else {
        $('#coche').html('Tout cocher');
        $('#optioncsv').hide();
      }
      calculateTotal();
      ligneCoche();
    });
    checkboxes.change(function() {
      selectAll.prop('checked', checkboxes.length === checkboxes.filter(':checked').length);
      updateCount();
      calculateTotal();
      ligneCoche();
    });
  });
</script>