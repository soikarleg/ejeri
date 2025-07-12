<?php

/**
 * Script d'optimisation automatique des images pour le SEO
 * Génère les attributs alt appropriés et optimise les performances
 */

class ImageOptimizer
{

  private $imageDir;
  private $webpSupported;
  private $optimizedImages = [];

  public function __construct($imageDir = 'assets/img/')
  {
    $this->imageDir = $imageDir;
    $this->webpSupported = extension_loaded('imagick') || extension_loaded('gd');
  }

  /**
   * Génère les attributs alt pour les images basés sur le contexte
   */
  public function generateAltAttributes()
  {
    $altTexts = [
      // Images de services
      'tonte' => 'Service de tonte de pelouse professionnelle EJERI Jardins',
      'taille' => 'Taille de haies professionnelle avec réduction d\'impôts',
      'debroussaillage' => 'Service de débroussaillage et nettoyage de terrain',
      'massif' => 'Entretien de massifs et plantations',
      'feuilles' => 'Ramassage professionnel de feuilles mortes',

      // Images d'équipe
      'jardinier' => 'Jardinier professionnel EJERI Jardins',
      'equipe' => 'Équipe de jardiniers professionnels EJERI',
      'intervenant' => 'Intervenant qualifié en entretien de jardins',

      // Images de logos et interface
      'logo' => 'Logo EJERI Jardins - Entretien de jardins et réduction d\'impôts',
      'favicon' => 'Favicon EJERI Jardins',
      'enooki' => 'Logo Enooki - Plateforme de gestion EJERI',

      // Images de résultats
      'avant' => 'Avant intervention EJERI Jardins',
      'apres' => 'Après intervention EJERI Jardins - Résultat professionnel',
      'resultat' => 'Résultat professionnel après intervention EJERI Jardins'
    ];

    return $altTexts;
  }

  /**
   * Scanne les fichiers HTML pour optimiser les images
   */
  public function optimizeHtmlImages($htmlContent)
  {
    $altTexts = $this->generateAltAttributes();

    // Regex pour trouver les images sans attribut alt ou avec alt vide
    $pattern = '/<img([^>]*?)src=["\']([^"\']*)["\']([^>]*?)(?:alt=["\'][^"\']*["\'])?([^>]*?)>/i';

    $optimizedContent = preg_replace_callback($pattern, function ($matches) use ($altTexts) {
      $beforeSrc = $matches[1];
      $src = $matches[2];
      $afterSrc = $matches[3];
      $afterAlt = $matches[4];

      // Générer un alt approprié basé sur le nom du fichier
      $filename = pathinfo($src, PATHINFO_FILENAME);
      $alt = $this->generateAltForFilename($filename, $altTexts);

      // Ajouter lazy loading si pas présent
      $loading = strpos($beforeSrc . $afterSrc . $afterAlt, 'loading=') === false ? ' loading="lazy"' : '';

      // Reconstruire la balise img
      return '<img' . $beforeSrc . 'src="' . $src . '"' . $afterSrc . ' alt="' . $alt . '"' . $loading . $afterAlt . '>';
    }, $htmlContent);

    return $optimizedContent;
  }

  /**
   * Génère un texte alt approprié basé sur le nom du fichier
   */
  private function generateAltForFilename($filename, $altTexts)
  {
    $filename = strtolower($filename);

    // Recherche directe
    if (isset($altTexts[$filename])) {
      return $altTexts[$filename];
    }

    // Recherche par mots-clés
    foreach ($altTexts as $keyword => $altText) {
      if (strpos($filename, $keyword) !== false) {
        return $altText;
      }
    }

    // Génération automatique basée sur le nom du fichier
    $alt = str_replace(['_', '-'], ' ', $filename);
    $alt = ucwords($alt);
    return $alt . ' - EJERI Jardins';
  }

