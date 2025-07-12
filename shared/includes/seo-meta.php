<?php

/**
 * Fichier de métadonnées SEO partagées
 * À inclure dans tous les modules pour un SEO optimisé
 */

class SEOMetaData
{

  /**
   * Génère les balises meta Open Graph pour les réseaux sociaux
   * @param array $data Données de la page (title, description, image, url)
   * @return string HTML des balises meta
   */
  public static function generateOpenGraphMeta($data)
  {
    $defaultData = [
      'title' => 'EJERI Jardins - Entretien de jardins et réduction d\'impôts 50%',
      'description' => 'Services d\'entretien de jardins avec 50% de réduction d\'impôts. Professionnels qualifiés, devis gratuit.',
      'image' => 'https://ejeri.fr/assets/img/logo/logo_ejeri.png',
      'url' => 'https://ejeri.fr',
      'type' => 'website',
      'locale' => 'fr_FR',
      'site_name' => 'EJERI Jardins'
    ];

    $data = array_merge($defaultData, $data);

    $html = "\n  <!-- Open Graph / Facebook -->\n";
    $html .= "  <meta property=\"og:type\" content=\"{$data['type']}\">\n";
    $html .= "  <meta property=\"og:url\" content=\"{$data['url']}\">\n";
    $html .= "  <meta property=\"og:title\" content=\"" . htmlspecialchars($data['title']) . "\">\n";
    $html .= "  <meta property=\"og:description\" content=\"" . htmlspecialchars($data['description']) . "\">\n";
    $html .= "  <meta property=\"og:image\" content=\"{$data['image']}\">\n";
    $html .= "  <meta property=\"og:locale\" content=\"{$data['locale']}\">\n";
    $html .= "  <meta property=\"og:site_name\" content=\"{$data['site_name']}\">\n";

    return $html;
  }

  /**
   * Génère les balises meta Twitter Card
   * @param array $data Données de la page
   * @return string HTML des balises meta
   */
  public static function generateTwitterMeta($data)
  {
    $defaultData = [
      'title' => 'EJERI Jardins - Entretien de jardins et réduction d\'impôts',
      'description' => 'Services d\'entretien de jardins avec 50% de réduction d\'impôts. Professionnels qualifiés, devis gratuit.',
      'image' => 'https://ejeri.fr/assets/img/logo/logo_ejeri.png',
      'url' => 'https://ejeri.fr',
      'card' => 'summary_large_image'
    ];

    $data = array_merge($defaultData, $data);

    $html = "\n  <!-- Twitter -->\n";
    $html .= "  <meta name=\"twitter:card\" content=\"{$data['card']}\">\n";
    $html .= "  <meta name=\"twitter:url\" content=\"{$data['url']}\">\n";
    $html .= "  <meta name=\"twitter:title\" content=\"" . htmlspecialchars($data['title']) . "\">\n";
    $html .= "  <meta name=\"twitter:description\" content=\"" . htmlspecialchars($data['description']) . "\">\n";
    $html .= "  <meta name=\"twitter:image\" content=\"{$data['image']}\">\n";

    return $html;
  }

  /**
   * Génère le Schema.org JSON-LD pour une entreprise locale
   * @param array $data Données de l'entreprise
   * @return string JSON-LD
   */
  public static function generateLocalBusinessSchema($data)
  {
    $defaultData = [
      'name' => 'EJERI Jardins',
      'description' => 'Entretien de jardins et services à la personne avec 50% de réduction d\'impôts',
      'url' => 'https://ejeri.fr',
      'telephone' => '+33-1-XX-XX-XX-XX',
      'priceRange' => '€€',
      'serviceType' => 'Entretien de jardins',
      'areaServed' => 'France',
      'latitude' => 46.603354,
      'longitude' => 1.888334,
      'addressCountry' => 'FR',
      'addressLocality' => 'France',
      'openingHours' => 'Mo-Sa 08:00-18:00'
    ];

    $data = array_merge($defaultData, $data);

    $services = [
      [
        'name' => 'Tonte de pelouse',
        'description' => 'Service de tonte professionnel avec réduction d\'impôts de 50%'
      ],
      [
        'name' => 'Taille de haies',
        'description' => 'Taille et entretien de haies avec réduction d\'impôts de 50%'
      ],
      [
        'name' => 'Débroussaillage',
        'description' => 'Débroussaillage et nettoyage de terrain avec réduction d\'impôts de 50%'
      ]
    ];

    $schema = [
      '@context' => 'https://schema.org',
      '@type' => 'LocalBusiness',
      'name' => $data['name'],
      'description' => $data['description'],
      'url' => $data['url'],
      'telephone' => $data['telephone'],
      'priceRange' => $data['priceRange'],
      'address' => [
        '@type' => 'PostalAddress',
        'addressCountry' => $data['addressCountry'],
        'addressLocality' => $data['addressLocality']
      ],
      'geo' => [
        '@type' => 'GeoCoordinates',
        'latitude' => $data['latitude'],
        'longitude' => $data['longitude']
      ],
      'openingHours' => $data['openingHours'],
      'serviceType' => $data['serviceType'],
      'areaServed' => $data['areaServed'],
      'hasOfferCatalog' => [
        '@type' => 'OfferCatalog',
        'name' => 'Services de jardinage',
        'itemListElement' => array_map(function ($service) {
          return [
            '@type' => 'Offer',
            'itemOffered' => [
              '@type' => 'Service',
              'name' => $service['name'],
              'description' => $service['description']
            ]
          ];
        }, $services)
      ],
      'potentialAction' => [
        '@type' => 'SearchAction',
        'target' => $data['url'] . '/?s={search_term_string}',
        'query-input' => 'required name=search_term_string'
      ]
    ];

    return "\n  <script type=\"application/ld+json\">\n  " . json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n  </script>\n";
  }

  /**
   * Génère les balises de preload pour les performances
   * @param array $resources Ressources à preloader
   * @return string HTML des balises preload
   */
  public static function generatePreloadTags($resources)
  {
    $html = "\n  <!-- Preload des ressources critiques -->\n";

    foreach ($resources as $resource) {
      $html .= "  <link rel=\"preload\" href=\"{$resource['href']}\" as=\"{$resource['as']}\"";
      if (isset($resource['type'])) {
        $html .= " type=\"{$resource['type']}\"";
      }
      if (isset($resource['crossorigin'])) {
        $html .= " crossorigin";
      }
      $html .= ">\n";
    }

    return $html;
  }
}
