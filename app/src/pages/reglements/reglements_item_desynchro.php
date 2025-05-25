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
$redirect_url = $synchro->setItem($tsat, $client_id, $client_secret);
$url = $synchro->getData($redirect_url, true);
$url_item = $url['redirect_url'];
$user_connu = $synchro->getUser($uuid, $client_id, $client_secret);
$items = $synchro->getItems($tsat, $client_id, $client_secret);
$delitem = $synchro->delItem($tsat, $iditem, $client_id, $client_secret);

$item = $synchro->getData($delitem);


$idacc = $_POST['idacc'];
$iditem = $_POST['iditem'];
$bank = $_POST['bank_id'];
$acname = $_POST['acname'];

//   foreach ($item['resources'] as $res) {
// $bank = $res['bank_id'];
$comptes = $synchro->getBank($bank, $client_id, $client_secret);
$compte = $synchro->getData($comptes);
//var_dump($comptes);
// $iditem = $res['id'];
$transactions = $synchro->getTransactions($tsat, $idacc, $client_id, $client_secret);
// var_dump($transactions);
$transaction = $synchro->getData($transactions);
$dateacc = explode('T', $transaction['generated_at']);
$date = AffDate($dateacc[0]);
$heure = explode('.', $dateacc[1]);
$h = $heure[0];
?>
<div>
  <?php
  if ($delitem === null) {
  ?>

    <p class="text-primary pointer" onclick="ajaxData('user_bridge=<?= $secteur ?>', '../src/pages/reglements/reglements_synchro.php', 'action', 'attente_target');">Retour aux comptes</p>
</div>
<?php
  }
  //}
  //echo 'https://sagaas.fr/bridge?source=connect&success=true&user_uuid=7304f687-53e4-4ddb-82c8-8946c6c0331a&step=sync_success&item_id=8715269';
?>






<script>
  $(function() {
    $('.bx').tooltip();
  });
</script>