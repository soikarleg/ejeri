<?php
session_start();
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
//include $chemin . '/inc/function.php';
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
$conn = new connBase();
$bridge = new Bridge();


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
  //echo $k . ' ' . $v . '</br>';
}
$connect  = $bridge->userAuthentification($secteur, $client_id, $client_secret);
$token = $bridge->getData($connect);
$access_token = $token['access_token'];
?>
<p class="titre_menu_item">Opérations "Bridge"</p>
<p class="text-bold mb-2">Banques</p>
<?php
$items = $bridge->getItems($access_token, $client_id, $client_secret);
$item = $bridge->getData($items);

?>
<div class="">
  <div class="row">
    <?php
    foreach ($item['resources'] as $k) {

      foreach ($k as $key => $value) {
        ${$key} = $value;
        //echo '$' . $key . '= ' . $value . '</br>';
      }
      $banks = $bridge->getBank($bank_id, $client_id, $client_secret);
      $bank = $bridge->getData($banks);
      //var_dump($bank);
      //echo $bank['id'] . ' - ' . $bank['name'] . '</br>';

      $webhooks_update = $bridge->getWebhookItem($secteur, $id);

      $accounts = $bridge->getAccounts($access_token, $id, $client_id, $client_secret);
      $accounts = $bridge->getData($accounts);
    ?>


      <div class="col-md-4">
        <div class="card card-body">
          <p class="small mb-4 text-bold"><span class="text-bold"><?= Tronque($bank['parent_name'], 25, 1) ?></span> <span class="small puce mr-2 pull-right"><?= $bank['id'] ?></span></p>
          <?php
          // $w_type = item.account.updated
          // $w_id = 1391
          // $w_idcompte = C4X
          // $w_time_maj = 2024-09-08 05:04:42
          // $w_user_uuid = 421eb5ea-3f6d-4e8a-b55a-1ca63a1ba283
          // $w_item_id = 8907343
          // $w_account_id = 41298667
          // $w_balance = 241.96
          // $w_nb_deleted_transactions = 0
          // $w_nb_new_transactions = 0
          // $w_nb_updated_transactions = 20
          // $w_full_refresh =
          // $w_status_code =
          // $w_status_code_info =
          // $w_timestamp = 1725764682

          foreach ($webhooks_update[0] as $key => $value) {
            ${'w_' . $key} = $value;
            //echo '$w_' . $key . ' = ' . $value . '</br>';
            $col = $w_balance > 0 ? 'text-success' : 'text-danger';
          }
          ?>
          <p><?= AffDate($w_time_maj) ?> à <?= AffHeure($w_time_maj) ?></p>
          <?php
          if ($w_nb_new_transactions > 0) {
          ?>
            <p class="text-warning"><?= $w_nb_new_transactions ?> nouvelles transactions</p>
          <?php
          } else {
          ?>
            <p class=" text-muted"><?= $w_nb_new_transactions ?> aucune transaction</p>
          <?php
          }
          ?>
          <p class="text-bold text-right <?= $col ?>"><?= Dec_2($w_balance, ' €') ?></p>

          <?php
          $seen = []; // Tableau pour suivre les enregistrements déjà affichés

          foreach ($accounts as $acc) {
            foreach ($acc as $a) {
              // Utilisez un tableau pour stocker les valeurs
              $a_data = [];
              foreach ($a as $key => $value) {
                $a_data[$key] = $value;
              }

              // Vérifiez si l'enregistrement a déjà été vu
              if (!in_array($a_data['name'], $seen)) {
                echo '<p>' . htmlspecialchars(Tronque($a_data['name'], 40, 1)) . '</p>';
                $seen[] = $a_data['name']; // Ajoutez à la liste des enregistrements vus
              }
            }
          }
          ?>
        </div>
      </div>

    <?php
    }
    ?>
    <div class="col-md-12">
      <div class="card card-body mt-4">
        <?php

        ?>
      </div>
    </div>
  </div>
</div>