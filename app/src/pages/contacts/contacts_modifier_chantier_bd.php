<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
session_start();
$secteur = $_SESSION['idcompte'];
$data = $_POST;
$conn = new connBase();
$num = new FormValidation($data);
?>
<p class="">
  <?php
  $message = $num->validationChantier($data);
  $success = $message['success'];
  if ($success === true) {
    foreach ($message['data'] as $k => $v) {
      ${$k} = addslashes($v);
      //echo $k . ' ' . $v . '</br>';
    }

  ?>
<div class="text-success text-bold mb-2">Fiche modifiée</div>
<p class="text-muted"><?= $civilite . ' ' . $prenom . ' ' . $nom ?></p>
<p class="text-muted"><?= $adresse ?></p>
<p class="text-muted"><?= $cp . ' ' . $ville ?></p>
<script>
  ajaxData('idcli=<?= $idcli ?>', 'https://app.enooki.com/src/pages/contacts/contacts_fiche.php', 'target-one', 'attente_target');
</script>
<?php

    $update_chantier = "update client_chantier set civilite='$civilite', nom='$nom', prenom='$prenom',adresse='$adresse', cp='$cp',ville='$ville' where idcli ='$idcli'";
    $conn->handleRow($update_chantier);
  } else {
?>
  <p><span><i class='bx bx-error bx-lg text-danger'></i></span></p>
  <?php
    foreach ($message['errors'] as $e) {
  ?>
    <div class="text-danger text-bold "></div>
    <p class="text-muted "><?= $e ?></p>
<?php
    }
  }
?>
</p>