<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'inc/function.php';


echo generateApiKey();
echo '</br>';
echo generateApiSecret();
// index.php

// Vérifie si les paramètres 'groupe' et 'action' sont définis
if (isset($_GET['groupe']) && isset($_GET['action'])) {
  echo $groupe = $_GET['groupe']; // Ex: users-infos
  echo $action = $_GET['action']; // Ex: user

  // Construire le chemin du fichier à inclure
  $filePath = "groupe/" . $groupe . ".php";

  // Vérifie si le fichier existe
  if (file_exists($filePath)) {
    require $filePath; // Inclut le fichier de groupe correspondant
  } else {
    http_response_code(404); // Fichier non trouvé
    echo json_encode(['error' => 'groupe non trouvé']);
  }
} else {
  http_response_code(400); // Mauvaise requête
  echo json_encode(['error' => 'Manque infos']);
}
