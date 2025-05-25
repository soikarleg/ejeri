<?php
session_start();
$secteur = $_SESSION['idcompte'];
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$data = $_POST;
$conn = new connBase();
$ins = new FormValidation($data);
$devis = new Devis($secteur);
foreach ($_POST as $key => $value) {
  ${$key} = $ins->valFull($value);
  // '$' . $key . ' = ' . $value . '<br>';
}
if ($numero_devis) {

  $numero = $numero_devis;
  $devis_existe = $devis->askDevisEntete($numero);
  $date_maj = AffDate($devis_existe['time_maj']);
  $titre = "Modification du devis ";
  $sous_titre = '<p class="small mb-2 text-mute">Date de la dernière modification : ' . $date_maj . '</p>';
  $bouton_titre = "Modifier le devis N° ";
} else {
  $numero = $devis->getNumDevis();
  $titre = "Création du devis ";
  $bouton_titre = "Créer le devis N° ";
}
$client = "select * from client_chantier where idcli='$idcli' limit 1";
$cli = $conn->oneRow($client);
$client_infos = "select * from client_infos where idcli='$idcli' limit 1";
$cli_i = $conn->oneRow($client_infos);
$reqbank = "select * from bank where cs='$secteur'";
$bank = $conn->allRow($reqbank);
$infos = $conn->askIdcompte($secteur);
$prod_det = $devis->askDevisLignes($numero);
$entete = $devis->askDevisEntete($numero);
$limite_carateres = 150;

?>
<script>
  function mettreAJourCompteur(num) {
    var texte = $('#designation' + num).val();
    var limite = <?= $limite_carateres ?>;
    var caracteresRestants = limite - texte.length;
    $('#max' + num).text(caracteresRestants);
    if (caracteresRestants < 1) {
      $('#max' + num).removeClass('text-muted');
      $('#max' + num).addClass('text-warning');
      $('#trop' + num).show();
    } else {
      $('#max' + num).removeClass('text-warning');
      $('#max' + num).addClass('text-muted');
      $('#trop' + num).hide();
    }
  }

  function calculerAcompte() {
    var selectedValue = parseFloat($('#demande_acompte').val());
    if (!isNaN(selectedValue)) {
      var totalTTC = parseFloat($('#total_general').val());
      if (!isNaN(totalTTC)) {
        var acompte = (totalTTC * selectedValue / 100).toFixed(2);
        $('#acompte').val(acompte);
      }
    }
  }
