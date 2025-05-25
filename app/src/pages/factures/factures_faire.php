<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
session_start();
$secteur = $_SESSION['idcompte'];
foreach ($_POST as $k => $v) {
  ${$k} = $v;
  //echo '$'.$k  . ' = ' .  var_dump($v) . '</br>';
}
$conn = new connBase();
$fact = new Factures($secteur);
if ($avoir) {
  $num_pieces = $fact->getNumAvoir();;
  $titre = 'Avoir';
  $s_titre = 'un AVOIR';
  $bouton_titre = "Valider l'avoir ";
  $titre_doc = "AVOIR";
} else {
  $num_pieces = $fact->getNumFacture();
  $titre = 'Facture';
  $s_titre = 'une FACTURE';
  $bouton_titre = "Valider la facture ";
  $titre_doc = "FACTURE";
}
$compte_infos = "select * from idcompte_infos where idcompte ='$secteur'";
$infos = $conn->oneRow($compte_infos);
$client = "select * from client_chantier where idcli='$idcli' limit 1";
$cli = $conn->oneRow($client);
$client_infos = "select * from client_infos where idcli='$idcli' limit 1";
$cli_i = $conn->oneRow($client_infos);
$devis  = "select * from devisestimatif where id='$idcli'";
$dev = $conn->allRow($devis);
$production  = "select SUM(quant) as tot from production where idcli='$idcli' and factok='non'";
$prod = $conn->oneRow($production);
$reqbank = "select * from bank where cs='$secteur'";
$bank = $conn->allRow($reqbank);
$production_det  = "select * from production where idcli='$idcli' and factok='non'";
$prod_det = $conn->allRow($production_det);
$acompte_verse = "select * from reglements where id='$idcli' and acompte='1'";
$acompte = $conn->allRow($acompte_verse);

