<?php
//$chemin = __DIR__;
$chemin = $_SERVER['DOCUMENT_ROOT'];

// require $chemin . '/class/MyMail.php';
//require  $chemin . '/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
//? *****************************************************
//? Ajouter Attachement, Bcc et traitement array(email,email,...) - DEBUT
//? Ajouter Attachement, Bcc et traitement array(email,email,...) - FIN
//? *****************************************************
class MyMail
{
  private $from_email;
  private $from_name;
  private $password;
  private $smtp_host;
  private $smtp_port;
  private $idcompte;
  private $iduser;

  public function __construct($from_name, $from_email)
  {
    $this->from_email = $from_email;
    $this->from_name = $from_name;
    $this->idcompte = $_SESSION['idcompte'];
    $this->iduser = $_SESSION['idusers'];
  }

  private function configHost()
  {
    $chemin = $_SERVER['DOCUMENT_ROOT'];
    $smtp = include $chemin . '/config/smtp.php';

    $this->password = $smtp['password'];
    $this->smtp_host = $smtp['host'];
    $this->smtp_port = $smtp['port'];
  }
  /**
   * configHost() -> sendEmail()
   *
   * Appliquer
   *  strEncoding($subject)
   *  strEncoding($body)
   *
   * @param  mixed $subject
   * @param  mixed $body
   * @param  mixed $to_emails
   * @param  mixed $bcc
   * @param  mixed $attachement
   *
   */
  public function sendEmail($subject, $body, $to_emails, $bcc = 'flxxx@flxxx.fr',  $attachement = '')
  {
    $dernier_car = substr($to_emails, -1);
    if ($dernier_car == ",") {
      $to_emails = rtrim($to_emails, ",");
    }
    $to_emails = explode(',', $to_emails);
    $mail = new PHPMailer(true);
    $this->configHost();
    try {
      $mail->isSMTP();
      $mail->SMTPAuth = true;

      $mail->Host       = $this->smtp_host;
      $mail->Port       = $this->smtp_port;
      $mail->Username   = $this->from_email;
      $mail->Password   = $this->password;

      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

      $mail->setFrom($this->from_email, $this->from_name);
      $mail->addAddress($to_emails[0]);
      $i = 1;
      foreach ($to_emails as $e) {
        if (!empty($e)) {
          $mail->addCC($e);
          $log = new MaGesquo($this->idcompte);
          $log->insertLog('Envoi mail',$this->iduser,'Envoi mail : '.$subject.' '.$e);
        }
        $i++;
      }
      if ($bcc != '') {
        $mail->addBcc($bcc, '*** Activite MaGESQUO ***');
      }
      if ($attachement != '') {
        $mail->addAttachment($attachement);
      }
      $mail->CharSet = 'UTF-8';
      $mail->Encoding = 'base64';
      $mail->isHTML(true);
      $mail->Subject = $subject;
      $mail->Body    = $body;
      $mail->send();
     
      return 'Email envoyé';
    } catch (Exception $e) {
      return "Erreur lors de l'envoi :): {$mail->ErrorInfo}";
    }
  }
}
