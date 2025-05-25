<?php
session_start();
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';


$data = $_POST;
$num = new FormValidation($data);
$conn = new connBase();


?>
<p class="">
  <?php

  foreach ($_POST as $k => $v) {
    ${$k} = $v;
    //echo '$' . $k . ' = ' . $v . '</br>';
  }




  $message = $num->validationNotes($data);
  $success = $message['success'];

  if ($block != "ok") {
    if ($success === true) {
      foreach ($message['data'] as $k => $v) {
        ${$k} = addslashes($v);
        // echo $k . ' ' . $v . '</br>';
      }

      $input_note = "INSERT INTO `client_notes`( `idcli`, `idcompte`, `note`,`rappel`) VALUES ('$idcli','$secteur','$note','$timestamp')";
      $conn->handleRow($input_note);
      $conn->insertLog('Ajout note client', $iduser, $note);

  ?>




    <?php
    } else {
    ?>
<p><span><i class='bx bx-error bx-lg text-danger'></i></span></p>
<?php
      foreach ($message['errors'] as $e) {
?>
  <div class="text-danger text-bold "></div>
  <p class="text-muted mb-5"><?= $e ?></p>
<?php
      }
    }
  }
?>
</p>

<div class="border-dot">
  <?php

  if ($eff == "ok") {
    $delete_note = "delete from client_notes where id='$idnote'";
    $conn->handleRow($delete_note);
  }
  $client_notes = $conn->askNotes($idcli);

  if ($client_notes) {

    foreach ($client_notes as $value) {

      $notification_rappel = $value['rappel'] === "0000-00-00 00:00:00" ? "" : "<i class='bx bxs-star icon-bar text-warning' ></i>" . AffDate($value['rappel']) . "";

  ?>
      <p class=""><i class='bx bxs-x-square icon-bar text-danger mr-4 pointer' onclick="ajaxData('idnote=<?= $value['id'] ?>&eff=ok&block=ok&idcli=<?= $idcli ?>', '../src/pages/contacts/contacts_notes_bd.php', 'sub-target', 'attente_target');"></i><span class="text-muted mr-4"><?= AffDate($value['time_maj']) . ' ' . AffHeure($value['time_maj']) ?></span><?= ucfirst($value['note']) ?> <?= $notification_rappel ?></p>
    <?php
    }
    ?>
  <?php
  } else {
  ?>
    <p>Aucune note</p>
  <?php
  }
  ?>
</div>