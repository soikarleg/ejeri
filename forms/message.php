<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin.'/class/enooki-db.php';

// Instancier la classe DB
$db = new enookiDB();

foreach ($_POST as $key => $value) {
    ${$key} = htmlspecialchars(trim($value));
    //echo $key . ' : ' . ${$key} . '<br>';
}

// Validation du numéro de téléphone
if (!preg_match('/^(0[1-79])(\d{8})$/', $telephone)) {
    die('Enooki : Numéro de téléphone invalide. Veuillez entrer un numéro français valide.');
}

//Check if the token is valid
if (empty($token)) {
    die('Enooki : Token is empty');
} else {
    if ($token != $_SESSION['token']) {
        die('Enooki : Token invalide </br>Token : ' . $token . '</br>Session : ' . $_SESSION['token']);
    }
}

if (!empty($_POST['myadresse'])) {
    // Si le champ "website" est rempli, c'est un bot
    die('Spam detected.');
}

try {
    // Utilisation de requêtes préparées pour éviter les injections SQL
    $stmt = $db->prepare('INSERT INTO demande (prenom, nom, email, telephone, sujet, message) VALUES (:prenom, :nom, :email, :telephone, :sujet, :message)');
    $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
    $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
    $stmt->bindParam(':sujet', $sujet, PDO::PARAM_STR);
    $stmt->bindParam(':message', $message, PDO::PARAM_STR);
    $stmt->execute();
} catch (Exception $e) {
    die("Erreur lors de l'enregistrement dans la base de données : " . $e->getMessage());
}

//Load Composer's autoloader (created by composer, not included with PHPMailer)
require '../vendor/autoload.php';

$smtp = require '../config/smtp.php';
$host = $smtp['host'];
$username = $smtp['username'];
$password = $smtp['password'];
$port = $smtp['port'];

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = $host;                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = $username;                     //SMTP username
    $mail->Password   = $password;                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = $port;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    // Set UTF-8 encoding
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    //Recipients
    $mail->setFrom('contact@enooki.fr', '[enooki] - Contact');
    $mail->addAddress($email, $prenom . ' ' . $nom);     //Add a recipient
    $mail->addReplyTo('contact@enooki.fr');
    $mail->addCC('flxxx@flxxx.fr'); //Add a BCC

    $mymessage = $prenom . ' ' . $nom . ' vous a contacté depuis le site https://' . $_SERVER['HTTP_HOST'] . '</br></br>';
    $mymessage .= 'Email : ' . $email . '</br>';
    $mymessage .= 'Téléphone : ' . $telephone . '</br>';
    $mymessage .= 'Sujet : ' . $sujet . '</br>';
    $mymessage .= 'Message : ' . $message . '</br></br>';
    $mymessage .= '<small>Demande depuis https://' . $_SERVER['HTTP_HOST'] . '</small>';
    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = '[enooki] - ' . $sujet;
    $mail->Body    = $mymessage;
    $mail->AltBody = $mymessage;

    echo $mail->send();
} catch (Exception $e) {
    die("Enooki : Envoi du message impossible : {$mail->ErrorInfo}");
}