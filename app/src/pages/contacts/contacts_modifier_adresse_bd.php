<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
session_start();
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
$data = $_POST;
$conn = new connBase();
$num = new FormValidation($data);
?>
<p class="">
  <?php
  $message = $num->validationCorrespondance($data);
  $success = $message['success'];
  if ($success === true) {
    foreach ($message['data'] as $k => $v) {
      ${$k} = addslashes($v);
      //echo $k . ' ' . $v . '</br>';
    }

  ?>
<div class="text-success text-bold mb-2">Fiche modifiée</div>
<p class="text-muted"><?= $mention ?></p>
<p class="text-muted"><?= $adresse ?></p>
<p class="text-muted"><?= $cp . ' ' . $ville ?></p>
<script>
  ajaxData('idcli=<?= $idcli ?>', 'https://app.enooki.com/src/pages/contacts/contacts_fiche.php', 'target-one', 'attente_target');
</script>
<?php
    $check_adresse = $conn->askClientAdresse($idcli);

    $datecrea = date('d-m-Y');
    //var_dump($check_adresse);

    if ($check_adresse) {

      $update_chantier = "update client_adresse set mention = '$mention', adressfact='$adresse', cpfact='$cp',villefact='$ville' where idcli ='$idcli'";
      $conn->handleRow($update_chantier);

      $nomcli = $conn->askClient($idcli);
      $nom = $nomcli["nom"];

      $conn->insertLog('Modification adresse', $iduser, 'Idcli : ' . $idcli . ' - ' . $nom . ', ' . $adresse . ' ' . $cp . ' ' . $ville . ' - ' . $mention);
    } else {
      $insert_chantier = "INSERT INTO `client_adresse`(`idcompte`, `idcli`, `mention`, `adressfact`, `cpfact`, `villefact`,`datecrea`) VALUES ('$secteur','$idcli','$mention','$adresse','$cp','$ville','$datecrea')";
      $conn->handleRow($insert_chantier);
    }
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