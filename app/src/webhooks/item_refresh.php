<?php

$secret = '50a1a339-1c79-4a28-8494-57cedfa633b5';
$webhook_data = file_get_contents('php://input');
$headers = getallheaders();
$signature =  $headers['Bridgeapi-Signature'];
$hash = hash_hmac('sha256', $webhook_data, $secret);
$hash = strtoupper($hash);
$validation = strcmp($signature, 'v1=' . $hash);
$chemin = $_SERVER['DOCUMENT_ROOT'];
$horodatage = date('Y-m-d_H-i-s');



if ($validation === 0) {


  // $nomjson = "/src/webhooks/json/bridge_item_refresh.json";
  // $filejson = $chemin . $nomjson;


  // $data_existante  = file_get_contents($filejson);

  // $data_existante = json_decode($data_existante, true);
  // // var_dump($data_existante);
  // if (json_last_error() != JSON_ERROR_NONE) {
  //   echo 'Erreur JSON : ' . json_last_error_msg();
  // }

  // $data_webhook = json_decode($webhook_data, true);
  // // var_dump($data_webhook);

  // $combination = array_merge($data_existante, $data_webhook);
  // // var_dump($combination);
  // $combi_json = json_encode($combination, JSON_PRETTY_PRINT);

  // var_dump($combi_json);

  $nomfichier = "/src/webhooks/txt/webhook_item_refresh.txt";
  $fichier = $chemin . $nomfichier;

  file_put_contents($fichier, "[" . $horodatage . "]\n?" . $webhook_data . "?\nHash : " . $hash . "\n\n", FILE_APPEND);
}
