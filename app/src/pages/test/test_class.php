<?php
$chemin = $_SERVER['DOCUMENT_ROOT'];
//include $chemin . '/inc/error.php';
$mytable = '';
$v = '';
foreach ($_GET as $key => $value) {
  ${$key} = $value;
}
$magesquo = new MaGesquo(idcompte: $idcompte);
$mytables = $magesquo->montreTables();
$clients = new Clients(idcompte: $idcompte);
//echo 'Numéro client : ' . $clients->genereNumeroClient(recordNumber: 4664);
$user = new Users(iduser: $idusers);
$myuser = $user->showUser();
// echo '</br>';
// echo '<h4 class="text-success">' . $mytable . '</h4>';
// echo '</br>';
?>
<?php
// $verif_idcompte = $magesquo->bilanData($idcompte, 'idcompte');
// pretty('Rempli a ' . Dec_0($verif_idcompte['pourcentage'], '%'));
// //pretty($verif_idcompte['data_ok']);
// echo $magesquo->getIdcompte();
?>
<div class="row">
  <div class="col-md-12 text-justify">
    <?php
    foreach ($mytables as $row) {
    ?>
      <span class="mr-2 mb-1"><a class="text-white" href="/test?v=1&mytable=<?= $row ?>"><?= '@' . $row ?></a></span>
    <?php
    }
    ?>
  </div>
  <div class="col-md-4">
    <p class="mt-2 text-bold text-primary">Champs : <?= $mytable ?></p>
    <?php
    if ($v === '1') {
    ?>
      <div class="scroll-200 mt-2"><?= $myrows = $magesquo->montreRows($mytable); ?></div>
    <?php
    }
    ?>
  </div>
  <div class="col-md-8">
    <p class="mt-2 text-bold text-primary">Rangs : <?= $mytable ?></p>
    <div class="scroll mt-2 mb-4">
      <?php
      if ($v === '1') {
        $mycontenu = $magesquo->montreContenu($mytable);
        $modele = $magesquo->generateSQLModels($mytable, $myrows);
        foreach ($mycontenu as $c) {
      ?>
          <?= $c ?>
      <?php
        }
      }

      ?>
      <p class="mb-2 mt-2 text-bold text-primary">Modèles de SQL</p>
      <p class="mb-2 "><?= $modele['param'] ?>;</p>
      <p class="mb-2 ">$insert_<?= $mytable ?> ="<?= $modele['insert'] ?>";</p>
      <p class="mb-2 ">$update_<?= $mytable ?> ="<?= $modele['update'] ?>";</p>
      <p class="mb-2 ">$delete_<?= $mytable ?> ="<?= $modele['delete'] ?>";</p>
      <p class="mb-2 "><?= $modele['handle'] ?></p>
    </div>
  </div>
</div>