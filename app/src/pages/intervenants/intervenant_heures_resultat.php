<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';

$conn = new connBase();

session_start();
$secteur = $_SESSION['idcompte'];
$requsers = "select * from users where idcompte='$secteur' and actif='1'";
$users = $conn->allRow($requsers);
$requsersno = "select * from users where idcompte='$secteur' and actif='0'";
$usersno = $conn->allRow($requsersno);
?>



<div class="row">



  <?php
  foreach ($_POST as $k => $v) {
    ${$k} = $v;
    // echo '$'.$k.'='.$v.'</br>';
  }

  ?>
  <div class="col-md-12 mb-4">
    <div class="">
      <p class="titre_menu_item mb-2"><span class="puce-mag mr-2">N° <?= $idinter ?></span><?= NomColla($idinter) ?></p>
      <div class="scroll">
        <div class="border-dot p-1">
          <table class="table100 table-hover">
            <tr>
              <td>Mois</td>
              <td class="text-right">Heures</td>
              <td class="text-right text-muted">Non facturé</td>
              <td class="text-right text-muted">%</td>
              <td class="text-right">Total</td>
              <td class="text-right"></td>
            </tr>
            <?php
            $tour = 0;
            $tourhf = 0;
            $tourhnf = 0;
            $roll = 0;
            for ($i = 1; $i <= 12; $i++) {
              $mois_lisible = getMoisNom($i);
              $annee = date('Y');
              $mois = str_pad($i, 2, '0', STR_PAD_LEFT);
              $reqheuresAI = "SELECT
              SUM(CASE WHEN codeprod IN ('MO', 'NF') THEN quant ELSE 0 END) AS heures,
              SUM(CASE WHEN codeprod = 'MO' THEN quant ELSE 0 END) AS heures_mo,
              SUM(CASE WHEN codeprod = 'NF' THEN quant ELSE 0 END) AS heures_nf
          FROM
              production
          WHERE
              idinter = '$idinter'
              AND mois = '$mois'
              AND annee = '$annee'";
              $heures = $conn->oneRow($reqheuresAI);

              if ($heures['heures'] != 0) {
                $heures_mois = Dec_2($heures['heures']);
            ?>
                <tr>
                  <td><?= $mois_lisible . ' ' . $annee ?></td>
                  <td class="text-right"><span class="text-right small text-warning"> <?= Dec_2($heures['heures_mo'] * 48) / 1.2 ?> HT - </span><?= $hf_mois = Dec_2($heures['heures_mo']) ?></td>

                  <td class="text-right text-muted"><?= $hnf_mois = Dec_2($heures['heures_nf']) ?> </td>
                  <td class="text-right text-primary small"><?= Dec_0(($hnf_mois * 100) / $heures_mois) ?>%</td>
                  <td class="text-right"><?= $heures_mois  ?></td>
                  <td class="text-right"><a class="pointer" href="../src/pages/intervenants/intervenant_heures_pdf.php?periode=<?= $mois . '-' . date('Y') ?>&u=<?= $idinter ?>" target="_blank"><i class='bx bxs-file-pdf text-danger  bx-flxxx icon-bar'></i></a></td>
                </tr>
            <?php
                $tour = $tour + $heures_mois;
                $tourhnf = $tourhnf + $hnf_mois;
                $tourhf = $tourhf + $hf_mois;
                $roll++;
                $moyenne = $tour / $roll;
                $moyennehf = $tourhf / $roll;
                //echo $moyenne;
              }
            }
            ?>
            <tr class="text-bold">
              <td class="text-bold">Total</td>
              <td class="text-bold text-right"><?= Dec_2($tourhf) ?></td>
              <td class="text-bold text-right text-muted"><?= Dec_2($tourhnf) ?></td>
              <td class="text-bold text-right text-primary small"><?= Dec_0(($tourhnf * 100) / $tour) ?>%</td>
              <td class="text-bold text-right"><?= Dec_2($tour) ?></td>
              <td class="text-right"><a class="pointer" href="../src/pages/intervenants/intervenant_heures_pdf.php?periode=<?= 'X-' . date('Y') ?>&u=<?= $idinter ?>" target="_blank"><i data-bs-toggle="tooltip" data-bs-placement="right" title="Rapport annuel" class='bx bxs-file-pdf text-primary  bx-flxxx icon-bar'></i></a></td>
            </tr>
            <tr class="text-bold">
              <td class="text-bold">Moyenne mensuelle HF</td>
              <td class="text-bold text-right"></td>
              <td></td>
              <td></td>
              <td class="text-bold text-right"><?= Dec_0($moyennehf) ?> h/mois</td>
              <td></td>
            </tr>
            <tr class="text-bold">
              <td class="text-bold">CA potentiel mensuel moyen</td>
              <td class="text-bold text-right"></td>
              <td></td>
              <td></td>
              <td class="text-bold text-right"><?= Dec_0($moyennehf * 48) ?> €/mois</td>
              <td></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <?php

    ?>
  </div>
</div>
<?php
include '../../../inc/foot.php';
?>

<script src="../../../assets/js/script.js?=<?= time(); ?>"></script>