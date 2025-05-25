<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
session_start();
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$conn = new connBase();
foreach ($_POST as $k => $v) {
  ${$k} = $v;
  //echo $k . " " . $v . "</br>";
};
$tt = "select SUM(totttc) as tot from facturesentete where cs='$secteur' and paye='non' group by cs";
$t = $conn->allRow($tt);
// affichage de toutes les factures marquage des regle en 'muted' sans champs enlevé de la requette suivante : "and paye='non'"
$reqreg = "select id,numero,totttc,nom,datefact,factav,paye from facturesentete where cs='$secteur' and annee='$annref' or cs='$secteur' and annee='$ann'  order by nom asc ";
$reglements = $conn->allRow($reqreg);
?>
<div class="row">
  <div class="col-md-6">

    <p class="titre_menu_item mb-2">Pointage des règlements client <?= $annref ?></p>
    <form id="formReg">
      <div class="input-group mb-2">
        <span class="input-group-text l-9" id="basic-addon3">Date</span>
        <input type="text" class="form-control" id="dateremise" name="date" value="<?= date('d/m/Y'); ?>">
        <input type="hidden" id="semaine" name="semaine" value="<?= date('W'); ?>">
        <input type="hidden" name="iduser" value="<?= $iduser ?>">
      </div>
      <div class="input-group btn-block">
        <span class="input-group-text l-9" id="basic-addon3">Compte</span>
        <select class="form-control" aria-label="Default select example" name="bank">
          <option selected value="">Compte bancaire</option>
          <?php
          $reqcompte = "select nom_bank, iban, titulaire,idrib from bank where cs='$secteur' order by nom_bank";
          $compte = $conn->allRow($reqcompte);
          foreach ($compte as $c) {
          ?>
            <option value="<?= $c['nom_bank'] . ' - ' . $c['iban'] . '/' . $c['idrib'] ?>"> <?= $c['nom_bank'] . ' - ' . $c['titulaire'] ?></option>
          <?php
          }
          ?>
          <option value="Aucun compte - extourne/X">Aucun compte - extourne</option>
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
          <option value="XXX">Extourne</option>
        </select>
        <script>
          $('#mode').on('change', function() {
            let selector = $(this).val();
            let madat = $('#dateremise').val();
            let madate = madat.replace(/\//g, '');
            switch (selector) {
              case 'CHEQUE':
                $('#bordereau').val('rc_chq_' + madate);
                break;
              case 'VIREMENT':
                $('#bordereau').val('vrt_' + madate);
                break;
              case 'CESU':
                $('#bordereau').val('cesu_' + madate);
                break;
              case 'XXX':
                $('#bordereau').val('extourne_' + madate + '_');
                break;
              default:
                $('#bordereau').val('');
            }
          });
        </script>
      </div>
      <div class="input-group mb-4">
        <span class="input-group-text l-9" id="basic-addon3">Bordereau</span>
        <input type="text" class="form-control" id="bordereau" name="bordereau" value="">
      </div>
      <input type="hidden" id="acompte" name="acompte" value="">
      <div class="input-group">
        <span class="input-group-text l-9" id="basic-addon1">Recherche</span>
        <input type="text" class="form-control" placeholder="Par nom, n° facture ou montant" value="" id="valrech" onmouseover="eff_form(this);">
      </div>
      <p class="small mb-2 text-right pointer" onclick="VoirRemise();"><i class='bx bxs-exit text-warning bx-rotate-90 icon-bar-p'></i> Voir la remise préparée</p>
      <!-- <div id="affreg"></div> -->


      <script>
        document.addEventListener('DOMContentLoaded', function() {
          const form = document.getElementById('fromReg');

          // Function to create a serialized JSON object from the form inputs
          function createFormDataJSON() {
            let formData = {};

            // Loop through form inputs and serialize the data
            form.querySelectorAll('input, select, textarea').forEach(function(input) {
              if (input.type === 'checkbox') {
                formData[input.name] = input.checked; // true/false for checkboxes
              } else if (input.type === 'radio') {
                if (input.checked) {
                  formData[input.name] = input.value;
                }
              } else {
                formData[input.name] = input.value; // serialize other inputs
              }
            });

            return formData;
          }

          // Function to send form data and create .json file
          function sendFormData() {
            const jsonData = createFormDataJSON(); // Create JSON data from form inputs

            fetch('../src/pages/reglements/process_form.php', { // Replace with your PHP handler
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                },
                body: JSON.stringify(jsonData), // Send the serialized JSON
              })
              .then(response => response.json())
              .then(data => {
                console.log('Response:', data); // Log server response
                if (data.status === 'success') {
                  alert('Form submitted and JSON file created!');
                } else {
                  alert('There was an error: ' + data.message);
                }
              })
              .catch(error => console.error('Error:', error));
          }

          // Add event listeners to form inputs for change events
          form.querySelectorAll('input, select, textarea').forEach(function(input) {
            input.addEventListener('change', sendFormData); // Trigger form submission on change
          });
        });

        //$(document).ready(function() {
        function eff_form(t) {
          $(t).val('');
        }
        $("#valrech").autocomplete({
          minLength: 1,
          source: function(request, response) {
            $.ajax({
              url: "https://app.enooki.com/inc/suggest_reg.php",
              dataType: "json",
              data: {
                term: request.term
              },
              success: function(data) {
                console.log('data ************************');
                console.log(data);

                // 1. Créer un bloc HTML pour afficher les résultats dans le div 'action'
                let htmlOutput = ''; // Créer une div scrollable



                // Parcourir les données et ajouter chaque item au HTML sous forme de <tr>
                $.each(data, function(index, item) {
                  htmlOutput += '<tr class="trsoul">'; // Début de la ligne du tableau

                  // Checkbox avec appel à TotRem()
                  htmlOutput += '<td>';
                  htmlOutput += '<input type="checkbox" id="chk_' + item.numero + '" class="coche" onclick="TotRem();">';
                  htmlOutput += '</td>';

                  // Nom et prénom en gras + informations supplémentaires
                  htmlOutput += '<td>';
                  htmlOutput += '<b>' + item.label + '</b><br>';
                  htmlOutput += '<span class="text-muted small">Facture N°</span> ';
                  htmlOutput += '<span class="small text-primary text-bold numrem">' + item.numero + '</span>';
                  htmlOutput += '<span class="text-muted small"> de ' + parseFloat(item.value).toFixed(2) + '€ du ' + item.date + '</span>';
                  htmlOutput += '</td>';

                  // Colonnes vides (ajouter du contenu si nécessaire)
                  htmlOutput += '<td></td>';
                  htmlOutput += '<td></td>';

                  // Input de changement de montant avec appel à TotRem()
                  htmlOutput += '<td class="pull-right">';
                  htmlOutput += '<input id="montchange_' + item.numero + '" class="form-control l-9 text-right totalrem coche" onchange="TotRem();" type="text" value="' + parseFloat(item.value).toFixed(2) + '">';
                  htmlOutput += '</td>';

                  htmlOutput += '</tr>'; // Fin de la ligne du tableau
                });

                // Injecter ce HTML dans le corps du tableau (par exemple #tableBody)
                $("#regTab").html(htmlOutput);
                //$("#regTab").append(htmlOutput);




                // 2. Utiliser response pour l'autocomplétion
                // response($.map(data, function(item) {
                //   return {
                //     label: item.label, // Ce qui sera affiché dans le champ d'autocomplétion
                //     value: item.value, // La valeur qui sera insérée dans le champ
                //     idcli: item.idcli, // ID supplémentaire à utiliser pour la sélection
                //     numero: item.numero
                //   };
                // }));
              },
            });
          },
          select: function(event, ui) {
            var idcli = ui.item.idcli;
            $("#valrech").html('');
            ajaxData('idcli=' + idcli + '', 'src/pages/contacts/contacts_fiche.php', 'target-one', 'attente_target');
          },
          close: function(event, ui) {
            $("#valrech").val('');
          },
        });
        //  });
      </script>

      <div class="scroll-s">


        <table class="tg" style=" width: 100%" id="regTab">
          <tbody>
            <!--     <?php
                      foreach ($reglements as $r) {
                        $idcli = $r['id'];
                        $reqcli = "select prenom from client_chantier where idcli='$idcli' limit 1";
                        $client = $conn->oneRow($reqcli);
                        $factref = $r['numero'];
                        $reglements_effectue = "select SUM(montant) as mt  from reglements where factref = '$factref' ";
                        $regeff = $conn->oneRow($reglements_effectue);
                        $ded = $regeff['mt'];
                        $reel = $r['totttc'] - $ded;
                        $factav = strtolower($r['factav']);
                        $factav = ucfirst($factav);
                        if ($r['paye'] === 'oui') { ?>
                <tr class="trsoul">
                  <td><i class='bx bxs-like bx-flxxx icon-bar text-success'></td>
                  <td class="text-muted"><b> <?= $r['nom'] . ' ' . $client['prenom'] ?></b></br><span class="text-muted small"><?= $factav ?> N°</span> <span class="small text-primary text-bold numrem"><?= $r['numero'] ?></span><span class="text-muted small"> de <?= Dec_2($r['totttc']) ?>€ du <?= AffDate($r['datefact']) ?></span></td>
                  <td></td>
                  <td></td>
                  <td class="pull-right"></i>
                    <input id="montchange" disabled class="form-control l-9 text-right totalrem coche" onchange="TotRem();" type="text" value="<?= Dec_2($r['totttc']) ?>">
                  </td>
                </tr>
              <?php
                        } else {
              ?>
                <tr class="trsoul">
                  <td><input type="checkbox" id="chk" class="coche" onclick="TotRem();"></td>
                  <td class=""><b> <?= $r['nom'] . ' ' . $client['prenom'] ?></b></br><span class="text-muted small"><?= $factav ?> N°</span> <span class="small text-primary text-bold numrem"><?= $r['numero'] ?></span><span class="text-muted small"> de <?= Dec_2($reel) ?>€ du <?= AffDate($r['datefact']) ?></span></td>
                  <td></td>
                  <td></td>
                  <td class="pull-right">
                    <input id="montchange" class="form-control l-9 text-right totalrem coche" onchange="TotRem();" type="text" value="<?= Dec_2($reel) ?>">
                  </td>
                </tr>
            <?php
                        }
                      }
            ?>-->
          </tbody>
        </table>
      </div>
      <script>
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
      </script>
      <div class="">
        <div id="regliste"></div>
        <div class="container-fluid btn btn-mag mb-2 mt-4 text-bold" onclick="ajaxForm('#formReg','../src/pages/reglements/reglements_inscription_bd.php','sub-target','attente_target')"><span class="pull-right text-bold" id="totalrem"></span>Enregistrer la remise de : </div>
        <input type="hidden" id="refreg" value="" name="value">
        <input type="hidden" class="totrem" value="" name="total">
      </div>
    </form>
  </div>
  <div class="col-md-6">
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