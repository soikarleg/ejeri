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
$message_brut = $_POST['message'];
//echo $_POST['piece'];
foreach ($_POST as $key => $value) {
  ${$key} = $ins->valFull($value);
  //echo '$' . $key . ' = ' . $value . '<br>';
}
?>
<p class="text-bold mb-2">Envoi du mail de relance</p>
<?php
$devis_liste = $conn->askAllDevis($secteur, "and annee like '20%' ");
$mail_client = $conn->askClient($idcli, 'email,nom,prenom,civilite');
$mail_secteur = $conn->askIdcompte($secteur, 'email,denomination');
$path = $devis->getCheminRelance($idcli);
$verification_fichier = $devis->getRelance($idcli);
if ($verification_fichier != 0) {
  $piece_jointe = $path . '/' . $verification_fichier;
}
$email_cli;
$mail_secteur['email'];
$piece_jointe;
$cgv = $chemin . "/documents/pdf/factures/$secteur/cgv.pdf";
$logo = "https://app.enooki.com/documents/img/$secteur/logo.png";
//mb_convert_encoding($l['designation'], 'ISO-8859-1');
$nomcliinfos = mb_convert_encoding($mail_client['civilite'] . ' ' . $mail_client['prenom'] . ' ' . $mail_client['nom'], 'ISO-8859-1');
$message = nl2br($message_brut);
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

  if ($mail_secteur['denomination'] === '') {
    $s = NomSecteur($secteur);
  } else {
    $s = $mail_secteur['denomination'];
  }
  $sect = strtolower(NomSecteur($secteur));
  $sect = str_replace(' ', '', $sect);
  $mail->setFrom('noreply@sagaas.fr', '[' . $s . ']');
  // foreach ($em as $e) {
  //   $mail->addCC($e);
  // }
  //$mail->addAddress($email);     //Add a recipient
  $mail->addAddress($email_cli, $nomcliinfos);                 //Name is optional
  $mail->addReplyTo($mail_secteur['email'], '[' . $s . ']');
  //$mail->addCC('cc@example.com');
  //$mail->addCC($email_cli, $nomcliinfos);
  $mail->addBCC($mail_secteur['email'], '[' . $s . ']');
  $mail->addBCC('noreply@sagaas.fr', 'MAGESQUO utilisateur [' . $s . ']');
  //Attachments
  $mail->addAttachment($piece_jointe);         //Devis
  //$mail->addAttachment($cgv);    //CGV
  //Content
  $mail->isHTML(true);                                  //Set email format to HTML
  $mail->Subject = mb_convert_encoding('[' . $s . '] - Informations règlements', 'ISO-8859-1');
  $mail->Body    =  '
        <body style="background-color:#eeeeee">
            <div  style="padding:10px; width:70%; border:#ddd 1px dotted; margin:auto; background-color:#ffffff; border-radius:3px">
                <div style="text-align:center">
                  <img src="' . $logo . '" alt="Logo" height="80px">
                </div>
                <table width="100%" border="0" cellspacing="0" cellpadding="3">
                    <tr>
                        <td valign="top">
                            <p><b>' . $titre . '</b></p>
                            <p>' . mb_convert_encoding($message, 'ISO-8859-1') . '</p>
                        </td>

                    </tr>
                </table>
            </div>
        </body>';
  $mail->AltBody = 'Relance : ' . mb_convert_encoding($message, 'ISO-8859-1');
  $mail->send();

  $conn->insertLog('Envoi relance', $iduser, 'Relance pour ' . $nomcliinfos . ' ' . $verification_fichier);

?>
  <div class="border-dot">
    <p class="text-success text-bold ">La relance <?= $verification_fichier ?> a été envoyée </p>
    <p>Sur <?= $email_cli ?>, pour le client.</p>
    <p>Sur <?= $mail_secteur['email'] ?>, en copie pour vous.</p>
  </div>
  <div class="text-right">

    <input name="Envoyer" type="button" class="btn btn-mag-n text-primary" value="Retour" onclick="ajaxData('cs=cs', '../src/pages/factures/factures_relance.php', 'action', 'attente_target');" />
  </div>
<?php
} catch (Exception $e) {
?>
  <div class="border-dot">
    <p class="titre"><i class="bx bx-error icon-bar icon_red bx-sm"></i><b>Mail non envoyé. Vous avez une erreur : </b></br><?= $mail->ErrorInfo ?></p>
  </div>
<?php
};
?>