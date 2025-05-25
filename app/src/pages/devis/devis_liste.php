<?php

session_start();
// error_reporting(\E_ALL);
//ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$secteur = $_SESSION['idcompte'];
//var_dump($_SESSION);
$conn = new connBase();

//var_dump($_POST);
$data = "";
$data_transfert = "";
$statut_devis = isset($_POST['statut_devis']) ? $_POST['statut_devis'] : null;
switch ($statut_devis) {
  case 'Refus':
    $r_statut_devis = "AND validite like '$statut_devis%' ";
    $data .= $r_statut_devis;
    $data_transfert .= 'validite=' . $statut_devis . '&';
    break;
  case 'Validé':
    $r_statut_devis = "AND validite like '$statut_devis' ";
    $data .= $r_statut_devis;
    $data_transfert .= 'validite=' . $statut_devis . '&';
    break;
  case 'En attente':
    $r_statut_devis = "AND validite like '$statut_devis' ";
    $data .= $r_statut_devis;
    $data_transfert .= 'validite=' . $statut_devis . '&';
    break;
  default:
    $r_statut_devis = "";
    $data .= $r_statut_devis;
    $data_transfert .= $statut_devis;
    break;
}
$nomcli = isset($_POST['nomcli']) ? $_POST['nomcli'] : null;
if ($nomcli) {
  $r_nomcli = "AND nom like '$nomcli%' ";
  $data .= $r_nomcli;
  $data_transfert .= 'nomcli=' . $nomcli . '&';
} else {
  $r_nomcli = "";
  $data .= $r_nomcli;
  $data_transfert .= $nomcli;
}
$idcli = isset($_POST['idcli']) || $_POST['idcli'] === "" ? $_POST['idcli'] : null;
if ($idcli) {
  $r_idcli = "AND id like '$idcli' ";
  $data .= $r_idcli;
  $data_transfert .= 'idcli=' . $idcli . '&';
} else {
  $r_idcli = "";
  $data .= $r_idcli;
  $data_transfert .= $idcli;
}
$numero = isset($_POST['numero']) ? $_POST['numero'] : null;
if ($numero) {
  $r_numero = "AND numero like '%$numero%' ";
  $data .= $r_numero;
  $data_transfert .= 'numero=' . $numero . '&';
} else {
  $r_numero = "";
  $data .= $r_numero;
}
$annref = isset($_POST['annref']) ? $_POST['annref'] : null;
if ($annref) {
  $r_annref = "AND annee like '$annref' ";
  $data .= $r_annref;
  $data_transfert .= 'annref=' . $annref . '&';
} else {
  $r_annref = "";
  $data .= $r_annref;
}
$moisref = isset($_POST['moisref']) || $_POST['moisref'] === "" ? $_POST['moisref'] : null;
if ($moisref) {
  $r_moisref = "AND mois like '$moisref' ";
  $data .= $r_moisref;
  $data_transfert .= 'moisref=' . $moisref . '&';
} else {
  $r_moisref = "";
  $data .= $r_moisref;
}
$term = isset($_POST['term']) ? $_POST['term'] : null;
if ($term) {
  $r_term = "AND (
    nom LIKE '%$term%'
    OR numero LIKE '%$term%'
    OR totttc LIKE '%$term%'
    OR datefact LIKE '%$term%'  
  ) ";
  $data .= $r_term;
} else {
  $r_term = "";
  $data .= $r_term;
}
?>
<div>
  <span id="affcsv" class="pull-right"></span>
</div>
<form id="datacsv">
  <input type="hidden" id="lignes_coche" name="datacsv" class="form-control" value="">
