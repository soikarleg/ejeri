<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/error.php';

$secteur = $_SESSION['idcompte'];
$iduser =  $_SESSION['idusers'];
$data = $_POST;
$conn = new Magesquo($secteur);
$num = new FormValidation($data);
?>
<p class="">
  <?php
  $message = $num->validationContacts($data);
  //var_dump($data);
  $success = $message['success'];
  if ($success === true) {
    foreach ($message['data'] as $k => $v) {
      ${$k} = addslashes($v);
      //echo 'données '.$k . ' ' . addslashes($v) . '</br>';
    }
  ?>
    <?php
    $verification_doublons = $conn->checkClient($nom, $prenom, $adresse, $cp);
    if ($verification_doublons) {
      foreach ($verification_doublons as $key => $value) {
        ${'v_' . $key} = $value;
      }
    ?>
<p class="text-warning text-bold mb-2">La fiche N° <?= $v_idcli ?> existe déjà avec ces infos.</p>
<p><?= $v_civilite . ' ' . $v_prenom . ' ' . $v_nom ?></p>
<p><?= $v_adresse  ?></p>
<p><?= $v_cp . ' ' . $v_ville  ?></p>
<p><?= $v_email ?></p>
<p class="mb-4"><?= afficherTiret($v_telephone, $v_portable) ?></p>
<p class="btn btn-mag-n" onclick="ajaxData('idcli=<?= $v_idcli ?>', 'src/pages/contacts/contacts_fiche.php', 'target-one', 'attente_target');">Ouvrir la fiche client de <?= $v_civilite . ' ' . $v_prenom . ' ' . $v_nom ?></p>
<?php
    } else {
      //echo  $adresse = addslashes($adresse);
?>
  <div class="text-success text-bold mb-2">Client ajouté</div>
  <p class="text-muted"><?= $civilite . ' ' . $prenom . ' ' . $nom ?></p>
  <p class="text-muted"><?= stripslashes($adresse) ?></p>
  <p class="text-muted"><?= $cp . ' ' . $ville ?></p>
  <p class="text-muted mb-4"><?= afficherTiret($telephone, $portable) ?></p>
  <!-- <p class="text-muted">Type <?= ($type) ?></p>
  <p class="text-muted">Commercial <?= NomColla($commercial) ?></p>
  <p class="text-muted mb-4">Intervenant <?= NomColla($tournee) ?></p> -->
  <?php
      $datecrea = date('d-m-Y');

       $insert_client_chantierA = "INSERT INTO `client`(`idcompte`,  `civilite`, `nom`, `prenom`,  `email`, `telephone`, `portable`, `datecrea`) VALUES ('$secteur','$civilite','$nom','$prenom','$email','$telephone','$portable','$datecrea')";
      $last = $conn->handleRow($insert_client_chantierA);


      $insert_client_chantier = "INSERT INTO `client_chantier`(`idcli`,`idcompte`,   `adresse`, `cp`, `ville`, `email`, `telephone`, `portable`) VALUES ('$last','$secteur','$adresse','$cp','$ville','$email','$telephone','$portable')";
      $conn->handleRow($insert_client_chantier);

     

      $conn->insertLog('Ajout client', $iduser, $civilite . ' ' . $nom . ' ' . $prenom . ' ' . $cp . ' ' . $ville);
      //?
      //? Insertion client dans CLIENT afin d'éviter les écart entre version.
      //?

      // $insert_CLIENT = "INSERT INTO `CLIENT`(`CS`, `numero`, `datecrea`, `civilite`, `nom`, `prenom`, `adresse`, `cp`, `ville`, `civifact`, `nomfact`, `prenomfact`,  `adressfact`, `cpfact`, `villefact`, `tournee`, `commercial`, `tel`, `port`, `email`) VALUES ('$secteur','$last','$datecrea','$civilite','$nom','$prenom','$adresse','$cp','$ville','$civilite','$nom','$prenom','$adresse','$cp','$ville','$iduser','$iduser','$telephone','$portable','$email')";
      // $conn->handleRow($insert_CLIENT);
      //?
      //?---------------------------------------------------------------------
      //?

      $insert_client_adresse = "INSERT INTO `client_correspondance`(`idcompte`,`idcli`,   `adresse`, `cp`, `ville`) VALUES ('$secteur','$last','$adresse','$cp','$ville')";
      $conn->handleRow($insert_client_adresse);



      $bit = 24;
      $clef = '0_' . bin2hex(random_bytes($bit));
      $insert_client_compte = "INSERT INTO `client_compte`(`idcompte`, `idcli`, `clef`) VALUES ('$secteur','$last','$clef')";
      $conn->handleRow($insert_client_compte);

      $insert_client_infos = "INSERT INTO `client_infos`(`idcompte`, `idcli`, `id_i`,`id_c`) VALUES ('$secteur','$last','$iduser','$iduser')";
      $conn->handleRow($insert_client_infos);

      $req_cli = "SELECT *
    FROM client_chantier AS cc
    JOIN client_correspondance AS ca ON cc.idcli = ca.idcli AND cc.idcompte = ca.idcompte
    JOIN client_infos AS ci ON cc.idcli = ci.idcli AND cc.idcompte = ci.idcompte
    WHERE cc.idcli ='$last';";
      $cli = $conn->oneRow($req_cli);
      $idcli = $cli['idcli'];
  ?>
  <a href="/fiche_client?idcli=<?=$idcli?>" class="btn btn-valider">Ouvrir la fiche client de <?= $civilite . ' ' . $prenom . ' ' . $nom ?></a>
<?php
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