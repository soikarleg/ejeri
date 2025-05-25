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
  ${$key} = $value;
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
$redirect_url = $synchro->setItem($iduser, $tsat, $client_id, $client_secret);
$url = $synchro->getData($redirect_url, true);
$url_item = $url['redirect_url'];
$user_connu = $synchro->getUser($uuid, $client_id, $client_secret);
$items = $synchro->getItems($tsat, $client_id, $client_secret);
//var_dump($items);
$item = $synchro->getData($items);
//var_dump($item);

$iditem = $_POST['iditem'];
$bank = $_POST['bank_id'];


//   foreach ($item['resources'] as $res) {
// $bank = $res['bank_id'];
$comptes = $synchro->getBank($bank, $client_id, $client_secret);
$compte = $synchro->getData($comptes);
//var_dump($comptes);
// $iditem = $res['id'];
$accounts = $synchro->getAccounts($tsat, $iditem, $client_id, $client_secret);
// var_dump($accounts);
$account = $synchro->getData($accounts);
$dateacc = explode('T', $account['generated_at']);
$date = AffDate($dateacc[0]);
$heure = explode('.', $dateacc[1]);
$h = $heure[0];
?>
<p class="titre_menu_item mb-2">Détails du compte</p>
<div class="col-md-12 border-dot">
  <div class="row">
    <div class="col-md-3">

      <p class="mb-8"><img class=" p-0 " style="margin-left:-15px;margin-bottom:0px" src="<?= $compte['logo_url'] ?>" alt="logo" width="80px"><b><?= $compte['name'] ?></b> <span class="small text-muted">ID : <?= $iditem ?></span></b></p>
    </div>
    <div class="col-md-3">
      <p class="small" id="refresh"></p>
    </div>
    <div class="col-md-6">
      <nav class="nav justify-content-end">
        <a class="btn btn-mag-n mr-1" onclick="ajaxData('iditem=<?= $res['id'] ?>&bank_id=<?= $bank ?>','../src/pages/reglements/reglements_item_refresh.php','refresh','attente_target');" aria-current="page" href="#"><i class='bx bx-refresh bx-flxxx icon-bar'></i></a>

        <a class="btn btn-mag-n pointer"><span onclick="ajaxData('iditem=<?= $iditem ?>&bank_id=<?= $bank ?>','../src/pages/reglements/reglements_desynchro.php','land','attente_target');">Désynchroniser ce compte</span></a>

      </nav>
    </div>
  </div>








  <div class=" p-8 mb-4">

  </div>


  <div>
    <?php
    $total = 0;

    foreach ($account['resources'] as $ac) {
      //var_dump($ac);
      $colbal = $ac['balance'] > 0 ? 'text-success' : 'text-muted';
      $iban = $ac['iban'] != '' ? 'IBAN : ' . $ac['iban'] : 'CB';
      $status = $ac['status'] == '1010' ? 'Actif' : 'Inactif';

    ?>
      <div class="border-dot p-2 mt-2 pointer" onclick="ajaxData('idacc=<?= $ac['id'] ?>&iban=<?= $iban ?>&iditem=<?= $iditem ?>&bank_id=<?= $bank ?>&acname=<?= $ac['name'] ?>','../src/pages/reglements/reglements_transaction.php','land','attente_target');">
        <p class="mr-1"><span class="mr-1"><?= $ac['name'] ?></span><span class="<?= $colbal ?> pull-right"><?= $balance = $ac['balance'] . ' ' . $ac['currency_code'] ?></span><span class="puce-mag mr-1"><?= $ac['type'] ?></span><span class="puce ml-1"><?= $cur = $ac['currency_code'] ?></span></p>

        <p><span class="small text-muted">ID : <?= $ac['id'] ?></span> <span class="small text-muted"><?= $iban ?></span></p>


      </div>
    <?php
      $total = $total + $balance;
      $text_solde = $total > 0 ? 'text-success' : 'text-danger';
    }
    ?>
  </div>
  <div class="border-dot p-2 mt-4">
    <p class="pull-right"><span class=" <?= $text_solde ?>"><?= $total . ' ' . $cur ?></span></p>

    <p><span class="mb-4 text-muted">Situation globale <?= $date ?> : </span></p>

  </div><!--
  <div class="mt-2">
    <span class=""><span class="btn btn-mag-n text-danger btn-sm" onclick="alert('Lien vers page la suppression du compte <?= $iditem ?>')">Désynchroniser ce compte</span></span>
  </div> -->


  <!-- <p class=" mt-1 pointer"><i title="Supprimer ce compte" class='bx bxs-message-square-x bx-flxxx icon_bar text-danger'></i></p> -->

</div>
<?php
//}
//echo 'https://sagaas.fr/bridge?source=connect&success=true&user_uuid=7304f687-53e4-4ddb-82c8-8946c6c0331a&step=sync_success&item_id=8715269';
?>






<script>
  $(function() {
    $('.bx').tooltip();
  });
</script>