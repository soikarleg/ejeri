<?php
error_reporting(\E_ALL);
ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
//include $chemin . '/myclass/flxxx/src/FormValidation.php';
include $chemin . '/inc/function.php';
session_start();
$secteur = $_SESSION['idcompte'];
$data = $_POST;
$conn = new connBase();
$validation = new FormValidation($data);
?>

<p class="">
  <?php

  foreach ($data as $k => $v) {
    echo $k . ' ' . $v . PHP_EOL;
  }


  $message = $validation->validationCompte($data);
  $success = $message['success'];
  if ($success === true) {
    foreach ($message['data'] as $k => $v) {
      ${$k} = $v;
      echo $k . ' ' . $v . PHP_EOL;
    }

    $sap = $sap === null ? "non" : "oui";


    $req_verif = "select * from idcompte_infos where idcompte='$secteur'";
    $verif = $conn->oneRow($req_verif);

    if (!empty($verif)) {
      $titre = "Mise à jour des informations";
      $update_secteur = "UPDATE `idcompte_infos`
  SET
    `secteur` = '$nom',
    `denomination` = '$denomination',
    `nic` = '$nic',
    `naf` = '$naf',
    `adresse` = '$adresse',
    `cp` = '$cp',
    `ville` = '$ville',
    `telephone` = '$telephone',
    `email` = '$email',
    `siret` = '$siret',
    `statut` = '$juridique',
    `agre`='$agre',
    `sap`='$sap'
  WHERE
    `idcompte` = '$secteur';";
      $conn->handleRow($update_secteur);
    } else {
      $titre = "Insertion des informations";
      $insert_secteur = "INSERT INTO `idcompte_infos`(`secteur`, `denomination`, `nic`, `naf`,`adresse`, `cp`, `ville`, `telephone`, `email`, `idcompte`, `siret`,`statut`,`agre`,`sap`) VALUES ('$nom','$denomination','$nic','$naf','$adresse','$cp','$ville','$telephone','$email','$secteur','$siret','$juridique','$agre','$sap')";

      $conn->handleRow($insert_secteur);
    }




  ?>



<div class="text-success text-bold mb-2"><?= $titre ?></div>

<p class="text-muted"><?= stripslashes($nom) . ' ' . $juridique  ?></p>
<p class="text-muted"><?= $denomination ?></p>

<p class="text-muted"><?= $adresse ?></p>
<p class="text-muted"><?= $cp . ' ' . $ville ?></p>
<p class="text-muted"><?= Tel($telephone) ?></p>
<p class="text-muted mb-4"><?= $email ?></p>
<p class="text-muted"><?= 'SIRET ' . $siret . '/ SIREN ' . $siret . $nic ?></p>
<p class="text-muted"><?= 'Code NAF ' . $naf ?></p>
<p class="text-muted"><?= 'N° agrément ' . $agre ?></p>
<p class="text-muted"><?= 'SAP ' . $sap ?></p>

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