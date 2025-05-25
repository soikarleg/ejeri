<?php
session_start();
$secteur = $_SESSION['idcompte'];
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$data = $_POST;
$conn = new connBase();
$num = new FormValidation($data);
?>

<p class="">
  <?php
  $message = $num->validationIntervenants($data);
  $success = $message['success'];
  if ($success === true) {
    foreach ($message['data'] as $k => $v) {
      ${$k} = $v;
      //echo $k . ' ' . $v . '</br>';
    }
  ?>
<div class="text-success text-bold mb-2">Client ajouté</div>
<p class="text-muted"><?= $civilite . ' ' . $prenom . ' ' . $nom ?></p>
<p class="text-muted"><?= $adresse ?></p>
<p class="text-muted"><?= $cp . ' ' . $ville ?></p>
<p class="text-muted mb-4"><?= Tel($portable) ?></p>
<p class="text-muted"><?= ($email) ?></p>
<p class="text-muted mb-4">Statut : <?= ($statut) ?></p>
<?php
    echo $insert_user = "INSERT INTO users(idcompte, civilite, nom, prenom, adresse, cp, ville, porperso,  actif, emailcollabo, statut) VALUES ('$secteur','$civilite','$nom','$prenom','$adresse','$cp','$ville','$portable','1','$email','$statut')";
    $last = $conn->handleRow($insert_user);
    echo $last;
?>
<p class="btn btn-mag-n" onclick="ajaxData('idusers=<?= $last ?>','../src/pages/intervenants/intervenant_dossier.php','action','attente_target');">Ouvrir la fiche intervenant de <?= $civilite . ' ' . $prenom . ' ' . $nom ?></p>

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