<?php
session_start();
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$secteur = $_SESSION['idcompte'];
$conn = new connBase();

//var_dump($_POST);

$paye = isset($_POST['paye']) ? $_POST['paye'] : null;
if ($paye) {
  $r_paye = "AND paye like '$paye'";
} else {
  $r_paye = "";
}

$annref = isset($_POST['annref']) ? $_POST['annref'] : null;
if ($annref) {
  $r_annref = "AND annee like '$annref'";
} else {
  $r_annref = "";
}

$moisref = isset($_POST['moisref']) ? $_POST['moisref'] : null;
if ($moisref) {
  $r_moisref = "AND mois like '$moisref'";
} else {
  $r_moisref = "";
}

$term = isset($_POST['term']) ? $_POST['term'] : null;
if (!empty($term) || isset($term)) {
  $transterm = $term;
  $term = "
  AND (
    nom LIKE '%$term%'
    OR numero LIKE '%$term%'
    OR totttc LIKE '%$term%'
    OR datefact LIKE '%$term%'  
  )
  $r_paye
  $r_moisref 
  $r_annref
  ";
} else {
  $transterm = "";
  $term = "";
}
?>
<p class="titre_menu_item mb-2">Liste des factures <span class="small text-muted"><?= $transterm . ' ' . $paye . ' ' . $annref . ' ' . $moisref ?></span></p>
<ul class="nav justify-content-end">
  <select class="btn btn-mag-n mr-1 l-160" id="paye">
    <option value="" class="text-bold">Facture payée</option>

    <option value="oui" onclick="ajaxData('term=<?= $transterm ?>&moisref=<?= $moisref ?>&annref=<?= $annref ?>&paye=oui','../src/pages/factures/factures_liste.php','action','attente_target')" <?php $selected = $paye === 'oui' ? "selected" : "";  ?><?= $selected ?>>Oui</option>
    <option value="non" onclick="ajaxData('term=<?= $transterm ?>&moisref=<?= $moisref ?>&annref=<?= $annref ?>&paye=non','../src/pages/factures/factures_liste.php','action','attente_target')" <?php $selected = $paye === 'non' ? "selected" : "";  ?><?= $selected ?>>Non</option>
    <option onclick="ajaxData('term=<?= $transterm ?>&moisref=<?= $moisref ?>&annref=<?= $annref ?>','../src/pages/factures/factures_liste.php','action','attente_target')">Oui & Non</option>
  </select>
  <select class="btn btn-mag-n mr-1 l-160" id="mois">
    <option>Mois</option>
    <?php
    for ($i = '01'; $i < 13; $i++) {
      if (strlen($i) < 2) {
        $i = '0' . $i;
      } else {
        $i = $i;
      };
      // if ($moisref) {
      //   $moisref = $moisref;
      // } else {
      //   $moisref = date('m');
      // }
      $selected = $moisref != $i ? "" : "selected";
    ?>
      <option value="<?= $i ?>" onclick="ajaxData('term=<?= $transterm ?>&moisref=<?= $i ?>&annref=<?= $annref ?>&paye=<?= $paye ?>','../src/pages/factures/factures_liste.php','action','attente_target')" <?= $selected ?>><?= $i ?></option>

    <?php
    }
    ?>
    <option value="<?= $i ?>" onclick="ajaxData('term=<?= $transterm ?>&annref=<?= $annref ?>&paye=<?= $paye ?>','../src/pages/factures/factures_liste.php','action','attente_target')" <?= $selected ?>>Tous les mois</option>
  </select>
  <select class="btn btn-mag-n mr-1 l-160" id="annee">
    <option>Année</option>
    <?php
    for ($i = date('Y') - 5; $i < date('Y') + 1; $i++) {
      // if ($moisref) {
      //   $moisref = $moisref;
      // } else {
      //   $moisref = date('m');
      // }
      $selected = $annref != $i ? "" : "selected";
    ?>
      <option value="<?= $i ?>" onclick="ajaxData('term=<?= $transterm ?>&moisref=<?= $moisref ?>&annref=<?= $i ?>&paye=<?= $paye ?>','../src/pages/factures/factures_liste.php','action','attente_target')" <?= $selected ?>><?= $i ?></option>

    <?php
    }
    ?>
    <option value="<?= $i ?>" onclick="ajaxData('term=<?= $transterm ?>&moisref=<?= $moisref ?>&paye=<?= $paye ?>','../src/pages/factures/factures_liste.php','action','attente_target')" <?= $selected ?>>Toutes les années</option>
  </select>
