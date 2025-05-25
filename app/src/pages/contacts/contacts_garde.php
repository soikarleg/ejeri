<?php
$secteur = $_SESSION['idcompte'];
$username = $_SESSION['auth_username'];
$chemin = $_SERVER['DOCUMENT_ROOT'];
//include $chemin . '/inc/error.php';
$conn = new Magesquo($idcompte);
$contacts = new Contacts($secteur);
$clients = new Clients(idcompte: $idcompte);
$chantiers = new Chantiers(idcompte: $idcompte);
$param = ['idcompte' => $secteur];
$reqclient = "select * from client c join client_correspondance cc ON c.idcli = cc.idcli where c.idcompte=:idcompte order by c.idcli desc limit 5";
$client = $conn->allRow($reqclient, $param);
//prettyc($client);
$clef_ajouter = md5('ajouter');
$csrf_token_valid = strcmp($_POST['csrf_token'] ?? '', $_SESSION['csrf_token'] ?? '') === 0; // si 0 c'est ok
$ajouter_valid = strcmp($_POST['ajouter'] ?? '', $clef_ajouter ?? '') === 0; // si 0 c'est ok
foreach ($_POST as $k => $v) {
  ${$k} = $v;
  //echo '$' . $k . ' = ' . $v . '</br>';
}
$filtre = $clients->filtreContacts($_POST);
if (isset($filtre)) {
  foreach ($filtre as $k => $v) {
    ${$k} = $v;
    // echo '$' . $k . ' = ' . $v . '</br>';
  }
}
if ($ajouter_valid) {
  if ($csrf_token_valid) {
    if (!empty($filtre)) {
?>
      <div class="col-md-12">
        <div class="border-dot mysecurity mb-2">
          <?php
          foreach ($filtre as $k => $v) {
          ?>
            <span><?= $v ?> </span>
          <?php
          }
          ?>
        </div>
      </div>
    <?php
    } else {
      include $chemin . '/src/pages/contacts/contacts_inscription_bd.php';
      include $chemin . '/src/menus/mymenupied.php';
      die();
    }
    //prettyc($_POST);
    include $chemin . '/src/pages/contacts/contacts_ajouter.php';
    include $chemin . '/src/menus/mymenupied.php';
    die();
  } else {
    include $chemin . '/src/pages/contacts/contacts_ajouter.php';
    ?>
    <div class="col-md-12">
      <div class="border-dot mysecurity mb-2">Enregistrement des données impossible.</div>
    </div>
<?php
    include $chemin . '/src/menus/mymenupied.php';
    die();
  }
}
?>
<div class="row">
  <div class="col-md-4 ">
    <p class="titre_menu_item mb-2">Derniers clients inscrits (5)</p>
    <div class="row">
      <?php
      //
      foreach ($client as $c) {
        $num = $clients->genereNumeroClient(recordNumber: $c['idcli']);
        $ma = $clients->suffAnneeMois(id: $c['idcli']);
        $n = $clients->nombreRec();
        $global = $clients->showClient(idcli: $c['idcli']);
        $infosClient = $clients->infosClient(idcli: $c['idcli']);
        $chantierClient = $chantiers->chantierClient(idcli: $c['idcli']);
      ?>
        <div class="col-md-12">


          <div onclick="window.open('/fiche_client?idcli=<?= $c['idcli'] ?>','_self')" class="pointer border-dot mb-2">
            <!-- <a href="/fiche_client?idcli=<?= $c['idcli'] ?>" class="small text-primary pull-right pointer">Voir dossier <i class='bx bx-link-external icon-bar pointer text-primary '></i></a> -->
            <p class="small text-muted "><?= 'Mise à jour, le ' . AffDate($c['time_maj']) ?></p>
            <!-- <p class="small text-success pull-right pointer" onclick="ajaxData('idcli=<?= $c['idcli'] ?>', '../src/pages/devis/devis_faire.php', 'action', 'attente_target');">Faire un devis <i class='bx bx-file icon-bar pointer text-success '></i></p> -->
            <p> <?= '<b>' . $clients->showNomClient($c['idcli']) . '</b> - N° ' . $num ?></p>
            
          </div>
        </div>
      <?php
      }
      ?>
    </div>
  </div>
  <div class="col-md-8">
    <p class="titre_menu_item mb-4"></p>
    <?php
    $repartition = $contacts->getSerieRepartition($secteur);
    //echo ($repartition);
    $data = json_decode($repartition, true);
    // Extraire le premier nombre ("value") du tableau
    $premierNombre = $data[0]['value'];
    // Extraire le dernier nombre ("value") du tableau
    $dernierNombre = $data[count($data) - 1]['value'];
    ?>
    <script src="../../assets/js/echarts.js"></script>
    <script src="../../src/graph/graph_contacts_repartition.js"></script>
    <div class="center-graph">
      <div id="contacts_repartition" style="width: 600px;height:500px;margin-top:-30px"></div>
    </div>
    <script type="text/javascript">
      $(function() {
        repartitionContacts('contacts_repartition', <?= $repartition ?>, <?= $premierNombre ?>, <?= $dernierNombre ?>);
      });
    </script>
  </div>
</div>