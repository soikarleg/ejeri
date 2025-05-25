<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];

include $chemin . '/inc/function.php';
$conn = new connBase();
session_start();
$secteur = $_SESSION['idcompte'];

foreach ($_POST as $k => $v) {
  ${$k} = $v;
  //echo '$'.$k.'= '.$v.'<br class=""> ';
};

if ($annee) {
  $annee = "and annee = '$annee'";
} else {
  $annee = '';
}
switch ($paye) {
  case 'oui':
    $paye = "and factok='oui'";
    break;
  case 'non':
    $paye = "and factok='non'";
    break;

  default:
    $paye = "";
    break;
}

$reqdevis = "select * from reglements where id='$idcli' $paye $annee and cs='$secteur' order by annee desc, mois desc, jour asc ";
$devis = $conn->allRow($reqdevis);

?>
<p class="titre_menu_item">Règlements</p>
<?php
if ($lockbar != '1') {
?>
  <ul class="nav justify-content-end">
    <!-- <li class="nav-item">
    <a class="btn btn-mag-n mr-1 mt-2 text-danger" aria-current="page" href="#" onclick="ajaxData('idcli=<?= $idcli ?>&paye=non&annee=', '../src/pages/contacts/contacts_reglements.php', 'action', 'attente_target');">Non facturée</a>
  </li> -->

    <li class="nav-item">
      <a class="btn btn-mag-n mr-1 mt-2 " aria-current="page" href="#" onclick="ajaxData('idcli=<?= $idcli ?>&paye=&annee=', '../src/pages/contacts/contacts_reglements.php', 'action', 'attente_target');">Tous</a>
    </li>
    <?php

    for ($anneeref = date('Y'); $anneeref >= date('Y') - 4; $anneeref--) { ?>



      <li class="nav-item">
        <a class="btn btn-mag-n mr-1 mt-2" aria-current="page" href="#" onclick="ajaxData('idcli=<?= $idcli ?>&paye=&annee=<?= $anneeref ?>', '../src/pages/contacts/contacts_reglements.php', 'action', 'attente_target');"><?= $anneeref ?></a>
      </li>



    <?php
    }


    ?>


    <!-- <li class="nav-item">
    <a class="btn btn-mag-n ml-2 mt-2" aria-current="page" href="#" onclick="ajaxData('idcli=<?= $idcli ?>', '../src/menus/menu_reglements.php', 'action', 'attente_target');">Inscrire un règlement</a>
  </li> -->



  </ul>
<?php
}
?>

<div class="scroll-m">


  <?php
  if ($devis) {
  ?>
    <table class="table table-hover">

      <thead>
        <tr>
          <td></td>
          <td>Numero facture</td>
          <td>Mode</td>
          <td>Date</td>

          <td>Bordereau</td>

          <td class="text-right">Montant</td>
        </tr>
      </thead>
      <tbody>
        <?php
        //var_dump($devis);


        foreach ($devis as $d) {
          $payee = $d['factok'];
          $p = "";
          if ($payee === 'oui') {
            $p = "<i class='bx bxs-check-circle text-success bx-sm' ></i>";
          }
          if ($payee === 'non') {
            $p = "<i class='bx bxs-time-five text-danger bx-sm' ></i>";
          }

        ?>

          <tr>
            <td><i title="Imprimer bordereau" class='bx bxs-file-pdf bx-sm icon-bar text-danger pointer'></i></td>
            <td><?= $d['factref']   ?></td>
            <td><?= $d['mode'] . ' ' . $d['bank'] ?></td>
            <td><?= $d['jour'] . '/' . $d['mois'] . '/' . $d['annee'] ?></td>
            <!-- <td><?= $d['travaux'] ?><span class="text-muted">  <?= $d['dettvx'] ?></span></td> -->
            <td class="l-5">
              <p class="puce-mag text-center"><?= ($d['bordereau']) ?></p>
            </td>

            <td class="text-right text-bold"><?= Dec_2($d['montant'], '') ?></td>
          </tr>





        <?php
        }

        ?>




      </tbody>
      <tfoot>

      </tfoot>
    </table>
  <?php } else {  ?>
    <p class="text-danger text-bold">Aucun réglement</p>
  <?php }  ?>



</div>