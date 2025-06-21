<?php
$apiKey = 'your_api_key';
$apiSecret = 'your_api_secret';
$data = "userid=15&info=long"; // Exemple de données à envoyer
$signature = generateSignature($apiSecret, $data);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.otto.com/api/users-info/user?$data");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "API-Key: $apiKey",
  "Signature: $signature",
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
