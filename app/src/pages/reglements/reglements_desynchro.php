<?php

namespace IbanApi;

use connBase;
use Bridge;

// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
session_start();
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/vendor/autoload.php';
include $chemin . '/inc/function.php';
$conn = new connBase();
$synchro = new Bridge($secteur, $iduser);
$ib = new Api("ea988e7179b7695c5763b311474affffc5b40510");
$banks = $conn->askBank($secteur);
//var_dump($_POST);
foreach ($_POST as $key => $value) {
  ${'p_' . $key} = $value;
}
    //echo $_SERVER['REQUEST_URL'];

    $production = true;

    if ($production == true) {
  $cheminFichier = $chemin . '/config/bridge_config.json';
} else {
  $cheminFichier = $chemin . '/config/bridge_config_sb.json';
}
$contenuFichier = file_get_contents($cheminFichier);
$data = json_decode($contenuFichier, true);
//var_dump($data);
foreach ($data as $k => $v) {
  ${$k} = $v;
}
$infos_user = $synchro->userCreation($client_id, $client_secret);
$info_user = $synchro->getData($infos_user);
//var_dump($info_user);
if ($info_user['type'] === 'conflict') {
  // echo 'yes, conflit';
}
$auth = $synchro->userAuthentification($secteur, $client_id, $client_secret);
$token = $synchro->getData($auth);
$tsat = $token['access_token'];
$uuid = $token['user']['uuid'];
$redirect_url = $synchro->setItem($tsat, $client_id, $client_secret);
$url = $synchro->getData($redirect_url, true);
$url_item = $url['redirect_url'];
$user_connu = $synchro->getUser($uuid, $client_id, $client_secret);
$items = $synchro->getItems($tsat, $client_id, $client_secret);
//var_dump($items);
$item = $synchro->getData($items);
//var_dump($item);
?>
<p class="titre_menu_item mb-2">Désynchronisation du compte</p>
<div class="row">

  <div class="col-md-12 border-dot">
    <p class="">Désynchroniser le compte ID : <?= $p_iditem ?> ?</p>

    <?php
    foreach ($item['resources'] as $res) {
      $bank = $res['bank_id'];
      $comptes = $synchro->getBank($bank, $client_id, $client_secret);
      $compte = $synchro->getData($comptes);
      //var_dump($compte);
      $iditem = $res['id'];
      $accounts = $synchro->getAccounts($tsat, $iditem, $client_id, $client_secret);

      $account = $synchro->getData($accounts);
      $dateacc = explode('T', $account['generated_at']);
      $date = AffDate($dateacc[0]);
      $heure = explode('.', $dateacc[1]);
      $h = $heure[0];
      if ($iditem == $p_iditem) {
    ?>
        <p class="text-warning pointer" onclick="ajaxData('iditem=<?= $iditem ?>&bank_id=<?= $bank ?>','../src/pages/reglements/reglements_item_desynchro.php','desynchro','attente_target');">Oui, je confirme désynchroniser le compte ID : <?= $iditem ?></p>
    <?php
      }
    }
    ?>
    <div id="desynchro">

    </div>
  </div>

</div>
<script>
  $(function() {
    $('.bx').tooltip();
  });
</script>