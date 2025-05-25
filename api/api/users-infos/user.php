<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Récupérer l'ID utilisateur et le type d'information
$userid = isset($_GET['userid']) ? (int)$_GET['userid'] : null; // Récupère l'ID utilisateur
$info = isset($_GET['info']) ? $_GET['info'] : null; // Récupère le type d'information

// Initialiser un tableau pour stocker les données
$response = [
  'userid' => $userid,
  'info' => $info,

];

// Parcourir tous les paramètres de la requête GET
foreach ($_GET as $key => $value) {
  // Ignorer les clés déjà utilisées
  if ($key !== 'userid' && $key !== 'info') {
    $response['parameters'][$key] = $value; // Ajouter les autres paramètres au tableau
  }
}

// Définir l'en-tête de la réponse en JSON
header('Content-Type: application/json');

// Convertir le tableau en JSON et l'afficher
echo json_encode($response);
