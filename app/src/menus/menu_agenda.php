<?php
session_start();
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
$term = null;
$verifier_client = verifInfosClient($secteur);
?>
<!-- <p class="titre_menu_item">Planning</p> -->
<?php
echo $data_verifie = verifData($secteur);
?>


<div id="action"></div>


<script>
  ajaxData('cs=cs', '../src/pages/calendar/calendar_planning.php', 'action', 'attente_target');
  $(function() {
    $('.bx').tooltip();
  });
</script>