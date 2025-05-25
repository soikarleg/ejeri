<?php
//session_start();
$chemin = $_SERVER['DOCUMENT_ROOT'];
//include $chemin . '/inc/function.php';
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
$moisref = date('m');
?>
<p class="titre_menu_item">Productions</p>
<?php
echo $data_verifie = verifData($secteur);
?>
<ul class="nav  mb-4">
  <li class=" nav-item">
    <a href="#" class="btn btn-mag-n mr-2  mt-2" onclick="ajaxData('cs=cs', '../src/pages/production/production_bilan.php', 'action', 'attente_target');"><i class='bx bx-line-chart bx-flxxx icon-bar'></i></a>
  </li>

  <li class=" nav-item">
    <a href="#" class="btn btn-mag-n mr-1 mt-2" onclick="ajaxData('cs=cs', '../src/pages/production/production_ajouter_hf.php', 'action', 'attente_target');$(' #action').addClass('rel')"><i class='bx bx-qr bx-flxxx icon-bar'></i> Productions facturables</a>
  </li>
  <li class=" nav-item">
    <a href="#" class="btn btn-mag-n mr-1 mt-2" onclick="ajaxData('cs=cs', '../src/pages/production/production_ajouter_hnf.php', 'action', 'attente_target');"><i class='bx bx-qr bx-flxxx icon-bar text-warning'></i> Productions non facturables</a>
  </li>
  <li class="nav-item">
    <a href="#" class="btn btn-mag-n mr-1 mt-2" onclick="ajaxData('moisref=<?= $moisref ?>', '../src/pages/production/production_pointage.php', 'action', 'attente_target');"><i class='bx bx-list-check bx-flxxx icon-bar'></i> Pointage production</a>
  </li><!--
  <li class="nav-item">
    <a href="#" class="btn btn-mag-n mr-1 mt-2" onclick="ajaxData('cs=cs', '../src/pages/contacts/contacts_dossier.php', 'action', 'attente_target');">Ajouter un intervenant</a>
  </li>-->
  <li class="nav-item">
    <a href="#" class="btn btn-mag-n  mr-1 mt-2" onclick="ajaxData('cs=cs', '../src/pages/intervenants/intervenant_heures.php', 'action', 'attente_target');"><i class='bx bxs-user-rectangle bx-flxxx icon-bar'></i> Intervenants</a>
  </li>
  <?php
  if ($iduser == "61") {
  ?>
    <li class="nav-item">
      <a href="#" class="btn btn-mag-n  mr-1 mt-2" onclick="ajaxData('annref=<?= date('Y') ?>&iduser=<?= $iduser ?>', '../src/pages/production/production_ik.php', 'action', 'attente_target');"><i class='bx bxs-car bx-flxxx icon-bar'></i> IK / <?= NomColla($iduser) ?></a>
    </li>
    <li class="nav-item">
      <a href="#" class="btn btn-mag-n  mr-1 mt-2" onclick="ajaxData('annref=<?= date('Y') ?>&iduser=<?= $iduser ?>', '../src/pages/production/production_immat.php', 'action', 'attente_target');"><i class='bx bxs-car bx-flxxx icon-bar'></i> Immat </a>
    </li>
  <?php
  }
  ?>
</ul>
<div id="action">
</div>
<?php
include $chemin . '/inc/foot.php';
?>
<script>
  // ajaxData('cs=cs', '../src/pages/production/production_bilan.php', 'action', 'attente_target');
</script>