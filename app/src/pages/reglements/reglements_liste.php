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
$term = isset($_POST['term']) ? $_POST['term'] : null;
$annee = isset($_POST['annnee']) ? $_POST['annnee'] : date('Y');
$mois = isset($_POST['mois']) ? $_POST['mois'] : date('m');
foreach ($_POST as $k => $v) {
  ${$k} = $v;
}
// $m=;
// $a=;
//$total_remise = $regl->remiseTotalBordereau('12', '2023');
?>

<div class="row">
  <div class="col-md-3">
    <form id="form_reglements" method="post">
      <p class="titre_menu_item mb-2">Critères de recherche</p>
      <p class="text-bold">Période</p>
      <div class="input-group mb-2">
        <span class="input-group-text l-9" id="basic-addon3">Mois</span>
        <select class="form-control" id="stype" name="mois">
          <!-- <option value="">Mois</option> -->
          <?php
          for ($i = '01'; $i < 13; $i++) {
            if (strlen($i) < 2) {
              $i = '0' . $i;
            } else {
              $i = $i;
            };
            $d = ($i == $mois) ? "selected" : '';
          ?>
            <option value="<?= $i ?>" <?= $d ?>><?= moisLettre($i)  ?></option>
          <?php
          } ?>
          <option value="">Tout les mois</option>
        </select>
        </select>
      </div>
      <div class="input-group mb-2">
        <span class="input-group-text l-9" id="basic-addon3">Année</span>
        <select class="form-control" id="stype" name="annee">
          <!-- <option value="">Année</option> -->
          <?php
          for ($i = date('Y') - 10; $i < date('Y') + 1; $i++) {
            $a = ($i == $annee) ? "selected" : '';
          ?>
            <option value="<?= $i ?>" <?= $a ?>><?= $i  ?></option>
          <?php
          }
          ?>
          <option value="">Toutes</option>
        </select>
      </div>
      <p class="text-bold">Client</p>
      <div class="input-group mb-2">
        <input type="text" class="form-control" id="client" value="" onfocus="eff_form('#nomcli');eff_form('#idcli');eff_form(this);" placeholder="Nom client">
        <input type="hidden" id="idcli" name="idcli" value="">
        <input type="hidden" id="nomcli" name="nomcli" value="">
      </div>
      <p class="text-bold">Mode d'encaissement</p>
      <div class="input-group mb-2">
        <span class="input-group-text l-9" id="basic-addon3">Mode</span>
        <select class="form-control" id="stype" name="type">
          <option value="">Modes</option>
          <option value="CHEQUE">Chèques</option>
          <option value="VIREMENT">Virements</option>
          <option value="CESU">CESU</option>
          <option value="XXX">Extourne</option>

        </select>
      </div>

      <div class="text-right">
        <p>

          <button type="reset" class="btn btn-mag-n"><i class="bx bx-reset icon-bar"></i></button>
          <input name="Envoyer" type="button" class="btn btn-mag-n text-primary" value="Rechercher" onclick="ajaxForm('#form_reglements', '../src/pages/reglements/reglements_liste_affiche.php', 'res', 'attente_target');" />
        </p>
      </div>
    </form>
  </div>
  <div class="col-md-9">
    <p class="titre_menu_item mb-2">Liste des encaissements</p>
    <div id="res"></div>

  </div>
</div>
<script>
  ajaxData('mois=<?= date('m') ?>&annee=<?= date('Y') ?>', '../src/pages/reglements/reglements_liste_affiche.php', 'res', 'attente_target');
  $(function() {
    $("#client").autocomplete({
      minLength: 1,
      source: function(request, response) {
        $.ajax({
          url: "../inc/suggest_clients_factures_liste.php",
          dataType: "json",
          data: {
            term: request.term
          },
          success: function(data) {
            response(data);
          },
        });
      },
      select: function(event, ui) {
        var idcli = ui.item.ncli;
        var nomcli = ui.item.label;
        var index = nomcli.indexOf(' ');
        // Couper la chaîne à l'index de l'espace
        var nom = nomcli.slice(0, index);
        $("#idcli").val(idcli);
        $("#nomcli").val(nom);
        // ajaxData('idcli=' + idcli + '', 'pages/01-contacts/fiche_contact.php', 'resultat', 'attente_resultat');
      },
      close: function(event, ui) {
        $("#inter").val('');
      },
    });
  })
</script>