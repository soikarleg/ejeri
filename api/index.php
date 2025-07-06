<?php
define('PROJECT_ROOT', dirname(__FILE__, 2));
require_once PROJECT_ROOT . '/api/controllers/SecteurController.php';

// Gestion des routes API
$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);
$segments = explode('/', trim($path, '/'));

// Debug pour le développement
if (isset($_GET['debug'])) {
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
}

// Routes API secteur
if (count($segments) >= 2 && $segments[1] === 'secteur') {
  try {
    $controller = new SecteurController();

    switch ($segments[2] ?? '') {
      case 'by-ip':
        $controller->getSecteurByIP();
        break;

      case 'all':
        $controller->getAllSecteurs();
        break;

      case 'set-force':
        $controller->setSecteurForce();
        break;

      case 'clear-cache':
        $controller->clearCache();
        break;

      case 'diagnostic':
        $controller->getDiagnostic();
        break;

      default:
        http_response_code(404);
        echo json_encode([
          'success' => false,
          'error' => 'Endpoint secteur non trouvé',
          'available_endpoints' => [
            '/api/secteur/by-ip',
            '/api/secteur/all',
            '/api/secteur/set-force',
            '/api/secteur/clear-cache',
            '/api/secteur/diagnostic'
          ]
        ]);
    }
  } catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
      'success' => false,
      'error' => 'Erreur serveur secteur',
      'message' => $e->getMessage(),
      'timestamp' => date('Y-m-d H:i:s')
    ]);
  }
  exit;
}

// API existante - maintenir la compatibilité
include 'inc/function.php';

// Vérifie si les paramètres 'groupe' et 'action' sont définis
if (isset($_GET['groupe']) && isset($_GET['action'])) {
  $groupe = $_GET['groupe']; // Ex: users-infos
  $action = $_GET['action']; // Ex: user

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
  // Page d'accueil de l'API
  header('Content-Type: application/json');
  echo json_encode([
    'success' => true,
    'message' => 'API EJERI',
    'version' => '1.0',
    'endpoints' => [
      'Secteurs' => [
        'GET /api/secteur/by-ip' => 'Obtient le secteur selon l\'IP',
        'GET /api/secteur/all' => 'Liste tous les secteurs',
        'POST /api/secteur/set-force' => 'Force un secteur spécifique',
        'POST /api/secteur/clear-cache' => 'Vide le cache de géolocalisation'
      ],
      'Existant' => '?groupe=XXX&action=XXX'
    ],
    'timestamp' => date('Y-m-d H:i:s')
  ]);
}
