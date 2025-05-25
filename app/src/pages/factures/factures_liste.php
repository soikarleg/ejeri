<?php
session_start();
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$secteur = $_SESSION['idcompte'];
$conn = new connBase();
//var_dump($_POST);
$data = "";
$paye = isset($_POST['paye']) ? $_POST['paye'] : null;
if ($paye) {
  if ($paye == 'oui' || $paye == 'non') {
    $r_paye = "AND paye like '$paye' ";
    $data .= $r_paye;
  } else {
    $j = date('d');
    $m = date('m');
    $y = date('Y');
    $r_paye = "AND paye='non' AND dateche<='$y-$m-$j' ";
    $data .= $r_paye;
  }
} else {
  $r_paye = "";
  $data .= $r_paye;
}
$nomcli = isset($_POST['nomcli']) ? $_POST['nomcli'] : null;
if ($nomcli) {
  $r_nomcli = "AND nom like '$nomcli%' ";
  $data .= $r_nomcli;
} else {
  $r_nomcli = "";
  $data .= $r_nomcli;
}
$idcli = isset($_POST['idcli']) || $_POST['idcli'] === "" ? $_POST['idcli'] : null;
if ($idcli) {
  $r_idcli = "AND id like '$idcli' ";
  $data .= $r_idcli;
} else {
  $r_idcli = "";
  $data .= $r_idcli;
}
$numero = isset($_POST['numero']) ? $_POST['numero'] : null;
if ($numero) {
  $r_numero = "AND numero like '%$numero%' ";
  $data .= $r_numero;
} else {
  $r_numero = "";
  $data .= $r_numero;
}
$annref = isset($_POST['annref']) ? $_POST['annref'] : null;
if ($annref) {
  $r_annref = "AND annee like '$annref' ";
  $data .= $r_annref;
} else {
  $r_annref = "";
  $data .= $r_annref;
}
$moisref = isset($_POST['moisref']) || $_POST['moisref'] === "" ? $_POST['moisref'] : null;
if ($moisref) {
  $r_moisref = "AND mois like '$moisref' ";
  $data .= $r_moisref;
} else {
  $r_moisref = "";
  $data .= $r_moisref;
}
$term = isset($_POST['term']) ? $_POST['term'] : null;
if ($term) {
  $r_term = "AND (
    nom LIKE '%$term%'
    OR numero LIKE '%$term%'
    OR totttc LIKE '%$term%'
    OR datefact LIKE '%$term%'
  ) ";
  $data .= $r_term;
} else {
  $r_term = "";
  $data .= $r_term;
}
?>

<div>
  <span id="affcsv" class="pull-right"> <span class="" id="optioncsv" style="display:none">
      <span class="puce-btn pointer text-muted" onclick="ajaxForm('#datacsv','../src/pages/factures/factures_export_csv.php','affcsv','attente_target')">Export comptable <img src=" ../../../assets/img/svg/bxs-file-txt.svg" alt=".csv" width="14px"></span>
      <span class="puce-btn text-muted  pointer" onclick="handleFormSubmit('#datacsv');">Impression factures <img src=" ../../../assets/img/svg/pdf_2.svg" alt=".pdf" width="12px"></span>
      <span class="puce-btn text-muted  pointer" onclick="handleFormSubmit('#datacsv','liste');">Impression liste <img src=" ../../../assets/img/svg/pdf_2.svg" alt=".pdf" width="12px"></span>
    </span></span>
</div>
<form action="https://app.enooki.com/src/pages/factures/factures_02_pdf.php" method="POST" id="datacsv">
  <input type="hidden" id="idcli" name="idcli" value="">
  <input type="hidden" id="lignes_coche" name="numero" class="form-control" value="">
</form>
<p class="titre_menu_item mb-2">Liste des factures</p>
<p class="pull-right puce-titre">Sélection : <span id="nbr_coche" class="ml-2">0</span> facture(s) d'un montant de <span id="total" class="ml-1">0.00</span> euros</p>
<p class="m-1 "><input type="checkbox" name="" id="checkmaster">
  <span id="coche" class="ml-2 mr-1">Tout cocher</span>
  <!-- <span class="mr-2 " id="optioncsv" style="display:none">
    <span class="pointer text-success py-0 px-2  mr-1" onclick="ajaxForm('#datacsv','../src/pages/factures/factures_export_csv.php','affcsv','attente_target')">Export compta .csv</span>
    <span class="text-danger py-0 px-2 pointer" onclick="handleFormSubmit('#datacsv');">Impression <img src=" ../../../assets/img/svg/pdf_2.svg" alt=".pdf" width="17px"></span>
  </span> -->
