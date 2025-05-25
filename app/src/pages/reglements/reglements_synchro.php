<?php

namespace IbanApi;

use connBase;
use Bridge;

error_reporting(\E_ALL);
ini_set('display_errors', 'stdout');
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

$uuid_exist = $synchro->getUUID($secteur);
if ($uuid_exist === false) {
  $auth = $synchro->userAuthentification($secteur, $client_id, $client_secret);
  $token = $synchro->getData($auth);
  $uuid = $token['user']['uuid'];
  $insert_uuid = "INSERT INTO `bridge`( `idcompte`, `user_uuid`) VALUES ('" . $secteur . "','" . $uuid . "')";
  $conn->handleRow($insert_uuid);
  $presence_uuid = "";
} else {
  $presence_uuid = "<i class='bx bxs-star text-warning'></i>";
}
$infos_user = $synchro->userCreation($client_id, $client_secret);
$info_user = $synchro->getData($infos_user);
//var_dump($info_user);
$conflict = isset($info_user['type']) ? $info_user['type'] : null;
if ($conflict != null) {
  echo 'conflict';
}
$auth = $synchro->userAuthentification($secteur, $client_id, $client_secret);


$token = $synchro->getData($auth);
$access_token = $token['access_token'];
$uuid = $token['user']['uuid'];

$redirect_url = $synchro->setItem($iduser, $access_token, $client_id, $client_secret);
//var_dump($redirect_url);
$url = $synchro->getData($redirect_url, true);
$url_item = $url['redirect_url'];
$user_connu = $synchro->getUser($uuid, $client_id, $client_secret);
//var_dump($user_connu);
$items = $synchro->getItems($access_token, $client_id, $client_secret);
//var_dump($items);
$item = $synchro->getData($items);
//var_dump($item);
//$ip = $_SERVER['SERVER_ADDR'];
?>
<ul class="nav justify-content-end">
  <li class="nav-item">
    <a href="<?= $url_item ?>" target="_blank" class="btn btn-mag-n"><i class='bx bxs-plus-square bx-flxxx icon-bar text-warning'></i>Synchroniser un compte</a>
  </li>
</ul>
<div class="row">
  <div class="col-md-3">
    <p class="titre_menu_item mb-2">Comptes synchronisés <?= $presence_uuid  ?></p>
    <!-- <div class="border-dot">
      <?php
      $list_users = $synchro->listUsers($client_id, $client_secret);
      $list_user = $synchro->getData($list_users);
      foreach ($list_user['resources'] as $l) {
      ?>
        <p><?= $l['external_user_id'] ?></p>
      <?php
      }
      ?>
    </div> -->
    <div class="row">
      <?php
      foreach ($item['resources'] as $res) {
        $bank = $res['bank_id'];
        $comptes = $synchro->getBank($bank, $client_id, $client_secret);
        $compte = $synchro->getData($comptes);
        $iditem = $res['id'];
        $accounts = $synchro->getAccounts($access_token, $iditem, $client_id, $client_secret);
        //var_dump($accounts);
        $account = $synchro->getData($accounts);
        //var_dump($account);
        $status = $account['resources'][0]['status'];
        $dateacc = explode('T', $account['generated_at']);
        $date = AffDate($dateacc[0]);
        $heure = explode('.', $dateacc[1]);
        $h = $heure[0];
      ?>
        <div class="col-md-12 mb-2">
          <div class="border-dot pointer" onclick="ajaxData('iditem=<?= $idit = $res['id'] ?>&bank_id=<?= $bank ?>','../src/pages/reglements/reglements_detail_compte.php','land','attente_target');">
            <img class="logobk  py-1 px-1 mb-1" style="margin-right:1px" src="<?= $compte['logo_url'] ?>" alt="logo" width="100px">
            <!-- <?= $compte['name'] ?> -->
            <p> <span class="small text-muted">ID : <?= $res['id'] ?></span></p>
            <?php
            $total = 0;
            foreach ($account['resources'] as $ac) {
              $balance = $ac['balance']
            ?>
            <?php
              $total = $total + $balance;
              $text_solde = $total > 0 ? 'text-success' : 'text-danger';
            }
            ?>
            <p><span class="small text-muted">Situation au <?= $date ?> : </span><span class="small <?= $text_solde ?>"><?= $total ?></span></p>




          </div> <?php
                  if ($status == '1010') {
                    echo $reconn = $synchro->getRecon($access_token, $idit, $client_id, $client_secret)
                  ?>

            <p class="puce mt-1 pointer" onclick="alert('<?= $reconn ?>')"><i title="Supprimer ce compte" class='bx bx-refresh text-white bx-flxxx icon-bar '></i>Reconnecter ce compte</p>
          <?php
                  }
          ?>
        </div>
      <?php
      }
      //echo 'https://sagaas.fr/bridge?source=connect&success=true&user_uuid=7304f687-53e4-4ddb-82c8-8946c6c0331a&step=sync_success&item_id=8715269';
      ?>
    </div>
  </div>
  <div class="col-md-9">
    <!-- <p class="titre_menu_item mb-2">Dernières infos</p> -->
    <div id="land">
      <div class="scroll">
        <?php
        // $refresh = $conn->askRefresh($secteur);
        // //var_dump($refresh);
        // foreach ($refresh as $k) {
        //   $compte = $k['account_id'];

        //   $accounts = $synchro->getListAccount($access_token, $client_id, $client_secret);
        //   //var_dump($accounts);
        //   $account = $synchro->getData($accounts);
        //   //var_dump($account);
        //   $compte_nom = '';
        //   foreach ($account['resources'] as $a) {
        //     // echo $a['bank_id'] . '<br>';
        //     if ($a['id'] == $compte) {
        //       $idbank = $a['bank_id'];
        //       $bank_nom = $synchro->getBank($idbank, $client_id, $client_secret);
        //       $bk = $synchro->getData($bank_nom);

        //       $compte_nom = $a['name'] . ' **' . $bk['name'];
        //     }
        //   }
        ?>
        <!-- <p><?= AffDate($k['time_maj']) ?> à <?= AffHeure($k['time_maj']) ?> - <?= $k['type'] ?></p>
        <p><?= $compte_nom ?></p>
        <p>-</p> -->
        <?php
        //  }
        ?>
      </div>
    </div>
  </div>
</div>
<script>
  $(function() {
    $('.bx').tooltip();
  });
</script>