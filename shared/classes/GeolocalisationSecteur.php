<?php
// Définir PROJECT_ROOT seulement s'il n'est pas déjà défini
if (!defined('PROJECT_ROOT')) {
  define('PROJECT_ROOT', dirname(__FILE__, 3));
}


class GeolocalisationSecteur
{
  private $secteursData;
  private $secteurDefaut;
  private $cacheExpiry = 3600; // 1 heure

  public function __construct()
  {
    $jsonPath = PROJECT_ROOT . '/shared/data/secteurs.json';

    if (!file_exists($jsonPath)) {
      throw new Exception("Fichier secteurs.json introuvable : {$jsonPath}");
    }

    $jsonData = json_decode(file_get_contents($jsonPath), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
      throw new Exception("Erreur JSON dans secteurs.json : " . json_last_error_msg());
    }

    $this->secteursData = $jsonData['secteurs'] ?? [];
    $this->secteurDefaut = $jsonData['secteur_defaut'] ?? null;
  }

  /**
   * Obtient les coordonnées à partir de l'IP utilisateur avec cache
   */
  public function getCoordonneesByIP($ip = null)
  {
    // S'assurer que la session est démarrée (seulement si aucun header n'a été envoyé)
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
      session_start();
    }

    if (!$ip) {
      $ip = $this->getClientIP();
    }

    // Protection IP locale/interne
    if ($this->isLocalIP($ip)) {
      if (isset($_GET['debug']) || isset($_SERVER['DEBUG_MODE'])) {
        error_log("IP locale détectée ({$ip}), utilisation secteur par défaut");
      }
      return $this->secteurDefaut;
    }

    // Vérifier le cache en session
    $cacheKey = 'secteur_cache_' . md5($ip);
    if (isset($_SESSION[$cacheKey])) {
      $cache = $_SESSION[$cacheKey];
      if (time() - $cache['timestamp'] < $this->cacheExpiry) {
        error_log("Secteur trouvé en cache pour IP: {$ip}");
        return $cache['secteur'];
      }
    }

