<?php
session_start();
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
foreach ($_POST as $k => $v) {
  ${$k} = $v;
  //echo '$' . $k . ' = ' . $v . '</br>';
}
$conn = new connBase();
$facture = $conn->askFactureNum($secteur, $numero);
//var_dump($facture);
$montant = Dec_2($facture['totttc']);
$nom_bdx = strtolower($facture['nom']);
?>
<div class="">
  <p class="pull-right text-warning" onclick="ajaxData('idcli=<?= $idcli ?>&annee=<?= $annee ?>&paye=<?= $paye ?>', '../src/pages/contacts/contacts_factures.php' , 'action' , 'attente_target' );$('#action').removeClass('rel');"><i class='bx bxs-chevron-left bx-flxxx icon-bar text-bold text-white bx-md pointer bx-close'></i></p>
  <div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4 text-center">
      <p class="titre_menu_item mb-2">Pointage règlement</p>
      <p class="text-bold mb-2">Facture N° <?= $numero ?></p>
      <p class="mb-4"><?= $idcli . ' - ' . NomClient($idcli) ?></p>
      <form method="POST" id="form_prod">
        <div class="input-group mb-2">
          <?php
          $cd = $_COOKIE['dateprod'];
          $cw = $_COOKIE['weekprod'];
          if ($cd || $cw) {
            $cd = $cd;
            $cw = $cw;
          } else {
            $cd = date('d/m/Y');
            $cw = date('W');
          }
          ?>
          <span class="input-group-text l-9" id="basic-addon3">Date</span>
          <input type="text" class="form-control text-right" id="dateprod" name="date" value="<?= $cd ?>">
          <input type="hidden" id="semaine" name="semaine" value="<?= $cw ?>">
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text l-9" id="basic-addon3">Mode</span>
          <select class="form-control text-right" id="mode" name="mode">
            <option value="">Mode de règlement</option>
            <?php
            $reqtitre = "select * from reglements where cs='$secteur' group BY mode ";
            $titre = $conn->allRow($reqtitre);
            foreach ($titre as $t) {
            ?>
              <option value="<?= $t['mode']; if($t['mode']==='XXX') {$t['mode'].' extourne';}else{$t['mode'];} ?>"><?= $t['mode']; if($t['mode']=='XXX') {$t['mode'].' extourne';}else{$t['mode'];} ?></option>
            <?php
            }
            ?>
          </select>
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text l-9" id="basic-addon3">Banque</span>
          <select class="form-control text-right" id="idrib" name="idrib">
            <?php
            $reqtitre = "select * from bank where cs='$secteur' ORDER BY defaut DESC";
            $titre = $conn->allRow($reqtitre);
            foreach ($titre as $t) {
            ?>
              <option value="<?= $t['idrib'] ?>"><?= $t['nom_bank'] . ' - ' . $t['idrib'] ?></option>
            <?php
            }
            ?>
          </select>
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text l-9" id="basic-addon3">Bordereau</span>
          <input type="text" class="form-control text-right" id="bdx" name="bdx" value="dirfic_<?= $nom_bdx . '_' . date('dmY') ?>">
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text l-9" id="basic-addon3">Montant</span>
          <input type="text" class="form-control text-right" id="montant" name="montant" value="<?= $montant ?>">
        </div>
        <p class="text-right">
          <button type="reset" class="btn btn-mag-n"><i class="bx bx-reset icon-bar"></i></button>
          <input id="val" name="envoyer" type="button" class="btn btn-mag-n text-primary pull-right" value="Pointer le règlement" onclick="ajaxForm('#form_prod', '../src/pages/contacts/contacts_direct_pointage.php', 'sub-target', 'attente_target');">
          <input id="val" name="envoyer" type="button" class="btn btn-mag-n text-danger mr-1" value="Retour" onclick="ajaxData('idcli=<?= $idcli ?>&annee=<?= $annee ?>&paye=<?= $paye ?>', '../src/pages/contacts/contacts_factures.php' , 'action' , 'attente_target' );$('#action').removeClass('rel');">
          <input name="iduser" id="iduser" type="hidden" value="<?= $iduser ?>">
          <input name="idcompte" id="idcompte" type="hidden" value="<?= $secteur ?>">
          <input name="numcli" id="numcli" type="hidden" value="<?= $idcli ?>">
          <input name="nomcli" id="nomcli" type="hidden" value="<?= $facture['nom'] ?>">
          <input name="numero" id="numero" type="hidden" value="<?= $numero ?>">
        </p>
        <p id="info" class="petit text-warning text-right"></p>
      </form>
    </div>
    <div class="col-md-4"></div>


    <div class="" id="sub-target"></div>


  </div>
