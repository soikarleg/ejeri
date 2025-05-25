

<?php
function generateSignature($apiSecret, $data)
{
  return hash_hmac('sha256', $data, $apiSecret);
}


function generateApiKey($length = 32)
{
  $api = 'ovg_' . bin2hex(random_bytes($length / 2));
  return  $api; // Génère une clé API aléatoire
}

function generateApiSecret($length = 64)
{
  return bin2hex(random_bytes($length / 2)); // Génère un secret API aléatoire
}

function readJson($fichier)
{
  $path = $_SERVER['DOCUMENT_ROOT'];
  $fichier_sanitized = basename($fichier);
  $chemin_endpoint = $path . '/config/' . $fichier_sanitized;

  if (file_exists($chemin_endpoint)) {
    $contenu_json = file_get_contents($chemin_endpoint);
    $res = json_decode($contenu_json, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
      return 'Erreur de décodage JSON';
    }
  } else {
    return 'Lecture impossible';
  }
  return $res;
}
?>