<?php
session_start();
$secteur = $_SESSION['idcompte'];
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/vendor/autoload.php';
include $chemin . '/inc/dbconn.php';
include $chemin . '/inc/function.php';
$db = Connexion();
$auth = new Delight\Auth\Auth($db);
$conn = new connBase();
$connauth = new authBase();

foreach ($_POST as $k => $v) {
  ${$k} = $v;
}

$users = $conn->askDossierIntervenant($idusers);
$color = "bg-danger";
$etat = 'Inactif';
if ($users['actif'] === 1) {
  $color = 'bg-success';
  $etat = 'Actif';
}

$emailcollabo=$users['emailcollabo'];
$identcollabo=$users['identcollabo'];

$select_auth = "select email,username from users where email='$emailcollabo' and username='$identcollabo'";
$check_auth = $connauth->oneRowAuth($select_auth);

?>
<p class="titre_menu_item mb-1"><?= $users['civilite'] . ' ' . NomColla($idusers) ?>
  <span class="puce pull-right <?= $color ?> "><?= $etat ?></span>
</p>


<p class="small text-muted mb-3"> N° <?= $users['idusers'] ?>/<?= $users['idcompte'] ?></p>
<div class="row">
  <div class="col-md-4">
    <?php
    if (!$check_auth) {
    ?>
      <p class="text-bold mb-2">Créer un compte de connexion</p>
      <div class="input-group mb-2">
        <span class="input-group-text l-12" id="basic-addon1">Identifiant</span>
        <input type="text" class="form-control" value="">
      </div>
      <div class="input-group mb-2">
        <span class="input-group-text l-12" id="basic-addon1">Email</span>
        <input type="email" class="form-control" value="<?= $users['email'] ?>">
      </div>
      <div class="input-group mb-4">
        <span class="input-group-text l-12" id="basic-addon1">Mot de passe</span>
        <input type="password" class="form-control" value="">
      </div>
    <?php
    }
    ?>
    <?php
    foreach ($users as $key => $value) {
      ${$key} = $value;
      //echo $key . ' ' . $value .'<br>';

      $key = $key === 'cp' ? 'Code Postal' : $key;
      $key = $key === 'idcompte' ? 'X' : $key;
      $key = $key === 'idusers' ? 'X' : $key;
      $key = $key === 'idauth' ? 'X' : $key;
      $key = $key === 'mail_valid' ? 'X' : $key;
      $key = $key === 'time' ? 'X' : $key;
      $key = $key === 'ratpro' ? 'X' : $key;
      $key = $key === 'identcollabo' ? 'X' : $key;
      $key = $key === 'idcompte' ? 'X' : $key;
      $key = $key === 'mdpcollabo' ? 'X' : $key;
      $key = $key === 'civilite' ? 'X' : $key;
      $key = $key === 'nom' ? 'X' : $key;
      $key = $key === 'prenom' ? 'X' : $key;
      $key = $key === 'adresse' ? 'Adresse' : $key;
      $key = $key === 'ville' ? 'Ville' : $key;
      $key = $key === 'telperso' ? 'Téléphone' : $key;
      $key = $key === 'porperso' ? 'Portable' : $key;
      $key = $key === 'actif' ? 'X' : $key;
      $key = $key === 'emailcollabo' ? 'Email' : $key;
      if ($key != 'X') {
    ?>
        <div class="input-group mb-2">
          <span class="input-group-text l-12" id="basic-addon1"><?= $key ?></span>
          <input type="text" class="form-control" placeholder="<?= $key ?>" value="<?= $value ?>">
        </div>
    <?php
      }
    }
    ?><p class="btn btn-mag-n pointer mb-5" onclick="ajaxData('cs=cs', '../src/pages/intervenants/intervenant_garde.php', 'action', 'attente_target');">Retour</p>
  </div>
  <div class="col-md-8"></div>
</div>