  /**
   * Génère les balises picture avec support WebP
   */
  public function generatePictureTag($imagePath, $alt, $classes = '')
  {
    $pathInfo = pathinfo($imagePath);
    $webpPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';

    $html = '<picture>';

    // Source WebP si elle existe
    if (file_exists($webpPath)) {
      $html .= '<source srcset="' . $webpPath . '" type="image/webp">';
    }

    // Image par défaut
    $html .= '<img src="' . $imagePath . '" alt="' . htmlspecialchars($alt) . '"';

    if ($classes) {
      $html .= ' class="' . $classes . '"';
    }

    $html .= ' loading="lazy">';
    $html .= '</picture>';

    return $html;
  }

  /**
   * Optimise les images d'un répertoire
   */
  public function optimizeDirectory($directory)
  {
    $results = [];

    if (!is_dir($directory)) {
      return ['error' => 'Répertoire non trouvé'];
    }

    $iterator = new RecursiveIteratorIterator(
      new RecursiveDirectoryIterator($directory)
    );

    foreach ($iterator as $file) {
      if ($file->isFile()) {
        $extension = strtolower($file->getExtension());

        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
          $result = $this->optimizeImage($file->getPathname());
          $results[] = $result;
        }
      }
    }

    return $results;
  }

  /**
   * Optimise une image individuelle
   */
  private function optimizeImage($imagePath)
  {
    $info = getimagesize($imagePath);
    $fileSize = filesize($imagePath);

    return [
      'path' => $imagePath,
      'size' => $fileSize,
      'dimensions' => $info[0] . 'x' . $info[1],
      'type' => $info['mime'],
      'optimized' => $fileSize < 500000 ? 'OK' : 'À optimiser'
    ];
  }

  /**
   * Génère un rapport d'optimisation
   */
  public function generateOptimizationReport()
  {
    $report = [
      'date' => date('Y-m-d H:i:s'),
      'images_total' => 0,
      'images_optimized' => 0,
      'images_to_optimize' => 0,
      'total_size' => 0,
      'recommendations' => []
    ];

    $images = $this->optimizeDirectory($this->imageDir);

    foreach ($images as $image) {
      $report['images_total']++;
      $report['total_size'] += $image['size'];

      if ($image['optimized'] === 'OK') {
        $report['images_optimized']++;
      } else {
        $report['images_to_optimize']++;
        $report['recommendations'][] = 'Optimiser ' . $image['path'] . ' (' . round($image['size'] / 1024, 2) . 'KB)';
      }
    }

    return $report;
  }

  /**
   * Traite tous les fichiers HTML du projet
   */
  public function processAllHtmlFiles($directory = '.')
  {
    $processed = 0;

    $iterator = new RecursiveIteratorIterator(
      new RecursiveDirectoryIterator($directory)
    );

    foreach ($iterator as $file) {
      if ($file->isFile() && $file->getExtension() === 'html') {
        $content = file_get_contents($file->getPathname());
        $optimizedContent = $this->optimizeHtmlImages($content);

        if ($content !== $optimizedContent) {
          file_put_contents($file->getPathname(), $optimizedContent);
          $processed++;
        }
      }
    }

    return $processed;
  }
}

// Utilisation
if (php_sapi_name() === 'cli') {
  $optimizer = new ImageOptimizer();

  echo "=== RAPPORT D'OPTIMISATION DES IMAGES ===\n";
  $report = $optimizer->generateOptimizationReport();

  echo "Date : " . $report['date'] . "\n";
  echo "Images totales : " . $report['images_total'] . "\n";
  echo "Images optimisées : " . $report['images_optimized'] . "\n";
  echo "Images à optimiser : " . $report['images_to_optimize'] . "\n";
  echo "Taille totale : " . round($report['total_size'] / 1024 / 1024, 2) . " MB\n\n";

  if (!empty($report['recommendations'])) {
    echo "=== RECOMMANDATIONS ===\n";
    foreach ($report['recommendations'] as $recommendation) {
      echo "- " . $recommendation . "\n";
    }
  }

  echo "\n=== OPTIMISATION DES FICHIERS HTML ===\n";
  $processed = $optimizer->processAllHtmlFiles();
  echo "Fichiers HTML traités : " . $processed . "\n";
}
