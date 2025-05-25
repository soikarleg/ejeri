<?php
session_start();
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$secteur = $_SESSION['idcompte'];
foreach ($_POST as $k => $v) {
  ${$k} = $v;
  //echo '$' . $k . ' = ' . $v . '</br>';
}
$conn = new connBase();
?>
<div class="">
  <p class="pull-right text-warning" onclick="ajaxData('cs=cs', '../src/pages/production/production_bilan.php' , 'action' , 'attente_target' );$('#action').removeClass('rel');"><i class='bx bxs-chevron-left bx-flxxx icon-bar text-bold text-white bx-md pointer bx-close'></i></p>
  <div class="row">
    <div class="col-md-3">
      <p class="titre_menu_item mb-2">Heures facturables</p>
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
          <input type="text" class="form-control" id="dateprod" name="date" value="<?= $cd ?>">
          <input type="hidden" id="semaine" name="semaine" value="<?= $cw ?>">
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text l-9" id="basic-addon3">Client </span>
          <input type="text" class="form-control" id="client" value="" onfocus="eff_form('#idcli');eff_form(this);">
          <input type="hidden" id="idcli" name="idcli" value="">
          <input type="hidden" id="nomcli" name="nomcli" value="">
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text l-9" id="basic-addon3">Intervenant </span>
          <input type="text" class="form-control" id="intervenant" value="" onfocus="eff_form('#idinter');eff_form(this);">
          <input type="hidden" id="idinter" name="idinter" value="">
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text l-9" id="basic-addon3">Titre</span>
          <select class="form-control" id="stype">
            <option value="">Titre</option>
            <?php
            $reqtitre = "select * from devistitres where cs='$secteur' and type='MO'and type not like ' '  order by titre asc";
            $titre = $conn->allRow($reqtitre);
            foreach ($titre as $t) {
            ?>
              <option value="<?= $t['titre'] ?>"><?= $t['titre'] ?></option>
            <?php
            }
            ?>
            <option value="X" class="text-primary">Nouveau type</option>
          </select>
        </div>
        <div class="input-group mb-2" style="display:none" id="afftype">
          <span class="input-group-text l-9 text-primary" id="basic-addon3">Nouveau <i class="bx bxs-info-circle ml-2 text-primary" title="Soyez succint... un ou deux mots."></i> </span>
          <input type="text" class="form-control" value=" " id="type" name="travaux">
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text l-9" id="basic-addon3">Description </span>
          <textarea class="form-control" name="dettvx" id="travaux" cols="30" rows="3" spellcheck="false"></textarea>
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text l-9" id="basic-addon3">Heures <i class="bx bxs-info-circle ml-2 text-primary" title="En 100 ème d'heure - 1.25 pour 1h15'"></i> </span>
          <input type="text" class="form-control " id="heures" name="quant" value="" onfocus="eff_form(this);">
        </div>
        <p class="text-right">
          <button type="reset" class="btn btn-mag-n"><i class="bx bx-reset icon-bar"></i></button>
          <input id="val" name="envoyer" type="button" class="btn btn-mag-n text-primary" value="Noter" onclick="ajaxForm('#form_prod', '../src/pages/production/production_liste.php', 'sub-target', 'attente_target');">
          <input name="validation" id="validation" type="hidden" value="ok">
          <input name="codeprod" id="codeprod" type="hidden" value="MO">
          <input name="factok" id="factok" type="hidden" value="non">
        </p>
        <p id="info" class="petit text-warning text-right"></p>
      </form>
    </div>
    <div class="col-md-9">
      <div class="" id="sub-target"></div>

    </div>
  </div>
</div>

<script>
  // $(function() {

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
      ajaxData('date=' + date + '', '../src/pages/production/production_liste.php', 'sub-target', 'attente');
      document.cookie = "dateprod=" + date + "; SameSite=none; Secure;";
      document.cookie = "weekprod=" + week + "; SameSite=none; Secure;";


    }
  });
  // });
  ajaxData('date=<?= $cd ?>', '../src/pages/production/production_liste.php', 'sub-target', 'attente');
</script>
<?php
include $chemin . '/inc/foot.php';
?>