</form>
<p class="titre_menu_item mb-2">Liste des devis </p>
<div class="scroll">
  <table class="table100 table-hover" id="factures_tab">
    <thead>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
    </thead>
    <tbody>
      <?php
      // $factures = $conn->askAllFacture($secteur, $term);
      $data_transfert = rtrim($data_transfert, '&');
      $reqfacturesn = "select SUM(totttc) as mont from devisestimatif where cs='$secteur' $data ";
      $mont = $conn->oneRow($reqfacturesn);
      $reqfactures = "select * from devisestimatif where cs='$secteur' $data order by jour desc, mois asc";
      $factures = $conn->allRow($reqfactures);
      $nbrfact = count($factures);
      if ($factures != null) {
        foreach ($factures as $f) {
          $statut = $f['validite'];
          $attente = "text-muted";
          $valide = "text-muted";
          $refuse = "text-muted";
          if ($statut === "En attente") {
            $attente = "text-primary";
          };
          if ($statut === "Validé") {
            $valide = "text-success";
          };
          if ($statut === "Refusé") {
            $refuse = "text-danger";
          }
          $numero = $f['numero'];
          $id = $f['id'];
          $r = 10;
          $mail = "";
          $commercial = $f['commercial'] == "" or $f['commercial'] == null ? "" : $f['commercial'];
          if ($f['validite'] == 'Validé') {
            $payee = "<i class='bx bxs-check-circle bx-flxxx icon-bar text-success'></i>";
            $indic = '';
            $mail = "-";
          } elseif ($f['validite'] == 'En attente') {
            $payee = "<i class='bx bxs-error-circle bx-flxxx icon-bar text-primary'></i>";
            $indic = '';
            $mail = "<i title='Envoi express du devis'class='bx bx-mail-send bx-flxxx icon-bar text-primary pointer' onclick=\"$('#rel$id').fadeIn('fast', 'linear');\"></i>";
          }
          if ($r > 0 && $f['validite'] == 'Refusé') {
            $payee = "<i class='bx bxs-check-circle bx-flxxx icon-bar text-danger'></i>";
            $indic = '';
            $mail = "-";
          }
          $infos_cli = $conn->askClient($f['id']);
          $infos_cli['email'];


      ?>
          <div id="rel<?= $f['id'] ?>" class="rel" style="display:none">
            <p class="pull-right"><i class='bx bx-x bx-flxxx icon-bar text-bold text-white bx-md pointer' onclick="$('#rel<?= $f['id'] ?>').fadeOut('fast', 'linear');"></i></p>

            <?php
            $idcli = $f['id'];
            $email_cli = $conn->askClient($idcli, 'email');

            ?>
            <p><?= NomClient($f['id']) ?></p>
            <p><?= $email_cli['email']; ?></p>
            <script>
              getIframePDF('../src/pages/devis/devis_01_pdf.php?numero=<?= $numero ?>', 'pdf');
            </script>
            <div class="row">
              <div class="col-md-4">
                <p class="text-bold mb-2">Envoyer le devis par email au client</p>
                <!--  
    <p class="btn btn-mag pointer mb-2" onclick="getIframePDF('../src/pages/devis/devis_01_pdf.php?numero=<?= $numero ?>','pdf')">Afficher le document</p>-->
                <?php
                if ($client['email']) {
                  $devis_liste = $conn->askAllDevis($secteur, "and annee like '20%' ");
                  $mail_client = $conn->askClient($idcli, 'email,nom,civilite');
                  $mail_secteur = $conn->askIdcompte($secteur, 'email,nom,prenom,secteur,telephone');
                  $path = $devis->getChemin();
                  $verification_fichier = $devis->getFichier($numero);
                  if ($verification_fichier != 0) {
                    $piece_jointe = $path . $verification_fichier;
                    $piece = $verification_fichier;
                  }
                  $email_client = $mail_client['email'];
                  $nom_client = $mail_client['civilite'] . ' ' . $mail_client['nom'];
                  $email_secteur = $mail_secteur['email'];
                  $nom_secteur = NomColla($iduser);
                  $nom_entreprise = NomSecteur($secteur);
                  $message_type = "Bonjour $nom_client,\n\nVeuillez trouver votre devis en pièce jointe.\nNous restons à votre écoute pour répondre à toutes vos questions.\n\nCordialement,\n$nom_secteur\n" . $mail_secteur['telephone'] . "\n$email_secteur\n\n$nom_entreprise";
                ?>
                  <form action="" id="email_devis">
                    <div class="input-group mb-2">
                      <span class="input-group-text l-9" id="basic-addon1">Destinataire</span>
                      <input type="text" name="email_client" class="form-control" value="<?= $email_client ?>">
                    </div>

                    <input type="hidden" name="email_secteur" class="form-control" value="<?= $email_secteur ?>">

                    <div class="input-group mb-2">
                      <span class="input-group-text l-9">Message</span>
                      <textarea class="form-control" name="message" rows="8"><?= $message_type ?></textarea>
                    </div>

                    <input type="hidden" name="piece" class="form-control" value="<?= $piece ?>">
                    <input type="hidden" name="numero" class="form-control" value="<?= $numero ?>">
                    <input type="hidden" name="idcli" class="form-control" value="<?= $idcli ?>">
                    <div class="text-right">
                      <button type="reset" class="btn btn-mag-n"><i class="bx bx-reset icon-bar"></i></button>
                      <input name="Envoyer" type="button" class="btn btn-mag-n text-primary" value="Envoyer le devis" onclick="ajaxForm('#email_devis', '../src/pages/devis/devis_envoi_mail.php', 'sous-target', 'attente_target');" />
                    </div>
                  </form>
                  <div id="sous-target"></div>
                <?php
                } else {
                ?>
                  <p>Le client n'a pas d'email.</p>
                <?php
                }
                ?>
              </div>
              <div class="col-md-8">
                <iframe id="pdf" width="100%" height="600px" src=""></iframe>
              </div>
            </div>
            <tr>
              <td>
                <a target="_blank" href="../src/pages/devis/devis_bt_pdf.php?numero=<?= $f['numero'] ?>&idcli=<?= $f['id'] ?>"><i title="Bon de travail" class='bx bx-briefcase bx-flxxx bx icon-bar text-muted'></i></a>
                <a target="_blank" href="../src/pages/devis/devis_01_pdf.php?numero=<?= $f['numero'] ?>&idcli=<?= $f['id'] ?>"><i title="Devis" class='bx bxs-file-pdf bx-flxxx bx icon-bar text-danger'></i></a>
              </td>
              <td><span class="pointer"><?= NomClient($f['id']) ?></span></td>
              <td><span class="numero"><?= $f['numero'] ?></span><span class="small text-muted"> du <?= $f['jour'] . ' ' . $f['mois'] . ' ' . $f['annee'] ?></span></td>
              <td><span> <?= $f['titre'] ?></span><span class="text-muted small"> pour <?= Dec_2($f['totttc'], ' €') ?></span></td>
              <td></td>
              <td class="text-left tot"></td>
              <td class="text-center"><?= $mail ?></td>
              <td class="text-right">
                <span><i onclick="modifierStatut('<?= $f['numero'] ?>','En attente','<?= $data_transfert ?>');" title="En attente" class='<?= $attente ?> bx pointer st bx-sm bxs-time-five'></i></span>
                <span><i onclick="modifierStatut('<?= $f['numero'] ?>','Validé','<?= $data_transfert ?>');" title="Validé" class='<?= $valide ?> bx pointer st bx-sm bxs-check-circle'></i></span>
                <span><i onclick="modifierStatut('<?= $f['numero'] ?>','Refusé','<?= $data_transfert ?>');" title="Refusé" class='<?= $refuse ?> bx pointer st bx-sm bxs-x-circle'></i></span>
              </td>
              <td></td>
            </tr>
          <?php
        }
      } else {
          ?>
          <tr>
            <td class="text-danger text-bold">Aucun devis pour cette sélection</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
        <?php
      }
        ?>
    </tbody>
  </table>
