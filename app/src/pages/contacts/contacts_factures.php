<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
session_start();
$secteur = $_SESSION['idcompte'];
$conn = new connBase();
$reglements = new Reglements($secteur);

foreach ($_POST as $k => $v) {
  ${$k} = $v;
  //echo '$'.$k.'= '.$v.'<br class=""> ';
};
$annref = $annee;
if ($annee) {

  $annee = "and annee = '$annee'";
} else {
  $annee = '';
}
switch ($paye) {
  case 'oui':
    $paye = "and paye='oui'";
    break;
  case 'non':
    $paye = "and paye='non'";
    break;
  default:
    $paye = "";
    break;
}
$reqdevis = "select * from facturesentete where id='$idcli' $paye $annee and cs='$secteur' order by annee desc, mois desc, jour asc ";
$devis = $conn->allRow($reqdevis);
?>
<p class="titre_menu_item">Factures</p>

<ul class="nav justify-content-end">
  <span class="mr-2 " id="optioncsv" style="display:none">
    <li class="nav-item">
      <!-- <a class="btn btn-mag-n mr-1 mt-2 bg-danger" aria-current="page" href="#" onclick="ajaxData('idcli=<?= $idcli ?>&paye=non&annee=', '../src/pages/contacts/contacts_factures.php', 'action', 'attente_target');"><i class='bx bxs-file-pdf text-white'></i> de <span id=""></span> u.</a> -->
      <form action="https://app.enooki.com/src/pages/factures/factures_02_pdf.php" method="POST" id="fd">
        <input type="hidden" name="idcli" value="<?= $idcli ?>">
        <input type="hidden" id="lignes_coche" name="numero" value="">
        <a onclick="handleFormSubmit('#fd')" class="btn btn-mag-n mr-1 mt-2 bg-danger"><i class='bx bxs-file-pdf text-white'></i> de <span id="nbr_coche"></span> u.</a>
      </form>
    </li>
  </span>
  <li class="nav-item">
    <a class="btn btn-mag-n mr-1 mt-2 " aria-current="page" href="#" onclick="ajaxData('idcli=<?= $idcli ?>&paye=non&annee=', '../src/pages/contacts/contacts_factures.php', 'action', 'attente_target');">En attente</a>
  </li>
  <li class="nav-item">
    <a class="btn btn-mag-n mr-1 mt-2 " aria-current="page" href="#" onclick="ajaxData('idcli=<?= $idcli ?>&paye=&annee=', '../src/pages/contacts/contacts_factures.php', 'action', 'attente_target');">Toutes</a>
  </li>
  <?php
  for ($anneeref = date('Y'); $anneeref >= date('Y') - 3; $anneeref--) { ?>
    <li class="nav-item">
      <a class="btn btn-mag-n mr-1 mt-2" aria-current="page" href="#" onclick="ajaxData('idcli=<?= $idcli ?>&paye=&annee=<?= $anneeref ?>', '../src/pages/contacts/contacts_factures.php', 'action', 'attente_target');"><?= $anneeref ?></a>
    </li>
  <?php
  }
  ?>
  <li class="nav-item">
    <a class="btn btn-mag-n ml-2 mt-2" aria-current="page" href="#" onclick="ajaxData('idcli=<?= $idcli ?>&facture=facture&avoir=&source=I', '../src/pages/factures/factures_faire.php', 'action', 'attente_target');">Faire une facture</a>
  </li>

</ul>
<div class="scroll-m">
  <?php
  if ($devis) {
  ?>
    <!-- <span id="coche" class="ml-2">Tout cocher</span> -->
    <table class="table table-hover">
      <thead>
        <tr>
          <td><input class="mr-2" type="checkbox" name="" id="checkmaster"></td>
          <td>Numero</td>
          <td>Date</td>
          <td>Titre</td>
          <td>Commentaire</td>
          <td class="text-center">Payée</td>
          <td class="text-center">Pointage</td>
          <td class="text-right"></td>
          <td class="text-right">Montant</td>
          <td class="text-right">Reste</td>
        </tr>
      </thead>
      <tbody>
        <?php
        //var_dump($devis);
        foreach ($devis as $d) {
          $payee = $d['paye'];
          $p = "";
          if ($payee === 'oui') {
            $p = "<i class='bx bxs-check-circle text-success bx-sm' ></i>";
            $apointer = "";
          }
          if ($payee === 'non') {
            $p = "<i class='bx bxs-time-five text-danger bx-sm' ></i>";
            $apointer = "<i class='bx bxs-bookmark-plus bx-sm text-primary pointer'></i>";
          }
          $element = 'Facture_' . $d['numero'] . '.pdf';
          $f = 'https://app.enooki.com/documents/pdf/factures/' . $secteur . '/' . $element;

          $fs = $chemin . '/documents/pdf/factures/' . $secteur . '/' . $element;

          if (file_exists($fs)) {
            $ex = "Le fichier existe.";
          } else {
            $ex = "Le fichier n'existe pas.";
          }

          $reg = $reglements->askReglementsFact($d['numero'], $idcli);
          $deja_reg = $reg['tot'];
          $attenu = $deja_reg == $d['totttc'] ? 'text-muted small' : 'text-warning small';
        ?>
          <tr>

            <td><input type="checkbox" name="fact_select" class="checkslave" onclick="updateCount();calculateTotal();ligneCoche();" id="fact_<?= $d['numero'] ?>" value="<?= $d['numero'] ?>"></td>
            <td>
              <a href="https://app.enooki.com/src/pages/factures/factures_02_pdf.php?numero=<?= $d['numero'] ?>&idcli=<?= $idcli ?>" target="_blank"><i class='bx bxs-file-pdf text-danger mr1 bx-sm icon-bar pointer'></i></a>
            </td>
            <td class="numero"><?= $d['numero'] ?></td>
            <td><?= $d['jour'] . '/' . $d['mois'] . '/' . $d['annee'] ?></td>
            <td><?= $d['titre'] ?> <span class="small"> <?= $d['commentaire'] ?></span></td>

            <td class="text-center  "><?= $p ?></td>

            <td class="text-center">
              <?php
              if ($payee === 'non') {
              ?>
                <i class='bx bxs-bookmark-plus bx-sm text-primary pointer' onclick="ajaxData('numero=<?= $d['numero'] ?>&idcli=<?= $idcli ?>&annee=<?= $annref ?>&paye=<?= $paye ?>&lockbar=1', '../src/pages/contacts/contacts_pointage.php', 'action', 'attente_target');$('#action').addClass('rel')"></i>
              <?php
              }
              ?>

            </td>
            <td class="text-right <?= $attenu ?>"><?= Dec_2($deja_reg) ?></td>
            <td class="text-right"><?= $pa = Dec_2($d['totttc'], '') ?></td>
            <td class="text-right"><?= Dec_2($pa - $deja_reg) ?></td>
          </tr>
        <?php
        }
        ?>
      </tbody>
      <tfoot>
      </tfoot>
    </table>
  <?php } else {  ?>
    <p class="text-danger text-bold">Aucune facture</p>
  <?php }  ?>
</div>

<script>
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

  function handleFormSubmit(form) {
    var serializedData = $(form).serialize();
    var url = $(form).attr('action') + '?' + serializedData;
    console.log('url =  ' + url);
    window.open(url, '_blank');;

  }
</script>





<?php

/*#region*/
/*#endregion*/

include $chemin . '/inc/foot.php';
?>