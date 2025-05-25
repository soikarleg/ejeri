<?php
//include $chemin . '/inc/error.php';

use PHPMailer\PHPMailer\PHPMailer;

$sel = '';
$tok = '';
if (isset($username_signup) || isset($email_signup) || isset($password_signup)) {

  try {
    $userId = $auth->registerWithUniqueUsername($_POST['email_signup'], $_POST['password_signup'], $_POST['username_signup'], function ($selector, $token) use (&$sel, &$tok) {
      $sel = $selector;
      $tok = $token;
      $connauth = new authBase();
      $uname = $_POST['username_signup'];
      $uemail = $_POST['email_signup'];
      $creaidcompte = \creerNumeroClient($uname);

      $requsers = "SELECT id FROM `users_sagaas` WHERE username like '" . $_POST['username_signup'] . "'";
      $u =  $connauth->oneRowAuth($requsers);
      $idauth = $u['id'];

      $magesquo = new MaGesquo($creaidcompte);
      $paramsusers = ['id' => $idauth, 'idcompte' => $creaidcompte, 'email' => $uemail];
      $insusers = "INSERT INTO `users_infos`(`id`,`idcompte`,`email`) VALUES (:id,:idcompte,:email)";
      $magesquo->handleRow($insusers, $paramsusers, 'insert', (int)$idauth, 'Ajout infos utilisateur de ' . $idauth . '/' . $creaidcompte);
      $insdroits = "INSERT INTO `users_droits`(`id`,`idcompte`) VALUES (:id,:idcompte)";
      $magesquo->handleRow($insdroits, $paramsusers, 'insert', (int)$idauth, 'Ajout droits utilisateur de ' . $idauth . '/' . $creaidcompte);
      $insconsents = "INSERT INTO `users_consents`(`id`,`idcompte`) VALUES (:id,:idcompte)";
      $magesquo->handleRow($insconsents, $paramsusers, 'insert', (int)$idauth, 'Ajout consentement utilisateur de ' . $idauth . '/' . $creaidcompte);
      $paramidcompte = ['idcompte' => $creaidcompte, 'id' => $idauth, 'email' => $uemail];
      $insidcompte = "INSERT INTO `idcompte`(`idcompte`,`referent_id`,`email`) VALUES (:idcompte,:id,:email)";
      $magesquo->handleRow($insidcompte, $paramidcompte, 'insert', (int)$idauth, 'Ajout entreprise de ' . $idauth . '/' . $creaidcompte);
      $params = ['idcompte' => $creaidcompte];
      $insidcompte_edition = "INSERT INTO `idcompte_edition`(`idcompte`) VALUES (:idcompte)";
      $magesquo->handleRow($insidcompte_edition, $params, 'insert', (int)$idauth, 'Ajout edition entreprise de ' . $idauth . '/' . $creaidcompte);
      $insidcompte_infos = "INSERT INTO `idcompte_infos`(`idcompte`) VALUES (:idcompte)";
      $magesquo->handleRow($insidcompte_infos, $params, 'insert', (int)$idauth, 'Ajout infos entreprise de ' . $idauth . '/' . $creaidcompte);
      $insidcompte_legal = "INSERT INTO `idcompte_legal`(`idcompte`) VALUES (:idcompte)";
      $magesquo->handleRow($insidcompte_legal, $params, 'insert', (int)$idauth, 'Ajout infos legale entreprise de ' . $idauth . '/' . $creaidcompte);
      $insidcompte_tarifs = "INSERT INTO `idcompte_tarifs`(`idcompte`) VALUES (:idcompte)";
      $magesquo->handleRow($insidcompte_tarifs, $params, 'insert', (int)$idauth, 'Ajout tarifs entreprise de ' . $idauth . '/' . $creaidcompte);
      $insidcompte_nova = "INSERT INTO `idcompte_nova`(`idcompte`) VALUES (:idcompte)";
      $magesquo->handleRow($insidcompte_nova, $params, 'insert', (int)$idauth, 'Ajout infos NOVA entreprise de ' . $idauth . '/' . $creaidcompte);
      $update_users_sagaas = "UPDATE users_sagaas SET idcompte=:idcompte WHERE id=:id";
      $magesquo->handleRow($update_users_sagaas, $paramsusers, 'update', (int)$idauth, 'Mise à jour utilisateur de ' . $idauth . '/' . $creaidcompte);
    });
  } catch (\Delight\Auth\InvalidEmailException $e) {
    $erreur = 'Format de l\'email invalide.';
    $code = "danger";
  } catch (\Delight\Auth\InvalidPasswordException $e) {
    $erreur = 'Format du mot de passe invalide.';
    $code = "danger";
  } catch (\Delight\Auth\UserAlreadyExistsException $e) {
    $erreur = 'Un compte avec cet email existe déjà';
    $code = "warning";
  } catch (\Delight\Auth\TooManyRequestsException $e) {
    $erreur = 'Trop de requête';
    $code = "danger";
  } catch (\Delight\Auth\DuplicateUsernameException $e) {
    $erreur = 'Pseudo déjà utilisé';
    $code = "warning";
  }

  if ($sel && $tok) {
    $chemin = $_SERVER['DOCUMENT_ROOT'];
    $smtp_data = include $chemin . '/config/smtp.php';
    $mail = new PHPMailer();
    $email_signup = $_POST['email_signup'];
    $username_signup = $_POST['username_signup'];
    $url = 'https://app.enooki.com/verify?selector=' . \urlencode($sel) . '&token=' . \urlencode($tok);
    try {
      //Server settings
      // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
      $mail->isSMTP();                                            //Send using SMTP
      $mail->Host       = $smtp_data['host'];
      $mail->SMTPAuth   = true;                  //Set the SMTP server to send through
      // $mail->SMTPAuthTLS   = true;                                   //Enable SMTP authentication
      $mail->Username   = $smtp_data['username'];                     //SMTP username
      $mail->Password   = $smtp_data['password'];
      // $mail->ConfirmReadingTo = $msect;
      //SMTP password
      //$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;           //Enable implicit TLS encryption
      $mail->Port       = $smtp_data['port'];                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`// 465
      $mail->setFrom('noreply@magesquo.com', '[MaGESQUO] Inscription');
      $mail->addAddress($email_signup);
      //$mail->addCC($email_signup);
      $mail->addBCC('contact@sagaas.fr');
      $mail->isHTML(true);                                  //Set email format to HTML
      $mail->Subject = '[MaGESQUO] - Validation de votre compte';
      $mail->Body    =  '<body style="background-color:#fff"><div  style="padding:20px; width:650px; border:#b3b3b3 0px solid; margin:auto; background-color:#FFF; border-radius:3px"><table width="100%" border="0" cellspacing="0" cellpadding="3"><tr><td valign="top"><p>Bonjour ' . $username_signup . '</p><p><b>Afin de valider votre compte, suivez le lien suivant : </p><a href="' . $url . '">' . $url . '</a></td></tr></table></div></body>';
      $erreur = "Un email de validation a été envoyer sur " . $email_signup;
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
