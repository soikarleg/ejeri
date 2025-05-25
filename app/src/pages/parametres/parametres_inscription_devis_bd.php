<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
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
    ${$k} = $v;
    //echo $k . ' ' . $v . PHP_EOL;
  }

  $racine = $validation->valString($racine);



  if ($racine === "") {
    $errors = "Le tarif ne peux pas être nul.";
  } else {
    $titre = "Mise à jour des infos";
    $update_secteur = "UPDATE idcompte_infos
    SET
    dev_racine = '$racine',
    valdev = '$valdev'
    WHERE
    idcompte = '$secteur'";
    $conn->handleRow($update_secteur);
  }
  ?>
<div class="text-success text-bold mb-2"><?= $titre ?></div>
<p class="text-muted">Racine devis : <?= $racine ?></p>
<p class="text-muted">Délai de validité du devis : <?= $valdev ?></p>
<script>
  ajaxData('cs=cs', '../src/menus/menu_parametres.php', 'target-one', 'attente_target');
</script>
<?php
if ($errors) {
?>
  <p><span><i class='bx bx-error bx-lg text-danger'></i></span></p>
  <?php
  ?>
  <p class="text-muted "><?= $errors ?></p>
<?php
}
?>
</p>