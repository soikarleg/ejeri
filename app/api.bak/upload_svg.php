<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $chemin = $_SERVER['DOCUMENT_ROOT'];
  $svgContent = $_POST['svgContent'];
  $name = $_POST['name'] ?? 'noname' . date('dmY');
  $fileName = $chemin . '/uploads/svg/' . $name . '.svg'; // Génère un nom de fichier unique


  // Sauvegarder le contenu SVG dans un fichier
  file_put_contents($fileName, $svgContent);

  echo "SVG enregistré avec succès : " . $fileName;
} else {
  echo "Méthode non autorisée.";
}
