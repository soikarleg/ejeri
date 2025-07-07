<?php

/**
 * API simple pour la recherche d'équipe par code postal
 * Version de secours si besoin d'étendre les fonctionnalités côté serveur
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

// Récupération du code postal
$codePostal = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $input = json_decode(file_get_contents('php://input'), true);
  $codePostal = $input['code_postal'] ?? '';
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $codePostal = $_GET['code_postal'] ?? '';
}

// Validation du code postal
if (!preg_match('/^\d{5}$/', $codePostal)) {
  http_response_code(400);
  echo json_encode([
    'success' => false,
    'error' => 'Code postal invalide'
  ]);
  exit;
}

// Données des secteurs (même structure que le JavaScript)
$secteursParCodePostal = [
  // Secteur EJERI Jardins - Orléans (Loiret 45 + Loir-et-Cher 41)
  '45000' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45100' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45120' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45140' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45160' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Olivet'],
  '45170' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45190' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Beaugency'],
  '45200' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45210' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45220' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45230' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45240' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45250' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45260' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45270' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45290' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45300' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45320' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45330' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45340' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45350' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45360' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45370' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45380' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45390' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45400' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45410' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45420' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45430' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45450' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45460' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45470' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45480' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45490' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45500' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45510' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45520' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45530' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45540' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45550' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45560' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45570' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45600' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45700' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45740' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Lailly-en-Val'],
  '45750' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '45800' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '41210' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '41230' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '41250' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '41300' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '41350' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],
  '41600' => ['secteur' => 'orleans', 'nom' => 'EJERI Jardins - Orléans'],

  // Secteur EJERI Jardins - Nantes (44, 49, 85)  
  '44000' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44100' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44110' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44120' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44130' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44140' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44150' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44160' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44170' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44190' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44200' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44210' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44220' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44230' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44240' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44250' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44260' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44270' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44280' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44290' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44300' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44310' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44320' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44330' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44340' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44350' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44360' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44370' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44380' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44390' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44400' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44410' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44420' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44430' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44440' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44450' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44460' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44470' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44480' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44490' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44500' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44510' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44520' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44530' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44540' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44550' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44560' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44570' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44580' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44590' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44600' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44610' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44620' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44630' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44640' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44650' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44660' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44670' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44680' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44690' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44700' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44710' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44720' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44730' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44740' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44750' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44760' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44770' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44780' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44790' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44800' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44810' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44820' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44830' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44840' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44850' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44860' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44870' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44880' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44890' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '44900' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],

  // Maine-et-Loire (49)
  '49000' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '49100' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '49300' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '49310' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '49320' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '49330' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '49340' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '49350' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '49360' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '49370' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '49380' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '49390' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '49400' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '49410' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '49420' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '49430' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '49440' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '49450' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '49460' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '49470' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '49500' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '49600' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '49700' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '49800' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],

  // Vendée (85)
  '85000' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85100' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85110' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85120' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85130' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85140' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85150' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85160' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85170' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85180' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85190' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85200' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85210' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85220' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85230' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85240' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85250' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85260' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85270' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85280' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85290' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85300' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85310' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85320' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85330' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85340' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85350' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85360' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85400' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85440' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85500' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85600' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85700' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
  '85800' => ['secteur' => 'nantes', 'nom' => 'EJERI Jardins - Nantes'],
];

$equipesParSecteur = [
  'orleans' => [
    'nom' => 'EJERI Jardins - Orléans',
    'description' => 'Équipe spécialisée dans l\'entretien de jardins en région Centre-Val de Loire',
    'contact' => [
      'nom' => 'Équipe Orléans',
      'telephone' => '02 38 45 15 78',
      'email' => 'orleans@ejeri.fr',
      'adresse' => '12 Rue de la République, 45000 Orléans'
    ],
    'zone' => 'Orléans et un rayon de 25km (Loiret 45, Loir-et-Cher 41)',
    'specialites' => ['Jardins privés', 'Espaces verts', 'Taille et élagage', 'Entretien saisonnier'],
    'image' => 'assets/img/team/team-orleans.jpg'
  ],
  'nantes' => [
    'nom' => 'EJERI Jardins - Nantes',
    'description' => 'Équipe spécialisée dans l\'entretien de jardins en région Pays de la Loire',
    'contact' => [
      'nom' => 'Équipe Nantes',
      'telephone' => '02 40 12 34 56',
      'email' => 'nantes@ejeri.fr',
      'adresse' => '15 Boulevard de la Prairie, 44000 Nantes'
    ],
    'zone' => 'Nantes et un rayon de 25km (Loire-Atlantique 44, Maine-et-Loire 49, Vendée 85)',
    'specialites' => ['Jardins côtiers', 'Grands espaces verts', 'Tonte et débroussaillage', 'Jardins de prestige'],
    'image' => 'assets/img/team/team-nantes.jpg'
  ],
  'general' => [
    'nom' => 'Équipe Générale',
    'description' => 'Notre équipe principale pour tous types d\'interventions',
    'contact' => [
      'nom' => 'Service Client EJERI',
      'telephone' => '02 38 45 15 78',
      'email' => 'contact@ejeri.fr',
      'adresse' => '12 Rue de la République, 45000 Orléans'
    ],
    'zone' => 'Toute la France et régions limitrophes',
    'specialites' => ['Tous types de jardins', 'Devis gratuit', 'Intervention rapide'],
    'image' => 'assets/img/team/team-general.jpg'
  ]
];

// Recherche du secteur
$secteurInfo = $secteursParCodePostal[$codePostal] ?? ['secteur' => 'general', 'nom' => 'Équipe Générale'];

// Récupération des données de l'équipe
$equipe = $equipesParSecteur[$secteurInfo['secteur']] ?? $equipesParSecteur['general'];

// Réponse JSON
echo json_encode([
  'success' => true,
  'code_postal' => $codePostal,
  'secteur' => $secteurInfo,
  'equipe' => $equipe
]);
