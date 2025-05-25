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
$idacc = $_POST['idacc'];
$iditem = $_POST['iditem'];
$bank = $_POST['bank_id'];
$iban = $_POST['iban'];
$acname = $_POST['acname'];
//   foreach ($item['resources'] as $res) {
// $bank = $res['bank_id'];
$comptes = $synchro->getBank($bank, $client_id, $client_secret);
$compte = $synchro->getData($comptes);
//var_dump($comptes);
// $iditem = $res['id'];
$transactions = $synchro->getTransactions($tsat, $idacc, $client_id, $client_secret);

$transaction = $synchro->getData($transactions);
$dateacc = explode('T', $transaction['generated_at']);
$date = AffDate($dateacc[0]);
$heure = explode('.', $dateacc[1]);
$h = $heure[0];
// foreach ($transaction as $k => $v) {
//   echo  '$' . $k . ' = ' . $v;
// }
?>
<div class="col-md-12 mb-2">

  <div class="border-dot">
    <div class="row">
      <div class="col-md-1"><span class="btn btn-mag-n text-warning btn-sm" onclick="ajaxData('iditem=<?= $iditem ?>&bank_id=<?= $bank ?>','../src/pages/reglements/reglements_detail_compte.php','land','attente_target');"><i class='bx bxs-chevron-left icon-bar'></i></span></div>
      <div class="col-md-9">
        <p class="mb-1"><b><?= $compte['name'] ?></b> <span class="small text-muted">ID : <?= $iditem ?></span></p>
        <p class="mb-1 text-primary"><b><?= $acname ?></b></p>
      </div>
      <div class="col-md-2"><img class="pull-right p-0 " style="margin-right:-15px;margin-bottom:0px" src="<?= $compte['logo_url'] ?>" alt="logo" width="60px"></div>
    </div>
    <div class="scroll">
      <?php
      $transac_view = $conn->askTransac($secteur);
      // var_dump($transac_view);
      // $transac_view['id'];
      $total = 0;
      foreach ($transaction['resources'] as $ac) {

        foreach ($ac as $k => $v) {
          ${$k} = $v;
          //echo '$' . $k . ' = ' . $v . '<br>';
        }

        $transac_num = $conn->askTransacNum($secteur, $id, 'id');
        $transac_id = $transac_num['id'];
        if (!$transac_id) {
          $insert_transac = "INSERT INTO `transactions` (`cs`,`id`, `clean_description`, `bank_description`, `amount`, `date`, `booking_date`, `transaction_date`, `value_date`, `updated_at`, `currency_code`, `category_id`, `account_id`, `show_client_side`, `is_deleted`, `is_future`
         ) VALUES ( '$secteur','$id', '$clean_description', '$bank_description', '$amount', '$date', '$booking_date', '$transaction_date', '$value_date', '$updated_at', '$currency_code', '$category_id', '$account_id', '$show_client_side', '$is_deleted', '$is_future');";
          $conn->handleRow($insert_transac);
          $conn->insertLog('Inscription transaction ' . $id, $iduser, $clean_description . ' ' . $amount);
        }
        /* #region */

        // Inscription BD ici **

        // Basé sur
        // $id = 37000246242788
        // $clean_description = Vir Flga Comp
        // $bank_description = VIR FLGA - COMP
        // $amount = -1000
        // $date = 2024-08-23
        // $booking_date = 2024-08-23
        // $transaction_date = 2024-08-23
        // $value_date = 2024-08-23
        // $updated_at = 2024-08-24T03:04:31.973Z
        // $currency_code = EUR
        // $category_id = 78
        // $account_id = 41291817
        // $show_client_side = 1
        // $is_deleted =
        // $is_future =


        // -- Création de la base de données
        // CREATE DATABASE IF NOT EXISTS `transaction_db`;
        // USE `transaction_db`;

        // -- Création de la table pour stocker les données de la transaction
        // CREATE TABLE IF NOT EXISTS `transactions` (
        //     `id` BIGINT NOT NULL, -- ID de la transaction
        //     `clean_description` VARCHAR(255) NOT NULL, -- Description simplifiée
        //     `bank_description` VARCHAR(255) NOT NULL, -- Description bancaire
        //     `amount` DECIMAL(10, 2) NOT NULL, -- Montant de la transaction
        //     `date` DATE NOT NULL, -- Date de la transaction
        //     `booking_date` DATE NOT NULL, -- Date de comptabilisation
        //     `transaction_date` DATE NOT NULL, -- Date de la transaction
        //     `value_date` DATE NOT NULL, -- Date de valeur
        //     `updated_at` TIMESTAMP NOT NULL, -- Date de mise à jour
        //     `currency_code` VARCHAR(3) NOT NULL, -- Code de devise
        //     `category_id` INT NOT NULL, -- ID de la catégorie
        //     `account_id` BIGINT NOT NULL, -- ID du compte
        //     `show_client_side` TINYINT(1) DEFAULT 1, -- Affichage côté client (booléen)
        //     `is_deleted` TINYINT(1) DEFAULT 0, -- Est supprimé (booléen)
        //     `is_future` TINYINT(1) DEFAULT 0, -- Est une transaction future (booléen)
        //     PRIMARY KEY (`id`)
        // );

        // -- Insertion des données fournies
        // INSERT INTO `transactions` (
        //     `id`, `clean_description`, `bank_description`, `amount`, `date`, `booking_date`, `transaction_date`, `value_date`, `updated_at`, `currency_code`, `category_id`, `account_id`, `show_client_side`, `is_deleted`, `is_future`
        // ) VALUES (
        //     37000246242788, 'Vir Flga Comp', 'VIR FLGA - COMP', -1000.00, '2024-08-23', '2024-08-23', '2024-08-23', '2024-08-23', '2024-08-24 03:04:31', 'EUR', 78, 41291817, 1, 0, 0
        // );

        // ibd **
        /* #endregion */
        $track_paye = "oui";
        $track_total = "";
        $track_nom = '';
        $track_numero = '';
        $track_montant = '';
        // var_dump($ac);
        $colbal = $ac['amount'] > 0 ? 'text-success' : 'text-muted';
        if ($ac['amount'] > 0) {
          if (preg_match("/\b(\d{4}-\d{4})\b/", $ac['bank_description'], $matches) or preg_match("/\b(\d{4} \d{4})\b/", $ac['bank_description'], $matches)) {
            $numeroFacture = $matches[1];
            if (strpos($numeroFacture, "-") !== false) {
              $track = $numeroFacture;
            } else {
              $track = str_replace(' ', '-', $numeroFacture);
            }
            $facture_trouve = $conn->askTrackNumFact($secteur, $track);
            //var_dump($facture_trouve);
            $track_idcli = $facture_trouve['id'];
            $track_nom = $facture_trouve['nom'];
            $track_numero = $facture_trouve['numero'];
            $track_total = $facture_trouve['totttc'];
            $track_paye = $facture_trouve['paye'];
          } else {
            $track = explode(' ', $ac['bank_description']);
            foreach ($track as $t) {
              //echo $ac['amount'];
              $nom_trouve = $conn->askTrackCliFact($secteur, $t, $ac['amount']);
              //var_dump($nom_trouve);
              if ($nom_trouve) {
                $track_idcli = $nom_trouve['id'];
                $track_nom = $nom_trouve['nom'];
                $track_numero = $nom_trouve['numero'];
                $track_total = $nom_trouve['totttc'];
                $track_paye = $nom_trouve['paye'];
              }
            }
          }

          $is_future = $ac['is_future'] != "" ? "<i title='A venir' class='bx bx-history text-warning'></i>" : '';
      ?>
          <div class="border-dot p-2 mb-1 pointer">
            <p class="mr-1"><span class="puce-mag mr-1"><?= AffDate($ac['date']) ?></span><span class="mr-1"><?= $ac['clean_description'] . ' ' . $is_future ?></span><br><span class="small text-muted"><?= $ac['bank_description'] ?></span><span class="<?= $colbal ?> pull-right"><?= $balance = $ac['amount'] . ' ' . $ac['currency_code'] ?></span></p>
            <?php
            if ($track_nom != '') {
              if ($track_paye != 'oui') {
                $cli = $conn->askClient($track_idcli);
                //var_dump($cli);
                $mybank = $conn->askBank($secteur, 'idrib,iban, nom_bank');
                $bank_len = count($mybank);
                $iban = str_replace('IBAN : ', '', $iban);
                $idrib = '';
                $myb = '';
                $mynamebank = '';
                for ($i = 0; $i < $bank_len; $i++) {
                  $myb = $mybank[$i]['iban'];

                  $myb = str_replace(' ', '', $myb);
                  //echo $iban . ' ' . $myb . '<br>';
                  if ($myb === $iban) {
                    $idribeq = $mybank[$i]['idrib'];
                    $mybeq = $mybank[$i]['iban'];
                    $mynamebank = $mybank[$i]['nom_bank'];
                  }
                }
            ?>
                <p id="appel<?= $track_idcli ?>" class="btn btn-mag-n small" onclick="openPointage('<?= $track_numero ?>');">A pointer <?= $track_nom . ' / ' . $track_numero . ' / ' . $track_total . ' euros / ' . $track_paye ?></p>
                <div id="fen<?= $track_numero ?>" class="rel" style="display:none">

                  <p class="pull-right text-warning" onclick="closePointage('<?= $track_numero ?>');"><i class='bx bxs-chevron-left bx-flxxx icon-bar text-bold text-white bx-md pointer bx-close'></i></p>
                  <p class="titre_menu_item">Pointage du règlement</p>
                  <div class="border-dot">

                    <p><b>Sur le compte <?= $mynamebank ?> - N° <?= $idribeq ?></b></p>
                    <p>N° <?= $track_idcli ?> - <?= $cli['civilite'] . ' ' . $cli['prenom'] . ' ' . $track_nom ?></p>
                    <p>Facture <?= $track_numero ?> de <?= Dec_2($track_total, ' €') ?></p>
                    <form action="" id="direg<?= $cli['idcli'] ?>">
                      <input type="hidden" id="bdx" name="bdx" value="direct_<?= $idribeq . '_' . date('dmY_His') ?>">
                      <input type="hidden" id="montant" name="montant" value="<?= $track_total  ?>">
                      <input type="hidden" id="date" name="date" value="<?= AffDate($ac['date'])  ?>">
                      <input type="hidden" id="numcli" name="numcli" value="<?= $cli['idcli'] ?>">
                      <input type="hidden" id="nomcli" name="nomcli" value="<?= $track_nom ?>">
                      <input type="hidden" id="mode" name="mode" value="VIREMENT">
                      <input type="hidden" id="numfact" name="numfact" value="<?= $track_numero ?>">
                      <input type="hidden" id="iduser" name="iduser" value="<?= $iduser ?>">
                      <!-- <input type="hidden" id="partiel" name="partiel" value="non"> -->
                      <input type="hidden" id="idcompte" name="idcompte" value="<?= $secteur ?>">
                      <input type="hidden" id="commentaire" name="commentaire" value="Pointage direct">
                      <!-- <input type="hidden" id="acompte" name="acompte" value="0"> -->
                      <input type="hidden" id="idrib" name="idrib" value="<?= $idribeq ?>">
                      <input type="button" class="btn btn-mag-n pull-right" onclick="ajaxForm('#direg<?= $cli['idcli'] ?>','../src/pages/reglements/reglements_direct_pointage.php','respointage<?= $track_idcli ?>','attente');" value="Pointer <?= $track_total  ?>€ pour <?= $cli['civilite'] . ' ' . $cli['prenom'] . ' ' . $track_nom ?>">

                      <input class="" type="checkbox" id="acompte" name="acompte" value="1">
                      <label class="form-check-label" for="acompte">Cochez si c'est un acompte.</label>
                      <br>
                      <input class="" type="checkbox" id="partiel" name="partiel" value="oui">
                      <label class=" form-check-label" for="partiel">Cochez si c'est un règlement partiel.</label>


                    </form>
                    <?php

                    ?>
                    <div id="respointage<?= $track_idcli ?>">
                    </div>

                  </div>
                </div>
            <?php
              }
            }
            ?>
          </div>
      <?php
          $total = $total + $balance;
        }
        $text_solde = $total > 0 ? 'text-success' : 'text-danger';
      }



      ?>
    </div>
    <!--  <div class=" border-dot p-2 mt-4">
      <p class="pull-right"><span class=" <?= $text_solde ?>"><?= $total ?></span></p>
      <p><span class="mb-4 text-muted">Total au <?= $date ?> : </span></p>
    </div>
    <div class="text-right mt-2">
      <span class=""><span class="btn btn-mag-n text-warning btn-sm" onclick="ajaxData('iditem=<?= $iditem ?>&bank_id=<?= $bank ?>','../src/pages/reglements/reglements_detail_compte.php','land','attente_target');">Retour aux comptes</span></span>
    </div> -->
    <!-- <p class=" mt-1 pointer"><i title="Supprimer ce compte" class='bx bxs-message-square-x bx-flxxx icon_bar text-danger'></i></p> -->
  </div>
</div>
<?php
//}
//echo 'https://sagaas.fr/bridge?source=connect&success=true&user_uuid=7304f687-53e4-4ddb-82c8-8946c6c0331a&step=sync_success&item_id=8715269';
?>
<script>
  function openPointage(fen) {
    $('#fen' + fen).fadeIn();
  };

  function closePointage(fen) {
    $('#fen' + fen).fadeOut();
  };
  $(function() {
    $('.bx').tooltip();
  });
</script>