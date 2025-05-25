<?php
$chemin = $_SERVER['DOCUMENT_ROOT'];
$idusers = $_SESSION['idusers'];
$conn = new connBase();
?>
<p class="titre_menu_item">Déconnexion</p>
<ul class="nav  mb-4">
  <li class="ms-auto nav-item">
    <?php
    //$deconnexion = hash('sha256', 'deconnexion');
    ?>
    <a href="https://app.enooki.com/sortie" class="btn btn-danger text-white mt-2">Vous confirmez la déconnexion ?</a>
    <!-- <h2 class="pointer" onclick="ajaxData('sortie=sortie','https://app.enooki.com/sortie','main','attente-target')">pp</h2> -->
  </li>
</ul>
<?php
// pretty($_SESSION);
// prettyc($_COOKIE);
?>
<!-- <a href="/sortie">ici</a>
<a href="https://app.enooki.com/sortie" class="btn btn-success text-white mt-2">Vous confirmez la déconnexion ?</a> -->
<div>
  <?php
  $date_jour = date('Y-m-d');
  //$date_jour = '2024-01-05';
  $reqlog = "select * from log_action where idcolla = '$idusers' and date like '$date_jour%' order by date desc ";
  $log = $conn->allRow($reqlog);
  //var_dump($log);
  ?>
  <p class="titre_menu_item mb-2">Votre log du jour</p>
  <div class="scroll">
    <div class="row">

      <?php
      foreach ($log as $l) {

        $type = $l['type'] === 'Connexion' ? "<i class='bx bxs-log-in-circle bx-flxxx icon-bar text-success'></i> " . $l['type'] : "<i class='bx bxs-message-square-edit bx-flxxx icon-bar text-primary'></i>" . $l['type'];

      ?>
        <div class="col-md-4 mb-1">
          <div class="border-dot px-3 py-1">
            <p class="pull-right puce-mag"><?= AffDate($l['date']) . ' - ' . AffHeure($l['date']) ?></p>
            <p><span class="text-bold"><?= $type ?></span> </p>
            <p><span class="small text-muted"><?= ($l['contenu']) ?></span></p>
          </div>
        </div>
      <?php
      }
      ?>
    </div>
  </div>
</div>

<div id="action">
</div>
<?php
include $chemin . '/inc/foot.php';
?>