<?php

use PHPMailer\PHPMailer\PHPMailer;

$sel = '';
$tok = '';


if (isset($email_renvoi)) {


  try {
    $auth->resendConfirmationForEmail($email_renvoi, function ($selector, $token) use (&$sel, &$tok) {
      $sel = $selector;
      $tok = $token;
    });
  } catch (\Delight\Auth\ConfirmationRequestNotFound $e) {
    $erreur = ('Aucune validation en attente');
    $code = "warning";
  } catch (\Delight\Auth\TooManyRequestsException $e) {
    $erreur = ('Trop de demande, réessayez plus tard');
    $code = "danger";
  }


  if ($sel && $tok) {
    $chemin = $_SERVER['DOCUMENT_ROOT'];
    $smtp_data = include $chemin . '/config/smtp.php';
    $mail = new PHPMailer();

    $user = new connBase();
    $u = $user->askUserMail($email_renvoi);

    $url = 'https://app.enooki.com/verify?selector=' . \urlencode($sel) . '&token=' . \urlencode($tok);
    try {

      $mail->isSMTP();
      $mail->Host       = $smtp_data['host'];
      $mail->SMTPAuth   = true;
      $mail->Username   = $smtp_data['username'];
      $mail->Password   = $smtp_data['password'];
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port       = $smtp_data['port'];
      $mail->setFrom('noreply@magesquo.com', '[MaGESQUO] Inscription');
      $mail->addAddress($email_renvoi);
      //$mail->addCC($email_signup);
      $mail->addBCC('contact@sagaas.fr');
      $mail->isHTML(true);
      $mail->Subject = '[MaGESQUO] - Validation de votre compte #2';
      $mail->Body    =  '<body style="background-color:#fff"><div  style="padding:20px; width:650px; border:#b3b3b3 0px solid; margin:auto; background-color:#FFF; border-radius:3px"><table width="100%" border="0" cellspacing="0" cellpadding="3"><tr><td valign="top"><p>Bonjour ' . ucfirst($u['username']) . '</p><p><b>Afin de valider votre compte, suivez le lien suivant : </p><a href="' . $url . '">' . $url . '</a></td></tr></table></div></body>';
      $erreur = "Un email de validation a été envoyer sur " . $email_renvoi;
      $code = "success";
      $mail->send();
    } catch (Exception $e) {
      $erreur = "Mail non envoyé. Vous avez une erreur : " . $mail->ErrorInfo;
      $code = "danger";
    };
  } else {
    // En cas d'échec
    $erreur = $erreur ?? 'Impossible de générer un lien de réinitialisation.';
    $code = $code ?? 'danger';
  }
}
