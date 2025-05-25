<?php

// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
$secret = 'ccb1c752-9ad7-4f01-a9c7-917bda128066';

include $chemin . '/inc/function.php';
$conn = new connBase();
$access = new Access();

$webhook_data = file_get_contents('php://input');

//$webhook_data = file_get_contents($chemin . '/src/webhooks/json/item_created.json');
//$webhook_data = '{"content":{"item_id":1234567890,"status":0,"user_uuid":"9a95b38f-f98b-417a-988b-9d0d584893e7"},"timestamp":1611681789,"type":"TEST_EVENT"}';

$headers = getallheaders();
$signature =  $headers['Bridgeapi-Signature'];
$hash = hash_hmac('sha256', $webhook_data, $secret);
$hash = strtoupper($hash);
$validation = strcmp($signature, 'v1=' . $hash);


if ($validation === 0) {
  // SIGNATURE OK
  $horodatage = date('Y-d-m_H-i-s');
  $d = json_decode($webhook_data, true);
  // var_dump($d);
  $user_uuid = $d['content']['user_uuid'];
  $item = $d['content']['item_id'];
  $type = $d['type'];
  $timestamp = $d['timestamp']/1000;
  $maDate = new DateTime("@$timestamp");
  $date = $maDate->format('Y-m-d H:i:s');
  $idcompte = $access->getIdcompte($user_uuid);
  $idcompte =  $idcompte['idcompte'];


  $insert_base = "INSERT INTO webhooks( idcompte, user_uuid, item_id,timestamp, type)
  VALUES ('$idcompte','$user_uuid','$item','$timestamp','$type')";
$ins = $conn->handleRow($insert_base);
  

} else {
  // ERREUR DE SIGNATURE
  echo 'Une erreur de signature';
}
