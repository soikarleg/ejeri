<?php
$chemin = $_SERVER['DOCUMENT_ROOT'];
//include $chemin . '/inc/error.php';
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
$conn = new Magesquo($secteur);
$dataClient = new Clients($secteur);
$dataChantiers = new Chantiers($secteur);
$purge = new DataValidator();
$nom = "";
$prenom = "";
$civilite = "";
$email = "";
$telephone = "";
$portable = "";
$passcli = "";
$action = '';
foreach ($_GET as $k => $v) {
  ${$k} = $v;
  //echo '$' . $k . '= ' . $v . '<br class=""> ';
};
$ajouter_chantier = "";
$modifier_infos = "";
foreach ($_POST as $k => $v) {
  ${$k} = $purge->purgeStr($v);
  // echo '$' . $k . '= ' . $v . '<br class=""> ';
};
if ($ajouter_chantier == md5('ajouter_chantier') and $csrf_token == $_SESSION['csrf_token']) {
  $params = ['idcli' => $idcli, 'idcompte' => $secteur, 'adresse' => $adresse, 'ville' => $ville, 'cp' => $cp, 'email' => $email, 'telephone' => $telephone, 'portable' => $portable];
  $insert_client_chantier = "INSERT INTO `client_chantier`(`idcli`,`idcompte`,   `adresse`, `cp`, `ville`, `email`, `telephone`, `portable`) VALUES (:idcli,:idcompte,:adresse,:cp,:ville,:email,:telephone,:portable)";
  $magesquo->handleRow($insert_client_chantier, $params, 'Ajout chantier', $iduser, $adresse . ' ' . $cp . ' ' . $ville);
}
if ($modifier_infos == md5('modifier_infos') and $csrf_token == $_SESSION['csrf_token']) {
  $params = ['idcli' => $idcli, 'idcompte' => $idcompte, 'id_i' => $intervenant, 'id_c' => $commercial, 'type' => $type, 'categorie' => $categorie, 'connu' => $connu];
  $update_client_infos = "UPDATE client_infos SET idcompte=:idcompte,id_i=:id_i,id_c=:id_c,type=:type,categorie=:categorie,connu=:connu WHERE idcli=:idcli";
  $magesquo->handleRow($update_client_infos, $params, 'Modifier infos', $iduser, $categorie . ' ' . $type . ' ' . $connu . ' ' . $commercial . ' ' . $intervenant . ' pour ' . $dataClient->showNomClient($idcli));
}
$factures =  new  Factures($secteur);
$factures_attente = $factures->askFacturesAttenteClient($idcli);
if ($factures_attente) {
  $note = '<span class="notification-mark"></span>';
} else {
  $note = '';
}
$nom = $dataClient->nomClient($idcli);
foreach ($nom as $k => $v) {
  ${$k} = is_array($v) ? $v : stripslashes($v !== null ? $v : '');
  //echo '$' . $k . ' = ' . $v . '<br>';
}
$infos = $dataClient->infosClient($idcli);
foreach ($infos as $k => $v) {
  ${$k} = is_array($v) ? $v : stripslashes($v !== null ? $v : '');
  //echo '$' . $k . ' = ' . $v . '<br>';
}
$correspondance = $dataClient->correspondanceClient($idcli);
foreach ($correspondance as $k => $v) {
  ${$k} = is_array($v) ? $v : stripslashes($v !== null ? $v : '');
  //echo '$' . $k . ' = ' . $v . '<br>';
}
$chantiers = $dataChantiers->chantierClient($idcli);
foreach ($chantiers as $chantier) {
  foreach ($chantier as $k => $v) {
    ${$k} = is_array($v) ? $v : stripslashes($v !== null ? $v : '');
    //echo '$'.$k . ' = ' . $v . '<br>';
  }
}
// $idcompte = B24003
// $idcli = 104
// $civilite = M. et Mme
// $nom = GORDAS
// $prenom = Michel
// $email = francois@ejeri.fr
// $telephone = 0238451578
// $portable = 0665677503
// $datecrea = 08-03-2025
// $time_maj = 2025-03-08 20:06:49
// $idcompte = B24003
// $idcli = 104
// $actif = 1
// $id_i = 3
// $id_c = 3
// $type =
// $categorie =
// $connu =
// $time_maj = 2025-03-08 00:14:47
// $correspondance_mention = 
// $correspondance_idcli = 104
// $correspondance_idcompte = B24003
// $correspondance_adresse = 41 Rue Colbert
// $correspondance_cp = 59800
// $correspondance_ville = Lille
// $chantier_id = 4
// $chantier_idcli = 104
// $chantier_idcompte = B24003
// $chantier_adresse = 41 Rue Colbert
// $chantier_cp = 59800
// $chantier_ville = Lille
// $chantier_id = 5
// $chantier_idcli = 104
// $chantier_idcompte = B24003
// $chantier_adresse = 5 chemin de la Messe
// $chantier_cp = 45740
// $chantier_ville = Lailly-en-Val
if ($idcli === "") {
  $nomcli = 'Aucun client avec cet ID';
  $idcli = "<i class='bx bx-user-x bx-flxxx icon-bar'></i>";
  $passcli = 'XXXX';
}
// $params = ['idcli' => $idcli, 'idcompte' => $secteur];
// $client_suivant = "SELECT MIN(idcli) AS suivant FROM client WHERE idcompte = :idcompte AND idcli > :idcli;";
// $suivant = $conn->oneRow($client_suivant, $params);
// //pretty($suivant);
// $client_precedent = "SELECT MAX(idcli) AS precedent FROM client WHERE idcompte = :idcompte AND idcli < :idcli;";
// $precedent = $conn->oneRow($client_precedent, $params);
// //pretty($precedent);
// $nomcli = $civilite . ' ' . $prenom . ' ' . $nom . ' - N° ' . $idcli;
?>
<!-- <div class="bandeau_fiche_client">
  <span class="titre_menu_item ml-2 mr-4"><?= $nomcli ?></span>
  <div class="vertical-align-middle pull-right" style="margin-top:5px">
    <?php if (!empty($precedent['precedent'])): ?>
      <a href="fiche_client?idcli=<?= $precedent['precedent'] ?>" class='bx bxs-left-arrow pointer' title="Client précédent N° <?= $precedent['precedent'] ?>"></a>
    <?php else: ?>
      <span class="vertical-align-middle"><i class='bx bxs-error-circle text-warning'></i></span>
    <?php endif; ?>
    <span class="puce-tab vertical-align-middle"><small>N° <?= $idcli ?></small></span>
    <?php if (!empty($suivant['suivant'])): ?>
      <a href="fiche_client?idcli=<?= $suivant['suivant'] ?>" class='bx bxs-right-arrow pointer mr-4' title="Client suivant N° <?= $suivant['suivant'] ?>"></a>
    <?php else: ?>
      <span class="vertical-align-middle mr-4"><i class='bx bxs-error-circle text-danger'></i></span>
    <?php endif; ?>
  </div>
