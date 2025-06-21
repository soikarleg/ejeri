<?php
require_once __DIR__ . '../../config/error.php';
        
        $error = '';
        $success = false;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation CGV
            if (empty($_POST['accept_cgv'])) {
                $error = 'Vous devez accepter les Conditions Générales de Vente pour créer un compte.';
            } else {
                // Validation champs
                $fullname = trim($_POST['fullname'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';
                $password_confirm = $_POST['password_confirm'] ?? '';
                if (empty($fullname) || empty($email) || empty($password) || empty($password_confirm)) {
                    $error = 'Tous les champs sont obligatoires.';
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = 'Adresse email invalide.';
                } elseif ($password !== $password_confirm) {
                    $error = 'Les mots de passe ne correspondent pas.';
                } elseif (strlen($password) < 8) {
                    $error = 'Le mot de passe doit contenir au moins 8 caractères.';
                } else {
                    // Connexion PDO
                    $dbconf = require __DIR__ . '/../../config/mysql.php';
                    $dsn = 'mysql:host=' . $dbconf['host_name'] . ';dbname=' . $dbconf['database'] . ';charset=utf8mb4';
                    try {
                        $pdo = new PDO($dsn, $dbconf['user_name'], $dbconf['password']);
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    } catch (Exception $e) {
                        $error = 'Erreur de connexion à la base de données.';
                    }
                    if (!$error) {
                        require_once __DIR__ . '/../models/User.php';
                        $userModel = new \User($pdo);
                        if ($userModel->emailExists($email)) {
                            $error = 'Cet email est déjà utilisé.';
                        } else {
                            // Générer un token d'activation
                            $token = bin2hex(random_bytes(32));
                            // Créer l'utilisateur (statut inactif)
                            $userModel->createUser($email, $password, $token);
                            // Envoi mail activation
                            require_once __DIR__ . '/../../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
                            require_once __DIR__ . '/../../../vendor/phpmailer/phpmailer/src/SMTP.php';
                            require_once __DIR__ . '/../../../vendor/phpmailer/phpmailer/src/Exception.php';
                            $smtp = require __DIR__ . '/../config/smtp.php';
                            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
                            try {
                                $mail->isSMTP();
                                $mail->Host = $smtp['host'];
                                $mail->SMTPAuth = true;
                                $mail->Username = $smtp['username'];
                                $mail->Password = $smtp['password'];
                                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
                                $mail->Port = $smtp['port'];
                                $mail->CharSet = 'UTF-8';
                                $mail->setFrom($smtp['username'], 'Enooki');
                                $mail->addAddress($email, $fullname);
                                $mail->isHTML(true);
                                $mail->Subject = 'Activation de votre compte Enooki';
                                $activationLink = 'https://' . $_SERVER['HTTP_HOST'] . '/activate?token=' . $token;
                                $mail->Body = '<p>Bonjour ' . htmlspecialchars($fullname) . ',</p>' .
                                    '<p>Merci pour votre inscription sur Enooki.<br>Pour activer votre compte, cliquez sur le lien suivant :</p>' .
                                    '<p><a href="' . $activationLink . '">' . $activationLink . '</a></p>' .
                                    '<p>Si vous n’êtes pas à l’origine de cette demande, ignorez ce message.</p>';
                                $mail->send();
                               echo  $success = 'OK';
                            } catch (Exception $e) {
                                $error = "Erreur lors de l'envoi de l'email d'activation : " . $mail->ErrorInfo;
                            }
                        }
                    }
                }
            }
        }