<?php
$cheminRoot = $_SERVER['DOCUMENT_ROOT'];
$element = explode('/', $cheminRoot);
$dernier = array_key_last($element);
$cheminRoot =  rtrim($_SERVER['DOCUMENT_ROOT'], '/app');
if ($element[$dernier] === 'sagaas') {
	$chemin = $_SERVER['DOCUMENT_ROOT'] . '/app';
}
if ($element[$dernier] === 'cli') {
	$chemin = rtrim($_SERVER['DOCUMENT_ROOT'], $element[$dernier]) . 'app';
}
if ($element[$dernier] === 'pro') {
	$chemin = rtrim($_SERVER['DOCUMENT_ROOT'], $element[$dernier]) . 'app';
}
if ($element[$dernier] === 'admin') {
	$chemin = rtrim($_SERVER['DOCUMENT_ROOT'], $element[$dernier]) . 'app';
}

$chemin;
include $chemin . '/vendor/autoload.php';
include $chemin . '/client/class/Access.php';
include $chemin . '/client/class/Pieces.php';
include $chemin . '/admin/class/Admin.php';
include $chemin . '/class/Base.php';
include $chemin . '/class/authBase.php';
include $chemin . '/class/FormValidation.php';
include $chemin . '/class/Factures.php';
include $chemin . '/class/Production.php';
include $chemin . '/class/Devis.php';
include $chemin . '/class/Contacts.php';
include $chemin . '/class/Relances.php';
include $chemin . '/class/Compte.php';
include $chemin . '/class/Reglements.php';
include $chemin . '/class/Calendar.php';
include $chemin . '/class/Api.php';
include $chemin . '/class/Bridge.php';
include $chemin . '/class/MyMail.php';
include $chemin . '/class/FactureFPDF.php';
include $chemin . '/class/FactureListeFPDF.php';
include $chemin . '/class/BordereauFPDF.php';
include $chemin . '/class/MyIndics.php';

include $chemin . '/class/MaGesquo_connexion.php';
include $chemin . '/class/MaGesquo_idcompte.php';
include $chemin . '/class/MaGesquo_users.php';
include $chemin . '/class/MaGesquo_clients.php';
include $chemin . '/class/MaGesquo_chantiers.php';
include $chemin . '/class/MaGesquo_bugs.php';

include $chemin . '/class/dataValidator.php';
//include $chemin . '/inc/error.php';

function checkSession()
{
	//pretty($_SESSION);
	if (!isset($_SESSION['idusers']) || !isset($_SESSION['idcompte'])) {
		return $erreur = "Session dépassée";
		//exit;
	}
}
function Session()
{
	$data = array();
	if ($_SESSION) {
		foreach ($_SESSION as $k => $v) {
			${$k} = $v;
			$data[$k] = $v;
		}
		return $data;
	} else {
		return $data = null;
	}
}
function handleVar($var)
{
	return $var !== null && $var !== '' ? $var : '';
}
/**
 * calculerHT
 *
 * @param  mixed $ttc
 * @param  mixed $taux_tva
 *
 */
function calculerHT($ttc, $taux_tva)
{
	$ht = $ttc / (1 + $taux_tva / 100);
	return round($ht, 2); // Arrondir à 2 décimales
}
function cookieVerrou()
{
	$expiration = time() + (30 * 60);
	$chemin = "/";
	setcookie("verrou", false, $expiration, $chemin);
}
function roadMap($titre, $lien = "#")
{
	$res = '<div class="border-dot text-center mt-2">
  <p class="titre_menu_item ">' . $titre . '</p>
  <img src="../../../assets/img/svg/pin.svg" width="300px" alt="PIN">
  <h1 class="mb-4">Projet 2024</h1>
  <a href="' . $lien . '" target="_blank" class="btn btn-mag-n">En savoir plus</a>
  </div>';
	return $res;
}
function moisAbrege($mois)
{
	// Tableau des mois en abrégé
	$moisAbreges = [
		1 => 'Jan.',
		2 => 'Fév.',
		3 => 'Mar.',
		4 => 'Avr.',
		5 => 'Mai',
		6 => 'Juin',
		7 => 'Juil.',
		8 => 'Août',
		9 => 'Sept.',
		10 => 'Oct.',
		11 => 'Nov.',
		12 => 'Déc.'
	];
	// Si l'entrée est un string, la convertir en entier
	$mois = (int)$mois;
	// Retourner le mois correspondant s'il est valide
	if ($mois >= 1 && $mois <= 12) {
		return $moisAbreges[$mois];
	} else {
		return "Mois invalide";
	}
}
function moisLettre($mois)
{
	switch ($mois) {
		case 1:
			$mois_lettre = "Janvier";
			break;
		case 2:
			$mois_lettre = "Février";
			break;
		case 3:
			$mois_lettre = "Mars";
			break;
		case 4:
			$mois_lettre = "Avril";
			break;
		case 5:
			$mois_lettre = "Mai";
			break;
		case 6:
			$mois_lettre = "Juin";
			break;
		case 7:
			$mois_lettre = "Juillet";
			break;
		case 8:
			$mois_lettre = "Août";
			break;
		case 9:
			$mois_lettre = "Septembre";
			break;
		case 10:
			$mois_lettre = "Octobre";
			break;
		case 11:
			$mois_lettre = "Novembre";
			break;
		case 12:
			$mois_lettre = "Décembre";
			break;
		default:
			$mois_lettre = "Mois inconnu";
	}
	return $mois_lettre;
}
/**
 * @param mixed $str
 * Nettoie un str des caratères spéciaux
 * @return mixed
 */