</div> -->
<?php
if ($passcli == "XXXX") {
  echo $pas_de_client;
} else {
?>
  <div class="row">
    <div class="col-md-2">
      <div class="border-fiche mb-2 ">
        <a href="fiche_client?idcli=<?= $idcli ?>&action=correspondance_modification" class="small pull-right">Modifier</a>
        <p class="text-bold text-primary">Correspondance </p>
        <p class=""><?= stripslashes($correspondance_mention) ?></p>
        <p><?= $correspondance_adresse ?></p>
        <p><?= $correspondance_cp . ' ' . $correspondance_ville ?></p>
      </div>
      <div class="border-fiche mb-2">
        <div class="text-left">
          <?php
          $gravatar = hash('sha256', strtolower(trim($email)));
          $email = ($email === '') ? "Pas d'email" : $email;
          ?>
          <a href="fiche_client?idcli=<?= $idcli ?>&action=telemail_modification" class="small pull-right">Modifier</a>
          <p class="text-bold text-primary">Données de contact </p>
          <p class="mb-2"><?= afficherTiret('', Tel($portable))   ?></p>
          <p class="mb-2"><?= afficherTiret(Tel($telephone), '')   ?></p>

          <!-- pointer puce-mag bg-gradient -->
          <a href="" class=""><?= $email ?> </a><img class="rounded" src="https://gravatar.com/avatar/<?= $gravatar ?>?s=20" />
        </div>
      </div>
      <!-- <div class="border-fiche mb-2 ">
        <?php
        $i = 0;
        foreach ($chantiers as $c) {
          foreach ($c as $key => $value) {
            //echo $key . ' ' . $value . '*** ';
          }
          $i++;
        ?>
          <a href="fiche_client?idcli=<?= $idcli ?>&action=chantier_modification&idchantier=<?= $c['chantier_id'] ?>" class="small pull-right">Modifier</a>
          <p class="text-bold text-primary">Chantier N° <?= $c['chantier_idcli'] . '-' . $i ?></p>
          <p><?= $c['chantier_adresse'] ?></p>
          <p class="mb-2"><?= $c['chantier_cp'] . ' ' . $c['chantier_ville'] ?></p>
        <?php
        }
        ?>
        <a href="fiche_client?idcli=<?= $idcli ?>&action=chantier_plus">Ajouter un chantier</a>
      </div> -->
      <?php
      $bg = ($actif === '1') ? "bg-tb-c" : "bg-tb-b";
      ?>
      <div class=" border-fiche <?= $bg ?>">
        <p class="mb-2">
          <a href="fiche_client?idcli=<?= $idcli ?>&action=infos_modification" class="small pull-right text-white">Modifier</a>
        <div class="text-left">
          <?php
          $actif = ($actif === '1') ? "OK" : "Off";
          ?>
          <p class=" "><?= $categorie ?></p>
          <p class=" "><?= $type ?></p>
          <p class=" "><?= Tronque($connu, '8') ?></p>
          <div class="text-right">
            <p class="small mt-2"><i class='bx bxs-user-voice icon-bar'></i><?= NomColla($id_c) . ' ' . $id_c ?></p>
            <p class="small"><i class='bx bxs-user-detail icon-bar'></i> <?= NomColla($id_i) . ' ' . $id_i ?></p>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-10 ">
      <div class="">
        <?php
        switch ($action) {
          case 'chantier_plus':
            include $chemin . '/src/pages/contacts/contacts_ajouter_chantier.php';
            break;
          case 'correspondance_modification':
            include $chemin . '/src/pages/contacts/contacts_modifier_adresse.php';
            break;
          case 'contacts_devis':
            include $chemin . '/src/pages/contacts/contacts_devis.php';
            break;
          case 'contacts_chantiers':
            include $chemin . '/src/pages/contacts/contacts_chantiers.php';
            break;
          case 'telemail_modification':
            include $chemin . '/src/pages/contacts/contacts_modifier_telemail.php';
            break;
          case 'infos_modification':
            include $chemin . '/src/pages/contacts/contacts_modifier_infos.php';
            break;
          case 'chantier_modification':
            include $chemin . '/src/pages/contacts/contacts_modifier_chantier.php';
            break;
          default:
            include $chemin . '/src/pages/contacts/contacts_bilan.php';
            break;
        }
        ?>
      </div>
    </div>
    <!-- <div class="col-md-3 ">
      <div class="border-fiche">
      </div>
    </div> 
    <div class=" col-md-3 ">
     
    </div>-->
  </div>
  <div class="mt-4">
    <div id="action" class="mt-bot">
    </div>
  </div>
  <script>
    $(function() {
      $('.bx').tooltip();
    });
  </script>
<?php
}
?>