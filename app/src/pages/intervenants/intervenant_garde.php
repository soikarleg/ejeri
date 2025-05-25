<?php
error_reporting(\E_ALL);
ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
session_start();
$secteur = $_SESSION['idcompte'];
$conn = new connBase();
?>
<?php
$requsers_actif = "select * from users where idcompte='$secteur' and actif='1' order by idusers asc ";
$users_actif = $conn->allRow($requsers_actif);
$requsers_inactif = "select * from users where idcompte='$secteur' and actif='0' order by idusers asc ";
$users_inactif = $conn->allRow($requsers_inactif);
?>
<div class="row">
  <div class="col-md-12">
    <p class="titre_menu_item mb-2">Intervenants actifs</p>

    <div class="row mb-4">
      <?php
      $u = "";
      foreach ($users_actif as $c) {
      ?>
        <div class="col-md-4">
          <div class="border-dot mb-2 p-2">
            <p class="pull-right text-primary text-bold small mr-1 mb-2"> <i class='bx bx-link-external bx-flxxx icon-bar pointer' onclick="ajaxData('idusers=<?= $c['idusers'] ?>', '../src/pages/intervenants/intervenant_dossier.php', 'action', 'attente_target');"></i></p>
            <p class="mb-2"><span class="puce-mag mr-1">N° <?= $c['idusers'] ?></span><?= ($c['prenom']) ?> <?= ($c['nom']) ?></p>
            <?php
            $u .= $c['idusers'];
            $u .= "-";
            $moisref = date('m') == 01 ? 12 : date('m') - 1;
            $annref = $moisref == 12 ? date('Y') - 1 : date('Y');
            //        echo $date_mois = date('m');

            //          $date_annee = date('Y');
            $date_complete = $moisref . '/' . $annref;
            $periode = $moisref . '-' . $annref;
            ?>
            <a href=" ../src/pages/intervenants/intervenant_heures_pdf.php?periode=<?= $periode ?>&u=<?= $c['idusers'] ?>" target="_blank" class="text-muted"><i class='bx bxs-file-pdf bx-flxxx icon-bar text-danger'></i> Rapport mensuel individuel <?= $date_complete ?></a>
          </div>
        </div>
      <?php
      }
      ?>
    </div>
    <?php
    // $u = rtrim($u, '-');
    // $date_mois = date('m') - 1;
    // $date_annee = date('Y');
    // $date_complete = $date_mois . '/' . $date_annee;
    // $periode = $date_mois . '-' . $date_annee;
    ?>
    <ul class="nav">

      <li class="nav-item">
        <a href=" ../src/pages/intervenants/intervenant_heures_pdf.php?periode=<?= $periode ?>&u=<?= $u ?>" target=" _blank" class="btn btn-mag-n mr-1 " id="intervenant_sous_menu"><i class='bx bxs-file-pdf bx-flxxx icon-bar'></i> Rapport mensuel <?= $date_complete  ?>
        </a>
      </li>


      <li class="nav-item">
        <a href="https://app.enooki.com/src/pages/intervenants/intervenant_rapport_complet_pdf.php?secteur=<?= $secteur ?>&annee=<?= $annref ?>&ca=0" class="btn btn-mag-n mr-1" target="_blank"><i class='bx bxs-file-pdf bx-flxxx icon-bar'></i> Rapport annuel <?= $annref ?></a>
      </li>
      <li class="nav-item">
        <a href="https://app.enooki.com/src/pages/intervenants/intervenant_rapport_complet_pdf.php?secteur=<?= $secteur ?>&annee=<?= $annref ?>&ca=1" class="btn btn-mag-n mr-1" target="_blank"><i class='bx bxs-file-pdf bx-flxxx icon-bar'></i> Rapport annuel <?= $annref ?> avec CA</a>
      </li>
      <li class="nav-item">
        <button class="btn btn-mag-n text-warning text-right" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class='bx bx-chevron-down bx-flxxx icon-bar'></i> Intervenants inactifs
        </button>
      </li>

    </ul>





  </div>
  <div class="collapse" id="collapseExample">
    <div class="col-md-12">
      <p class="titre_menu_item mb-2 text-warning">Intervenants inactifs</p>
      <div class="scroll-s">
        <div class="row">
          <?php
          foreach ($users_inactif as $c) {
          ?>
            <div class="col-md-4">
              <div class="border-dot pointer mb-2 p-2" onclick="ajaxData('idusers=<?= $c['idusers'] ?>', '../src/pages/intervenants/intervenant_dossier.php', 'action', 'attente_target'); ">
                <p class="pull-right text-secondary text-bold small  mr-1">N° <?= ($c['idusers']) ?> <i class='bx bx-link-external icon-bar'></i></p>
                <p><?= ($c['prenom']) ?> <?= ($c['nom']) ?></p>
              </div>
            </div>
          <?php
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</div>