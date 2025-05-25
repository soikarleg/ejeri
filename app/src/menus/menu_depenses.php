<?php
session_start();
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$secteur = $_SESSION['idcompte'];
// include $chemin . '/vendor/autoload.php';
// require_once $chemin . '/inc/dbconn.php';
// $db = Connexion();
// $auth = new Delight\Auth\Auth($db);
// foreach ($_GET as $k => $v) {
//   ${$k} = $v;
//   echo '$' . $k . ' ' . $v . '</br>';
// }
// include $chemin . '/inc/head.php';

?>


<p class="titre_menu_item">Notes & Rappels</p>
<?php

echo $data_verifie = verifData($secteur);

?>
<ul class="nav  mb-4">

  <li class=" nav-item">
    <a href="#" class="btn btn-mag-n mr-1 mt-2" onclick="ajaxData('cs=cs', '../src/pages/devis/devis_nouveau.php', 'action', 'attente_target');">Nouveau rappel</a>
  </li>
  <!-- <li class=" nav-item">
    <a href="#" class="btn btn-mag-n mr-1 mt-2" onclick="ajaxData('cs=cs', '../src/pages/devis/devis_rechercher.php', 'action', 'attente_target');">Rechercher un devis</a>
  </li> -->
  <!-- <li class="nav-item">
    <a href="#" class="btn btn-mag-n mr-1 mt-2" onclick="ajaxData('cs=cs', '../src/pages/devis/devis_pointer.php', 'action', 'attente_target');">Pointer les devis</a>
  </li> -->

</ul>


<div id="action">
  <p>Pas de note</p>
</div>

<?php
include $chemin . '/inc/foot.php';
?>