<?php
// Désactiver l'affichage des erreurs pour éviter la pollution JSON
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);

require_once PROJECT_ROOT . '/shared/classes/GeolocalisationSecteur.php';

class SecteurController
{
  private $geoSecteur;

  public function __construct()
  {
    // Démarrer la session uniquement si nécessaire et si aucun header n'a été envoyé
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
      session_start();
    }
    $this->geoSecteur = new GeolocalisationSecteur();
  }

  /**
   * API endpoint pour obtenir le secteur selon IP
   */
  public function getSecteurByIP()
  {
    // Vider les buffers de sortie pour éviter la pollution
    while (ob_get_level()) {
      ob_end_clean();
    }

    // Démarrer un nouveau buffer propre
    ob_start();

    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');

    try {
      // Vérifier si un secteur est forcé
      $secteurForce = $this->geoSecteur->getSecteurForce();
      if ($secteurForce) {
        echo json_encode([
          'success' => true,
          'secteur' => $secteurForce,
          'source' => 'selection_manuelle',
          'timestamp' => date('Y-m-d H:i:s')
        ]);
        ob_end_flush();
        return;
      }

      $ip = $_GET['ip'] ?? null;
      $secteur = $this->geoSecteur->getCoordonneesByIP($ip);

      echo json_encode([
        'success' => true,
        'secteur' => $secteur,
        'source' => 'geolocalisation_ip',
        'timestamp' => date('Y-m-d H:i:s')
      ]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'success' => false,
        'error' => 'Erreur lors de la récupération du secteur',
        'message' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
      ]);
    }

    ob_end_flush();
  }

  /**
   * Version test qui ne force pas le flush
   */
  public function getSecteurByIPTest()
  {
    try {
      // Vérifier si un secteur est forcé
      $secteurForce = $this->geoSecteur->getSecteurForce();
      if ($secteurForce) {
        return json_encode([
          'success' => true,
          'secteur' => $secteurForce,
          'source' => 'selection_manuelle',
          'timestamp' => date('Y-m-d H:i:s')
        ]);
      }

      $ip = $_GET['ip'] ?? null;
      $secteur = $this->geoSecteur->getCoordonneesByIP($ip);

      return json_encode([
        'success' => true,
        'secteur' => $secteur,
        'source' => 'geolocalisation_ip',
        'timestamp' => date('Y-m-d H:i:s')
      ]);
    } catch (Exception $e) {
      return json_encode([
        'success' => false,
        'error' => 'Erreur lors de la récupération du secteur',
        'message' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
      ]);
    }
  }

  /**
   * API endpoint pour lister tous les secteurs
   */
  public function getAllSecteurs()
  {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');

    try {
      $secteurs = $this->geoSecteur->getTousLesSecteurs();

      echo json_encode([
        'success' => true,
        'secteurs' => $secteurs,
        'count' => count($secteurs),
        'timestamp' => date('Y-m-d H:i:s')
      ]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'success' => false,
        'error' => 'Erreur lors de la récupération des secteurs',
        'message' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
      ]);
    }
  }

  /**
   * API endpoint pour forcer un secteur spécifique
   */
  public function setSecteurForce()
  {
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      http_response_code(405);
      echo json_encode(['success' => false, 'error' => 'Méthode non autorisée']);
      return;
    }

    try {
      $input = json_decode(file_get_contents('php://input'), true);
      $secteurId = $input['secteur_id'] ?? $_POST['secteur_id'] ?? null;

      if (!$secteurId) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'ID secteur requis']);
        return;
      }

      $secteur = $this->geoSecteur->setSecteurForce($secteurId);

      echo json_encode([
        'success' => true,
        'secteur' => $secteur,
        'message' => 'Secteur sélectionné avec succès',
        'timestamp' => date('Y-m-d H:i:s')
      ]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'success' => false,
        'error' => 'Erreur lors de la sélection du secteur',
        'message' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
      ]);
    }
  }

  /**
   * API endpoint pour vider le cache
   */
  public function clearCache()
  {
    header('Content-Type: application/json');

    try {
      $this->geoSecteur->clearCache();

      echo json_encode([
        'success' => true,
        'message' => 'Cache vidé avec succès',
        'timestamp' => date('Y-m-d H:i:s')
      ]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'success' => false,
        'error' => 'Erreur lors du vidage du cache',
        'message' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
      ]);
    }
  }

  /**
   * API endpoint pour diagnostic de géolocalisation
   */
  public function getDiagnostic()
  {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');

    try {
      $ip = $_GET['ip'] ?? null;
      $diagnostic = $this->geoSecteur->getDiagnosticInfo($ip);

      echo json_encode([
        'success' => true,
        'diagnostic' => $diagnostic,
        'timestamp' => date('Y-m-d H:i:s')
      ], JSON_PRETTY_PRINT);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'success' => false,
        'error' => 'Erreur lors du diagnostic',
        'message' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
      ]);
    }
  }

  /**
   * Méthode pour obtenir le secteur actuel (utilisée dans les vues)
   */
  public function getSecteurActuel()
  {
    try {
      return $this->geoSecteur->getCoordonneesByIP();
    } catch (Exception $e) {
      error_log("Erreur getSecteurActuel: " . $e->getMessage());
      return $this->geoSecteur->getSecteurById('nantes'); // Fallback vers Nantes
    }
  }
}
