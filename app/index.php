<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
$logged = false;
$idcompte = null;
//echo $chemin = __DIR__;

$chemin = $_SERVER['DOCUMENT_ROOT'];

include $chemin . '/inc/cookies.php';
include $chemin . '/inc/dbconn.php';
include $chemin . '/inc/function.php';
//include $chemin . '/inc/error.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$myenv = array_intersect_key($_ENV, parse_ini_file(__DIR__ . '/.env'));

$db = Connexion();
$auth = new Delight\Auth\Auth($db);

$magesquo = new MaGesquo($idcompte);
//prettyc($magesquo);
$oubli = !isset($_POST['oubli']) ? '' : $_POST['oubli'];
$renvoi = !isset($_POST['renvoi']) ? '' : $_POST['renvoi'];

$erreur = !isset($_POST['erreur']) ? '' : $_POST['erreur'];
$code = !isset($_POST['code']) ? '' : $_POST['code'];
$token = empty($_GET['token']) ? '' : $_GET['token'];
$selector = empty($_GET['selector']) ? '' : $_GET['selector'];
foreach ($_POST as $k => $v) {
    ${$k} = $v;
    // echo 'POST $' . $k . ' ' . $v . '</br>';
}
foreach ($_GET as $k => $v) {
    ${$k} = $v;
    //echo 'GET $' . $k . '=' . $v . '</br>';
}
foreach ($_SESSION as $k => $v) {
    ${$k} = $v;
    //echo 'SESSION $' . $k . ' ' . $v . '</br>';
}

foreach ($_ENV as $k => $v) {
    ${$k} = $v;
    //echo 'ENV $' . $k . ' ' . $v . '</br>';
}
$url = !isset($_GET['url']) ? '' : $_GET['url'];
// {
//     "auth_logged_in": true,
//     "auth_user_id": 2,
//     "auth_email": "flxxx@flxxx.fr",
//     "auth_username": "mamadou",
//     "auth_status": 0,
//     "auth_roles": 0,
//     "auth_force_logout": 0,
//     "auth_remembered": false,
//     "auth_last_resync": 1733006354,
//     "idcompte": "M24002",
//     "idusers": 2
// }

$pageref = $_ENV['REQUEST_URI'];
$askurl = $_ENV['REQUEST_URI'];

require $chemin . '/inc/head.php';
include $chemin . '/inc/landing/genere_svg.php';
include $chemin . '/inc/landing/reset_mdp.php';
include $chemin . '/inc/landing/renvoi_verification.php';
include $chemin . '/inc/landing/deconnexion.php';

include $chemin . '/inc/landing/oubli.php';
include $chemin . '/inc/landing/connexion.php';
include $chemin . '/inc/landing/inscription.php';
include $chemin . '/inc/landing/verifier.php';
include $chemin . '/inc/landing/accepter_cookie.php';
require $chemin . '/inc/foot.php';


if ($logged === false || !isset($_SESSION)) {

    // Non connecté
    switch ($url) {
        case 'mdp_oubli':
            $title = 'Oubli mot de passe';
            include $chemin . '/src/connexion/forgotpass.php';
            break;
        case 'signup':
            $title = 'Inscription';
            include $chemin . '/src/connexion/signup.php';
            break;
        case 'mdp':
            $title = 'Réinitialisation';
            include $chemin . '/src/connexion/reinit_mdp.php';
            break;
        case 'myre':
            $title = 'Renvoi lien';
            include $chemin . '/src/connexion/renvoi_lien.php';
            break;
        default:
            $title = 'Connexion';
            include $chemin . '/src/connexion/login.php';
            break;
    }
} else {

    // Connecté
    switch ($url) {
        case 'sortie':
            $title = 'Déconnexion';
            include $chemin . '/inc/landing/deconnexion.php';
            break;
        case 'contacts':
        case 'intervenants':
        case 'devis':
        case 'planning':
        case 'production':
        case 'facturation':
        case 'encaissement':
        case 'fiche_client':
        case 'parametres':
        case 'test':
            $url_titre = str_replace('_', ' ', $url);
            $idcli_titre = isset($_GET['idcli']) ? ' n° ' . $idcli : '';
            $title = ucfirst($url_titre . $idcli_titre);
            include $chemin . '/src/menus/mymenu.php';
            break;

        default:
            $title = 'Indicateurs';
            include $chemin . '/src/menus/mymenu.php';
            break;
    }
}
