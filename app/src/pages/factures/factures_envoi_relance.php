<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
session_start();
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$data = $_POST;
$conn = new connBase();
$ins = new FormValidation($data);
$facture = new Factures($secteur);

$infos_brutes = $_POST['infos'];
$infos_brutes = $facture->infoPropre($infos_brutes);


foreach ($_POST as $key => $value) {
  ${$key} = $ins->valFull($value);
  //echo  '$' . $key . ' = ' . $value . '<br>';
}
?>
<script>
  getIframePDF('../src/pages/factures/factures_relance_01_pdf.php?numero=<?= $numero ?>&idcli=<?= $idcli ?>', 'pdf');
</script>
<div class="row">
  <p class="titre_menu_item mb-2 text-warning">Relance pour <?= NomClient($idcli) ?> <span class="text-bold text-primary pointer pull-right mb-2" onclick="getIframePDF('../src/pages/factures/factures_relance_01_pdf.php?numero=<?= $numero ?>&idcli=<?= $idcli ?>','pdf')">Réafficher le document</span></p>
  <div class="scroll">
    <div class="col-md-12">
      <div class="mb-3">
        <p class="text-bold">Relances déjà édités</p>
        <?php
        $repertoire = $facture->getCheminRelance($idcli);
        // Spécifiez le chemin du répertoire que vous souhaitez lister
        //'../../../documents/pdf/relances/' . $secteur;
        // Utilisez scandir() pour obtenir la liste des fichiers et répertoires
        $contenuRepertoire = scandir($repertoire);
        // Parcourir la liste et afficher les fichiers et les répertoires
        foreach ($contenuRepertoire as $element) {
          // Exclure les entrées "." et ".."&& $element == "Relance_" . $idcli . ".pdf"
          if ($element != "." && $element != "..") {
            // Afficher le nom de l'élément
            $f = 'https://app.enooki.com/documents/pdf/relances/' . $secteur . '/client_' . $idcli . '/' . $element;
        ?>

            <span class="puce-mag pointer" onclick="getDocPDF('<?= $f ?>','_blank');"><?= $element ?></span>
            <?php

            ?>

        <?php

          }
        }
        ?>
      </div>
    </div>
    <div class=" row">
      <div class="col-md-5">
        <?php
        $commentaire;
        $designation;
        $titre;
        $quant;
        $pu;
        $acompte;
        $datefact_entete = explode("/", $datefact);
        $jen = $datefact_entete[0];
        $men = $datefact_entete[1];
        $aen = $datefact_entete[2];
        $datefact_entete = $aen . '-' . $men . '-' . $jen;
        $dateche_entete = explode("/", $dateche);
        $jec = $dateche_entete[0];
        $mec = $dateche_entete[1];
        $aec = $dateche_entete[2];
        $dateche_entete = $aec . '-' . $mec . '-' . $jec;
        $verification_facture = $conn->askFactureNum($secteur, $numero);
        if (!$verification_facture) {
          // Enregistrement de l'entete
          $insert_entete = "INSERT INTO `facturesentete`(`id`, `datefact`, `dateche`, `factav`, `cs`, `numero`, `nom`, `commercial`, `devref`, `titre`, `totttc`, `txtva`, `commentaire`, `jour`, `mois`, `annee`, `paye`, `echejour`, `echemois`, `echeannee`)
    VALUES ('$idcli','$datefact_entete','$dateche_entete','$factav','$secteur','$numero','$nomcli','$commercial','$devref','$titre','$apayer','$tva','$commentaire','$jen','$men','$aen','non','$jec','$mec','$aec')";
          $conn->handleRow($insert_entete);
        } else {
          $la_facture_existe = "<i class='bx bxs-badge-check icon-bar text-primary'></i>";
        }
        // Enregistrement des lignes
        for ($t = 1; $t < $i + 1; $t++) {
          $p = ${'trans_' . $t};
          $donnees = explode('_', $p);
          $numinter = $donnees[1];
          $designation = $donnees[2];
          $quant = $donnees[3];
          $pu = $donnees[4];
          $tot = $donnees[5];
          $date_ligne = str_replace("/", "-", $datefact);
          if ($numinter) {
            $insert_ligne = "INSERT INTO `factureslignes`(`cs`, `id`, `nom`, `numero`, `commercial`, `numinter`, `designation`, `q`, `puttc`, `ptttc`, `date`)
    VALUES ('$secteur','$idcli','$nomcli','$numero','$commercial','$numinter','$designation','$quant','$pu','$tot','$date_ligne')";
            $conn->handleRow($insert_ligne);
            $update_prod = "UPDATE `production` SET `factok`='oui' WHERE `numero` = '$numinter'";
            $conn->handleRow($update_prod);
            $update_inter = "UPDATE `INTERVENTION` SET `factok`='oui' WHERE `numero` = '$numinter'";
            $conn->handleRow($update_inter);
          }
        }
        $facture_entete = $conn->askFactureNum($secteur, $numero);
        $facture_lignes = $conn->askFactureLigne($secteur, $numero);
        $id = $facture_entete['id'];
        $client = $conn->askClient($id);
        ?>
        <p class="text-bold mb-2">Envoyer la relance par email au client <span><?= $la_facture_existe ?></span></p>
        <?php
        if ($client['email']) {
          $devis_liste = $conn->askAllDevis($secteur, "and annee like '20%' ");
          $mail_client = $conn->askClient($idcli, 'email,nom,civilite');
          $mail_secteur = $conn->askIdcompte($secteur, 'email,nom,prenom,secteur,telephone');
          $path = $facture->getChemin();
          $verification_fichier = $facture->getFichier($numero);
          if ($verification_fichier != 0) {
            $piece_jointe = $path . $verification_fichier;
            $piece = $verification_fichier;
          }
          $email_client = $mail_client['email'];
          $nom_client = $mail_client['civilite'] . ' ' . $mail_client['nom'];
          $email_secteur = $mail_secteur['email'];
          $nom_secteur = NomColla($iduser);
          $nom_entreprise = NomSecteur($secteur);
          $message_type = "Bonjour $nom_client,\n\nPouvez-vous nous confirmer avoir reçu la facturation suivante :\n\n$infos_brutes\n\nNous ne parvenons pas à rapprocher votre règlement sur nos relevés bancaire. Nous restons à votre écoute pour répondre à toutes vos questions.\n\nCordialement,\n$nom_secteur\n" . $mail_secteur['telephone'] . "\n$email_secteur\n\n$nom_entreprise";
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
            <input type="hidden" name="piece" class="form-control" value="<?= $f ?>">
            <input type="hidden" name="infos" class="form-control" value="<?= $infos_brutes ?>">
            <input type="hidden" name="numero" class="form-control" value="<?= $numero ?>">
            <input type="hidden" name="idcli" class="form-control" value="<?= $idcli ?>">
            <div class="text-right">
              <button type="reset" class="btn btn-mag-n"><i class="bx bx-reset icon-bar"></i></button>
              <input name="Envoyer" type="button" class="btn btn-mag-n text-primary" value="Envoyer la relance" onclick="ajaxForm('#email_devis', '../src/pages/factures/facture_envoi_mail_relance.php', 'sous-target', 'attente_target');" />
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
      <div class="col-md-7">
        <p class="text-bold mb-2">Sauvegarder et/ou imprimer la relance <span><?= $la_facture_existe ?></span></p>
        <iframe id="pdf" width="100%" height="600px" src=""></iframe>
      </div>
    </div>
  </div>
</div>