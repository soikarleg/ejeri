<?php
session_start();
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$secteur = $_SESSION['idcompte'];
$user = $_SESSION['users'];

?>


<!-- <p class="titre_menu_item">Règlements factures et acomptes</p> -->
 

<script>
  ajaxData('cs=cs', '../src/pages/reglements/reglements_montotal.php', 'montotal', 'attente_target');
</script>
<?php

//echo $data_verifie = verifData($secteur);

$annref = date('Y');
$moisref = '01';
?>
<ul class="nav ">
  
  <!-- <li class=" nav-item">
    <a href="#" class="btn btn-mag-n mr-2  mt-2" onclick="ajaxData('annee=<?= $annref ?>', '../src/pages/reglements/reglements_pointer.php', 'action', 'attente_target');"><i class='bx bx-line-chart bx-flxxx icon-bar'></i></a>
  </li> -->
  <li class=" nav-item">
    <a href="#"  ><i class='bx bx-list-check bx-flxxx icon-bar'></i> Pointer un règlements</a>
  </li>
  <li class=" nav-item">
    <a href="#"  ><i class='bx bx-list-check bx-flxxx icon-bar'></i> Pointer un acompte</a>
  </li>
  <li class=" nav-item">
    <a href="#"  ><i class='bx bx-search bx-flxxx icon-bar'></i> Recherche encaissements</a>
  </li>

  <li class=" nav-item">
    <a href="#" ><i class='bx bx-sync bx-flxxx icon-bar text-warning'></i> Relevés bancaires</a>
  </li>
 <li class="nav-item">
  <span id="montotal" class=" pull-right"></span>
 </li>
  <?php
  if ($iduser == '61') {
  ?>
    <li class=" nav-item">
      <a href="#" class="btn btn-mag-n mr-1 mt-2" onclick="ajaxData('user_bridge=<?= $secteur ?>', '../src/pages/reglements/reglements_transac.php', 'action', 'attente_target');"><i class='bx bx-list-ul bx-flxxx icon-bar text-primary'></i><?= ($iduser) ?></a>
    </li>
    <li class=" nav-item">
      <a href="#" class="btn btn-mag-n mr-2  mt-2" onclick="ajaxData('annee=<?= $annref ?>', '../src/pages/reglements/reglements_pointer_n.php', 'action', 'attente_target');"><i class='bx bx-line-chart bx-flxxx icon-bar'></i> New</a>
    </li>
  <?php
  }
  ?>
  <!-- <li class="nav-item"> <i class='bx bx-list-ul'></i>
    <a href="#" class="btn btn-mag-n mr-1 mt-2" onclick="ajaxData('cs=cs', '../src/pages/reglements/reglements_dossier.php', 'action', 'attente_target');"><i class='text-muted bx bx-file-blank icon-bar'></i> Pointage des interventions</a>
  </li>
  <li class="nav-item">
    <a href="#" class="btn btn-mag-n mr-1 mt-2" onclick="ajaxData('cs=cs', '../src/pages/reglements/reglements_dossier.php', 'action', 'attente_target');"><i class='text-muted bx bx-file-blank icon-bar'></i>Ajouter un intervenant</a>
  </li>
  <li class="nav-item">
    <a href="#" class="btn btn-mag-n mr-1 mt-2" onclick="ajaxData('cs=cs', '../src/pages/intervenants/intervenant_dossier.php', 'action', 'attente_target');"><i class='text-muted bx bx-file-blank icon-bar'></i>Dossiers intervenants</a>
  </li> -->

</ul>

<div id="action">
  <div id="attente_target" class="attente" style="display:none"><i class='bx bx-refresh bx-spin bx-lg text-primary'></i></div>

</div>

<?php

if ($_GET['bridge'] === '1') {

?>
  <script>
   // ajaxData('cs=cs', '../src/pages/reglements/reglements_synchro.php', 'action', 'attente_target');
  </script>
<?php

} else {
?>

  <script>
   // ajaxData('annee=<?= $annref ?>', '../src/pages/reglements/reglements_pointer.php', 'action', 'attente_target');
  </script>
<?php
}
include $chemin . '/inc/foot.php';
?>