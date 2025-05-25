<?php
session_start();
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
$term = null;
$conn = new connBase();
?>
<p class="titre_menu_item">Dico</p>
<p class="small">Dictionnaire base de données</p>

<?php
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

$file = fopen("dico_bd_" . date('dmY_His') . ".odt", "w");
fwrite($file, "nom de la table.nom du champ\tType de données\n");
$previous_table = null;
foreach ($dic as $k) {
  if ($previous_table !== $k['nom']) {
    fwrite($file, "\n");
    fwrite($file, "Nom de la table " . $k["nom"] . ' ' . $k['commtab']);
    fwrite($file, "\n");
    $previous_table = $k["nom"];
  }
  $line = $k["nom"] . "." . $k["champ"] . "\t" . $k["donnee"];
  $line .= "\n";

  fwrite($file, $line);
}
$line .= "\f";
fclose($file);


$chemin = bonChemin();

$filename = $chemin . 'client/log/connexion.log';

if (file_exists($filename)) {
  $contenu = file_get_contents($filename);
  //echo $contenu;
} else {
  echo "Le fichier n'existe pas.";
}


$pattern = '/(?P<date>\d{2}-\d{2}-\d{4} \d{2}:\d{2}:\d{2}) - (?P<user_id>\d+) - (?P<action>.+?) : (?P<ipv4>\d{1,3}(?:\.\d{1,3}){3}) - (?P<ipv6>[a-fA-F0-9:]+)/';

$logEntries = [];
if (preg_match_all($pattern, $contenu, $matches, PREG_SET_ORDER)) {
  foreach ($matches as $match) {
    $date = DateTime::createFromFormat('d-m-Y H:i:s', $match['date']);
    $logEntries[] = [
      'date' => $date,
      'user_id' => $match['user_id'],
      'action' => trim($match['action']),
      'ipv4' => $match['ipv4'],
      'ipv6' => $match['ipv6']
    ];
  }
}

// Organisation des actions par utilisateur
$actionsParUtilisateur = [];
foreach ($logEntries as $entry) {
  $userId = $entry['user_id'];
  if (!isset($actionsParUtilisateur[$userId])) {
    $actionsParUtilisateur[$userId] = [];
  }
  $actionsParUtilisateur[$userId][] = $entry;
}

// Analyse des actions pour chaque utilisateur
foreach ($actionsParUtilisateur as $userId => $actions) {
  usort($actions, function ($a, $b) {
    return $a['date'] <=> $b['date'];
  });
  // Durée de la session
  $debutSession = $actions[0]['date'];
  $finSession = end($actions)['date'];
  $dureeSession = $finSession->diff($debutSession);

  echo  '<b>' . NomCli($userId) . " - Durée de la session : " . $dureeSession->format('%H:%I:%S') . '' . '</b></br>' . PHP_EOL;

  // Affichage des actions
  echo "Actions de l'utilisateur $userId : </br>" . PHP_EOL;
  foreach ($actions as $action) {
    echo $action['date']->format('d-m-Y H:i:s') . " - " . $action['action'] . '</br>' . PHP_EOL;
  }
  echo PHP_EOL;
}

?>


<p></p>

<div id="action"></div>
<p id="act"></p>

<script>
  //  ajaxData('cs=cs', '../src/pages/calendar/calendar_planning.php', 'action', 'attente_target');
  $(function() {
    $('.bx, #reg').tooltip();
  });
</script>