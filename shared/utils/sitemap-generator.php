<?php

/**
 * Générateur automatique de sitemap.xml
 * À exécuter périodiquement pour maintenir le sitemap à jour
 */

class SitemapGenerator
{

  private $baseUrl = 'https://ejeri.fr';
  private $urls = [];

  public function __construct()
  {
    $this->addStaticPages();
    $this->addServicePages();
    $this->addDynamicPages();
  }

  /**
   * Ajoute les pages statiques principales
   */
  private function addStaticPages()
  {
    $this->addUrl('/', '2025-07-08', 'weekly', 1.0);
    $this->addUrl('/#services', '2025-07-08', 'monthly', 0.8);
    $this->addUrl('/#team', '2025-07-08', 'monthly', 0.7);
    $this->addUrl('/#faq', '2025-07-08', 'monthly', 0.6);
    $this->addUrl('/#contact', '2025-07-08', 'monthly', 0.6);
  }

  /**
   * Ajoute les pages de services spécialisées
   */
  private function addServicePages()
  {
    $services = [
      'services/tonte-pelouse.html' => 0.8,
      'services/taille-haies.html' => 0.8,
      'services/debroussaillage.html' => 0.8,
      'services/entretien-massifs.html' => 0.7,
      'services/ramassage-feuilles.html' => 0.7,
    ];

    foreach ($services as $service => $priority) {
      $this->addUrl($service, '2025-07-08', 'monthly', $priority);
    }
  }

  /**
   * Ajoute les pages dynamiques (FAQ, blog, etc.)
   */
  private function addDynamicPages()
  {
    $this->addUrl('faq.html', '2025-07-08', 'monthly', 0.7);

    // Ajouter les pages de blog si elles existent
    if (file_exists(__DIR__ . '/blog/')) {
      $this->addBlogPages();
    }
  }

  /**
   * Ajoute les pages de blog si elles existent
   */
  private function addBlogPages()
  {
    $blogDir = __DIR__ . '/blog/';
    if (is_dir($blogDir)) {
      $files = scandir($blogDir);
      foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'html') {
          $this->addUrl('blog/' . $file, date('Y-m-d'), 'weekly', 0.6);
        }
      }
    }
  }

  /**
   * Ajoute une URL au sitemap
   */
  private function addUrl($path, $lastmod, $changefreq, $priority)
  {
    $this->urls[] = [
      'loc' => $this->baseUrl . ($path !== '/' ? '/' . ltrim($path, '/') : ''),
      'lastmod' => $lastmod,
      'changefreq' => $changefreq,
      'priority' => $priority
    ];
  }

  /**
   * Génère le fichier sitemap.xml
   */
  public function generate()
  {
    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . "\n";

    foreach ($this->urls as $url) {
      $xml .= "  <url>\n";
      $xml .= "    <loc>{$url['loc']}</loc>\n";
      $xml .= "    <lastmod>{$url['lastmod']}</lastmod>\n";
      $xml .= "    <changefreq>{$url['changefreq']}</changefreq>\n";
      $xml .= "    <priority>{$url['priority']}</priority>\n";
      $xml .= "  </url>\n";
    }

    $xml .= '</urlset>';

    return $xml;
  }

  /**
   * Sauvegarde le sitemap dans un fichier
   */
  public function save($filename = 'sitemap.xml')
  {
    $xml = $this->generate();
    $filepath = __DIR__ . '/' . $filename;

    if (file_put_contents($filepath, $xml)) {
      return "Sitemap généré avec succès : {$filepath}";
    } else {
      return "Erreur lors de la génération du sitemap";
    }
  }

  /**
   * Génère un sitemap pour les images
   */
  public function generateImageSitemap()
  {
    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

    // Ajouter les images importantes
    $images = [
      ['page' => '/', 'image' => '/assets/img/logo/logo_ejeri.png', 'title' => 'Logo EJERI Jardins'],
      ['page' => '/services/tonte-pelouse.html', 'image' => '/assets/img/services/tonte-pelouse.jpg', 'title' => 'Service de tonte de pelouse'],
      ['page' => '/services/taille-haies.html', 'image' => '/assets/img/services/taille-haies.jpg', 'title' => 'Service de taille de haies'],
    ];

    foreach ($images as $img) {
      $xml .= "  <url>\n";
      $xml .= "    <loc>{$this->baseUrl}{$img['page']}</loc>\n";
      $xml .= "    <image:image>\n";
      $xml .= "      <image:loc>{$this->baseUrl}{$img['image']}</image:loc>\n";
      $xml .= "      <image:title>{$img['title']}</image:title>\n";
      $xml .= "    </image:image>\n";
      $xml .= "  </url>\n";
    }

    $xml .= '</urlset>';

    return $xml;
  }
}

// Utilisation
if (php_sapi_name() === 'cli') {
  // Exécution en ligne de commande
  $generator = new SitemapGenerator();
  echo $generator->save();
  echo "\n";

  // Générer aussi le sitemap des images
  $imageSitemap = $generator->generateImageSitemap();
  file_put_contents(__DIR__ . '/sitemap-images.xml', $imageSitemap);
  echo "Sitemap des images généré : sitemap-images.xml\n";
} else {
  // Exécution via navigateur (pour tests)
  $generator = new SitemapGenerator();
  header('Content-Type: application/xml');
  echo $generator->generate();
}
