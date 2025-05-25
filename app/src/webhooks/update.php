<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
$secret = '0fc00950-9a2d-46f1-b736-008ba3f575a0';
$webhook_data = file_get_contents('php://input');
$webhook_data = file_get_contents($chemin . '/src/webhooks/json/bridge_data.json');
//$webhook_data = '{"content":{"item_id":1234567890,"status":0,"user_uuid":"9a95b38f-f98b-417a-988b-9d0d584893e7"},"timestamp":1611681789,"type":"TEST_EVENT"}';
// $headers = getallheaders();
// $signature =  $headers['Bridgeapi-Signature'];
// $hash = hash_hmac('sha256', $webhook_data, $secret);

// $hash = strtoupper($hash);

// $validation = strcmp($signature, 'v1=' . $hash);


$horodatage = date('Y-m-d_H-i-s');

$d = json_decode($webhook_data, true);
//var_dump($d);

$timestampo = $d['timestamp']/1000;
echo $date_format = date("d/m/Y H:i", $timestampo);
echo '<br>';
foreach ($d['content'] as $k => $v) {

  echo $k . ' ' . $v . '<br>';
};

$validation = 1;
if ($validation === 0) {


  $nomjson = "/src/webhooks/json/bridge_update.json";
  $filejson = $chemin . $nomjson;


//   $data_existante  = file_get_contents($filejson);
  
//   $data_existante = json_decode($data_existante,true);
//   var_dump($data_existante);
//   if (json_last_error() != JSON_ERROR_NONE) {
//     echo 'Erreur JSON : ' . json_last_error_msg();
//   }

//   $data_webhook = json_decode($webhook_data,true);
//   var_dump($data_webhook);

//   $combination = array_merge($data_existante, $data_webhook);
//   var_dump($combination);
//   $combi_json = json_encode($combination, JSON_PRETTY_PRINT);

// var_dump($combi_json);

  $nomfichier = "/src/webhooks/txt/webhook_update.txt";
  $fichier = $chemin . $nomfichier;

  if (file_put_contents($filejson, $combi_json) === false) {
    die('Erreur lors de l\'écriture dans le fichier JSON.');
  }
  file_put_contents($filejson, $webhook_data);

  file_put_contents($fichier, "[" . $horodatage . "]\n?" . $webhook_data . "?\nHash : " . $hash . "\n\n", FILE_APPEND);
}
