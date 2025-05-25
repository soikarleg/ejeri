<?php
require_once __DIR__ . '/../../shared/helpers/function.php';

// controllers/ClientController.php
class ClientController
{
    /**
     * Récupère les données pour le sidemenu (adresses, téléphones)
     */
    private function getSidemenuData($client_id)
    {
        require_once __DIR__ . '/../models/Database.php';
        require_once __DIR__ . '/../models/AdresseModel.php';
        require_once __DIR__ . '/../models/ClientModel.php';
        $pdo = Database::getConnection();
        $adresseModel = new AdresseModel($pdo, $client_id);
        $clientModel = new ClientModel($pdo, $client_id);
        $adresses = $adresseModel->getAllByClient($client_id);
        $client = $clientModel->getNomById($client_id);
        //pretty($client);
        return [
            'adresses' => $adresses,
            'client' => $client
        ];
    }

    public function info()
    {
        require_once __DIR__ . '/../model/User.php';
    }
    public function dashboard()
    {
        require_once __DIR__ . '/../classes/Auth.php';
        Auth::requireAuth();
        $client_id = $_SESSION['client_id'];
        $sidemenu = $this->getSidemenuData($client_id);
        $nom = $sidemenu['client']['nom'] ?? '';
        $prenom = $sidemenu['client']['prenom'] ?? '';
        $adresses = $sidemenu['adresses'];
        $telephones = $sidemenu['telephones'];
        require PROJECT_ROOT . '/cli/views/dashboard.php';
    }
    public function login()
    {
        require __DIR__ . '/../views/login.php';
    }
    public function logout()
    {
        session_destroy();
        header('Location: /login');
        exit;
    }
    public function preLogout()
    {
                require_once __DIR__ . '/../classes/Auth.php';
        Auth::requireAuth();
        $client_id = $_SESSION['client_id'];
        $sidemenu = $this->getSidemenuData($client_id);
        $nom = $sidemenu['client']['nom'] ?? '';
        $prenom = $sidemenu['client']['prenom'] ?? '';
        $adresses = $sidemenu['adresses'];
        $telephones = $sidemenu['telephones'];
        require __DIR__ . '/../views/pre_logout.php';
    }
    public function devis()
    {
        require_once __DIR__ . '/../classes/Auth.php';
        Auth::requireAuth();
        $client_id = $_SESSION['client_id'];
        require_once __DIR__ . '/../models/DevisModel.php';
        $devisModel = new DevisModel(Database::getConnection());
        $devis = $devisModel->getAllByClient($client_id);
        $sidemenu = $this->getSidemenuData($client_id);
        $nom = $sidemenu['client']['nom'] ?? '';
        $prenom = $sidemenu['client']['prenom'] ?? '';
        $adresses = $sidemenu['adresses'];
        $telephones = $sidemenu['telephones'];
        require __DIR__ . '/../views/devis.php';
    }
    public function devisPdf()
    {
        require_once __DIR__ . '/../classes/Auth.php';
        Auth::requireAuth();
        $numero = $_GET['numero'] ?? null;
        // if (!$numero) {
        //     die('Numéro de devis manquant');
        // }
        // Passe la variable dans $_GET pour le script inclus
        $_GET['numero'] = $numero;
        require PROJECT_ROOT . '/shared/pdf/devisPDF.php';
        exit;
    }
    public function attestations()
    {
        require_once __DIR__ . '/../classes/Auth.php';
        Auth::requireAuth();
        $client_id = $_SESSION['client_id'];
        $sidemenu = $this->getSidemenuData($client_id);
        $nom = $sidemenu['client']['nom'] ?? '';
        $prenom = $sidemenu['client']['prenom'] ?? '';
        $adresses = $sidemenu['adresses'];
        $telephones = $sidemenu['telephones'];
        require PROJECT_ROOT . '/cli/views/attestations.php';
    }
    public function interventions()
    {
        require_once __DIR__ . '/../classes/Auth.php';
        Auth::requireAuth();
        $client_id = $_SESSION['client_id'];
        $sidemenu = $this->getSidemenuData($client_id);
        $nom = $sidemenu['client']['nom'] ?? '';
        $prenom = $sidemenu['client']['prenom'] ?? '';
        $adresses = $sidemenu['adresses'];
        $telephones = $sidemenu['telephones'];
        require PROJECT_ROOT . '/cli/views/interventions.php';
    }
    public function compte()
    {
        require_once __DIR__ . '/../classes/Auth.php';
        Auth::requireAuth();
        $client_id = $_SESSION['client_id'];
        $sidemenu = $this->getSidemenuData($client_id);
        $nom = $sidemenu['client']['nom'] ?? '';
        $prenom = $sidemenu['client']['prenom'] ?? '';
        $adresses = $sidemenu['adresses'];
        $telephones = $sidemenu['telephones'];
        require PROJECT_ROOT . '/cli/views/compte.php';
    }
    public function factures()
    {
        require_once __DIR__ . '/../classes/Auth.php';
        Auth::requireAuth();
        $client_id = $_SESSION['client_id'];
        $sidemenu = $this->getSidemenuData($client_id);
        $nom = $sidemenu['client']['nom'] ?? '';
        $prenom = $sidemenu['client']['prenom'] ?? '';
        $adresses = $sidemenu['adresses'];
        $telephones = $sidemenu['telephones'];
        require PROJECT_ROOT . '/cli/views/factures.php';
    }
    public function register()
    {

        require __DIR__ . '/../views/register.php';
    }
    public function forgotPassword()
    {
        require __DIR__ . '/../views/forgot-password.php';
    }
    public function mentions()
    {
        require __DIR__ . '/../views/mentions.php';
    }
    public function cgv()
    {
        require __DIR__ . '/../views/cgv.php';
    }
    public function confidence()
    {
        require __DIR__ . '/../views/confidence.php';
    }
    // Inscription AJAX (pour validate.js)
    public function registerAjax()
    {
        header('Content-Type: text/plain; charset=utf-8');


        try {
            // On attend un POST AJAX (fetch)
            if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                http_response_code(400);
                echo 'Requête invalide';
                exit;
            }
            require_once __DIR__ . '/../models/Database.php';
            $pdo = Database::getConnection();
            require_once __DIR__ . '/../models/User.php';
            $userModel = new User($pdo);
            // Récupération des champs
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $password_confirm = $_POST['password_confirm'] ?? '';
            $accept_cgv = $_POST['accept_cgv'] ?? '';
            // Validation
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo 'Adresse email invalide';
                exit;
            }
            if (strlen($password) < 8) {
                echo 'Mot de passe trop court';
                exit;
            }
            if ($password !== $password_confirm) {
                echo 'Les mots de passe ne correspondent pas';
                exit;
            }
            if (!$accept_cgv) {
                echo 'Vous devez accepter les CGV';
                exit;
            }
            if ($userModel->emailExists($email)) {
                echo 'Cet email existe déjà';
                exit;
            }
            // Génération du token d'activation
            $token = bin2hex(random_bytes(32));
            if (!$userModel->createUser($email, $password, $token)) {
                echo 'Erreur lors de la création du compte';
                exit;
            }
            // Envoi de l'email d'activation (PHPMailer)
            $smtp = require __DIR__ . '/../config/smtp.php';
            require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/PHPMailer.php';
            require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/SMTP.php';
            require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/Exception.php';

            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = $smtp['host'];
                $mail->SMTPAuth = true;
                $mail->Username = $smtp['username'];
                $mail->Password = $smtp['password'];
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = $smtp['port'];
                $mail->setFrom($smtp['username'], 'Enooki');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Activation de votre compte Enooki';
                $mail->Body = 'Cliquez sur ce lien pour activer votre compte : <a href="https://' . $_SERVER['HTTP_HOST'] . '/activate?token=' . $token . '">Activer mon compte</a>';
                $mail->send();
            } catch (Exception $e) {
                echo 'Erreur lors de l\'envoi de l\'email d\'activation';
                exit;
            }
            echo 'OK';
            exit;
        } catch (Throwable $e) {
            error_log('[registerAjax] ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            http_response_code(500);
            echo 'Erreur serveur : ' . $e->getMessage();
            exit;
        }
    }
    // Activation de compte via lien email
    public function activate()
    {
        require_once __DIR__ . '/../models/Database.php';
        $pdo = Database::getConnection();
        require_once __DIR__ . '/../models/User.php';
        $userModel = new User($pdo);
        $token = $_GET['token'] ?? '';
        $success = false;
        $message = '';
        if ($token && strlen($token) === 64) {
            $user = $userModel->getUserByToken($token);
            if ($user && !$user['is_active']) {
                if ($userModel->activateUser($token)) {
                    $success = true;
                    $message = 'Votre compte a bien été activé. Vous pouvez maintenant vous connecter.';
                } else {
                    $message = 'Erreur lors de l’activation du compte.';
                }
            } elseif ($user && $user['is_active']) {
                $message = 'Votre compte est déjà activé.';
                $success = true;
            } else {
                $message = 'Lien d’activation invalide ou expiré.';
            }
        } else {
            $message = 'Lien d’activation invalide.';
        }
        require __DIR__ . '/../views/activate.php';
    }
    // Connexion AJAX (pour validate.js)
    public function loginAjax()
    {
        header('Content-Type: text/plain; charset=utf-8');
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                http_response_code(400);
                echo 'Requête invalide';
                exit;
            }
            require_once __DIR__ . '/../models/Database.php';
            $pdo = Database::getConnection();
            require_once __DIR__ . '/../models/User.php';
            $userModel = new User($pdo);
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo 'Adresse email invalide';
                exit;
            }
            $user = $userModel->findByEmail($email);
            if (!$user) {
                echo 'Aucun compte trouvé avec cet email';
                exit;
            }
            if (!$user['is_active']) {
                echo 'Votre compte n\'est pas activé. Vérifiez vos emails.';
                exit;
            }
            if (!password_verify($password, $user['password'])) {
                echo 'Mot de passe incorrect';
                exit;
            }
            require_once __DIR__ . '/../classes/Auth.php';
            if (!isset($user['idcli'])) {
                error_log('[loginAjax] ERREUR: clé idcli absente dans $user: ' . print_r($user, true));
                echo "Erreur technique : identifiant utilisateur manquant.";
                exit;
            }
            error_log('[loginAjax] ' . $user['idcli'] . ' login');
            Auth::login($user['idcli']);
            echo 'OK';
            exit;
        } catch (Throwable $e) {
            error_log('[loginAjax] ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            http_response_code(500);
            echo 'Erreur serveur : ' . $e->getMessage();
            exit;
        }
    }

    public function forgotPasswordAjax()
    {
        header('Content-Type: text/plain; charset=utf-8');
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                http_response_code(400);
                echo 'Requête invalide';
                exit;
            }
            require_once __DIR__ . '/../models/Database.php';
            $pdo = Database::getConnection();
            require_once __DIR__ . '/../models/User.php';
            $userModel = new User($pdo);
            $email = trim($_POST['email'] ?? '');
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo 'Si l’email existe, un lien a été envoyé.';
                exit;
            }
            $user = $userModel->findByEmail($email);
            if (!$user || !$user['is_active']) {
                // Toujours réponse générique
                echo 'Si l’email existe, un lien a été envoyé.';
                exit;
            }
            // Générer un token sécurisé et une date d’expiration (1h)
            $token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
            // Enregistrer le token et l’expiration
            $stmt = $pdo->prepare('UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?');
            $stmt->execute([$token, $expiry, $email]);
            // Envoi de l’email de réinitialisation
            $smtp = require __DIR__ . '/../config/smtp.php';
            require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/PHPMailer.php';
            require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/SMTP.php';
            require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/Exception.php';
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = $smtp['host'];
                $mail->SMTPAuth = true;
                $mail->Username = $smtp['username'];
                $mail->Password = $smtp['password'];
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = $smtp['port'];
                $mail->setFrom($smtp['username'], 'Enooki');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Changement de votre mot de passe Enooki';
                $resetUrl = 'https://' . $_SERVER['HTTP_HOST'] . '/newpass?token=' . urlencode($token);
                $mail->Body = 'Pour changer votre mot de passe, cliquez sur ce lien : <a href="' . $resetUrl . '">Changer mon mot de passe</a><br>Ce lien est valable 1 heure.';
                $mail->send();
            } catch (Exception $e) {
                // Ne pas révéler l’erreur à l’utilisateur
                error_log('[forgotPasswordAjax] Erreur envoi mail: ' . $e->getMessage());
            }
            echo 'OK';
            exit;
        } catch (Throwable $e) {
            error_log('[forgotPasswordAjax] ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            http_response_code(500);
            echo 'Erreur serveur.';
            exit;
        }
    }
    public function newPasswordAjax()
    {
        header('Content-Type: text/plain; charset=utf-8');
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                http_response_code(400);
                echo 'Requête invalide';
                exit;
            }
            require_once __DIR__ . '/../models/Database.php';
            $pdo = Database::getConnection();
            require_once __DIR__ . '/../models/User.php';
            $userModel = new User($pdo);
            $token = $_POST['token'] ?? '';
            $password = $_POST['password'] ?? '';
            $password_confirm = $_POST['password_confirm'] ?? '';
            if (!$token || strlen($token) < 32) {
                echo 'Lien invalide ou expiré.';
                exit;
            }
            if (strlen($password) < 8) {
                echo 'Le mot de passe doit contenir au moins 8 caractères.';
                exit;
            }
            if ($password !== $password_confirm) {
                echo 'Les mots de passe ne correspondent pas.';
                exit;
            }
            // Vérifier le token et expiration
            $user = $userModel->findByResetToken($token);
            if (!$user || !$user['reset_token_expiry'] || strtotime($user['reset_token_expiry']) < time()) {
                echo 'Lien invalide ou expiré.';
                exit;
            }
            // Mettre à jour le mot de passe et supprimer le token
            $userModel->updatePasswordByToken($token, $password);
            echo 'OK';
            exit;
        } catch (Throwable $e) {
            error_log('[newPasswordAjax] ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            http_response_code(500);
            echo 'Erreur serveur.';
            exit;
        }
    }
}
