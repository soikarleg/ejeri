<?php
session_start();
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$secteur = $_SESSION['idcompte'];
$moisref = date('m');
?>
<p class="titre_menu_item">Factures</p>
<?php
echo $data_verifie = verifData($secteur);
?>
<ul class="nav  mb-4">
  <li class=" nav-item">
    <a href="#" class="btn btn-mag-n mr-2  mt-2" onclick="ajaxData('cs=cs', '../src/pages/factures/factures_synthese.php', 'action', 'attente_target');"><i class='bx bx-line-chart icon-bar bx-flxxx'></i></a>
  </li>
  <li class=" nav-item">
    <a href="#" class="btn btn-mag-n mr-1 mt-2" onclick="ajaxData('cs=cs', '../src/pages/factures/factures_a_faire.php', 'action', 'attente_target');"><i class='bx bxs-file-export bx-flxxx icon-bar'></i> Facturations</a>
  </li>
  <!-- //? Facturation récurrente à prévoir -->
  <!-- <li class=" nav-item">
    <a href="#" class="btn btn-mag-n mr-1 mt-2 text-muted " onclick="ajaxData('cs=cs', '../src/pages/factures/facture_reccurente.php', 'action', 'attente_target');"><i class='bx bxs-file-export bx-flxxx icon-bar'></i> Facturations récurrentes</a>
  </li> -->
  <li class=" nav-item">
    <a href="#" class="btn btn-mag-n mr-4 mt-2" onclick="ajaxData('cs=cs', '../src/pages/factures/factures_recherche.php', 'action', 'attente_target');"><i class='bx bx-search bx-flxxx icon-bar text-primary'></i> Liste des factures</a>
  </li>
  <li class="nav-item">
    <a href="#" class="btn btn-mag-n mr-1 mt-2" onclick="ajaxData('moisref=<?= $moisref ?>', '../src/pages/production/production_pointage.php', 'action', 'attente_target');"><i class='bx bx-list-check bx-flxxx icon-bar text-warning'></i> Pointage production</a>
  </li>
  <li class=" nav-item">
    <a href="#" class="btn btn-mag-n mr-1 mt-2 " onclick="ajaxData('cs=cs', '../src/pages/factures/factures_relance.php', 'action', 'attente_target');"><i class='bx bxs-check-shield bx-flxxx icon-bar text-warning'></i> Edition des relances</a>
  </li>
  <li class=" nav-item">
    <!-- $chemin_doc = "https://app.enooki.com/documents/pdf/relance_tel/$fichier"; $fichier = 'Relance_tel_' .  date('dmY_His');-->
    <p href="#" class="btn btn-mag-n mr-1 mt-2 " onclick="getRelancesPDF('https://app.enooki.com/documents/pdf/relance_tel/relance.pdf','_blank','<?= $secteur ?>')"><i class='bx bxs-phone-outgoing bx-flxxx icon-bar text-warning'></i> Relances téléphoniques</p>
  </li>
</ul>
<div id="action">
</div>

<?php
if ($_POST['pass'] == 'reglement') {
?>
  <script>
    ajaxData('moisref=<?= $_POST['moisref'] ?>&annref=<?= $_POST['annref'] ?>', '../src/pages/factures/factures_recherche.php', 'action', 'attente_target');
  </script>
<?php
} else {
?>
  <script>
    ajaxData('cs=cs', '../src/pages/factures/factures_a_faire.php', 'action', 'attente_target');
  </script>
<?php
}
?>



<script>
  $(function() {
    $('.bx').tooltip();
  });
</script>