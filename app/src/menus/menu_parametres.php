<?php
$chemin = $_SERVER['DOCUMENT_ROOT'];
$secteur = $_SESSION['idcompte'];
$idusers = $_SESSION['idusers'];
$conn = new connBase();

?>



  <li class=" nav-item">
    <a href="#" class="btn btn-mag-n mr-1" >HH</a>
  </li>
  <li class=" nav-item">
    <a href="/param_factures" class="btn btn-mag-n mr-1">Facturations</a>
  </li>
  <li class=" nav-item">
    <a href="#" class="btn btn-mag-n mr-1" onclick="ajaxData('idcompte=<?= $secteur ?>', '../src/pages/parametres/parametres_referent.php', 'action', 'attente_target');">Référent</a>
  </li>
  <li class=" nav-item">
    <a href="#" id="infos_sous_menu" class="btn btn-mag-n mr-1" onclick="ajaxData('idcompte=<?= $secteur ?>', '../src/pages/parametres/parametres_secteur.php', 'action', 'attente_target');">Entreprise</a>
  </li>
  <li class=" nav-item">
    <a href="#" id="infos_sous_menu" class="btn btn-mag-n mr-1" onclick="ajaxData('idcompte=<?= $secteur ?>', '../src/pages/reglements/reglements_banque.php', 'action', 'attente_target');">Compte bancaires</a>
  </li>
  <li class=" nav-item">
    <a href="#" class="btn btn-mag-n mr-1" onclick="ajaxData('idcompte=<?= $secteur ?>', '../src/pages/parametres/parametres_tarifs.php', 'action', 'attente_target');">Tarifs</a>
  </li>
  <li class=" nav-item">
    <a href="#" class="btn btn-mag-n mr-1" onclick="ajaxData('idcompte=<?= $secteur ?>', '../src/pages/parametres/parametres_tva.php', 'action', 'attente_target');">TVA</a>
  </li>
  <li class=" nav-item">
    <a href="#" class="btn btn-mag-n mr-1" onclick="ajaxData('idcompte=<?= $secteur ?>', '../src/pages/parametres/parametres_devis.php', 'action', 'attente_target');">Devis</a>
  </li>
  <li class=" nav-item">
    <a href="#" class="btn btn-mag-n mr-1" onclick="ajaxData('idcompte=<?= $secteur ?>', '../src/pages/parametres/parametres_facture.php', 'action', 'attente_target');">Factures & Avoirs</a>
  </li>
  <li class=" nav-item">
    <a href="#" class="btn btn-mag-n mr-1" onclick="ajaxData('idcompte=<?= $secteur ?>', '../src/pages/parametres/parametres_document.php', 'action', 'attente_target');">Documents</a>
  </li>
  <li class=" nav-item">
    <a href="#" class="btn btn-mag-n mr-1" onclick="ajaxData('idcompte=<?= $secteur ?>', '../src/pages/parametres/parametres_logo.php', 'action', 'attente_target');">Logos</a>
  </li>
  <li class=" nav-item">
    <a href="#" class="btn btn-mag-n mr-1" onclick="ajaxData('idcompte=<?= $secteur ?>', '../src/pages/parametres/parametres_bugs.php', 'action', 'attente_target');">Mes bugs</a>
  </li>


<?php
//include $chemin . '/inc/foot.php';
?>