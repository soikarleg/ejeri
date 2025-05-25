<?php
$chemin = $_SERVER['DOCUMENT_ROOT'];
$secteur = $_SESSION['idcompte'];
?>
<ul class="nav">

  <!-- <li class=" nav-item">
    <a href="#" class="btn btn-mag-n" onclick="ajaxData('cs=cs', '../src/pages/intervenants/intervenant_garde.php', 'action', 'attente_target');"><i class='bx bx-list-ul bx-flxxx icon-bar'></i></a>
  </li> -->

  <li class=" nav-item">
    <a href="#" class="btn btn-mag-n" id="intervenant_sous_menu" onclick="ajaxData('cs=cs', '../src/pages/intervenants/intervenant_ajouter.php', 'target-one', 'attente_target');">Ajouter un intervenant</a>
  </li>



  <!-- <li class=" nav-item">
    <a href="#" class="btn btn-mag-n" onclick="ajaxData('cs=cs', '../src/pages/intervenants/intervenant_recherche.php', 'action', 'attente_target');">Rechercher un intervenant</a>
  </li> -->
  <!-- <li class=" nav-item mr-1">
    ajaxData('limit=1&term='+this.value+'', '../src/pages/contacts/contacts_recherche.php', 'action', 'attente_target');
    <input type="text" id="recherche_cli" class="form-control-n mt-2 placeholder mr-4" value="" onkeyup="delaiExecution();" onmouseover="eff_form(this);" placeholder="Recherche multicritères">
    <span><?= $term ?></span>
  </li> -->

  <li class="nav-item">
    <a href="#" class="btn btn-mag-n " onclick="ajaxData('cs=cs', '../src/pages/intervenants/intervenant_heures.php', 'action', 'attente_target');">Rapport d'activité</a>
  </li>

</ul>
<div id="action">

</div>
<?php
//include $chemin . '/inc/foot.php';
?>

<script>
  // ajaxData('cs=cs', '../src/pages/intervenants/intervenant_garde.php', 'action', 'attente_target');
</script>


<script>
  $(function() {
    $('#bx').tooltip();
  });
</script>