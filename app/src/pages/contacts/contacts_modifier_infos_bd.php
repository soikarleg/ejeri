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
    echo '$' . $k . ' = ' . $v . '</br>';
  }
  $message = $num->validationInfos($data);
  $success = $message['success'];
  if ($success === true) {
    foreach ($message['data'] as $k => $v) {
      ${$k} = $v;
      //echo '$'.$k . ' = ' . $v . '</br>';
    }
  ?>
<div class="text-success text-bold mb-2">Infos modifiées</div>
<p class="text-muted">Catégorie : <?= ($categorie) ?></p>
<p class="text-muted">Type : <?= ($type) ?></p>
<p class="text-muted">Connu : <?= ($connu) ?></p>
<p class="text-muted">Commercial : <?= $ncommercial = NomColla($commercial) ?><?= ' - N° ' . $commercial ?></p>
<p class="text-muted mb-4">Intervenant : <?= $nintervenant = NomColla($tournee) ?><?= ' - N° ' . $tournee ?></p>
<script>
  ajaxData('idcli=<?= $idcli ?>', '../src/pages/contacts/contacts_fiche.php', 'target-one', 'attente_target');
</script>
<?php
    $check_infos = $conn->askClientInfos($idcli);
    $datecrea = date('d-m-Y');
    if ($check_infos) {
      $update_client = "update client_infos set categorie='$categorie', type='$type', commercial='$ncommercial',connu='$connu', intervenant='$nintervenant',id_c='$commercial',id_i='$tournee' where idcli ='$idcli'";
      $conn->handleRow($update_client);
    } else {
      $insert_infos = "INSERT INTO `client_infos`(`idcompte`, `idcli`, `type`, `datecrea`,  `id_i`, `intervenant`, `id_c`, `commercial`, `categorie`, `connu`) VALUES ('$secteur','$idcli','$type','$datecrea','$tournee','$nintervenant','$commercial','$ncommercial','$categorie','$connu')";
      $conn->handleRow($insert_infos);
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