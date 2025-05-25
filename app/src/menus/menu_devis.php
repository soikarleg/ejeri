<?php
//session_start();
$chemin = $_SERVER['DOCUMENT_ROOT'];
//include  $chemin . '/inc/function.php';
//include  $chemin . '/inc/error.php';
$secteur = $_SESSION['idcompte'];
$devis = new Devis($secteur);
?>
<!-- <p class="titre ">Devis</p> -->
<?php
$data_verifie = verifData($secteur);
$numero_devis = $devis->getNumDevis();




// session_unset();
// session_destroy();
?>

<ul class="nav  mb-4">
   <li class=" nav-item">
      <a href="/devis" class="btn btn-mag-n  mr-1  " onclick="ajaxData('cs=cs', '../src/pages/devis/devis_garde.php', 'action', 'attente_target');"><i class='bx bx-line-chart bx-sm'></i>Indicateurs</a>
   </li>
   <li class=" nav-item">
      <?php

      ?>
      <a href="/devis" class="btn btn-mag-n  mr-1  " onclick="ajaxData('cs=cs', '../src/pages/devis/devis_faire.php', 'action', 'attente_target');"><i class='bx bxs-file-plus bx-sm'></i>Nouveau devis N° <?= $numero_devis ?></a>
   </li>
   <li class=" nav-item">
      <a href="/devis" class="btn btn-mag-n mr-1 " onclick="ajaxData('cs=cs', '../src/pages/devis/devis_recherche.php', 'action', 'attente_target');$('#action').addClass('rel');"><i class='bx bx-search-alt-2 bx-sm'></i> Recherche des devis</a>
   </li>

   <li class="nav-item dropdown">
      <a class=" dropdown-toggle btn btn-mag-n " href="/devis" role="button" data-bs-toggle="dropdown" aria-expanded="false">
         <i class='bx bxs-terminal bx-sm '></i> Titre et descriptif des devis.
      </a>
      <ul class="dropdown-menu">

         <li>
            <a href="/devis" class="btn btn-mag-n dropdown-item" onclick="ajaxData('cs=cs', '../src/pages/devis/devis_titres.php', 'action', 'attente_target');$('#action').addClass('rel');"><i class='bx bxs-terminal bx-sm'></i> Titres devis</a>
         </li>
         <li>
            <a href="/devis" class="btn btn-mag-n dropdown-item" onclick="ajaxData('cs=cs', '../src/pages/devis/devis_phrases.php', 'action', 'attente_target');$('#action').addClass('rel');"><i class='bx bxs-terminal bx-sm '></i> Descriptions devis</a>
         </li>
      </ul>
   </li>
   <!-- <li class=" nav-item">
      <?php
      $adr = urlencode('3,place de l\'Eglise 45740 Lailly en Val');
      $adr = urlencode('2 rue du bourg 45740');
      ?>
      <a href="/devis" class="btn btn-mag-n mr-1 " onclick="ajaxDataGET('<?= $adr ?>', 'https://www.google.com/maps/place/', 'action', 'attente_target');"><i class='bx bxs-terminal bx-sm text-primary'></i> Carte</a>
   </li> -->
</ul>
<div id="action" class="">
</div>