</p>
<div class="scroll">
  <table class="table100 table-hover" id="factures_tab">
    <thead>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
    </thead>
    <tbody>
      <?php
      // $factures = $conn->askAllFacture($secteur, $term);
      $reqfacturesn = "select SUM(totttc) as mont from facturesentete where cs='$secteur' $data order by datefact desc";
      $mont = $conn->oneRow($reqfacturesn);
      $reqfactures = "select * from facturesentete where cs='$secteur' $data order by datefact desc";
      $factures = $conn->allRow($reqfactures);
      $nbrfact = count($factures);
      if ($factures != null) {
        foreach ($factures as $f) {
          $numero = $f['numero'];
          $id = $f['id'];
          $reqreglements = "select SUM(montant) as reglee from reglements where cs='$secteur' and factref like '$numero' group by factref";
          $reglee = $conn->oneRow($reqreglements);
          $r = $reglee['reglee'] != false ? $reglee['reglee'] : false;
          $commercial = $f['commercial'] == "" or $f['commercial'] == null ? "" : $f['commercial'];
          if ($f['paye'] == 'oui') {
            $payee = "<i class='bx bxs-check-circle bx-flxxx icon-bar text-success'></i>";
            $indic = '';
            $mail = "";
          } elseif ($f['paye'] == 'non') {
            $payee = "<i class='bx bxs-error-circle bx-flxxx icon-bar text-danger'></i>";
            $indic = '';
            $mail = "<i class='bx bx-mail-send bx-flxxx icon-bar text-primary pointer' onclick=\"$('#rel$id').fadeIn('fast', 'linear');\"></i>";
          }
          if ($r > 0 && $f['paye'] == 'non') {
            $payee = "<i class='bx bxs-check-circle bx-flxxx icon-bar text-warning'></i>";
            $indic = 'Déjà payé : ' . Dec_2($r);
          }

      ?>
          <div id="rel<?= $f['id'] ?>" class="rel" style="display:none">
            <i class='bx bx-x pull-right' onclick="$('#rel<?= $f['id'] ?>').fadeOut('fast', 'linear');"></i>
            <p><?= NomClient($f['id']) ?></p>
          </div>
          <tr>

            <td><input type="checkbox" name="" class="checkslave" onclick="updateCount();calculateTotal();ligneCoche();"></td>
            <td>
              <a target="_blank" href="../src/pages/factures/factures_02_pdf.php?numero=<?= $f['numero'] ?>&idcli=<?= $f['id'] ?>"><img src="../../../assets/img/svg/pdf_2.svg" alt=".pdf" width="18px"></a>
            </td>
            <td>N° <span class="numero"><?= $f['numero'] ?></span> - <?= $f['titre'] ?><span></span></br><span class="small text-muted"><?= ' du ' . AffDate($f['datefact']) . ' échéance au ' . AffDate($f['dateche'])  ?></span></td>
            <td><span class="pointer mr-1"><i onclick="ajaxData('idcli=<?= $f['id'] ?>','../src/pages/contacts/contacts_fiche.php','target-one','attente_target')" class='bx bx-link bx-flxxx icon-bar text-primary' data-bs-placement="left" data-bs-toggle="tooltip" data-bs-title="Voir dossier <?= $f['id'] ?>"></i></span><span class="pointer"><?= NomClient($f['id']) ?></span><br><span class="text-muted small"><?= $indic ?></span></td>
            <td></td>
            <?php
            ?>
            <td><?= $payee ?></td>
            <td class="text-right tot"><?= Dec_2($f['totttc'], ' €') ?></td>
            <td><?= $mail ?></td>
          </tr>

        <?php
        }
      } else {
        ?>
        <tr>
          <td class="text-danger text-bold">Aucune facture pour cette sélection</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      <?php
      }
      ?>
    </tbody>
  </table>
</div>
<div class="border-dot mt-2 mb-2">
  <span class="mr-2" id="optioncsv" style="display:none">
    <span class="btn btn-warning text-dark py-0 px-2 mb-1 mr-1">csv</span><span class="btn btn-danger text-dark py-0 px-2 mb-1">pdf</span>
  </span>
  <span>Montant total : <?= Dec_2($mont['mont'], ' €TTC') ?> - <?= $nbrfact ?> factures</span>
</div>
<script>
  function handleFormSubmit(form, plus = "") {
    var serializedData = $(form).serialize();
    var url = $(form).attr('action') + '?' + serializedData + '&liste=' + plus;
    console.log('url =  ' + url);
    window.open(url, '_blank');;

  }
  $(document).ready(function() {
    var selectAll = $("#checkmaster");
    var checkboxes = $(".checkslave");
    var nbrCoche = $("#nbr_coche");
    var optionCSV = $('#optioncsv');
    console.log(checkboxes);

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
      checkboxes.filter(':checked').each(function() {
        ligneCochee += $(this).closest('tr').find('.numero').text() + "_";
      });
      ligneCochee = ligneCochee.slice(0, -1);
      $('#lignes_coche').val(ligneCochee);
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
<script>
  $(function() {
    $('[data-bs-toggle="tooltip"]').tooltip();
  });
</script>