?>
<div class="">
  <form autocomplete="off" action="" id="fact" class="">

    <div class="row">
      <div class="col-md-12">
        <!-- <span class="text-danger"><?= $source . ' ' . $avoir . ' ' . $facture ?></span> -->
        <p class="titre_menu_item mb-2">Etablir <?= $s_titre; ?> pour <?= $cli['civilite'] . ' ' . $cli['prenom'] . ' ' . $cli['nom'] ?>
          <?php if ($avoir) {
          ?>
            <span class="text-warning pointer pull-right" onclick="ajaxData('facture=facture&idcli=<?= $idcli ?>&avoir=&source=<?= $source ?>','../src/pages/factures/factures_faire.php','action','attente_target');"><i class='bx bx-refresh icon-bar mr-2'></i>Faire une facture à la place</span>
          <?php
          } else {
          ?>
            <span class="text-warning pointer pull-right" onclick="ajaxData('avoir=avoir&idcli=<?= $idcli ?>&source=<?= $source ?>&facture=','../src/pages/factures/factures_faire.php','action','attente_target');"><i class='bx bx-refresh icon-bar mr-2'></i>Faire un avoir à la place</span>
          <?php
          }
          ?>
        </p>
      </div>
      <div class="col-md-3 ">
        <div class="input-group mb-2">
          <span class="input-group-text l-9" id="basic-addon3">N° <?= $titre  ?></span>

          <input type="text" class="form-control text-primary" id="numero" name="numero" value="<?= $num_pieces ?>" readonly>
          <input type="hidden" name="factav" value="<?= $titre_doc ?>">
          <input type="hidden" name="nomcli" value="<?= $cli['nom'] ?>">
          <input type="hidden" name="idcli" value="<?= $cli['idcli'] ?>">
          <input type="hidden" name="cscli" value="<?= $secteur ?>">


        </div>
        <div class="input-group mb-2">
          <?php
          $reqtitre = "select * from devistitres where cs='$secteur' and type='MO' order by titre asc";
          $titre = $conn->allRow($reqtitre);
          ?>
          <span class="input-group-text l-9" id="basic-addon3">Titre facture</span>
          <select class="form-control" id="titre" name="titre">
            <option value="" selected>Choisir un titre...</option>
            <option value="N" class="text-primary">Nouveau titre</option>
            <?php
            foreach ($titre as $t) {
            ?>
              <option value="<?= $t['titre'] ?>" <?php $sel = $t['titre'] == 'Entretien du jardin' ? "selected=selected" : "";
                                                  echo $sel; ?>><?= $t['titre'] ?></option>
            <?php
            }

            ?>

          </select>

        </div>
      </div>
      <div class="col-md-3"></div>
      <div class="col-md-3">
        <div class="input-group mb-2">
          <?php
          $date_eche_compte = $infos['delj'];
          ?>
          <span class="input-group-text l-9" id="basic-addon3">Date</span>
          <input type="text" class="form-control text-right" id="dateprod" name="datefact" value="<?= date('d/m/Y') ?>">
          <!-- <input type="hidden" id="semaine" name="semaine" value="<?= $cw ?>"> -->
          <input type="hidden" id="jeche" value="<?= $date_eche_compte ?>">
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text l-9" id="basic-addon3">Référent</span>
          <?php
          $commercial = $fact->getNomNumCommercial($cli_i['commercial'], $cli_i['id_c']);
          ?>
          <input type="text" class="form-control text-right" id="referent" value="<?= ($commercial['nom']) ?>" readonly>
          <input type="hidden" name="commercial" value="<?= ($commercial['num']) ?>">
        </div>
      </div>
      <div class="col-md-3">
        <div class="input-group mb-2">
          <span class="input-group-text l-9" id="basic-addon3">Echéance</span>
          <input type="text" class="form-control text-right" id="echeance" name="dateche" value="0">
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text l-9" id="basic-addon3">TVA en %</span>
          <input type="text" class="form-control text-right" id="tva" name="tva" value="<?= $infos['t7'] ?>" readonly>
        </div>
      </div>
      <!-- <div class="col-md-3"></div>
      <div class="col-md-3"></div>
      <div class="col-md-3"></div>
      <div class="col-md-3"></div>
      <div class="col-md-3"></div> -->

      <p class="text-bold text-primary mb-2 mt-2">Origine de la facturation</p>
      <!-- <?php
            //echo 'source=D&numero='. $d['numero'].'&idcli='. $idcli.'&avoir='. $avoir .'&facture='. $facture ;
            ?> -->
      <div class="col-md-6">
        <div class="input-group mb-2">
          <span class="input-group-text l-9" id="basic-addon3">Depuis devis</span>
          <select class="form-control" id="source">
            <?php
            if ($dev) {
              foreach ($dev as $d) {
            ?>
                <option value="D" onclick="ajaxData('source=D&numero=<?= $d['numero'] ?>&idcli=<?= $idcli ?>&avoir=<?= $avoir ?>&facture=<?= $facture ?>','../src/pages/factures/factures_faire.php','action','attente_target');">N° <?= $d['numero'] . ' du ' . $d['jour'] . '/' . $d['mois'] . '/' . $d['annee'] . ' de ' . $d['totttc'] . ' €' ?> </option>
              <?php
              }
            } else {
              ?>
              <option value="X">Aucun devis </option>
            <?php
            }
            ?>
          </select>
        </div>
      </div>
      <div class="col-md-6 mb-2">
        <div class="input-group mb-2">
          <span class="input-group-text l-9" id="basic-addon3">Interventions</span>
          <input type="text" class="form-control" id="production" value="<?= $prod['tot'] ?>" onfocus="ajaxData('source=I&idcli=<?= $idcli ?>&avoir=<?= $avoir ?>&facture=<?= $facture ?>','../src/pages/factures/factures_faire.php','action','attente_target');" placeholder="<?= $prod['tot'] ?> heure(s) d'intervention">
        </div>
      </div>
      <div class="col-md-12">
        <p class="text-bold text-primary">Eléments <span class="text-primary pull-right mr-2 pointer" onclick="lignePlus();"><i class='bx bxs-plus-circle bx-tada bx-flip-horizontal'></i> Ajouter une ligne vierge</span></p>
        <table>
          <tbody id="corp">
            <?php
            $source = $source == null ? 'I' : $source;

            if ($source != 'D') {
            ?>
              <?php
              $tour = 0;

              $i = 1;
              foreach ($prod_det as $p) {
              ?><tr>
                  <td width="3%"><input type="checkbox" checked id="check<?= $p['numero'] ?>" value="<?= $p['numero'] ?>"></td>
                  <!--<td width="3%" class="text-secondary" disabled><i class='bx bxs-checkbox bx-sm icon-bar' disabled></i></td>-->
                  <td width="50%">
                    <input type="hidden" id='i<?= $p['numero'] ?>' name="i" value="<?= $i ?>">
                    <input type="hidden" id="numinter<?= $p['numero'] ?>" ame="numinter_<?= $p['numero'] ?>" value="<?= $p['numero'] ?>">
                    <input type="text" class="form-control" onblur="calculLigne(<?= $p['numero'] ?>);" id="designation<?= $p['numero'] ?>" ame="designation_<?= $p['numero'] ?>" value="Le <?= ($p['jour']) ?>/<?= ($p['mois']) ?>/<?= ($p['annee']) ?> - <?= NomColla($p['idinter']) ?>. <?= ($p['dettvx']) ?>">
                  </td>
                  <td width="8%"><input type="text" class="form-control text-right" onblur="calculLigne(<?= $p['numero'] ?>);" id="quant<?= $p['numero'] ?>" ame="quant_<?= $p['numero'] ?>" value="<?= Dec_2($p['quant']) ?>"></td>
                  <td width="8%"><input type="text" class="form-control text-right" onblur="calculLigne(<?= $p['numero'] ?>);" id="pu<?= $p['numero'] ?>" ame="pu_<?= $p['numero'] ?>" value="<?= Dec_2($infos['tr']) ?>"></td>
                  <td width="8%"><input type="text" class="form-control text-right" onblur="calculLigne(<?= $p['numero'] ?>);" id="tot_<?= $p['numero'] ?>" name="tot_<?= $p['numero'] ?>" value="<?= Dec_2($sum = $p['quant'] * $infos['tr']) ?>">
                    <input type="hidden" id="trans<?= $p['numero'] ?>" name="trans_<?= $i ?>" value="">
                  </td>
                </tr>
                <script>
                  function transLigne(num) {
                    var i = $('#i' + num + '').val();
                    var numinter = $('#numinter' + num + '').val();
                    var designation = $('#designation' + num + '').val();
                    var quant = $('#quant' + num + '').val();
                    var pu = $('#pu' + num + '').val();
                    var tot = $('#tot_' + num + '').val();
                    var concat = i + '_' + numinter + '_' + designation + '_' + quant + '_' + pu + '_' + tot;
                    $('#trans' + num + '').val(concat)
                    console.log(concat);
                  }
                  transLigne(<?= $p['numero'] ?>);

                  function calculLigne(num) {
                    var q = $('#quant' + num + '').val();
                    var pu = $('#pu' + num + '').val();
                    var multi = q * pu;
                    multi = multi.toFixed(2);
                    $('#tot_' + num + '').val(multi);
                    transLigne(num);
                    calculTotal();
                  }
                  $(document).ready(function() {
                    $('#check<?= $p['numero'] ?>').on('change', function() {
                      var isChecked = $(this).prop('checked');
                      var num = $(this).val();
                      var inputs = $(this).closest('tr').find('input[type="text"]');
                      if (!isChecked) {
                        inputs.removeAttr('name');
                        inputs.addClass('text-secondary');
                        $('#trans' + num).val('');
                        //transLigne(num);
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
              <?php $tour = $tour + $sum;

                $i++;
              }
              ?>
            <?php
            } else {
              $devis_select = "select * from devislignes where numero='$numero'";
              $devis = $conn->allRow($devis_select);
              $i = 1;
            ?>
              <?php
              foreach ($devis as $kk) {
              ?>
                <tr id="lignedevis">
                  <td width="3%"><input type="checkbox" checked id="check<?= $i ?>" value="<?= $i ?>"></td>
                  <!--<td width="3%" class="text-danger" onclick="suppLigne(this)"><i class='bx bxs-x-square bx-sm icon-bar'></i></td>-->
                  <input type="hidden" name="devref" value="<?= $kk['numero'] ?>">
                  <input type="hidden" id="i<?= $i ?>" name="i" value="<?= $i ?>">
                  <input type="hidden" id="numinter<?= $i ?>" name="numinter_<?= $i ?>" value="<?= $i ?>">
                  <td width="50%"><input type="text" class="form-control" id="designation<?= $i ?>" onblur="calculLigne(<?= $i ?>);" ame="designation_<?= $i ?>" value="<?= $kk['designation']  ?>"></td>
                  <td width="8%"><input type="text" class="form-control text-right" onblur="calculLigne(<?= $i ?>);" id="quant<?= $i ?>" ame="quant_<?= $i ?>" value="<?= Dec_2($kk['q']) ?>"></td>
                  <td width="8%"><input type="text" class="form-control text-right" onblur="calculLigne(<?= $i ?>);" id="pu<?= $i ?>" ame="pu_<?= $i ?>" value="<?= Dec_2($kk['puttc']) ?>"></td>
                  <td width="8%"><input type="text" class="form-control text-right" id="tot_<?= $i ?>" name="tot_<?= $i ?>" value="<?= Dec_2($kk['ptttc']) ?>">
                    <input type="hidden" id="trans<?= $i ?>" name="trans_<?= $i ?>" value="">
                  </td>
                </tr>
                <script>
                  function transLigne(num) {
                    var i = $('#i' + num + '').val();
                    var numinter = $('#numinter' + num + '').val();
                    var designation = $('#designation' + num + '').val();
                    var quant = $('#quant' + num + '').val();
                    var pu = $('#pu' + num + '').val();
                    var tot = $('#tot_' + num + '').val();
                    var concat = i + '_' + numinter + '_' + designation + '_' + quant + '_' + pu + '_' + tot;
                    $('#trans' + num + '').val(concat);
                    console.log(concat);
                  }
                  transLigne(<?= $i ?>);

                  function calculLigne(num) {
                    var q = $('#quant' + num + '').val();
                    var pu = $('#pu' + num + '').val();
                    var multi = q * pu;
                    multi = multi.toFixed(2);
                    $('#tot_' + num + '').val(multi);
                    transLigne(num);
                    calculTotal();
                  }
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
                //echo $kk['designation'];
                foreach ($kk as $k => $v) {
                  ${$k} = $v;
                  //echo '$' . $k . ' = ' . $v . '</br>';
                }
              }
              ?>
            <?php
            }
            ?>
          </tbody>
          <tfoot>
            <tr>
              <td> </td>
              <td>
                <!-- <span class="text-primary mr-2"><i class='bx bxs-plus-circle bx-tada bx-flip-horizontal'></i> Ajouter un RIB de règlement
                  <?php
                  foreach ($bank as $b) {
                  ?>
                    <span class="pointer puce mr-2" onclick="ribPlus('Règlement : <?= 'IBAN : ' . $b['iban'] . ' BIC : ' . $b['bic'] . ' / ' . $b['nom_bank'] ?>')"><?= $b['nom_bank'] ?> </span>
                  <?php
                  }
                  ?>
                </span> -->
              </td>
              <td></td>
              <td>
                <p class="text-right">Total TTC</p>
              </td>
              <td><input type="text" class="form-control text-right" id="total_general" name="totalgeneral" value="<?= Dec_2($tour) ?>" readonly></td>
            </tr>
            <tr>
              <td></td>
              <td>
                <?php
                $alert_acompte = "";
                if ($acompte) {
                  $alert_acompte = "<i class='bx bxs-star ml-2 text-warning'></i>";
                }
                ?>
                <div class="input-group mb-2">
                  <span class="input-group-text l-12" id="basic-addon3">Acompte versé <?= $alert_acompte ?></span>
                  <select class="form-control" id="acompte_liste">
                    
                    <?php
                    if ($acompte) {
                      foreach ($acompte as $a) {
                    ?><option value="" selected>Choississez l'acompte qui a été versé</option>
                        <option value="<?= $a['montant'] ?>" onclick="verseAcompte();">N° <?= $a['factref'] . ' du ' . $a['jour'] . '/' . $a['mois'] . '/' . $a['annee'] . ' de ' . $a['montant'] . ' €' ?> </option>
                      <?php
                      }
                    } else {
                      ?>
                      <option value="aucun">Aucun acompte versé </option>
                    <?php
                    }
                    ?>
                  </select>
                </div>
              </td>
              <td>
              </td>
              <td>
                <p class="text-right">Acompte</p>
              </td>
              <td><input type="text" class="form-control text-right" id="acompte" name="acompte" value="" placeholder="0.00" onblur="calculTotal()"></td>
            </tr>
            <tr>
              <td></td>
              <td>
                <div class="input-group mb-2">
                  <span class="input-group-text l-12" id="basic-addon3">Commentaire</span>
                  <input type="text" class="form-control" id="commentaire" name="commentaire" value="">
                </div>
              </td>
              <td></td>
              <td>
                <p class="text-right">A payer</p>
              </td>
              <td><input type="text" class="form-control text-right" id="apayer" name="apayer" value="0.00">
                <input type="hidden" id="numero_designation" value="<?= $i ?>">
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div><!--row  -->
  </form>
</div>

<div class="mb-5">
  <button class="btn  btn-mag-n text-danger mt-4 mb-2 " onclick="ajaxData('c=c','../src/pages/factures/factures_a_faire.php','action','attente_target');">Annuler</button>
  <button class="btn  btn-mag-n text-primary mt-4 mb-2 " onclick="factValidation();"><?= $bouton_titre . $num_pieces ?></button>
  <button id="factConf" class=" btn  btn-mag-n text-danger mt-4 mb-2" style="display:none" onclick="ajaxForm('#fact','../src/pages/factures/factures_enregistre.php','action','attente_target');"><?= "Confirmez l'édition de la pièce N° " . $num_pieces ?></button>
</div>

<div id="sous_action"></div>
<script>
  var compteur = $('#numero_designation').val();

  function factValidation() {
    $('#factConf').show();
  }

  function lignePlus() {


    var p = '<tr><td width="3%" class="text-danger" onclick="suppLigne(this)"><i class="bx bx-checkbox-minus bx-sm icon-bar"></i></td><td width="50%"><input type="hidden" id="i' + compteur + '" name="i" value="' + compteur + '"><input type="hidden" ame="numinter_' + compteur + '" id="numinter' + compteur + '" value="' + compteur + '"><input type="text" class="form-control" onblur="calculLigne(' + compteur + ');"id="designation' + compteur + '" ame="designation_' + compteur + '" value=""></td><td width="8%"><input type="text" class="form-control text-right" onblur="calculLigne(' + compteur + ');" id="quant' + compteur + '" ame="quant_' + compteur + '" value=""></td><td width="8%"><input type="text" class="form-control text-right" onblur="calculLigne(' + compteur + ');" id="pu' + compteur + '" ame="pu_' + compteur + '" value="<?= Dec_2($infos["tr"]) ?>"></td><td width="8%"><input type="text" class="form-control text-right" id="tot_' + compteur + '" name="tot_' + compteur + '" value=""><input type="hidden" id="trans' + compteur + '" name="trans_' + compteur + '" value=""></td></tr>';
    $('#corp').append(p);
    compteur = parseInt(compteur) + 1;
    $('#numero_designation').val(compteur);
  }

  function ribPlus(bank) {
    var p = '<tr><td width="3%" class="text-danger" onclick="suppLigne(this)"><i class="bx bx-checkbox-minus bx-sm icon-bar"></i></td><td width="50%"><input type="hidden" id="i' + compteur + '" name="i" value="' + compteur + '"><input type="hidden" ame="numinter_' + compteur + '" id="numinter' + compteur + '" value="' + compteur + '"><input type="text" class="form-control" onblur="calculLigne(' + compteur + ');"id="designation' + compteur + '" ame="designation_' + compteur + '" value="' + bank + '" readonly ></td><td width="8%"><input type="text" class="form-control text-right" onblur="calculLigne(' + compteur + ');" id="quant' + compteur + '" ame="quant_' + compteur + '" value="0.00" readonly></td><td width="8%"><input type="text" class="form-control text-right" onblur="calculLigne(' + compteur + ');" id="pu' + compteur + '" ame="pu_' + compteur + '" value="0.00" readonly></td><td width="8%"><input type="text" class="form-control text-right" id="tot_' + compteur + '" name="tot_' + compteur + '" value="0.00" readonly><input type="hidden" id="trans' + compteur + '" name="trans_' + compteur + '" value=""></td></td></tr>';
    $('#corp').append(p);
    transLigne(compteur);
    compteur = parseInt(compteur) + 1;
    $('#numero_designation').val(compteur);

  }

  function verseAcompte() {
    let acpt = $('#acompte_liste').val();
    let acptdec = parseFloat(acpt).toFixed(2);
    $('#acompte').val(acptdec);
    calculTotal();
  }

  function calculTotal() {
    var total = 0;
    $("input[name^='tot_']").each(function() {
      var value = parseFloat($(this).val());
      if (!isNaN(value)) {
        total += value;
      }
    });
    var acompte = $('#acompte').val();
    var totalnet = total - acompte;
    $("#total_general").val(total.toFixed(2));
    $("#apayer").val(totalnet.toFixed(2));
  }
  calculTotal();

  function suppLigne(element) {
    var ligne = $(element).closest('tr');
    var numLigne = ligne.attr('num');
    ligne.remove();
    calculTotal();
    compteur = parseInt(compteur) - 1;
    $('#numero_designation').val(compteur);
  }
  //var compteur = 1;

  function ajouterCinqJours(mydate) {
    var inputDate = document.getElementById("dateprod").value;
    var echecompte = parseInt(document.getElementById("jeche").value, 10);
    var dateParts = inputDate.split("/");
    var day = parseInt(dateParts[0], 10);
    var month = parseInt(dateParts[1], 10) - 1;
    var year = parseInt(dateParts[2], 10);
    var newDate = new Date(year, month, day + echecompte);
    var newDateFormatted = newDate.toLocaleDateString('fr-FR', {
      day: 'numeric',
      month: 'numeric',
      year: 'numeric'
    });
    $("#echeance").val(newDateFormatted);
    return newDateFormatted;
  }
  // $(function() {});// function datePicker(date) {
  $(document).ready(function() {

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
        var eche = ajouterCinqJours(dateText);
        $('#echeance').val(eche)
        $('#dateprod').val(dateText);
      }
    });
  });

  var mydate = $('#dateprod').val();
  var eche = ajouterCinqJours(mydate);
  $('#echeance').val(eche)
</script>
<?php
include $chemin . '/inc/foot.php';
?>