<?php

namespace PHPMailer\PHPMailer;

use connBase;
use FormValidation;
use Factures;

session_start();
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
include $chemin . '/vendor/autoload.php';
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
$data = $_POST;
$mail = new PHPMailer(true);
$conn = new connBase();
$ins = new FormValidation($data);
$devis = new Factures($secteur);
$email_cli = $_POST['email_client'];
//$message_brut = $_POST['message'];
foreach ($_POST as $key => $value) {
  ${$key} = $ins->valFull($value);
  //echo '$' . $key . ' = ' . $value . '<br>';
}

?>
<?php
$mail_secteur = $conn->askIdcompte($secteur, 'email');
$mail_secteur['email'];
//mb_convert_encoding($l['designation'], 'ISO-8859-1');
$nomcliinfos = mb_convert_encoding($mail_client['civilite'] . ' ' . $mail_client['prenom'] . ' ' . $mail_client['nom'], 'ISO-8859-1');
$titre = mb_convert_encoding('Bugs & Améliorations', 'ISO-8859-1');
$message = nl2br($message);
$logo = "https://sagaas.fr/assets/img/icon/noir/png/bxs-bug.png";
try {
  //Server settings
  // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
  $mail->isSMTP();                                            //Send using SMTP
  $mail->Host       = 'smtp.ionos.fr';
  $mail->SMTPAuth   = true;                  //Set the SMTP server to send through
  // $mail->SMTPAuthTLS   = true;                                   //Enable SMTP authentication
  $mail->Username   = 'noreply@sagaas.fr';                     //SMTP username
  $mail->Password   = 'FranLeg68FLGA-:!010268-:!';
  //$mail->ConfirmReadingTo = $msect;
  //SMTP password
  //$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;           //Enable implicit TLS encryption
  $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`// 465
  //Recipients
  $s = NomSecteur($secteur);
  $mail->setFrom($mail_secteur['email'], '[' . $s . ']');
  // foreach ($em as $e) {
  //   $mail->addCC($e);
  // }
  //$mail->addAddress($email);     //Add a recipient
  $mail->addAddress($mail_secteur['email'], '[' . $s . ']');                 //Name is optional
  $mail->addReplyTo($mail_secteur['email'], '[' . $s . ']');
  //$mail->addCC('cc@example.com');
  //$mail->addCC($email_cli, $nomcliinfos);
  //$mail->addBCC($mail_secteur['email'], '[' . $s . ']');
  $mail->addBCC('flxxx@flxxx.fr', '[ B&A - ' . $s . ']');
  //Content
  $mail->isHTML(true);                                  //Set email format to HTML
  $mail->Subject = mb_convert_encoding('[' . $s . '] - Bug & Amélioration', 'ISO-8859-1');
  $mail->Body    =  '
        <body style="background-color:#ffffff">
            <div  style="padding:10px; width:70%; border:#ddd 1px dotted; margin:auto; background-color:#ffffff; border-radius:3px">
                <div style="text-align:center">
                <img src="' . $logo . '" alt="Logo" height="80px">
                <h2><b>' . $titre . '</b> </h2>
                <p><span class="text_muted small">' . mb_convert_encoding(date("d/m/Y") . ' à ' . date("h:i") . '</span></p>
                <p><b>' . NomSecteur($idcompte) . ' - ' . ($secteur) . '</b>', 'ISO-8859-1') . '</p>
                </div>
                <table width="100%" border="0" cellspacing="0" cellpadding="3">
                    <tr>
                        <td valign="top">
                            <hr>
                            <p>' . mb_convert_encoding($page, 'ISO-8859-1') . '</p>
                            <hr>
                            <p>' . mb_convert_encoding($message, 'ISO-8859-1') . '</p>
                        </td>
                    </tr>
                </table>
            </div>
        </body>';
  $mail->AltBody = 'Bug : ' . mb_convert_encoding($message, 'ISO-8859-1');
  $mail->send();
  $insert_bug = "INSERT INTO `bug`(`idcompte`, `page`, `message`) VALUES ('$idcompte','$page','$message')";
  $nbr = $conn->handleRow($insert_bug);
  $log = $conn->insertLog('Envoi bug', $iduser, $message . ' ' . $page);
?>
  <div class="bug_reponse">
    <p class="text-success text-bold "><i class='bx bxs-like mr-1'></i><span class="text-muted mr-1 ">N°<?= $nbr . ' ' . $log ?></span></p>
  </div>
<?php
} catch (Exception $e) {
?>
  <div class="">
    <p class="titre"><i class="bx bx-error icon-bar icon_red bx-sm"></i><b>Mail non envoyé. Vous avez une erreur : </b><?= $mail->ErrorInfo ?></p>
  </div>
<?php
};
?>