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

  foreach ($data as $k => $v) {
    //echo '$' . $k . ' = ' . $v . '</br>';
  }

  $message = $num->validationTelemail($data);
  $success = $message['success'];
  if ($success === true) {
    foreach ($message['data'] as $k => $v) {
      ${$k} = $v;
      //echo '$'.$k . ' = ' . $v . '</br>';
    }

    echo $update_client = "update client_chantier set email='$email', telephone='$telephone',portable='$portable' where idcli ='$idcli'";

    $conn->handleRow($update_client);

  ?>
<div class="text-success text-bold mb-2">Infos modifiées</div>
<p class="text-muted">Portable : <?= ($portable) ?></p>
<p class="text-muted">Fixe : <?= ($telephone) ?></p>
<p class="text-muted">Email : <?= ($email) ?></p>

<script>
  ajaxData('idcli=<?= $idcli ?>', 'https://app.enooki.com/src/pages/contacts/contacts_fiche.php', 'target-one', 'attente_target');
</script>

<?php

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