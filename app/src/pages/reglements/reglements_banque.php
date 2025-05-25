<?php

namespace IbanApi;

use connBase;

// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
session_start();
$secteur = $_SESSION['idcompte'];
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/vendor/autoload.php';
include $chemin . '/inc/function.php';
$conn = new connBase();
$ib = new Api("ea988e7179b7695c5763b311474affffc5b40510");
$banks = $conn->askBank($secteur);

pretty($_POST);
foreach ($_POST as $key => $value) {
  ${$key} = $value;
}
//$idrib = $_POST['defaut'];
if ($action === "ajouter") {

  $verifiban = $ib->validateIBAN($iban);

  var_dump($verifiban);
  echo 'on ajoute ' . $nom_bank;
}
if ($action === "efface") {
  echo 'on efface ' . $idrib;
}
if ($action === "defaut") {
  echo 'defaut ' . $idrib;
}
?>
<!-- //@todo Ki -->
<p class="titre_menu_item mb-2">Compte bancaire </p>
<div class="text-right"><span id="toggleRIB" class="btn btn-mag-n "><i class='bx bxs-plus-circle bx-flxxx icon-bar'></i></span></div>
<div class="row">
  <div class="col-md-12" id="ribetat">
    <p class="text-bold mb-2">Compte enregistré</p>
    <?php
    foreach ($banks as $v) {
      $defaut = "<i class='bx bx-star' title='' ></i>";
      if ($v['defaut'] === '1') {
        $defaut = "<i class='bx bxs-star text-warning' title='Compte préféré'></i>";
      }
    ?>
      <div class="border-dot mb-2">
        <span><i class='bx bxs-x-square bx-flxxx text-danger pull-right pointer' title="Effacer le compte" onclick="ajaxData('idcompte=<?= $secteur ?>&idrib=<?= $v['idrib'] ?>&action=efface', '../src/pages/reglements/reglements_banque.php', 'action', 'attente_target');"></i></span>
        <p class="text-bold pointer" onclick="ajaxData('idcompte=<?= $secteur ?>&idrib=<?= $v['idrib'] ?>&action=defaut', '../src/pages/reglements/reglements_banque.php', 'action', 'attente_target');"><?= $v['nom_bank'] ?> <?= $defaut ?></p>
        <p>IBAN <?= $v['iban'] ?> - BIC <?= $v['bic'] ?></p>
        <p class="puce pull-right small">N° <?= $v['idrib'] ?></p>
        <p>RIB <?= $v['ets'] . ' ' . $v['guichet'] . ' ' . $v['compte'] . ' ' . $v['clef'] ?></p>
      </div>
    <?php
    }
    ?>
  </div>
  <div class="col-md-12" id="ribajout" style="display: none;">
    <div class="col-md-4">
      <p class="text-bold mb-2">Ajouter un compte</p>
      <span id="feniban">
        <form action="" id="formiban">
          <div class=" input-group mb-2">
            <span class="input-group-text l-9">Nom banque</span>
            <input type="text" aria-label="First name" class="form-control" name="nom_bank">
          </div>
          <div class=" input-group mb-2">
            <span class="input-group-text l-9">Titulaire</span>
            <input type="text" aria-label="First name" class="form-control" name="titulaire" value="<?= NomSecteur($secteur) ?>" readonly>
          </div>
          <div class=" input-group mb-2">
            <span class="input-group-text l-9">IBAN</span>
            <input type="text" aria-label="First name" class="form-control" name="iban">
          </div>
          <div class=" input-group mb-2">
            <span class="input-group-text l-9">BIC</span>
            <input type="text" aria-label="First name" class="form-control" name="bic">
          </div>
          <div class="text-right">
            <input type="hidden" name="action" value="ajouter">
            <button type="reset" class="btn btn-mag-n"><i class="bx bx-reset icon-bar"></i></button>
            <input name="Envoyer" type="button" class="btn btn-mag-n text-primary" value="Ajouter compte" onclick="ajaxForm('#formiban', '../src/pages/reglements/reglements_banque.php', 'action', 'attente_target');" />
          </div>
        </form>
        <script>
          function openIban() {
            $('#feniban').toggle('slide', 300);
          }
        </script>
      </span>
    </div>
    <div class="col-md-8"></div>
  </div>
</div>
<script>
  function handleRIB() {
    const ribetat = $("#ribetat");
    const ribajout = $("#ribajout");
    let ribtext = $('#toggleRIB');
    if (ribetat.is(":visible")) {
      ribetat.hide();
      ribajout.show();
      ribtext.html("<i class='bx bx-list-ol bx-flxxx icon-bar'></i>");
    } else {
      ribetat.show();
      ribajout.hide();
      ribtext.html("<i class='bx bxs-plus-circle bx-flxxx icon-bar'></i>");
    }
  }
  // handleRIB();
  $("#toggleRIB").click(handleRIB);
  $(function() {
    $('.bx').tooltip();
  });
</script>