    try {
      $secteur = $this->getGeolocationFromAPI($ip);

      // Stocker en cache
      $_SESSION[$cacheKey] = [
        'secteur' => $secteur,
        'timestamp' => time()
      ];

      return $secteur;
    } catch (Exception $e) {
      error_log("Erreur géolocalisation IP {$ip}: " . $e->getMessage());
      return $this->secteurDefaut;
    }
  }

  /**
   * Obtient la vraie IP du client (support IPv4 et IPv6)
   */
  private function getClientIP()
  {
    $ipKeys = [
      'HTTP_CF_CONNECTING_IP',     // Cloudflare
      'HTTP_X_FORWARDED_FOR',      // Proxy standard
      'HTTP_X_REAL_IP',            // Nginx proxy
      'HTTP_X_CLUSTER_CLIENT_IP',  // Cluster
      'HTTP_CLIENT_IP',            // Proxy client
      'REMOTE_ADDR'                // IP directe
    ];

    foreach ($ipKeys as $key) {
      if (!empty($_SERVER[$key])) {
        $ips = explode(',', $_SERVER[$key]);

        foreach ($ips as $ip) {
          $ip = trim($ip);

          // Valider IPv4 et IPv6 publiques
          if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            // Log pour diagnostic seulement en mode debug
            if (isset($_GET['debug']) || isset($_SERVER['DEBUG_MODE'])) {
              error_log("IP détectée via {$key}: {$ip} (IPv" . (strpos($ip, ':') !== false ? '6' : '4') . ")");
            }
            return $ip;
          }
        }
      }
    }

    $fallbackIP = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    if (isset($_GET['debug']) || isset($_SERVER['DEBUG_MODE'])) {
      error_log("IP fallback utilisée: {$fallbackIP}");
    }
    return $fallbackIP;
  }

  /**
   * Vérifie si l'IP est locale (support IPv4 et IPv6)
   */
  private function isLocalIP($ip)
  {
    // IPs locales IPv4 et IPv6
    $localIPs = ['127.0.0.1', '::1', 'localhost'];

    if (in_array($ip, $localIPs)) {
      return true;
    }

    // Vérifier si c'est une IP privée ou réservée
    if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
      return true;
    }

    // Vérifications supplémentaires pour IPv6
    if (strpos($ip, ':') !== false) {
      // IPv6 locale (fe80::/10, fc00::/7, etc.)
      if (preg_match('/^(fe80|fc00|fd00):/i', $ip)) {
        return true;
      }
    }

    return false;
  }

  /**
   * Récupère la géolocalisation via API avec fallback (support IPv4/IPv6)
   */
  private function getGeolocationFromAPI($ip)
  {
    $isIPv6 = strpos($ip, ':') !== false;
    error_log("Géolocalisation pour IP: {$ip} (IPv" . ($isIPv6 ? '6' : '4') . ")");

    // APIs avec support IPv6
    $apis = [
      [
        'url' => "https://ipapi.co/{$ip}/json/",
        'supports_ipv6' => true
      ],
      [
        'url' => "http://ip-api.com/json/{$ip}?fields=status,lat,lon,country,regionName,city",
        'supports_ipv6' => true
      ],
      [
        'url' => "https://ipinfo.io/{$ip}/json",
        'supports_ipv6' => true
      ],
      [
        'url' => "https://ipgeolocation.io/ipgeo?apiKey=&ip={$ip}",
        'supports_ipv6' => false // API limitée IPv4 seulement
      ]
    ];

    foreach ($apis as $api) {
      // Skip IPv6 non supportées
      if ($isIPv6 && !$api['supports_ipv6']) {
        continue;
      }

      try {
        $context = stream_context_create([
          'http' => [
            'timeout' => 5,
            'method' => 'GET',
            'header' => [
              'User-Agent: EJERI-Geolocation/2.0',
              'Accept: application/json'
            ],
            'ignore_errors' => true
          ]
        ]);

        error_log("Tentative API: " . $api['url']);
        $response = @file_get_contents($api['url'], false, $context);

        if ($response === false) {
          error_log("Échec connexion API: " . $api['url']);
          continue;
        }

        $data = json_decode($response, true);

        if (!$data) {
          error_log("Réponse JSON invalide de: " . $api['url']);
          continue;
        }

        // Log pour diagnostic
        error_log("Réponse API: " . json_encode($data));

        // Normaliser les données selon l'API
        $lat = $data['latitude'] ?? $data['lat'] ?? null;
        $lon = $data['longitude'] ?? $data['lon'] ?? null;

        if ($lat && $lon) {
          error_log("Coordonnées trouvées: {$lat}, {$lon}");
          $secteur = $this->trouverSecteurLesPlusProche($lat, $lon);
          error_log("Secteur trouvé: " . ($secteur ? $secteur['nom'] : 'aucun'));
          return $secteur;
        }
      } catch (Exception $e) {
        error_log("Erreur API géolocalisation {$api['url']}: " . $e->getMessage());
        continue;
      }
    }

    error_log("Aucune géolocalisation trouvée, utilisation secteur par défaut");
    return $this->secteurDefaut;
  }

  /**
   * Trouve le secteur le plus proche et vérifie la distance
   */
  private function trouverSecteurLesPlusProche($lat, $lon)
  {
    $secteurProche = null;
    $distanceMin = PHP_FLOAT_MAX;

    foreach ($this->secteursData as $secteur) {
      $distance = $this->calculerDistanceKm(
        $lat,
        $lon,
        $secteur['centre']['latitude'],
        $secteur['centre']['longitude']
      );

      // Vérifier si dans le rayon de couverture
      if ($distance <= $secteur['rayon_km'] && $distance < $distanceMin) {
        $distanceMin = $distance;
        $secteurProche = $secteur;
      }
    }

    return $secteurProche ?: $this->secteurDefaut;
  }

  /**
   * Calcule la distance entre deux points GPS (formule de Haversine)
   */
  private function calculerDistanceKm($lat1, $lon1, $lat2, $lon2)
  {
    $earthRadius = 6371; // Rayon de la Terre en km

    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat / 2) * sin($dLat / 2) +
      cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
      sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return $earthRadius * $c;
  }

  /**
   * Obtient tous les secteurs disponibles
   */
  public function getTousLesSecteurs()
  {
    return $this->secteursData;
  }

  /**
   * Obtient un secteur par son ID
   */
  public function getSecteurById($id)
  {
    foreach ($this->secteursData as $secteur) {
      if ($secteur['id'] === $id) {
        return $secteur;
      }
    }
    return $this->secteurDefaut;
  }

  /**
   * Permet de forcer un secteur spécifique (sélection manuelle)
   */
  public function setSecteurForce($secteurId)
  {
    $secteur = $this->getSecteurById($secteurId);
    $_SESSION['secteur_force'] = $secteur;
    return $secteur;
  }

  /**
   * Récupère le secteur forcé s'il existe
   */
  public function getSecteurForce()
  {
    return $_SESSION['secteur_force'] ?? null;
  }

  /**
   * Vide le cache et le secteur forcé
   */
  public function clearCache()
  {
    foreach ($_SESSION as $key => $value) {
      if (strpos($key, 'secteur_cache_') === 0) {
        unset($_SESSION[$key]);
      }
    }
    unset($_SESSION['secteur_force']);
  }

  /**
   * Méthode de diagnostic pour analyser les problèmes de géolocalisation
   */
  public function getDiagnosticInfo($ip = null)
  {
    if (!$ip) {
      $ip = $this->getClientIP();
    }

    $diagnostic = [
      'ip_detectee' => $ip,
      'type_ip' => strpos($ip, ':') !== false ? 'IPv6' : 'IPv4',
      'is_local' => $this->isLocalIP($ip),
      'server_vars' => [
        'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'] ?? 'non défini',
        'HTTP_X_FORWARDED_FOR' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? 'non défini',
        'HTTP_X_REAL_IP' => $_SERVER['HTTP_X_REAL_IP'] ?? 'non défini',
        'HTTP_CF_CONNECTING_IP' => $_SERVER['HTTP_CF_CONNECTING_IP'] ?? 'non défini',
      ],
      'secteurs_disponibles' => count($this->secteursData),
      'secteur_defaut' => $this->secteurDefaut['nom'] ?? 'non défini'
    ];

    // Test des APIs de géolocalisation
    if (!$this->isLocalIP($ip)) {
      $diagnostic['test_apis'] = [];
      $apis = [
        'ipapi.co' => "https://ipapi.co/{$ip}/json/",
        'ip-api.com' => "http://ip-api.com/json/{$ip}?fields=status,lat,lon,country,regionName,city",
        'ipinfo.io' => "https://ipinfo.io/{$ip}/json"
      ];

      foreach ($apis as $name => $url) {
        try {
          $context = stream_context_create([
            'http' => [
              'timeout' => 3,
              'method' => 'GET',
              'header' => 'User-Agent: EJERI-Diagnostic/1.0'
            ]
          ]);

          $response = @file_get_contents($url, false, $context);
          $data = json_decode($response, true);

          $diagnostic['test_apis'][$name] = [
            'success' => $response !== false && $data !== null,
            'has_coordinates' => isset($data['lat']) || isset($data['latitude']),
            'response_size' => strlen($response ?: ''),
            'data_preview' => $data ? array_slice($data, 0, 5, true) : null
          ];
        } catch (Exception $e) {
          $diagnostic['test_apis'][$name] = [
            'success' => false,
            'error' => $e->getMessage()
          ];
        }
      }
    }

    return $diagnostic;
  }
}