function MrPropre($str)
{
	$c = array('@', '&', '<', '>', '"', '\'', ' \\', '#', '$', '%', '^', '*', '(', ')', '+', '_', '=', '{', '}', '[', ']', '|', '~');
	$r = array('.at.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
	$cpropre = str_replace($c, $r, $str);
	return $cpropre;
}
/**
 * @param mixed $str
 * Encodage d'un str en ISO-8859-1. Utile pour FPDF
 * @return mixed
 */
function strEncoding($str)
{
	$str_encoded = mb_convert_encoding($str, 'ISO-8859-1');
	return $str_encoded;
}
function DateYmd($date)
{
	$part = explode('-', $date);
	$annee = $part[0];
	$mois = $part[1];
	$jour = $part[2];
	$ndate = $jour . '/' . $mois . '/' . $annee;
	return $ndate;
}
/**
 * @param mixed $str
 * Ajoutre un 0 devant un chiffre, notamment les mois 1 -> 01
 * @return mixed
 */
function ajoutZeroMois($str)
{
	if (strlen($str < 2)) {
		$str = '0' . $str;
	} else {
		$str = $str;
	}
	return $str;
}
/**
 * getMoisNom
 * Utilisation d'un switch pour retourner le nom du mois en fonction du numéro de mois. 1 -> Janvier
 * @param mixed $monthNumber
 *
 */
function getMoisNom($monthNumber)
{
	switch ($monthNumber) {
		case 1:
			return "Janvier";
		case 2:
			return "Février";
		case 3:
			return "Mars";
		case 4:
			return "Avril";
		case 5:
			return "Mai";
		case 6:
			return "Juin";
		case 7:
			return "Juillet";
		case 8:
			return "Août";
		case 9:
			return "Septembre";
		case 10:
			return "Octobre";
		case 11:
			return "Novembre";
		case 12:
			return "Décembre";
		default:
			return "Mois invalide"; // Mois invalide (ou autre cas de traitement si nécessaire)
	}
}
/**
 * SupAccX
 * Purge un str des accentuation è -> e et de certain caratères spéciaux # -> ''
 * @param mixed $varMaChaine
 *
 */
function SupAccX($varMaChaine)
{
	//, 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ'
	// , 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y'
	$search = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', ']', '[', '+', ')', '(', '_', '^', '=', '}', '{', '#', '"', '\\', '-');
	$replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', '', '', '', '', '', '', '', '', '', '', '', '', ' ', ' ');
	$varMaChaine = str_replace($search, $replace, $varMaChaine);
	$varMaChaine = strtolower($varMaChaine);
	$varMaChaine = preg_replace('/[^a-z0-9]+/', '', $varMaChaine);
	return $varMaChaine;
}
function Dec_2($theObject, $suffix = null)
{
	if ($theObject == "") {
		$theObject = 0;
	} else {
		$theObject = $theObject;
	}
	$currencyFormat = number_format($theObject, 2, '.', '');
	$value = ($currencyFormat) . $suffix;
	return $value;
}
function Dec_0($theObject, $suffix = null)
{
	if ($theObject == "") {
		$theObject = 0;
	} else {
		$theObject = $theObject;
	}
	$currencyFormat = number_format($theObject, 0, '', ' ');
	return ($currencyFormat) . $suffix;
}


//==  ***** verification des datas *****************************
function verifInfos(string $table, string $secteur, string $errorMessage)
{
	$conn = new connBase();
	$reqclient = "select * from $table where idcompte='$secteur' limit 1 ";
	$client = $conn->allRow($reqclient);
	if (!$client) {
		return $errorMessage;
	} else {
		return null;
	}
}

function verifData(string $secteur, string $username = null)
{
	// Vérifiez que le secteur est valide
	if (empty($secteur)) {
		return '<p class="alert alert-mag"><i class="bx bxs-error icon-bar"></i> Secteur invalide.</p>';
	}

	$clientError = verifInfos('client_chantier', $secteur, 'Ajoutez un client.');
	$infosError = verifInfos('idcompte', $secteur, 'Complétez les informations de votre compte.');
	$usersError = verifInfos(
		'users_infos',
		$secteur,
		'Ajoutez un intervenant.'
	);

	// Rassemblez tous les messages d'erreur
	$errors = array_filter([$clientError, $infosError, $usersError]);

	if (!empty($errors)) {
		$data = '<p class="alert alert-mag"><i class="bx bxs-error icon-bar"></i> Avant de poursuivre : ' . implode(' ', $errors) . '</p>';
	} else {
		$data = null; // Pas d'erreur
	}

	return $data;
}
// function verifInfosClient(string $secteur)
// {
// 	$conn = new connBase();
// 	$reqclient = "select * from client_chantier where idcompte='$secteur' limit 1 ";
// 	$client = $conn->allRow($reqclient);
// 	if (!$client) {
// 		return 'Ajoutez un client. ';
// 	}
// }
// function verifInfosCompte(string $secteur)
// {
// 	$conn = new connBase();
// 	$reqclient = "select * from idcompte where idcompte='$secteur' limit 1 ";
// 	$client = $conn->allRow($reqclient);
// 	if (!$client) {
// 		return 'Completez les informations de votre compte.';
// 	}
// }
// function verifInfosUsers(string $secteur)
// {
// 	$conn = new connBase();
// 	$reqclient = "select * from users_infos where idcompte='$secteur' limit 1 ";
// 	$client = $conn->allRow($reqclient);
// 	if (!$client) {
// 		return 'Ajoutez un intervenant.';
// 	}
// }
// function verifData(string $secteur, string $username = null)
// {
// 	$client = verifInfosClient($secteur);
// 	$infos = verifInfosCompte($secteur);
// 	$users = verifInfosUsers($secteur);
// 	if ($client or $infos or $users) {
// 		$data = '<p class="alert alert-mag"><i class="bx bxs-error icon-bar"></i> Avant de poursuivre : ' . $users . ' ' . $client . ' ' . $infos . '</p>';
// 	} else {
// 		$data = null;
// 	}
// 	return $data;
// }
//==  ***** verification des datas *****************************

/**
 * creerNumeroClient
 *
 * @param mixed $uname
 *
 */
function creerNumeroClient($uname)
{
	$conn = new connBase();
	$result = $conn->oneRow("SELECT COUNT(*) as user_count FROM users_sagaas");
	if ($result && count($result) > 0) {
		$userCount = $result['user_count'] + 1;
	} else {
		$userCount = 1;
	}
	$ann = date('y');
	$premiereLettre = substr($uname, 0, 1);
	$numeroClient = strtoupper($premiereLettre) . $ann . str_pad($userCount, 3, '0', STR_PAD_LEFT);
	return $numeroClient;
}
/**
 * Tronque
 *
 * @param mixed $chaine
 * @param mixed $max
 *
 */
function Tronque($chaine, $max, $point = '1')
{
	// Nombre de caractère
	if (strlen($chaine) >= $max) {
		// Met la portion de chaine dans $chaine
		$chaine = substr($chaine, 0, $max);
		// position du dernier espace
		$espace = strrpos($chaine, "");
		// test si il ya un espace
		if ($espace)
			// si ya 1 espace, coupe de nouveau la chaine
			$chaine = substr($chaine, 0, $espace);
		// Ajoute ... à la chaine
		if ($point == '1') {
			$chaine .= '...';
		}
	}
	return $chaine;
}
/**
 * NomConn
 *
 * @param mixed $idcolla
 *
 */
function NomConn($idcolla)
{
	$conn = new connBase();
	$req_colla = "select * from users where idusers = '$idcolla' ";
	$n = $conn->oneRow($req_colla);
	$prenom = (($n['prenom']));
	$initiales = $prenom;
	return $initiales;
}
function initialesColla($idcolla)
{
	$conn = new connBase();
	$req_colla = "select * from users where idusers = '$idcolla' ";
	$n = $conn->oneRow($req_colla);
	$prenom = substr($n['prenom'], 0, 1);
	$nom = substr($n['nom'], 0, 1);
	$initiales = $prenom . $nom;
	return $initiales;
}
function NomColla($idcolla, $tronque = null)
{
	$nom = '';
	$prenom = '';
	$conn = new connBase();
	$req_colla = "select * from users_sagaas where id = '$idcolla' ";
	$n = $conn->oneRow($req_colla);
	if (!empty($n['nom'])) {
		if ($tronque) {
			$nom = Tronque(($n['nom']), $tronque);
		} else {
			$nom = $n['nom'];
		}
	}
	$prenom = !empty($n['prenom']) ? $n['prenom'] : '-';
	$initiales = $prenom . ' ' . $nom;
	return $initiales;
}
function NomCommercial($idcolla, $tronque = null)
{
	$conn = new connBase();
	$req_colla = "select * from users where idusers = '$idcolla' ";
	$n = $conn->oneRow($req_colla);
	if ($tronque) {
		$nom = Tronque(($n['nom']), $tronque);
	} else {
		$nom = $n['nom'];
	}
	$prenom = ($n['prenom']);
	$initiales = $nom;
	return $initiales;
}
/**
 * NomSecteur
 *
 * @param mixed $secteur
 *
 */
function NomSecteur($secteur)
{
	$conn = new connBase();
	$req_colla = "select * from idcompte_infos where idcompte = '$secteur' limit 1 ";
	$n = $conn->oneRow($req_colla);
	$nom_sect = $n['secteur'];
	return $nom_sect;
}
/**
 * NomClient
 *
 * @param mixed $secteur
 *
 */
function supprimePointTelephone($tel)
{
	$tel = str_replace('.', '', $tel);
	return $tel;
}
function NomClient($secteur)
{
	$conn = new connBase();
	$req_colla = "select * from client_chantier where idcli = '$secteur' limit 1 ";
	$n = $conn->oneRow($req_colla);
	if (!$n) {
		$nom_sect = "Client inexistant";
		return $nom_sect;
	} else {
		$nom_sect = ($n['civilite'] . ' ' . $n['prenom'] . ' ' . $n['nom']);
		return $nom_sect;
	}
}
function NomCli($idcli)
{
	$conn = new connBase();
	$req_colla = "select * from client_chantier where idcli = '$idcli' limit 1 ";
	$n = $conn->oneRow($req_colla);
	if (!$n) {
		$nom_sect = "Client inexistant";
		return $nom_sect;
	} else {
		$nom_sect = ($n['nom'] . '-' . $n['idcli']);
		return $nom_sect;
	}
}
function pretty($array)
{
	echo '<pre>';
	echo json_encode($array, JSON_PRETTY_PRINT);
	echo '</pre>';
}

function prettyc($array)
{
	echo '<pre>';
	foreach ($array as $key => $value) {
		if (empty($value)) {
			// Affiche en rouge si l'élément est vide ou null
			echo '<span style="color:#3d76ad;"><b>' . json_encode($key) . '</b> : ' . json_encode($value) . '</span>' . PHP_EOL;
		} else {
			//echo  json_encode($key) . ' : ' . json_encode($value)  . PHP_EOL;

			// Affiche normalement les autres éléments
			echo '<span style="color:#959595;"><b>' . json_encode($key) . '</b> : ' . json_encode($value) . '</span>' . PHP_EOL;
		}
	}
	echo '</pre>';
}

function bonChemin()
{
	$path = $_SERVER['DOCUMENT_ROOT'];
	$elements = explode('/', $path);
	$lastElementKey = array_key_last($elements);
	$lastElement = $elements[$lastElementKey];
	$validElements = ['magesquo', 'app', 'pro', 'admin'];
	if ($lastElement === 'magesquo') {
		return $chemin = $_SERVER['DOCUMENT_ROOT'];
	} elseif (in_array($lastElement, $validElements)) {
		return $chemin = rtrim($_SERVER['DOCUMENT_ROOT'], $lastElement);
	}
}
function readJson($fichier, $dir = '/config/')
{
	$path = $_SERVER['DOCUMENT_ROOT'];
	$fichier_sanitized = basename($fichier);
	$chemin_endpoint = $path . $dir . $fichier_sanitized;
	if (file_exists($chemin_endpoint)) {
		$contenu_json = file_get_contents($chemin_endpoint);
		$res = json_decode($contenu_json, true);
		if (json_last_error() !== JSON_ERROR_NONE) {
			return 'Erreur de décodage JSON';
		}
	} else {
		$res = 'Lecture impossible';
	}
	return $res;
}
/**
 * getWeek
 * date au format Y-m-d
 * @param  mixed $date
 * @return void
 */
function getWeek($date)
{
	$date_specifique = new DateTime($date);
	$numero_semaine = $date_specifique->format("W");
	return $numero_semaine;
}
function PrenomCli($idcli)
{
	$conn = new connBase();
	$req_colla = "select * from client_chantier where idcli = '$idcli' limit 1 ";
	$n = $conn->oneRow($req_colla);
	if (!$n) {
		$nom_sect = "Client inexistant";
		return $nom_sect;
	} else {
		$nom_sect = ($n['nom'] . ' ' . $n['prenom'] . ' - ' . $n['idcli']);
		return $nom_sect;
	}
}
/**
 * Tel
 *
 * @param mixed $t
 *
 */
function Tel($t)
{
	$t = isset($t) ? ($t) : null;
	if (!empty($t) || isset($t)) {
		$tel = wordwrap($t, 2, '.', true);
		return $tel;
	} else {
		$tel = "";
		return $tel;
	}
}
/**
 * afficherTelephonePortable
 *
 * @param mixed $telephone
 * @param mixed $portable
 *
 */
function afficherTiret($telephone, $portable, $tiret = ' - ')
{
	if (!empty($telephone) && !empty($portable)) {
		// Les deux chaînes existent, ajouter un tiret entre elles
		return $telephone . $tiret . $portable;
	} elseif (!empty($telephone)) {
		// Seule la chaîne $telephone existe
		return $telephone;
	} elseif (!empty($portable)) {
		// Seule la chaîne $portable existe
		return $portable;
	} else {
		// Les deux chaînes sont vides
		return "Aucun numéro.";
	}
}
/**
 * AffDate
 *
 * @param mixed $date
 *
 */
function AffDate($date)
{
	$dh = explode(" ", $date);
	$date = $dh[0];
	$d = explode("-", $date);
	$annee = $d[0];
	$mois = $d[1];
	$jour = $d[2];
	$res_date = $jour . '/' . $mois . '/' . $annee;
	return $res_date;
}
/**
 * AffHeure
 *
 * @param mixed $date
 *
 */
function AffHeure($date)
{
	$dh = explode(" ", $date);
	$date = $dh[1];
	$d = explode(":", $date);
	$heure = $d[0];
	$minute = $d[1];
	//$seconde = $d[2];
	$res_heure = $heure . 'h' . $minute; //.'/'.$seconde;
	return $res_heure;
}
function definirHauteurImageProgressive($cheminImage)
{
	// Obtenez les informations sur l'image
	$infosImage = getimagesize($cheminImage);
	if ($infosImage) {
		$largeur = $infosImage[0];
		$hauteur = $infosImage[1];
		$ratio = $largeur / $hauteur;
		$h = $ratio * 80 / 2.30;
		// // Définissez les ratios maximum et minimum
		// $ratioMax = 2.5;
		// $ratioMin = 0.5;
		// // Calculez la hauteur en fonction du ratio
		// if ($ratio > $ratioMax) {
		// // Utilisez une règle de trois pour calculer la hauteur cible
		// $hauteur = $largeur / $ratioMax;
		// $hauteur = 80 - ($ratio - $ratioMax) ; //* 50 Progressivité pour les ratios supérieurs à 2.5
		// } elseif ($ratio < $ratioMin) {
		// // Utilisez une règle de trois pour calculer la hauteur cible
		// $hauteur=$largeur / $ratioMin;
		// $hauteur=30 + ($ratioMin - $ratio) ; //* 40 Progressivité pour les ratios inférieurs à 0.5
		// }
		// // Assurez-vous que la hauteur reste dans la plage de 30 à 80
		$hauteur = max(min($h, 80), 30);
		return $hauteur;
	} else {
		return false; // Impossible de lire les informations sur l'image
	}
}
