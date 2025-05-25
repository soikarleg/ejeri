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

$reqdevis = "select * from production where idcli='$idcli' $paye $annee and idcompte='$secteur' order by annee desc, mois desc, jour asc ";
$devis = $conn->allRow($reqdevis);

?>
<p class="titre_menu_item">Productions</p>
<ul class="nav justify-content-end">
  <li class="nav-item">
    <a class="btn btn-mag-n mr-1 mt-2" aria-current="page" href="#" onclick="ajaxData('idcli=<?= $idcli ?>&paye=non&annee=', '../src/pages/contacts/contacts_production.php', 'action', 'attente_target');">Non facturée</a>
  </li>

  <li class="nav-item">
    <a class="btn btn-mag-n mr-1 mt-2 " aria-current="page" href="#" onclick="ajaxData('idcli=<?= $idcli ?>&paye=&annee=', '../src/pages/contacts/contacts_production.php', 'action', 'attente_target');">Toutes</a>
  </li>
  <?php

  for ($anneeref = date('Y'); $anneeref >= date('Y') - 3; $anneeref--) { ?>



    <li class="nav-item">
      <a class="btn btn-mag-n mr-1 mt-2" aria-current="page" href="#" onclick="ajaxData('idcli=<?= $idcli ?>&paye=&annee=<?= $anneeref ?>', '../src/pages/contacts/contacts_production.php', 'action', 'attente_target');"><?= $anneeref ?></a>
    </li>



  <?php
  }


  ?>


  <li class="nav-item">
    <a class="btn btn-mag-n ml-2 mt-2" aria-current="page" href="#" onclick="ajaxData('idcli=<?= $idcli ?>', '../src/pages/factures/factures_nouveau.php', 'action', 'attente_target');">Inscrire une production</a>
  </li>



</ul>


<div class="scroll-m">


  <?php
  if ($devis) {
  ?>
    <table class="table table-hover">

      <thead>
        <tr>
          <td><input type="checkbox" name="" id=""></td>
          <td>Numero</td>
          <td>Date</td>
          <td>Titre</td>
          <td>Intervenant</td>
          <td>Facturée</td>
          <td class="text-right">Durée</td>
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
            <td><input type="checkbox" name="" id=""></td>
            <td><?= $d['numero'] ?></td>
            <td><?= $d['jour'] . '/' . $d['mois'] . '/' . $d['annee'] ?></td>
            <td><?= $d['travaux'] ?><span class="text-muted"> / <?= $d['dettvx'] ?></span></td>
            <td>
              <p class="puce-bottom l-5 text-center"><?= NomConn($d['idinter']) ?></p>
            </td>
            <td class="l-3"><?= $p ?></td>
            <td class="text-right"><?= Dec_2($d['quant'], '') ?></td>
          </tr>





        <?php
        }

        ?>




      </tbody>
      <tfoot>

      </tfoot>
    </table>
  <?php } else {  ?>
    <p class="text-danger text-bold mt-2">Aucune production</p>
  <?php }  ?>



</div>