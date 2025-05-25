<?php
// Récupérer les données JSON envoyées par JavaScript
$data = json_decode(file_get_contents('php://input'), true);

// Vérifier si les données sont bien reçues
if (isset($data['largeur']) && isset($data['hauteur'])) {
    // Extraire la largeur et la hauteur
    $largeur = $data['largeur'];
    $hauteur = $data['hauteur'];

    
    // Retourner les dimensions sous forme de réponse JSON
    echo json_encode([
        'largeur' => $largeur,
        'hauteur' => $hauteur
    ]);
} else {
    // Retourner une erreur si les données ne sont pas complètes
    http_response_code(400);
    echo json_encode(['message' => 'Données incomplètes']);
}
