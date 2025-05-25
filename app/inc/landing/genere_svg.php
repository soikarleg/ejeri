<?php

$repertoire = $chemin . '/uploads/svg';
$fichier = $idcompte . '.svg';
$cheminFichier = $repertoire . DIRECTORY_SEPARATOR . $fichier;
if (!file_exists($cheminFichier)) {


  $lettre = substr($idcompte, 0, 1);
?>
  <script>
    lettre = <?= "'" . $lettre . "'" ?>;
    name = <?= "'" . $idcompte . "'" ?>;
    console.log('Creation SVG : ', lettre, name)
    svgContent = generateCircleSVG(lettre);
    saveSVG(svgContent, name);
  </script>
<?php


}
