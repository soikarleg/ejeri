<?php

#92df6a0e-6d33-491d-9b5e-a6df875f84dd


$secret = '92df6a0e-6d33-491d-9b5e-a6df875f84dd';
$webhook_data = file_get_contents('php://input');
$headers = getallheaders();
$signature =  $headers['Bridgeapi-Signature'] . '    ';

$signature = explode(',', $signature);
$signature = $signature[0];


$hash = hash_hmac('sha256', $webhook_data, $secret);

$hash = strtoupper($hash);

$validation = strcmp($signature, 'v1=' . $hash);
//var_dump($validation);
$chemin = $_SERVER['DOCUMENT_ROOT'];
$horodatage = date('Y-m-d_H-i-s');



if ($validation === 0) {


  $nomjson = "/src/webhooks/json/bridge_data.json";
  $filejson = $chemin . $nomjson;


  $data_existante  = file_get_contents($filejson);

  $data_existante = json_decode($data_existante, true);
  //var_dump($data_existante);
  if (json_last_error() != JSON_ERROR_NONE) {
    echo 'Erreur JSON : ' . json_last_error_msg();
  }

  $data_webhook = json_decode($webhook_data, true);
  //var_dump($data_webhook);

  $combination = array_merge($data_existante, $data_webhook);
  //var_dump($combination);
  $combi_json = json_encode($combination, JSON_PRETTY_PRINT);

  //var_dump($combi_json);

  $nomfichier = "/src/webhooks/txt/webhook_data.txt";
  $fichier = $chemin . $nomfichier;

  if (file_put_contents($filejson, $combi_json) === false) {
    die('Erreur lors de l\'écriture dans le fichier JSON.');
  }
  file_put_contents($filejson, $combi_json);

  file_put_contents($fichier, "[" . $horodatage . "]\n?" . $webhook_data . "?\nHash : " . $hash . "\n\n", FILE_APPEND);
}
