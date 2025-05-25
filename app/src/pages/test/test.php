<!--TW -->
<h1 class="text-3xl font-bold underline">
  Hello world!
</h1>

<script>
  try {
    // Essaie d'importer le module FileSystem
    const fs = require('fs');
    console.log('Le module FileSystem (fs) est disponible sur ce serveur.');
  } catch (err) {
    // Si une erreur est levée, cela signifie que le module n'est pas disponible
    console.log('Le module FileSystem (fs) n\'est pas disponible sur ce serveur.');
  }
</script>

<!-- TW -->
<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$conn = new connBase();
session_start();
$secteur = $_SESSION['idcompte'];
foreach ($_POST as $p) {
  echo $p . "</br>";
};


$dico = "SELECT
C.TABLE_NAME AS 'nom',
T.TABLE_COMMENT AS 'commtab',
C.COLUMN_NAME AS 'champ',
C.COLUMN_TYPE AS 'donnee',
C.IS_NULLABLE AS 'null',
C.COLUMN_KEY AS 'key',
C.COLUMN_DEFAULT AS 'default',
C.EXTRA AS 'extra',
C.COLUMN_COMMENT AS 'comment'
FROM
INFORMATION_SCHEMA.COLUMNS C
JOIN
INFORMATION_SCHEMA.TABLES T
ON
C.TABLE_NAME = T.TABLE_NAME
WHERE
C.TABLE_SCHEMA = 'db607797151'
ORDER BY
C.TABLE_NAME ASC;
";
$dic = $conn->allRow($dico);

$file = fopen("dico_bd_" . date('dmY_His') . ".txt", "w");
fwrite($file, "nom de la table.nom du champ\tType de données\tNullable\tClé\tDéfaut\tExtra\tCommentaire\n");
$previous_table = null;
foreach ($dic as $k) {
  if ($previous_table !== $k['nom']) {
    fwrite($file, "\n");
    fwrite($file, "Nom de la table " . $k["nom"] . ' ' . $k['commtab']);
    fwrite($file, "\n");
    $previous_table = $k["nom"];
  }
  $line = $k["nom"] . "." . $k["champ"] . "\t" . $k["donnee"] . "\t" . $k["null"] . "\t" . $k["key"] . "\t" . $k["default"] . "\t" . $k["extra"] . "\t" . $k["comment"];
  $line .= "\n";

  fwrite($file, $line);
}
$line .= "\f";
fclose($file);


$requette = "SELECT c1.idcompte, c1.nom AS nc, c1.idcli AS numero_client_client_chantier,
c2.CS, c2.nom AS n, c2.numero AS numero_client_CLIENT
FROM client_chantier c1
LEFT JOIN CLIENT c2 ON c1.idcli = c2.numero
/* WHERE c1.idcompte = '$secteur' and c2.CS = '$secteur' */
where 1 order by c2.CS asc
";

$inco = $conn->allRow($requette);

foreach ($inco as $in) {
  $compare = strcmp($in['n'], $in['nc']);
  //echo $compare . '<br>';
  if ($compare === 1) {

?>
    <p><span class="text-primary text-bold"><?= 'Base CLIENT à conserver ' . $in['numero_client_CLIENT'] . ' ' . $in['n'] . ' ' . $in['CS'] ?></span> / <span class="text-muted"><?= 'Base client_chantier à modifier ' . $in['numero_client_client_chantier'] . ' ' . $in['nc'] . ' ' . $in['idcompte '] ?></span></p>
<?php

  }
}

?>

<script src="https://cdnjs.cloudflare.com/menus/libs/html2canvas/1.3.2/html2canvas.min.js"></script>


<div class="text-center">
  <!-- Contenu que vous souhaitez capturer -->
  <h1 id="monDiv" class="text-primary text-bold mb-2 mt-2 p-2" style="width:380px;height: 80px;background-color: transparent;">CS Loire Atlantique</h1>
</div>

<button id="captureBtn">Capturer</button>

<!-- Un élément pour afficher l'image générée -->
<div id="imageContainer"></div>

<script>
  document.getElementById("captureBtn").addEventListener("click", function() {
    // Sélectionnez l'élément div que vous souhaitez capturer
    const divToCapture = document.getElementById("monDiv");

    // Utilisez html2canvas pour capturer le contenu du div
    html2canvas(divToCapture).then(function(canvas) {
      // Convertissez le canevas en une URL de données (base64)
      const imageDataURL = canvas.toDataURL("image/png");

      // Créez un élément d'image pour afficher l'image générée
      const imageElement = document.createElement("img");
      imageElement.src = imageDataURL;

      // Ajoutez l'élément d'image à un conteneur
      const imageContainer = document.getElementById("imageContainer");
      imageContainer.innerHTML = "";
      imageContainer.appendChild(imageElement);
    });
  });
</script>