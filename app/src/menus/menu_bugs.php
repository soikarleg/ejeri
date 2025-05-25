<?php
$chemin = $_SERVER['DOCUMENT_ROOT'];
$secteur = $_SESSION['idcompte'];
$term = null;
?>


<!-- <li class="nav-item">
    <a href="/bugs_ajouter" class="btn btn-mag-n" id="sous_menu_contact">Signaler un bug</a>
</li> -->
<li class="nav-item">
    <a href="<?= $page ?>" class="btn btn-mag-n"><i class="bx bx-undo" style="margin-top:3px"></i>Retour à la page précédente</a>
</li>
<!-- <li class="nav-item">
    <a href="/bugs_resolus" class="btn btn-mag-n text-success">Bugs résolus</a>
</li> -->


<script>
    $(function() {
        $('.bx').tooltip();
    });
</script>