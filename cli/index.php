<?php
session_name('ENOOKI_CLI_SESSID');
// Désactive l'affichage des erreurs PHP à l'écran (elles restent loguées)
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
require_once __DIR__ . '/config/error.php';
require_once __DIR__ . '/classes/Auth.php';
Auth::startSession();
require_once __DIR__ . '/config/mysql.php';
require_once __DIR__ . '/classes/Autoloader.php';
Autoloader::register();

// Définition de la racine du projet (absolue)
if (!defined('PROJECT_ROOT')) {
    define('PROJECT_ROOT', dirname(__DIR__, 1)); // /cli/index.php → /enooki
}

// echo "Racine du projet : " . PROJECT_ROOT . "\n\n";
// echo "Racine du projet (absolue) : " . (PROJECT_ROOT) . "/shared/....\n";


if (!isset($_SESSION['token'])) {
    $token = bin2hex(random_bytes(32));
    $_SESSION['token'] = $token;
}

// Récupération de l'action depuis l'URL, avec une valeur par défaut sur 'dashboard'
$action = $_GET['action'] ?? 'dashboard';

$controller = new ClientController();
$adresses = new AdressesController();


switch ($action) {
    case 'login':
        $controller->login();
        break;
    case 'logout':
        $controller->logout();
        break;
    case 'devis':
        $controller->devis();
        break;
    case 'devis_pdf':
        $controller->devisPdf();
        break;
    case 'interventions':
        $controller->interventions();
        break;
    case 'attestations':
        $controller->attestations();
        break;
    case 'compte':
        $controller->compte();
        break;
    case 'factures':
        $controller->factures();
        break;
    case 'register':
        $controller->register();
        break;
    case 'register-ajax':
        $controller->registerAjax();
        break;
    case 'login-ajax':
        $controller->loginAjax();
        break;
    case 'forgot-password':
        $controller->forgotPassword();
        break;
    case 'forgot-password-ajax':
        $controller->forgotPasswordAjax();
        break;
    case 'mentions':
        $controller->mentions();
        break;
    case 'cgv':
        $controller->cgv();
        break;
    case 'confidence':
        $controller->confidence();
        break;
    case 'activate':
        $controller->activate();
        break;
    case 'newpass':
        require __DIR__ . '/views/newpass.php';
        break;
    case 'new-password-ajax':
        $controller->newPasswordAjax();
        break;
    case 'adresses':
        $adresses->index();
        break;
    case 'adresses_create':
        $adresses->create();
        break;
    case 'adresses_store':
        $adresses->store();
        break;
    case 'adresses_edit':
        $adresses->edit();
        break;
    case 'adresses_update':
        $adresses->update();
        break;
    case 'adresses_delete':
        $adresses->delete();
        break;
    default:
        $controller->dashboard();
        break;
}

// Le squelette HTML (doctype, html, head, body, header, main, footer) est désormais géré uniquement dans les vues via les partiels header/footer.