</script>
<p class="titre_menu_item mb-2"><?= $titre ?>N° <?= $numero ?> - <?= NomClient($idcli) ?></p>
<?= $sous_titre ?>
<div class="">
  <?php
  if ($idcli) {
  ?>
    <form autocomplete="off" action="" id="fact" class="">
      <div class="row">
        <div class="col-md-3 ">
          <div class="input-group mb-2">
            <span class="input-group-text l-9" id="basic-addon3">N° Devis</span>
            <input type="text" class="form-control text-primary" id="numero" name="numero" value="<?= $numero ?>" readonly>
            <input type="hidden" name="titre_doc" value="DEVIS ESTIMATIF">
            <input type="hidden" name="nomcli" value="<?= $cli['nom'] ?>">
            <input type="hidden" name="idcli" value="<?= $cli['idcli'] ?>">
            <input type="hidden" name="cscli" value="<?= $secteur ?>">
          </div>
          <div class="input-group mb-2">
            <?php
            $reqtitre = "select * from devistitres where cs='$secteur' and type='MO' order by titre asc";
            $titre = $conn->allRow($reqtitre);
            ?>
            <span class="input-group-text l-9" id="basic-addon3">Titre devis</span>
            <select class="form-control" id="titre" name="titre">
              <option value="">Choisir un titre...</option>

              <?php
              foreach ($titre as $t) { //


              ?>
                <option value="<?= $t['titre'] ?>" <?= $t['titre'] === $entete['titre'] ? "selected" : ""; ?>><?= $t['titre'] ?></option>
              <?php
              }
              ?>
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-group mb-2">
            <span class="input-group-text l-9" id="basic-addon3">Exécution </span>
            <input type="text" class="form-control" id="epoque" name="epoque" value="<?= $entete['epoque'] ?>">
          </div>
          <div class="input-group mb-2">
            <span class="input-group-text l-9" id="basic-addon3">Commentaire</span>
            <input type="text" class="form-control" id="commentaire" name="commentaire" value="<?= $entete['commentaire'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="input-group mb-2">
            <?php
            $date_eche_compte = $infos['delj'];
            ?>
            <span class="input-group-text l-9" id="basic-addon3">Date</span>
            <?php

            $date_devis = $entete['time_maj'] === null ? date('d/m/Y') : AffDate($entete['annee'] . '-' . $entete['mois'] . '-' . $entete['jour']);
            ?>
            <input type="text" class="form-control text-right" id="dateprod" name="datedevis" value="<?= $date_devis ?>">
            <input type="hidden" id="jeche" value="<?= $date_eche_compte ?>">
          </div>
          <div class="input-group mb-2">
            <span class="input-group-text l-9" id="basic-addon3">Commercial</span>
            <?php
            // echo $cli_i['commercial'] . ' ' . $cli_i['id_c'];
            $commercial = $devis->getNomNumCommercial($cli_i['commercial'], $cli_i['id_c']);
            if ($cli_i['commercial'] === 0 || $cli_i['id_c'] === 0) {
            ?>
              <script>
                pushError('ATTENTION', 'Renseignez commercial et intervenant dans la fiche client');
              </script>
            <?php
            }

            ?>
            <input type="text" class="form-control text-right" id="referent" value="<?= ($commercial['nom']) ?>" readonly>
            <input type="hidden" name="commercial" value="<?= ($commercial['num']) ?>">
          </div>
        </div>
        <div class="col-md-12 mt-2">
          <p class="text-bold text-primary">Description des travaux <span class="text-primary pull-right mr-2 pointer" onclick="lignePlus();"><i class='bx bxs-plus-circle bx-tada bx-flip-horizontal'></i> Ajouter une ligne</span></p>
          <table>
            <tbody id="corp">
              <?php
              $devis_select = "select * from devislignes where numero='$numero'";
              $devis = $conn->allRow($devis_select);
              $i = 1;
              ?>
              <?php
              foreach ($devis as $kk) {
              ?>
                <tr id="lignedevis" num="<?= $i ?>">
                  <!-- <input type="checkbox" checked id="check<?= $i ?>" value="<?= $i ?>"> -->
                  <?php
                  if ($kk['numdev']) {
                    $n = $kk['numdev'];
                    $efface_ligneJS = '; effaceLigne(' . $n . ');';
                  }
                  ?>


                  <td width="3%"><i class="bx bx-checkbox-minus text-danger pointer icon-bar bx-sm" onclick="suppLigne(this)<?= $efface_ligneJS ?>"></i>o</td>
                  <!-- <input type="hidden" name="devref" value="<?= $kk['numero'] ?>"> -->
                  <input type="hidden" id="i<?= $i ?>" name="i" value="<?= $i ?>">
                  <input type="hidden" id="numinter<?= $i ?>" name="numinter_<?= $i ?>" value="<?= $i ?>">

                  <td width="50%"><input type="hidden" id="numdev<?= $i ?>" name="numdev<?= $i ?>" value="<?= $kk['numdev'] ?>">
                    <div class="input-group"><input type="text" class="form-control" id="designation<?= $i ?>" onkeyup="askPhrases(<?= $i ?>);mettreAJourCompteur(<?= $i ?>)" onblur="calculLigne(<?= $i ?>);" ame="designation_<?= $i ?>" value="<?= $kk['designation']  ?>"><span class=" small text-bold text-danger input-group-text" id="trop<?= $i ?>" style="display: none;" value="<?= $limite_carateres ?>">Trop long</span><span id="max<?= $i ?>" class="input-group-text small text-muted l-3"><?= $limite_carateres ?></span></div>
                  </td>
                  <td width="8%"><input type="text" class="form-control text-right" onblur="calculLigne(<?= $i ?>);" id="quant<?= $i ?>" ame="quant_<?= $i ?>" value="<?= Dec_2($kk['q']) ?>"></td>
                  <td width="8%"><input type="text" class="form-control text-right" onblur="calculLigne(<?= $i ?>);" id="pu<?= $i ?>" ame="pu_<?= $i ?>" value="<?= Dec_2($kk['puttc']) ?>"></td>
                  <td width="8%"><input type="text" class="form-control text-right" id="tot_<?= $i ?>" name="tot_<?= $i ?>" value="<?= Dec_2($kk['ptttc']) ?>">
                    <input type="hidden" id="trans<?= $i ?>" name="trans_<?= $i ?>" value="">
                  </td>
                </tr>
                <script>
                  function effaceLigne(numdev) {
                    $.ajax({
                      type: "POST", // Méthode HTTP
                      url: "../src/pages/devis/devis_supprimer_ligne.php", // URL du script PHP pour la suppression
                      data: {
                        numdev: numdev
                      }, // Données à envoyer au script PHP
                      success: function(response) {

                        if (response === 'success') {
                          pushSuccess('Confirmation', 'La ligne ' + numdev + ' a bien été effacée.');
                          $("#lignedevis[num='" + numdev + "']").remove();
                        } else {

                          pushWarning('Erreur', 'La suppression ' + numdev + ' a échoué !');

                        }
                      },
                      error: function() {
                        // Cette fonction sera exécutée en cas d'erreur de la requête AJAX
                        pushWarning('Erreur', 'Erreur dans l\'AJAX.');
                      }
                    });
                  }


                  transLigne(<?= $i ?>);
                  $(document).ready(function() {
                    $('#check<?= $i ?>').on('change', function() {
                      var isChecked = $(this).prop('checked');
                      var num = $(this).val();
                      var inputs = $(this).closest('tr').find('input[type="text"]');
                      if (!isChecked) {
                        inputs.removeAttr('name');
                        inputs.addClass('text-secondary');
                        transLigne(num);
                        calculTotal();
                      } else {
                        inputs.attr('name', function() {
                          return $(this).attr('id');
                        });
                        inputs.removeClass('text-secondary');
                        transLigne(num);
                        calculTotal();
                      }
                    });
                  });
                </script>
              <?php
                $i++;
                foreach ($kk as $k => $v) {
                  ${$k} = $v;
                  //echo '$' . $k . ' = ' . $v . '</br>';
                }
              }
              ?>
            </tbody>
            <tfoot>
              <tr>
                <td> </td>
                <td><span class="text-primary mr-2"><i class='bx bxs-plus-circle bx-tada bx-flip-horizontal'></i> Ajouter un RIB de règlement
                    <?php
                    foreach ($bank as $b) {
                    ?>
                      <span class="pointer puce mr-2" onclick="ribPlus('Règlement : <?= 'IBAN : ' . $b['iban'] . ' BIC : ' . $b['bic'] . ' / ' . $b['nom_bank'] ?>')"><?= $b['nom_bank'] ?> </span>
                    <?php
                    }
                    ?>
                  </span></td>
                <td></td>
                <td>
                  <p class="text-right">Total TTC</p>
                </td>
                <td><input type="text" class="form-control text-right" id="total_general" name="totalgeneral" value="<?= Dec_2($tour) ?>" readonly></td>
              </tr>
              <tr>
                <td></td>
                <td>
                  <div class="input-group mb-2">
                    <label class="input-group-text l-9">Acompte</label>
                    <select class="form-control" id="demande_acompte" name="acompte_tx" onchange="calculerAcompte();calculTotal();">
                      <option value="0">Demandez un acompte </option>
                      <option value="10" <?= $entete['acompte_tx'] === '10' ? "selected" : ""; ?>>Travaux 10% </option>
                      <option value="30" <?= $entete['acompte_tx'] === '30' ? "selected" : ""; ?>>Travaux 30% </option>
                    </select>
                  </div>
                </td>
                <td>
                </td>
                <td>
                  <p class="text-right">Acompte</p>
                </td>
                <td><input type="text" class="form-control text-right" id="acompte" name="acompte" value="<?= $entete['acompte'] ?>" placeholder="0.00" onblur="calculTotal()"></td>
              </tr>
              <tr>
                <td></td>
                <td>
                </td>
                <td></td>
                <td>
                  <p class="text-right">Solde</p>
                </td>
                <td><input type="text" class="form-control text-right" id="apayer" name="solde" value="0.00">
                  <input type="hidden" id="numero_designation" value="<?= $i ?>">
                </td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </form>
</div>
<div class="mb-5">
  <button class="btn btn-mag-n text-danger mt-4 mb-2 " onclick="ajaxData('c=c','../src/menus/menu_contacts.php','target-one','attente_target');">Annuler</button>
  <button class="btn btn-mag-n text-primary mt-4 mb-2 " onclick="factValidation();"><?= $bouton_titre . $numero ?></button>
  <button id="factConf" class=" btn btn-mag-n text-danger mt-4 mb-2" style="display:none" onclick="ajaxForm('#fact','../src/pages/devis/devis_enregistre.php','action','attente_target');"><?= "Confirmez " ?></button>
</div>
<?php
  } else {
?>
  <div class="row">
    <p class="text-bold mt-4">Faire un devis pour un client déjà inscrit, sinon ajoutez-le.</p>
    <div class="col-md-3">

      <form autocomplete="off">
        <input type="text" id="recherche_client" class="form-control placeholder mt-2 " placeholder="Recherche client..." onmouseover="eff_form(this);">
      </form>
      <script>
        //$(document).ready(function() {
        function eff_form(t) {
          $(t).val('');
        }
        $("#recherche_client").autocomplete({
          minLength: 1,
          source: function(request, response) {
            $.ajax({
              url: "https://app.enooki.com/inc/suggest.php",
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
            $("#recherche_client").html('');
            ajaxData('idcli=' + idcli + '', 'src/pages/devis/devis_faire.php', 'action', 'attente_target');
          },
          close: function(event, ui) {
            $("#recherche_dossier").val('');
          },
        });
        //  });
      </script>



    </div>
    <div class="col-md-6">
      <p class="btn btn-mag-n mt-2" onclick="ajaxData('cs=cs', '../src/pages/contacts/contacts_ajouter.php', 'action', 'attente_target');">Ajoutez votre client</p>
    </div>
  </div>
<?php
  }
?>
<div id="sous_action"></div>
<script>
  var compteur = $('#numero_designation').val();

  function factValidation() {
    $('#factConf').show();
  }

  function lignePlus() {
    var limite = <?= $limite_carateres ?>;
    var compteur = ($('#numero_designation').val());
    var designation = $('#designation' + compteur).val();
    var num = compteur;
    var t = designation + num;
    var p = '<tr><td width="3%" class="text-danger" onclick="suppLigne(this)"><i class="bx bx-checkbox-minus bx-sm icon-bar"></i></td><input type="hidden" id="i' + compteur + '" name="i" value="' + compteur + '"><input type="hidden" ame="numdev_' + compteur + '" id="numdev' + compteur + '" value="X"><input type="hidden" ame="numinter_' + compteur + '" id="numinter' + compteur + '" value="' + compteur + '"><td width="50%"><div class="input-group"><input type="text" class="form-control" id="designation' + compteur + '" onkeyup="askPhrases(' + compteur + ');mettreAJourCompteur(' + compteur + ')" onblur="calculLigne(' + compteur + ');" ame="designation_' + compteur + '" value=""><span  id="save' + compteur + '" class="input-group-text"> <i onclick="Save(' + compteur + ');" class="bx bxs-save pointer text-muted"></i></span><span class="input-group-text text-bold small text-danger" id="trop' + compteur + '" style="display: none;">Trop long</span><span id="max' + compteur + '" class="input-group-text small text-muted text-right l-3">' + limite + '</span></div></td><td width="8%"><input type="text" class="form-control text-right" onblur="calculLigne(' + compteur + ');" id="quant' + compteur + '" ame="quant_' + compteur + '" value=""></td><td width="8%"><input type="text" class="form-control text-right" onblur="calculLigne(' + compteur + ');" id="pu' + compteur + '" ame="pu_' + compteur + '" value="<?= Dec_2($infos["tr"]) ?>"></td><td width="8%"><input type="text" class="form-control text-right" id="tot_' + compteur + '" name="tot_' + compteur + '" value=""><input type="hidden" id="trans' + compteur + '" name="trans_' + compteur + '" value="' + compteur + '"></td></tr>';
    $('#corp').append(p);
    compteur = parseInt(compteur) + 1;
    $('#numero_designation').val(compteur);
  }

  function ribPlus(bank) {
    var compteur = ($('#numero_designation').val());
    var p = '<tr><td width="3%" class="text-danger" onclick="suppLigne(this)"><i class="bx bx-checkbox-minus bx-sm icon-bar"></i></td><td width="50%"><input type="hidden" id="i' + compteur + '" name="i" value="' + compteur + '"><input type="hidden" ame="numdev_' + compteur + '" id="numdev' + compteur + '" value="X"><input type="hidden" ame="numinter_' + compteur + '" id="numinter' + compteur + '" value="' + compteur + '"><input type="text" class="form-control" onblur="calculLigne(' + compteur + ');"id="designation' + compteur + '" ame="designation_' + compteur + '" value="' + bank + '" readonly ></td><td width="8%"><input type="text" class="form-control text-right" onblur="calculLigne(' + compteur + ');" id="quant' + compteur + '" ame="quant_' + compteur + '" value="0.00" readonly></td><td width="8%"><input type="text" class="form-control text-right" onblur="calculLigne(' + compteur + ');" id="pu' + compteur + '" ame="pu_' + compteur + '" value="0.00" readonly></td><td width="8%"><input type="text" class="form-control text-right" id="tot_' + compteur + '" name="tot_' + compteur + '" value="0.00" readonly><input type="hidden" id="trans' + compteur + '" name="trans_' + compteur + '" value="' + compteur + '"></td></td></tr>';
    $('#corp').append(p);
    transLigne(compteur);
    compteur = parseInt(compteur) + 1;
    $('#numero_designation').val(compteur);
  }

  function Save(compteur) {
    var champ = $('#designation' + compteur).val();
    ajaxData('text=' + champ, '../src/pages/devis/devis_sauve_phrases.php', 'save' + compteur, 'attente_target')
  }
  calculTotal();
  $('#dateprod').datepicker({
    firstDay: 1,
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    closeText: "Fermer",
    nextText: ">>",
    prevText: "Précédent",
    currentText: "Aujourd'hui",
    dayNamesMin: ["Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa"],
    monthNames: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre",
      "Octobre", "Novembre", "Décembre"
    ],
    weekHeader: "S",
    dayNames: ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"],
    showWeek: true,
    dateFormat: 'dd/mm/yy',
    onSelect: function(dateText, inst) {
      $('#echeance').val(eche)
      $('#dateprod').val(dateText);
    }
  });
</script>
<?php
if (!$prod_det) {
?>
  <script>
    lignePlus();
  </script>
<?php
}
?>