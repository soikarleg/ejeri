<?php

/**
 * Test unitaire pour le système de géolocalisation par secteur
 * Utilisation : php test-geolocalisation.php
 */

define('PROJECT_ROOT', dirname(__FILE__));
require_once PROJECT_ROOT . '/shared/classes/GeolocalisationSecteur.php';

class TestGeolocalisationSecteur
{
  private $geo;
  private $testsPassed = 0;
  private $testsFailed = 0;

  public function __construct()
  {
    session_start();
    $this->geo = new GeolocalisationSecteur();
    echo "🚀 Test du système de géolocalisation EJERI\n";
    echo "==========================================\n\n";
  }

  public function runAllTests()
  {
    $this->testChargementDonnees();
    $this->testCalculDistance();
    $this->testGeolocalisationIP();
    $this->testSecteurForce();
    $this->testCache();

    $this->afficherResultats();
  }

  private function testChargementDonnees()
  {
    echo "📋 Test 1: Chargement des données secteurs\n";

    try {
      $secteurs = $this->geo->getTousLesSecteurs();
      $this->assert(count($secteurs) === 5, "5 secteurs chargés");

      $secteurNantes = $this->geo->getSecteurById('nantes');
      $this->assert($secteurNantes['nom'] === 'EJERI Jardins - Nantes', "Secteur Nantes trouvé");

      echo "✅ Chargement des données OK\n\n";
    } catch (Exception $e) {
      echo "❌ Erreur: " . $e->getMessage() . "\n\n";
      $this->testsFailed++;
    }
  }

  private function testCalculDistance()
  {
    echo "📐 Test 2: Calcul de distance\n";

    try {
      $reflection = new ReflectionClass($this->geo);
      $method = $reflection->getMethod('calculerDistanceKm');
      $method->setAccessible(true);

      // Distance Paris -> Nantes (environ 380km)
      $distance = $method->invoke($this->geo, 48.8566, 2.3522, 47.2184, -1.5536);
      $this->assert($distance > 350 && $distance < 400, "Distance Paris-Nantes ≈ 380km");

      echo "✅ Calcul de distance OK\n\n";
    } catch (Exception $e) {
      echo "❌ Erreur: " . $e->getMessage() . "\n\n";
      $this->testsFailed++;
    }
  }

  private function testGeolocalisationIP()
  {
    echo "🌐 Test 3: Géolocalisation par IP\n";

    try {
      // Test avec IP localhost (doit retourner secteur par défaut)
      $secteurLocal = $this->geo->getCoordonneesByIP('127.0.0.1');
      $this->assert($secteurLocal['id'] === 'nantes', "IP locale -> secteur par défaut");

      // Test avec IP française (simulation)
      $secteurFR = $this->geo->getCoordonneesByIP('8.8.8.8'); // IP publique pour test
      $this->assert(!empty($secteurFR['nom']), "IP publique -> secteur trouvé");

      echo "✅ Géolocalisation IP OK\n\n";
    } catch (Exception $e) {
      echo "❌ Erreur: " . $e->getMessage() . "\n\n";
      $this->testsFailed++;
    }
  }

  private function testSecteurForce()
  {
    echo "🎯 Test 4: Secteur forcé\n";

    try {
      // Forcer un secteur
      $secteurForce = $this->geo->setSecteurForce('cholet');
      $this->assert($secteurForce['id'] === 'cholet', "Secteur Cholet forcé");

      // Vérifier que le secteur forcé est récupéré
      $secteurRecupere = $this->geo->getSecteurForce();
      $this->assert($secteurRecupere['id'] === 'cholet', "Secteur forcé récupéré");

      // Nettoyer
      $this->geo->clearCache();

      echo "✅ Secteur forcé OK\n\n";
    } catch (Exception $e) {
      echo "❌ Erreur: " . $e->getMessage() . "\n\n";
      $this->testsFailed++;
    }
  }

  private function testCache()
  {
    echo "💾 Test 5: Système de cache\n";

    try {
      // Vider le cache
      $this->geo->clearCache();

      // Premier appel (doit utiliser l'API)
      $start = microtime(true);
      $secteur1 = $this->geo->getCoordonneesByIP('8.8.8.8');
      $time1 = microtime(true) - $start;

      // Deuxième appel (doit utiliser le cache)
      $start = microtime(true);
      $secteur2 = $this->geo->getCoordonneesByIP('8.8.8.8');
      $time2 = microtime(true) - $start;

      $this->assert($secteur1['id'] === $secteur2['id'], "Même secteur retourné");
      $this->assert($time2 < $time1, "Cache plus rapide que l'API");

      echo "✅ Système de cache OK\n\n";
    } catch (Exception $e) {
      echo "❌ Erreur: " . $e->getMessage() . "\n\n";
      $this->testsFailed++;
    }
  }

  private function assert($condition, $message)
  {
    if ($condition) {
      echo "  ✓ $message\n";
      $this->testsPassed++;
    } else {
      echo "  ✗ $message\n";
      $this->testsFailed++;
    }
  }

  private function afficherResultats()
  {
    echo "📊 RÉSULTATS DES TESTS\n";
    echo "======================\n";
    echo "✅ Tests réussis: {$this->testsPassed}\n";
    echo "❌ Tests échoués: {$this->testsFailed}\n";

    if ($this->testsFailed === 0) {
      echo "🎉 Tous les tests sont passés avec succès!\n";
    } else {
      echo "⚠️  Certains tests ont échoué. Vérifiez la configuration.\n";
    }

    echo "\n📋 VÉRIFICATIONS SUPPLÉMENTAIRES RECOMMANDÉES:\n";
    echo "- Tester avec différentes IP géographiques\n";
    echo "- Vérifier les performances sous charge\n";
    echo "- Tester la robustesse en cas de panne d'API\n";
    echo "- Ajouter les photos des responsables de secteur\n";
    echo "- Configurer les vraies coordonnées GPS des secteurs\n";
  }
}

// Lancement des tests
if (php_sapi_name() === 'cli') {
  $tester = new TestGeolocalisationSecteur();
  $tester->runAllTests();
} else {
  echo "<pre>";
  $tester = new TestGeolocalisationSecteur();
  $tester->runAllTests();
  echo "</pre>";
}
