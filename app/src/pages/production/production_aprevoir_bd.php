<?php
session_start();
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$conn = new connBase();
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];

foreach ($_POST as $key => $value) {
  ${$key} = $value;
  //echo $key . ' ' . $value . '</br>';
}

if ($eff == 'ok') {
  $delete_previ = "DELETE FROM `prevoir` WHERE idprevi='$id'";
  $conn->handleRow($delete_previ);
}


if ($validation) {

  if ($idcli == '' || $nomcli == '' || $travaux == '' || $temps == '') {
?>
    <p class="text-danger text-bold mb-4">Demande non conforme</p>
<?php
  } else {
    $insert_previ = "INSERT INTO `prevoir`( `idcompte`, `iduser`,`travaux`, `temps`, `idcli`, `infos`) VALUES ('$secteur','$iduser','$travaux','$temps','$idcli','$infos')";
    $conn->handleRow($insert_previ);
  }
}

$prevision = "SELECT * FROM prevoir WHERE idcompte='$secteur'";
$previs = $conn->allRow($prevision);
$nbr_previs = count($previs);
//var_dump($previs);
?>
<table class="table table-hover">
  <?php
  for ($i = 0; $i < $nbr_previs; $i++) {
    $previs_infos = $previs[$i]['infos'] == '' ? '' : ' - ' . $previs[$i]['infos'];

  ?>
    <tr>

      <td><i class='bx bxs-hard-hat bx-flxxx text-primary mt-1 pointer'></i></td>
      <td><span class="puce-mag mr-2"><?= NomConn($previs[$i]['iduser']) ?></span></td>
      <td><?= NomClient($previs[$i]['idcli']) ?></td>
      <td><?= $previs[$i]['travaux'] .  $previs_infos ?></td>
      <td></td>
      <td class="text-right"><?= Dec_2($previs[$i]['temps']) ?></td>
      <td><i onclick="ajaxData('id=<?= $previs[$i]['idprevi'] ?>&eff=ok','../src/pages/production/production_aprevoir_bd.php','sub-target', 'attente_target');" class='bx bx-x-circle bx-flxxx text-danger mt-1 pointer'></i></td>
    </tr>


  <?php
  }
  ?>
</table>