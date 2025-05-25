<?php
session_start();
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
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
  //echo '$' . $key . ' = ' . $value . '<br>';
}
// preparation des données
$datedevis_entete = explode("/", $datedevis);
$jour = $datedevis_entete[0];
$mois = $datedevis_entete[1];
$annee = $datedevis_entete[2];
$commercial_nom = NomCommercial($commercial);
$commercial_numero = $commercial;
$insert_devis_entete = "insert into devisestimatif (id,cd,numero,nom,commercial)values()";
$devis_existe = $devis->askDevisEntete($numero);
$client = $conn->askClient($idcli);
$idcompte = $conn->askIdcompte($secteur);
$validite = $idcompte['valdev'];
if ($devis_existe['numero'] != null) {
?>
  <p class=" titre_menu_item mb-2">Modification du devis N° <?= $numero ?></p>
  <?php
  $update_devisestimatif = "UPDATE devisestimatif SET id='$idcli',cs='$secteur',numero='$numero',nom='$nomcli',commercial='$commercial',titre='$titre',totttc='$totalgeneral',acompte_tx='$acompte_tx',acompte='$acompte',commentaire='$commentaire',epoque='$epoque',jour='$jour',mois='$mois',annee='$annee' WHERE numero = '$numero'";
  $conn->handleRow($update_devisestimatif);
  $conn->insertLog('Modification devis',$iduser,'Devis '.$numero.' '.$nomcli);
  for ($t = 1; $t < $i + 1; $t++) {
    $p = ${'trans_' . $t};
    $donnees = explode('_', $p);
    $numinter = $donnees[1];
    $designation = $donnees[2];
    $quant = $donnees[3];
    $pu = $donnees[4];
    $tot = $donnees[5];
    $numdev = $donnees[6];
    if ($numdev != 'X') {
      $update_devislignes = "update devislignes set designation='$designation',q='$quant',puttc='$pu',ptttc='$tot' where numdev='$numdev'";
      $conn->handleRow($update_devislignes);
    } else {
      $insert_devislignes = "insert into devislignes (id,numero,nom,commercial,designation,q,puttc,ptttc)values('$idcli','$numero','$nomcli','$commercial_numero','$designation','$quant','$pu','$tot')";
      $conn->handleRow($insert_devislignes);
    }
  }
} else {
  ?>
  <p class="titre_menu_item mb-2">Création du devis N° <?= $numero ?></p>
<?php
  $insert_devisestimatif = "INSERT INTO devisestimatif(id, cs, numero,  nom, commercial, titre, totttc,  acompte_tx, acompte, commentaire, epoque,  jour, mois, annee) VALUES ('$idcli','$secteur','$numero','$nomcli','$commercial','$titre','$totalgeneral','$acompte_tx','$acompte','$commentaire','$epoque','$jour','$mois','$annee')";
  $conn->handleRow($insert_devisestimatif);
  $conn->insertLog('Création devis',$iduser,'Devis '.$numero.' '.$nomcli);
  for ($t = 1; $t < $i + 1; $t++) {
    $p = ${'trans_' . $t};
    $donnees = explode('_', $p);
    $numinter = $donnees[1];
    $designation = $donnees[2];
    $quant = $donnees[3];
    $pu = $donnees[4];
    $tot = $donnees[5];
    $numdev = $donnees[6];
    $insert_devislignes = "insert into devislignes (id,numero,nom,commercial,designation,q,puttc,ptttc)values('$idcli','$numero','$nomcli','$commercial_numero','$designation','$quant','$pu','$tot')";
    $conn->handleRow($insert_devislignes);
  }
}
?>
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