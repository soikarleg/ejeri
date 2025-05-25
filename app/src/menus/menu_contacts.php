<?php
$chemin = $_SERVER['DOCUMENT_ROOT'];
$secteur = $_SESSION['idcompte'];
$term = null;
?>


  <li class="nav-item">
    <a href="/contacts_ajouter" class="btn btn-mag-n" id="sous_menu_contact"><i class="bx  bx-user-plus" style="margin-top:4px"></i>Ajouter un contact</a>
  </li>
  <li class="nav-item">
    <a href="/contacts_liste" class="btn btn-mag-n"><i class="bx  bx-search" style="margin-top:4px"></i>Recherche des contacts</a>
  </li>


<script>
  $(function() {
    $('.bx').tooltip();
  });
</script>