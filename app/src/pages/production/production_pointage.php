<?php
session_start();
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
foreach ($_POST as $k => $v) {
  ${$k} = $v;
  // echo '$' . $k . ' = ' . $v . '</br>';
}
$conn = new connBase();
$prod = new Production($secteur);



if ($marque) {
  $update_production = "UPDATE production SET factok = 'oui' WHERE numero = $numero limit 1";
  $conn->handleRow($update_production);
  $conn->insertLog('Pointage production',$iduser,$numero.' marqué facturé');
}
?>

<p class="titre_menu_item mb-2">Pointage des production déjà facturée</p>
<div class="scroll">
  <div class="row">
    <?php
    $h = $prod->getNonFacture();

    foreach ($h as $heures) {


    ?>
      <div class="col-md-4">
        <div class="border-dot p-3 mb-2" style="display:none" id="confirmation<?= $heures['numero'] ?>">
          <p class="mb-2">Confirmez-vous la réaffectation de la production du <?= $heures['jour'] . '/' . $heures['mois'] . '/' . $heures['annee'] ?> de <?= Dec_2($heures['quant'], ' heures') ?> pour <?= NomClient($heures['idcli'])   ?> en 'Facturée' ?</p>

          <p class="btn puce-mag pull-right text-success" id="note" title="Passer la production <?= $heures['numero'] ?> en 'facturée'" onclick="ajaxData('numero=<?= $heures['numero'] ?>&marque=ok', '../src/pages/production/production_pointage.php', 'action', 'attente_target');">Oui je confirme.</p>
          <p class="btn puce-mag text-danger ml-2" onclick="ajaxData('moisref=<?= $moisref ?>', '../src/pages/production/production_pointage.php', 'action', 'attente_target');">Non</p>
        </div>


        <div class="border-dot p-3 mb-2">
          
          <p><span class="text-bold"><?= $heures['nomcli']   ?></span><span class="pull-right puce-mag ml-2 mb-2"><?= Dec_2($heures['quant'], ' heures') ?></span></p>
          <p class="btn puce-mag pull-right text-warning" id="note<?= $heures['numero'] ?>" title="Passer la production <?= $heures['numero'] ?> en 'facturée'" onclick="Ouvre('#note<?= $heures['numero'] ?>','#confirmation<?= $heures['numero'] ?>');"><i class='bx bxs-cart-download icon-bar bx-flxxx'></i></p>
          <p class="text-muted small mb-3">N° <?= $heures['numero'] ?> - Production du <?= $heures['jour'] . '/' . $heures['mois'] . '/' . $heures['annee'] ?></p>



        </div>
      </div>
      <script>
        function Ouvre(btn, fen) {
          $(fen).show('fade', 500);
          $(btn).hide('fade', 500);
        }
        $(function() {
          $('#note<?= $heures['numero'] ?>').tooltip();
        });
      </script>
    <?php

    }


    //var_dump($h);
    ?>
  </div>
</div>
<script>
  $(function() {
    $('.bx, #note').tooltip();
  });
</script>