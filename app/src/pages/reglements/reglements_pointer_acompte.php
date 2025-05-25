<?php


session_start();
$secteur = $_SESSION['idcompte'];
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';

$conn = new connBase();
$reg = new Reglements($secteur);


foreach ($_POST as $p) {
  // echo $p . "</br>";
};

$reqreg = "select id,numero,acompte,nom,jour,mois,annee from devisestimatif where cs='$secteur' and acompte !='' order by nom asc ";
$reglements = $conn->allRow($reqreg);
?>

<div class="row">

  <div class="col-md-5">
    <p class="titre_menu_item mb-2 text-warning">Pointage des acomptes client</p>

    <form id="formReg">
      <div class="input-group mb-2">
        <span class="input-group-text l-9" id="basic-addon3">Date</span>
        <input type="text" class="form-control" id="dateremise" name="date" value="<?= date('d/m/Y'); ?>">
        <input type="hidden" id="semaine" name="semaine" value="<?= date('W'); ?>">
      </div>
      <div class="input-group btn-block">
        <span class="input-group-text l-9" id="basic-addon3">Compte</span>
        <select class="form-control" aria-label="Default select example" name="bank">
          <option selected value="">Compte bancaire</option>
          <?php
          $reqcompte = "select nom_bank, iban, titulaire from bank where cs='$secteur' order by nom_bank";
          $compte = $conn->allRow($reqcompte);
          foreach ($compte as $c) {
          ?>
            <option value="<?= $c['nom_bank'] . ' - ' . $c['iban'] ?>"> <?= $c['nom_bank'] . ' - ' . $c['titulaire'] ?></option>
          <?php
          }
          ?>
        </select>
      </div>
      <p class="small mb-2 text-right pointer" onclick="ajaxData('cs=<?= $secteur ?>','../src/pages/reglements/reglements_banque.php','sub-target','attente_sub-target')"><i class='bx bxs-exit text-warning bx-rotate-90 icon-bar-p'></i> Gérer les compte bancaires</p>
      <div class="input-group btn-block mb-2">
        <span class="input-group-text l-9" id="basic-addon3">Mode</span>
        <select class="form-control" id="mode" name="modereg">
          <option value="" selected>Mode de règlement</option>
          <option value="CHEQUE">Chèque</option>
          <option value="VIREMENT">Virement</option>
          <option value="CESU">CESU</option>
          <option value="XXX">Autre</option>
        </select>
      </div>
      <script>
        $('#mode').on('change', function() {
          let selector = $(this).val();
          let madat = $('#dateremise').val();
          let madate = madat.replace(/\//g, '')
          switch (selector) {
            case 'CHEQUE':
              $('#bordereau').val('acpt_chq_' + madate);
              break;
            case 'VIREMENT':
              $('#bordereau').val('acpt_vrt_' + madate);
              break;
            case 'CESU':
              $('#bordereau').val('acpt_cesu_' + madate);
              break;
            case 'XXX':
              $('#bordereau').val('acpt_xxx_' + madate);
              break;
            default:
              $('#bordereau').val('');
          }
        });
      </script>
      <div class="input-group mb-4">
        <span class="input-group-text l-9" id="basic-addon3">Bordereau</span>
        <input type="text" class="form-control" id="bordereau" name="bordereau" value="">
      </div>


      <input type="hidden" id="acompte" name="acompte" value="acompte">
      <div class="input-group">
        <span class="input-group-text l-9" id="basic-addon1">Recherche.. </span>
        <input type="text" class="form-control" placeholder="..par N° devis, nom et montant" onmouseover="eff_form(this);" value="" aria-label="Username" aria-describedby="basic-addon1" id="valrech">
      </div>
      <p class="small mb-2 text-right pointer" onclick="VoirRemise();"><i class='bx bxs-exit text-warning bx-rotate-90 icon-bar-p'></i> Voir la remise </p>
      <div class="scroll-s">
        <table class="tg" style=" width: 100%" id="regTab">
          <tbody>
            <?php
            foreach ($reglements as $r) {
              $demande = $r['acompte'];
              $idcli = $r['id'];
              $reqcli = "select prenom from client_chantier where idcli='$idcli' limit 1";
              $client = $conn->oneRow($reqcli);
              $factref = $r['numero'];
              $reglements_effectue = "select SUM(montant) as mt  from reglements where factref = '$factref' ";
              $regeff = $conn->oneRow($reglements_effectue);
              $ded = $regeff['mt'];
              $reel = $r['totttc'] - $ded;
              $datedev = $r['jour'] . '/' . $r['mois'] . '/' . $r['annee'];
              $bilan = $demande - $ded;
              // echo  'b' . $bilan . '<br>';
              // echo $factref . ' d' . $demande . ' ded' . $ded . ' sur  r' . $reel . '<br>';

              $trop_paye = $bilan < 0 ? "<i class='bx bxs-error-circle bx-flxxx icon-bar text-danger'></i> " . $bilan : "";
              if ($bilan != 0) {
            ?>
                <tr class="trsoul">
                  <td><input type="checkbox" id="chk" class="coche" onclick="TotRem();"></td>
                  <td class=""><b> <?= $r['nom'] . ' ' . $client['prenom'] ?></b> <?= $trop_paye ?></br><span class="text-muted small ">Devis N°</span> <span class="small text-warning text-bold numrem"><?= $r['numero'] ?></span><span class="small text-muted"> acpt de <?= Dec_2($r['acompte']) ?>€ du du <?= ($datedev) ?></span></td>
                  <td></td>
                  <td></td>

                  <td class="pull-right">

                    <input id="montchange" class="form-control l-9 text-right totalrem coche" onchange="TotRem();" type="text" value="<?= Dec_2($r['acompte']) ?>">

                  </td>

                </tr>
            <?php
              }
            }
            ?>
          </tbody>
        </table>
      </div>
      <script>
        $(document).ready(function() {

          $("#regTab input.coche[type='checkbox']").click(function() {
            if ($(this).is(":checked")) {
              $(this).closest("tr").addClass("svert");
            } else {
              $(this).closest("tr").removeClass("svert");
            }
          });

          $("#valrech").click(function() {
            $("#regTab tr").show();
          });

          $("#valrech").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#regTab tr").filter(function() {
              $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
          });

          $("#dateremise").datepicker({
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
              $('#semaine').val(week);
              // ajaxData('date=' + date + '', 'pages/03-production/menus/target/at_production_liste.php', 'sub-target', 'attente');
              document.cookie = "dateprod=" + date + "; SameSite=lax; Secure;";
              document.cookie = "weekprod=" + week + "; SameSite=lax; Secure;";
            }
          });
        });
      </script>
      <div class="">
        <div id="regliste"></div>
        <div class="container-fluid btn btn-mag mb-2 mt-4 text-bold" onclick="ajaxForm('#formReg','../src/pages/reglements/reglements_inscription_bd.php','sub-target','attente_target')"><span class="pull-right text-bold" id="totalrem"></span>Enregistrer l'acompte : </div>
        <input type="hidden" id="refreg" value="" name="value">
        <input type="hidden" class="totrem" value="" name="total">
      </div>
    </form>
  </div>


  <div class="col-md-7">
    <div id="sub-target"></div>
  </div>

  <script>
    function TotRem() {
      $("#regTab").on("change", ".coche", function() {
        var total = 0;
        var mligneArray = []; // Tableau pour stocker les valeurs de mligne
        $("#regTab .coche").each(function() {
          if ($(this).is(":checked")) {
            var mligne = Number($(this).closest("tr").find(".totalrem").val());
            var refact = $(this).closest("tr").find(".numrem").text();
            total += mligne;
            mligneArray.push(mligne + '_' + refact);
          }
        });
        var totalf = total.toFixed(2);
        var ref = mligneArray.join('/'); // Concaténer les valeurs avec '/'

        console.log(totalf);
        console.log(ref);
        $("#totalrem").html(totalf);
        $(".totrem").val(totalf);
        $("#refreg").val(ref); // Utiliser .text() pour modifier le contenu du champ



      });
    }



    // function Ai
    function VoirRemise() {
      $('#tabReg').prop("display", "none");
      $("#voiremise").on("click", function() {
        $("#tabReg tr").each(function() {
          if ($(this).find(".coche").is(":checked")) {
            $(this).show();
          } else {
            $(this).hide();
          }
        });
      });
    }

    function SeeRem() {
      /* button onclick select the checked row of a table */
      var table = document.getElementById('table');
      var rows = table.getElementsByTagName('tr');
      for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        var checkbox = row.getElementsByTagName('input')[0];
        checkbox.onclick = function() {
          if (this.checked) {
            for (var j = 0; j < rows.length; j++) {
              rows[j].getElementsByTagName('input')[0].checked = false;
            }
            this.checked = true;
          }
        };
      }
    }

    function VoirRemise() {
      $("#regTab").find(".trsoul").hide();
      $("#chk:checked").each(function() {
        $(this).closest(".trsoul").show();
      });
    }
  </script>