</ul>
<input type="text" id="lignes_coche" class="" value="">
<div class="scroll">

  <p class="m-2">Sélction : <span id="nbr_coche" class="ml-2">0</span> facture(s) d'un montant de <span id="total" class="ml-1">0.00</span> euros</p>
  <p class="m-2 "><input type="checkbox" name="" id="checkmaster">
    <span id="coche" class="ml-2 mr-1">Tout cocher</span>
    <span class="btn btn-mag-n ml-2" style="display:none">imp</span>
  </p>

  <script>
    $(document).ready(function() {
      var $selectAll = $("#checkmaster");
      var $checkboxes = $(".checkslave");
      var $nbrCoche = $("#nbr_coche");

      function updateCount() {
        var count = $checkboxes.filter(':checked').length;

        $nbrCoche.html(count);
      }

      function calculateTotal() {
        var total = 0;
        $checkboxes.filter(':checked').each(function() {
          total += parseFloat($(this).closest('tr').find('.tot').text());
        });

        $('#total').html(total.toFixed(2));

      }

      function ligneCoche() {
        var ligneCochee = "";
        $checkboxes.filter(':checked').each(function() {
          ligneCochee += $(this).closest('tr').find('.numero').text() + "_";
        });
        ligneCochee = ligneCochee.slice(0, -1);
        $('#lignes_coche').val(ligneCochee);
      }
      $selectAll.change(function() {
        $checkboxes.prop('checked', $selectAll.prop('checked'));
        updateCount();
        if ($selectAll.prop('checked')) {
          $('#coche').html('Tout décocher');

        } else {
          $('#coche').html('Tout cocher');

        }
        calculateTotal();
        ligneCoche();
      });
      $checkboxes.change(function() {
        $selectAll.prop('checked', $checkboxes.length === $checkboxes.filter(':checked').length);
        updateCount();
        calculateTotal();
        ligneCoche();
      });
    });
  </script>
  <table class="table table-hover" id="factures_tab">
    <thead>
      <tr>
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
      $reqfactures = "select * from facturesentete where cs='$secteur' $term order by datefact desc";
      $factures = $conn->allRow($reqfactures);
      $nbrfact = count($factures);

      if ($factures != null) {
        foreach ($factures as $f) {
          $commercial = $f['commercial'] == "" or $f['commercial'] == null ? "" : $f['commercial'];


      ?>

          <tr>
            <td><input type="checkbox" name="" class="checkslave" onclick="updateCount();calculateTotal();ligneCoche();"></td>
            <td>
              <i class='bx bxs-file-pdf bx-sm text-danger icon-bar pointer' onclick="ajaxData('numero=<?= $f['numero'] ?>&idcli=<?= $f['id'] ?>', '../src/pages/factures/factures_enregistre.php' , 'action' , 'attente_target' );"></i>
            </td>
            <td>N° <span class="numero"><?= $f['numero'] ?></span><span class="small text-muted"><?= ' du ' . AffDate($f['datefact']) ?></span></td>
            <td><?= NomClient($f['id']) ?></td>
            <td><?= $f['titre'] ?></td>
            <?php
            $payee = $f['paye'] === 'oui' ? "<i class='bx bxs-check-circle bx-flxxx icon-bar text-success'></i>" : "<i class='bx bxs-error-circle bx-flxxx icon-bar text-danger'></i>";
            ?>
            <td><?= $payee ?></td>
            <td class="text-right tot"><?= Dec_2($f['totttc']) ?></td>
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
  <span>Montant total : <?= Dec_2(0.00, ' €TTC') ?> - <?= $nbrfact ?> factures</span>
</div>