<?php
// Supprimer les warnings pour le test
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 1);

echo "<h2>Test API Secteur</h2>";

// Test direct de l'API
echo "<h3>1. Test direct classe GeolocalisationSecteur</h3>";
try {
  define('PROJECT_ROOT', dirname(__FILE__));
  require_once PROJECT_ROOT . '/shared/classes/GeolocalisationSecteur.php';

  $geo = new GeolocalisationSecteur();
  $secteur = $geo->getCoordonneesByIP();

  echo "<pre>";
  echo "Secteur trouvé:\n";
  print_r($secteur);
  echo "</pre>";
} catch (Exception $e) {
  echo "<p style='color: red;'>Erreur: " . $e->getMessage() . "</p>";
}

// Test via contrôleur
echo "<h3>2. Test via contrôleur SecteurController</h3>";
try {
  require_once PROJECT_ROOT . '/api/controllers/SecteurController.php';

  $controller = new SecteurController();

  // Utiliser la méthode de test qui ne force pas le flush
  $output = $controller->getSecteurByIPTest();

  echo "<h4>Réponse JSON:</h4>";
  echo "<pre style='background: #f5f5f5; padding: 10px;'>";
  echo htmlspecialchars($output);
  echo "</pre>";

  $data = json_decode($output, true);
  if ($data) {
    echo "<h4>Données parsées:</h4>";
    echo "<pre>";
    print_r($data);
    echo "</pre>";
  } else {
    echo "<p style='color: red;'>Impossible de parser le JSON</p>";
    echo "<p>Erreur JSON: " . json_last_error_msg() . "</p>";
  }
} catch (Exception $e) {
  echo "<p style='color: red;'>Erreur contrôleur: " . $e->getMessage() . "</p>";
}

// Test diagnostic
echo "<h3>3. Test diagnostic</h3>";
try {
  $diagnostic = $geo->getDiagnosticInfo();
  echo "<pre>";
  print_r($diagnostic);
  echo "</pre>";
} catch (Exception $e) {
  echo "<p style='color: red;'>Erreur diagnostic: " . $e->getMessage() . "</p>";
}

echo "<h3>4. Variables serveur</h3>";
echo "<pre>";
echo "REMOTE_ADDR: " . ($_SERVER['REMOTE_ADDR'] ?? 'non défini') . "\n";
echo "HTTP_X_FORWARDED_FOR: " . ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? 'non défini') . "\n";
echo "HTTP_X_REAL_IP: " . ($_SERVER['HTTP_X_REAL_IP'] ?? 'non défini') . "\n";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'non défini') . "\n";
echo "</pre>";
