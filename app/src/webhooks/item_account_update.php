<?php

// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
$secret = 'fe90cfd6-9c3f-404c-992c-141d7a14a29b';

include $chemin . '/inc/function.php';
$conn = new connBase();
$access = new Access();
$bridge = new Bridge();

$webhook_data = file_get_contents('php://input');

$headers = getallheaders();
$signature =  $headers['Bridgeapi-Signature'];
$hash = hash_hmac('sha256', $webhook_data, $secret);
$hash = strtoupper($hash);
$validation = strcmp($signature, 'v1=' . $hash);

if ($validation === 0) {
  // SIGNATURE OK
  $horodatage = date('Y-d-m_H-i-s');
  $d = json_decode($webhook_data, true);
  //var_dump($d);

  $account_id = $d['content']['account_id'];
  $balance = $d['content']['balance'];
  $item = $d['content']['item_id'];
  $nb_deleted_transactions = $d['content']['nb_deleted_transactions'];
  $nb_new_transactions = $d['content']['nb_new_transactions'];
  $nb_updated_transactions = $d['content']['nb_updated_transactions'];

  $user_uuid = $d['content']['user_uuid'];

  $type = $d['type'];
  $timestamp = $d['timestamp'] / 1000;
  $maDate = new DateTime("@$timestamp");
  $date = $maDate->format('Y-m-d H:i:s');
  $idcompte = $access->getIdcompte($user_uuid);
  $idcompte =  $idcompte['idcompte'];

  $insert_base = "INSERT INTO webhooks(idcompte, user_uuid, item_id, account_id, balance, nb_deleted_transactions, nb_new_transactions, nb_updated_transactions,timestamp, type)
  VALUES ('$idcompte','$user_uuid','$item','$account_id','$balance','$nb_deleted_transactions','$nb_new_transactions','$nb_updated_transactions','$timestamp','$type')";
  $ins = $conn->handleRow($insert_base);
} else {
  // ERREUR DE SIGNATURE
  echo 'Une erreur de signature';
}