</div>
<div class="border-dot mt-2 mb-2">
  <span class="mr-2" id="optioncsv" style="display:none">
    <span class="btn btn-warning text-dark py-0 px-2 mb-1 mr-1">csv</span><span class="btn btn-danger text-dark py-0 px-2 mb-1">pdf</span>
  </span>
  <span>
    <b><?= $nbrfact ?> devis</b> pour un montant total de <?= Dec_2($mont['mont'], ' €TTC') ?></span>
</div>
<script>
  function modifierStatut(numero, statut, data) {
    $.ajax({
      type: "POST", // Méthode HTTP
      url: "../src/pages/devis/devis_modifier_statut.php", // URL du script PHP pour la suppression
      data: {
        numero: numero,
        statut: statut
      }, // Données à envoyer au script PHP
      success: function(response) {
        //alert(response);
        if (response === 'success') {
          // pushSuccess('Confirmation', 'Le statut de ' + numero + ' a bien été modifié.');
          ajaxData(data, '../src/pages/devis/devis_liste.php', 'recherche', 'attente_target');
        } else {
          pushWarning('Erreur', 'La suppression ' + numero + ' a échoué !');
        }
      },
      error: function() {
        // Cette fonction sera exécutée en cas d'erreur de la requête AJAX
        pushWarning('Erreur', 'Erreur dans l\'AJAX.');
      }
    });
  }
  $(document).ready(function() {
    var selectAll = $("#checkmaster");
    var checkboxes = $(".checkslave");
    var nbrCoche = $("#nbr_coche");
    var optionCSV = $('#optioncsv');
    console.log(checkboxes);

    function updateCount() {
      var count = checkboxes.filter(':checked').length;
      console.log(count);
      nbrCoche.html(count);
      if (count > 0) {
        optionCSV.show('fade', 500);
      } else {
        optionCSV.hide('fade', 500);
      }
    }

    function calculateTotal() {
      var total = 0;
      checkboxes.filter(':checked').each(function() {
        total += parseFloat($(this).closest('tr').find('.tot').text());
      });
      $('#total').html(total.toFixed(2));
    }

    function ligneCoche() {
      var ligneCochee = "";
      checkboxes.filter(':checked').each(function() {
        ligneCochee += $(this).closest('tr').find('.numero').text() + "_";
      });
      ligneCochee = ligneCochee.slice(0, -1);
      $('#lignes_coche').val(ligneCochee);
    }
    selectAll.change(function() {
      checkboxes.prop('checked', selectAll.prop('checked'));
      updateCount();
      if (selectAll.prop('checked')) {
        $('#coche').html('Tout décocher');
        $('#optioncsv').show();
      } else {
        $('#coche').html('Tout cocher');
        $('#optioncsv').hide();
      }
      calculateTotal();
      ligneCoche();
    });
    checkboxes.change(function() {
      selectAll.prop('checked', checkboxes.length === checkboxes.filter(':checked').length);
      updateCount();
      calculateTotal();
      ligneCoche();
    });
  });
</script>