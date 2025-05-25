<?php
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
session_start();
$secteur = $_SESSION['idcompte'];
$conn = new connBase();
$auth = new authBase();

$siret_prefix = $_GET['siret_prefix']; // Récupère les 6 premiers chiffres du SIRET depuis le formulaire

// URL pour obtenir le jeton d'accès
$token_url = "https://api.insee.fr/token";

// Paramètres pour la requête POST pour obtenir le jeton d'accès
$token_params = "grant_type=client_credentials";

// Configuration de la requête cURL pour obtenir le jeton d'accès
$token_curl = curl_init();
curl_setopt($token_curl, CURLOPT_URL, $token_url);
curl_setopt($token_curl, CURLOPT_POST, 1);
curl_setopt($token_curl, CURLOPT_POSTFIELDS, $token_params);
curl_setopt($token_curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($token_curl, CURLOPT_HTTPHEADER, array(
  "Authorization: Basic " . base64_encode("6fbjJnknJZzhjb8v6UQly8bfZL8a:sO5wPVP5DiuXPOYtdT1tAD8Aijga")
));

// Exécution de la requête cURL pour obtenir le jeton d'accès
$token_response = curl_exec($token_curl);

// Gestion des erreurs
if ($token_response === false) {
  echo "Erreur cURL : " . curl_error($token_curl);
}

// Fermeture de la session cURL pour le jeton d'accès
curl_close($token_curl);

// Traiter la réponse JSON du jeton d'accès
$token_data = json_decode($token_response, true);

// Vérifier si le jeton d'accès a été obtenu avec succès
if (isset($token_data["access_token"])) {
  $access_token = $token_data["access_token"];

  // URL de l'API SIRENE
  $api_url = "https://api.insee.fr/entreprises/sirene/V3/siret?q=siren:$siret_prefix";

  // Configuration de la requête cURL pour l'API SIRENE
  $api_curl = curl_init();
  curl_setopt($api_curl, CURLOPT_URL, $api_url);
  curl_setopt($api_curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($api_curl, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer $access_token"
  ));

  // Exécution de la requête cURL pour l'API SIRENE
  $api_response = curl_exec($api_curl);

  // Gestion des erreurs
  if ($api_response === false) {
    echo "Erreur cURL : " . curl_error($api_curl);
  }

  // Fermeture de la session cURL pour l'API SIRENE
  curl_close($api_curl);

  // Traiter la réponse JSON de l'API SIRENE
  $data = json_decode($api_response, true);
  // ... Traitez les données JSON pour extraire les informations dont vous avez besoin
  $etablissements = $data["etablissements"]; // Tableau contenant les informations des établissements

  $suggestions = array();

  foreach ($etablissements as $etablissement) {
    $adresseEtablissement = $etablissement["adresseEtablissement"];

    $nom = $etablissement["uniteLegale"]["denominationUniteLegale"]; // Nom de l'entreprise
    $catJuridique = $etablissement["uniteLegale"]["categorieJuridiqueUniteLegale"];
    $reqcat = "select libelle from cat_juridique where code = '$catJuridique'";
    $libelle_cat_juridique = $conn->oneRow($reqcat);
    $lcj = $libelle_cat_juridique['libelle'];

    $denominationUsuelle = $etablissement["periodesEtablissement"][0]["denominationUsuelleEtablissement"];
    $denominationUsuelle = $denominationUsuelle !== "" ? $denominationUsuelle : "Aucune dénomination"; // Dénomination usuelle
    $adresse = $adresseEtablissement["numeroVoieEtablissement"] . ' ' .
      $adresseEtablissement["typeVoieEtablissement"] . ' ' .
      $adresseEtablissement["libelleVoieEtablissement"]; // Adresse de l'entreprise
    $codePostal = $adresseEtablissement["codePostalEtablissement"]; // Code postal de l'entreprise
    $ville = $adresseEtablissement["libelleCommuneEtablissement"]; // Ville de l'entreprise
    $nic = $etablissement["nic"]; // Numéro NIC de l'établissement
    $codeNAF = $etablissement["periodesEtablissement"][0]["activitePrincipaleEtablissement"]; // Code NAF de l'entreprise

    $suggestions[] = array(
      "label" => $nom . ' ' . $adresse . ' ' . $codePostal . ' ' . $ville,
      "value" => $etablissement["siren"], // Numéro SIREN de l'entreprise
      "nom" => $nom,
      "denomination" => $denominationUsuelle,
      "adresse" => $adresse,
      "codePostal" => $codePostal,
      "ville" => $ville,
      "nic" => $nic,
      "codeNAF" => $codeNAF,
      "catJuridique" => $catJuridique,
      "lcj"=>$lcj
    );
  }
  echo json_encode($suggestions); // Renvoyer les données au format JSON
} else {
  echo "Échec de l'obtention du jeton d'accès.";
}
