<?php
error_reporting(\E_ALL);
ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
//include $chemin . '/myclass/flxxx/src/FormValidation.php';
include $chemin . '/inc/function.php';
session_start();
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
$data = $_POST;
$conn = new connBase();
$validation = new FormValidation($data);
?>

<p class="">
  <?php

  foreach ($data as $k => $v) {
    echo $k . ' ' . $v . PHP_EOL;
  }


  $message = $validation->validationReferent($data);
  $success = $message['success'];
  if ($success === true) {
    foreach ($message['data'] as $k => $v) {
      ${$k} = $v;
      echo '$' . $k . ' = ' . $v . PHP_EOL;
      echo '<br>';
    }




    $req_verif = "select * from users_infos where id='$iduser'";
    $verif = $conn->oneRow($req_verif);

    if (!empty($verif)) {
      $titre = "Mise à jour des informations";
      $update_secteur = "UPDATE `users_infos`
  SET
  `nom` = '$nom',
  `prenom` = '$prenom',
  `civilite` = '$civilite',
    `adresse` = '$adresse',
    `cp` = '$cp',
    `ville` = '$ville',
    `telephone` = '$telephone',
    `email` = '$email',
    `statut` = '$statut'


  WHERE
    `idauth` = '$iduser'";
      $conn->handleRow($update_secteur);
    } else {
      $titre = "Insertion des informations";
      //echo $insert_secteur = "INSERT INTO `users_infos`(`idauth`,`idcompte`,``) VALUES ('$iduser','$secteur','$nic','$naf','$adresse','$cp','$ville','$telephone','$email','$secteur','$siret','$juridique')";

      //$conn->handleRow($insert_secteur);
    }




  ?>



<div class="text-success text-bold mb-2"><?= $titre ?></div>

<p class="text-muted"><?= $civilite . ' ' . $nom . ' ' . $prenom ?></p>
<p class="text-muted"><?= $adresse ?></p>
<p class="text-muted"><?= $cp . ' ' . $ville ?></p>
<p class="text-muted"><?= Tel($telephone) ?></p>
<p class="text-muted mb-4"><?= $email ?></p>
<script>
  // ajaxData('cs=cs', '../src/menus/menu_parametres.php', 'target-one', 'attente_target');
</script>
<?php




  } else {
?>
  <p><span><i class='bx bx-error bx-lg text-danger'></i></span></p>
  <?php


    foreach ($message['data'] as $k => $v) {
      ${$k} = $v;
      echo $k . ' ' . $v . PHP_EOL;
    }
    foreach ($message['errors'] as $e) {
  ?>
    <p class="text-muted "><?= $e ?></p>
<?php
    }
  }
?>
</p>