</div>

<script>
  $('.bx').tooltip();

  // $('#heures').keydown(function(event) {
  //   // Si la touche appuyée n'est pas numérique, empêche sa propagation
  //   if (!$.isNumeric(event.key) || event.key == '.') {
  //     //event.preventDefault();
  //     $('#info').show();
  //     $('#info').html('Que des chiffres');
  //     $('#val').prop('disabled', true);
  //   } else {
  //     $('#info').hide();
  //     $('#val').prop('disabled', false);
  //   }
  // });

  $('#stype').change(function() {
    var item = $('#stype').val();
    $('#type').val(item);
    if (item == 'X') {
      $('#afftype').show();
      $('#type').val('');
    } else {
      //alert("j'affiche valeur " + item);
      $('#afftype').hide();
    }
  });

  $("#client").autocomplete({
    minLength: 1,
    source: function(request, response) {
      $.ajax({
        url: "../inc/suggest_prod.php",
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
      $("#idcli").val(idcli);
      $("#nomcli").val(nomcli);
      // ajaxData('idcli=' + idcli + '', 'pages/01-contacts/fiche_contact.php', 'resultat', 'attente_resultat');
    },
    close: function(event, ui) {
      $("#inter").val('');
    },
  });

  $("#intervenant").autocomplete({
    minLength: 1,
    source: function(request, response) {
      $.ajax({
        url: "../inc/suggest_idinter.php",
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
      var idinter = ui.item.ncli;
      $("#idinter").val(idinter);
      // ajaxData('idcli=' + idcli + '', 'pages/01-contacts/fiche_contact.php', 'resultat', 'attente_resultat');
    },
    close: function(event, ui) {
      // $("#intervenant").val('');
    },
  });

  $("#dateprod").datepicker({
    firstDay: 1,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    closeText: "Fermer",
    nextText: ">>",
    prevText: "Précédent",
    currentText: "Aujourd'hui",
    // showOn: "both",
    // buttonText: '>',
    // buttonImageOnly: false,
    // altField: "#datshow",
    // showWeek: true,
    // dateFormat: "dd/mm/yy",
    dayNamesMin: ["Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa"],
    monthNames: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre",
      "Octobre", "Novembre", "Décembre"
    ],
    weekHeader: "S",
    dayNames: ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"],
    showWeek: true,
    dateFormat: 'dd/mm/yy',
    altField: "#datesupp2, #datesupp1, #datesupp3, #datesupp4",
    altFormat: "dd/mm/yy",
    onSelect: function(dateText, inst) {
      var date = $(this).datepicker('getDate');
      week = $.datepicker.iso8601Week(date);
      var date = $('#dateprod').val();
      console.log(date)
      $('#semaine').val(week);
      //ajaxData('date=' + date + '', '../src/pages/production/production_liste.php', 'sub-target', 'attente');
      document.cookie = "dateprod=" + date + "; SameSite=none; Secure;";
      document.cookie = "weekprod=" + week + "; SameSite=none; Secure;";


    }
  });


  //ajaxData('idcli=<?= $idcli ?>&annee=<?= $annref ?>&lockbar=1', '../src/pages/contacts/contacts_reglements.php', 'sub-target', 'attente');
</script>
<?php
include $chemin . '/inc/foot.php';
?>