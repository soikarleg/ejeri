<?php
//include $chemin . '/inc/error.php';

use PHPMailer\PHPMailer\PHPMailer;

$sel = '';
$tok = '';
if ($oubli === 'mdp_oubli') {
  try {
    $auth->forgotPassword($_POST['email_reinit'], function ($selector, $token) use (&$sel, &$tok) {
      $sel = $selector;
      $tok = $token;
      $email_reinit = $_POST['email_reinit'];
      $conn = new connBase();
      $db = Connexion();
      $auth = new Delight\Auth\Auth($db);
      $infos_users = $conn->askUserMail($email_reinit);
      $id = $infos_users['id'];
      $idcompte = $infos_users['idcompte'];
      $conn->insertLog('Réinitialisation mot de passe', $id, 'Changement de mot de passe - Oubli mot de passe ' . $idcompte);
      $email_reinit = $_POST['email_reinit'] === '' ? 'master@ejeri.fr' : $_POST['email_reinit'];
    });
  } catch (\Delight\Auth\InvalidEmailException $e) {
    $erreur = $_POST['email_reinit'] === '' ? 'Email vide.</br>Renouvellez votre demande' : "\"" . $_POST['email_reinit'] . "\" n'est pas référencé sur notre base.</br>Renouvellez votre demande";
    $code = 'danger';
  } catch (\Delight\Auth\EmailNotVerifiedException $e) {
    $erreur = ('Email non verifié');
    $code = "warning";
  } catch (\Delight\Auth\ResetDisabledException $e) {
    $erreur = ('Opération non autorisée');
    $code = "warning";
  } catch (\Delight\Auth\TooManyRequestsException $e) {
    $erreur = ('Trop de demande');
    $code = "danger";
  }

  if ($sel && $tok) {
    $chemin = $_SERVER['DOCUMENT_ROOT'];
    $smtp_data = include $chemin . '/config/smtp.php';

    $url = 'https://app.enooki.com/mdp?selector=' . \urlencode($sel) . '&token=' . \urlencode($tok);
    $message = strEncoding('Cliquez sur le lien suivant pour réinitialiser votre mot de passe : ');
    // Envoi du message ou autres actions
    $mail = new PHPMailer();
    try {
      $mail->isSMTP();                                            //Send using SMTP
      $mail->Host       = $smtp_data['host'];
      $mail->SMTPAuth   = true;                  //Set the SMTP server to send through
      $mail->Username   = $smtp_data['username'];                     //SMTP username
      $mail->Password   = $smtp_data['password'];
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;           //Enable implicit TLS encryption
      $mail->Port       = $smtp_data['port'];                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`// 465
      $mail->setFrom('noreply@magesquo.com', '[MaGESQUO]');
      $mail->addAddress($email_reinit);
      $mail->addBCC('contact@sagaas.fr');
      $mail->isHTML(true);                                  //Set email format to HTML
      $mail->Subject = strEncoding('[MaGESQUO] - Réinitialisation de votre mot de passe');
      $mail->Body    =  '<body style="background-color:#fff"><div  style="padding:20px; width:650px; border:#b3b3b3 0px solid; margin:auto; background-color:#FFF; border-radius:3px"><table width="100%" border="0" cellspacing="0" cellpadding="3"> <tr><td valign="top"><p>Bonjour,</p><p>' . $message . '</p><a href="' . $url . '">' . $url . '</a><p>Merci.</p></td></tr></table></div></body> ';
      $mail->send();
      $erreur = "Un email de réinitialisation a été envoyer sur " . $email_reinit . "<br>Consultez vos SPAMS";
      $code = 'success';
    } catch (Exception $e) {
      $erreur = "Mail non envoyé. Vous avez une erreur : " . $mail->ErrorInfo;
      $code = 'muted';
    };
    // Configuration et envoi du mail
  } else {
    // En cas d'échec
    $erreur = $erreur ?? 'Impossible de générer un lien de réinitialisation.';
    $code = $code ?? 'danger';
  }
}
