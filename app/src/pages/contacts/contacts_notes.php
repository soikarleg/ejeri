<?php
session_start();
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
//include $chemin . '/myclass/flxxx/src/Base.php';
include $chemin . '/inc/function.php';
$conn = new connBase();
$secteur = $_SESSION['idcompte'];
foreach ($_POST as $k => $v) {
  ${$k} = addslashes($v);
  // echo '$' . $k . ' = ' . $v . '</br>';
}


$client_notes = $conn->askNotes($idcli);
?>


<div class="row">
  <p class="titre_menu_item mb-2">Notes & Rappels</p>
  <div class="col-md-3">


    <form action="" id="note"><input type="hidden" name="ok" value="ok">
      <input type="hidden" name="idcli" value="<?= $idcli ?>">
      <div class="input-group mb-3">

        <span class="input-group-text l-5" id="basic-addon3">Note</span>
        <textarea class="form-control" id="note" name="note" rows="3"></textarea>
      </div>

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


        <span class="input-group-text l-5" id="basic-addon3"><input class="mb-1" type="checkbox" name="rappel" value="rappel"></span>
        <input type="text" class="form-control" id="dateprod" name="date" value="<?= $cd ?>">
        <input type="hidden" id="semaine" name="semaine" value="<?= $cw ?>">
        <input type="hidden" id="timestamp" name="timestamp" value="">
      </div>

      <div class="text-right">
        <p>
          <button type="reset" class="btn btn-mag-n"><i class="bx bx-reset icon-bar"></i></button>
          <input name="Envoyer" type="button" class="btn btn-mag-n text-primary" value="Ajouter la note" onclick="ajaxForm('#note', '../src/pages/contacts/contacts_notes_bd.php', 'sub-target', 'attente_target');" />

        </p>
      </div>
    </form>






  </div>
  <div class="col-md-9">

    <div id="sub-target">

    </div>
  </div>
</div>


<script>
  $(function() {

    $('.bx').tooltip();




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

        var timestamp = new Date(date); // Convert to timestamp
        timestamp.setHours(8, 0, 0, 0);
        var formattedTimestamp = timestamp.toISOString().slice(0, 19).replace("T", " "); // Format to "YYYY-MM-DD HH:MM:SS"
        $('#timestamp').val(formattedTimestamp);
        var date = $('#dateprod').val();
        $('#semaine').val(week);
        // ajaxData('date=' + date + '', '../src/pages/production/production_liste.php', 'sub-target', 'attente');
        document.cookie = "dateprod=" + date + "; SameSite=none; Secure;";
        document.cookie = "weekprod=" + week + "; SameSite=none; Secure;";


      }
    });
  });


  ajaxData('idcli=<?= $idcli ?>&block=ok', '../src/pages/contacts/contacts_notes_bd.php', 'sub-target', 'attente_target');
</script>