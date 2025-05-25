<?php
session_start();
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$secteur = $_SESSION['idcompte'];
$conn = new connBase();
$term = isset($_POST['term']) ? $_POST['term'] : null;
$annref = isset($_POST['annref']) ? $_POST['annref'] : date('Y');
$moisref = isset($_POST['moisref']) ? $_POST['moisref'] : date('m');
if (!empty($term) || isset($term)) {
  $transterm = $term;
  $term = "
  AND (
    nom LIKE '%$term%'
    OR numero LIKE '%$term%'
    OR factav LIKE '%$term%'
    OR totttc LIKE '%$term%'
   )
  ";
} else {
  $transterm = "";
  $term = "";
}
//var_dump($_POST) . '<br>';
//var_dump($_GET) . '<br>';

?>
<div class="col-md-12">
  <p class="pull-right text-warning" onclick="ajaxData('cs=cs', '../src/pages/devis/devis_garde.php', 'action', 'attente_target');$('#action').removeClass('rel');"><i class='bx bxs-chevron-left bx-flxxx icon-bar text-bold text-white bx-md pointer bx-close'></i></p>
  <div class="row">
    <div class="col-md-3">
      <p class="titre_menu_item mb-2">Critères de recherche <span class="small text-muted"></span></p>
      <p class="text-bold">Période</p>
      <!--
      <p><span class="small text-muted"><?= ' ' . $term . ' ' . $annref . ' ' . $moisref ?></span></p>-->
      <form id="form_recherche">
        <div class="input-group mb-2">
          <span class="input-group-text l-9" id="basic-addon1">Mois</span>
          <select class="form-control" name="moisref">
            <!-- <option value="">Mois</option> -->
            <?php
            for ($i = '01'; $i < 13; $i++) {
              if (strlen($i) < 2) {
                $i = '0' . $i;
              } else {
                $i = $i;
              };
              $d = ($i == $moisref) ? "selected" : '';
            ?>
              <option value="<?= $i ?>" <?= $d ?>><?= moisLettre($i)  ?></option>
            <?php
            } ?>
            <option value="">Tout les mois</option>
          </select>
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text l-9" id="basic-addon1">Année</span>
          <select class="form-control" name="annref">
            <!-- <option value="">Année</option> -->
            <?php
            for ($i = date('Y') - 10; $i < date('Y') + 1; $i++) {
              $a = ($i == $annref) ? "selected" : '';
            ?>
              <option value="<?= $i ?>" <?= $a ?>><?= $i  ?></option>
            <?php
            }
            ?>
            <option value="">Toutes</option>
          </select>
        </div>

        <div class="input-group mb-2">
          <span class="input-group-text l-9" id="basic-addon1">Client</span>
          <input type="text" class="form-control" id="client" value="" onfocus="eff_form('#nomcli');eff_form('#idcli');eff_form(this);" placeholder="Nom client">
          <input type="hidden" id="idcli" name="idcli" value="">
          <input type="hidden" id="nomcli" name="nomcli" value="">
        </div>


        <div class="input-group mb-2">
          <span class="input-group-text l-9" id="basic-addon1">Devis</span>
          <input type="text" class="form-control" name="numero" placeholder="Numéro de devis" aria-describedby="basic-addon1">
        </div>
        <p class="text-bold">Statut du devis</p>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="statut_devis" id="inlineRadio1" value="" checked>
          <label class="form-check-label pointer" for="inlineRadio1">Tous</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="statut_devis" id="inlineRadio2" value="Validé">
          <label class="form-check-label pointer" for="inlineRadio2">Validé</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="statut_devis" id="inlineRadio3" value="Refus">
          <label class="form-check-label pointer" for="inlineRadio3">Refusé</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="statut_devis" id="inlineRadio4" value="En attente">
          <label class="form-check-label pointer" for="inlineRadio4">En attente</label>
        </div>

        <div class="text-right">
          <p>

            <button type="reset" class="btn btn-mag-n"><i class="bx bx-reset icon-bar"></i></button>
            <input name="Envoyer" type="button" class="btn btn-mag-n text-primary" value="Rechercher" onclick="ajaxForm('#form_recherche', '../src/pages/devis/devis_liste.php', 'recherche', 'attente_target');" />
          </p>
        </div>
      </form>

    </div>
    <div class="col-md-9">
      <div class="" id="recherche"></div>
    </div>
  </div>
</div>
<script>
  ajaxForm('#form_recherche', '../src/pages/devis/devis_liste.php', 'recherche', 'attente_target');


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