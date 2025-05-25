<?php

namespace PHPMailer\PHPMailer;

use connBase;

session_start();
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/vendor/autoload.php';
include $chemin . '/inc/function.php';
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');

$conn = new connBase();
$mail = new PHPMailer(true);

// infos du secteur
$infos_secteur = $conn->askIdcompte($secteur, 'email,secteur,denomination');
$email_secteur = $infos_secteur['email'];
$nom_secteur = $infos_secteur['secteur'];

// VARIABLES $_POST
foreach ($_POST as $var => $val) {
  ${$var} = $val;
  //echo "$" . $var . "=" . $val . " </br> ";
}
$dernier_car = substr($emailcli, -1);
if ($dernier_car == ",") {
  $emailcli = rtrim($emailcli, ",");
}

$verif_plusieurs_email = strpos($emailcli, ",");

$em = array();
if ($verif_plusieurs_email != false) {
  $nbremail = 1;
  $emails = explode(',', $emailcli);


  foreach ($emails as $e) {
    $email = $e;
    $em[] = $email;
  }
} else {
  $nbremail = 0;
  $em[] = $emailcli;
}

if ($_FILES["fichier"]["name"] != "") {
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier s'il y a des erreurs lors de l'envoi du fichier
    if ($_FILES["fichier"]["error"] == UPLOAD_ERR_OK) {
      $nomFichier = $_FILES["fichier"]["name"];
      $typeFichier = $_FILES["fichier"]["type"];
      $tailleFichier = $_FILES["fichier"]["size"];
      $fichierTemporaire = $_FILES["fichier"]["tmp_name"];

      // Vous pouvez maintenant manipuler le fichier temporaire, le déplacer, le copier, etc.
      // Par exemple, pour le déplacer vers un emplacement permanent sur le serveur :
      $nouvelEmplacement = "/tmp/" . $nomFichier;
      move_uploaded_file($fichierTemporaire, $nouvelEmplacement);

      echo "Fichier téléchargé avec succès : $nomFichier";
    } else {
      echo "Erreur lors du téléchargement du fichier. " . $_FILES["fichier"]["error"] . "";
    }
  }
}

$titre = mb_convert_encoding('Adresse partagée', 'ISO-8859-1');
$logo = "https://app.enooki.com/documents/img/$secteur/logo.png";

try {
  //Server settings
  //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
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

  if ($infos_secteur['denomination'] === '') {
    $s = NomSecteur($secteur);
  } else {
    $s = $infos_secteur['denomination'];
  }
  $sect = strtolower(NomSecteur($secteur));
  $sect = str_replace(' ', '', $sect);
  $mail->setFrom('noreply@sagaas.fr', '[' . $s . ']');
  //$mail->setFrom($secteur . '@sagaas.fr', '[' . $nom_secteur . ']');
  //$mail->setFrom($email_secteur, '[' . $nom_secteur . ']');
  // $mail->From = $email_secteur;
  // $mail->FromName = '[' . $nom_secteur . ']';

  foreach ($em as $e) {
    $mail->addAddress($e);
  }


  // $mail->addAddress($emailcli);
  $mail->addReplyTo($email_secteur, '[' . $s . ']');
  $mail->addBCC($email_secteur, '[' . $s . ']');

  //Attachments
  $nomFichier = $nomFichier == null ? "" : $nomFichier;
  if ($nomFichier) {
    $mail->addAttachment("/tmp/" . $nomFichier);         //Add attachments
  }
  // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

  //Content
  $mail->isHTML(true);
  $sujet = $sujet === "" ? '[' . $s . '] - Message' : '[' . $s . '] - ' . $sujet;

  $titre = explode('-', $sujet);
  $titre = trim($titre[1]);
  $mail->Subject = mb_convert_encoding($sujet, 'ISO-8859-1');
  $mail->Body    = '
  <body style="background-color:#eeeeee">
      <div  style="padding:10px; width:70%; border:#ddd 1px dotted; margin:auto; background-color:#ffffff; border-radius:3px">
          <div style="text-align:center">
            <img src="' . $logo . '" alt="Logo" height="80px">
          </div>
          <table width="100%" border="0" cellspacing="0" cellpadding="3">
              <tr>
                  <td valign="top">
                      <p>Objet : <b>' . mb_convert_encoding($titre, 'ISO-8859-1') . '</b></p>
                      <p>' . mb_convert_encoding($message, 'ISO-8859-1') . '</p>
                  </td>

              </tr>

          </table>
      </div>
  </body>';

  $mail->send();
  $message_court = explode('<br>', $message);
  $message_court = $message_court[1];
  $message_court = Tronque($message_court, 60);
  $message_court = addslashes($message_court);
  //var_dump($nomFichier);
  $pj = $nomFichier === null ? "" : "pj : \'" . $nomFichier . "\'";
  $conn->insertLog('Envoi mail ', $iduser, $titre . ' - ' . $message_court .  ' sur ' . $emailcli . ' ' . $pj);


  $logfile = 'contact_envoi_mail.log';
  $logMessage = 'Envoi mail ok : ' . $mail->getToAddresses()[0];
  error_log($logMessage, 3, $logfile);



?>
  <div class="card-body">
    <p class="text-success text-bold ">Message envoyé sur <?= $emailcli ?></p>
  </div>


<?php

} catch (Exception $e) {

  $conn->insertLog('Envoi mail **ERR** ', $iduser, 'Pour ' . $emailcli . ' ' . $pj . ' // ' . $mail->ErrorInfo);
  $logfile = 'contact_err_envoi_mail.log';
  $logMessage = 'Erreur : ' . $mail->ErrorInfo . ' ' . $e->getMessage();
  error_log($logMessage, 3, $logfile);
  // print_r($e);
?>
  <div class="card-body">
    <p class="titre"><i class="bx bx-error icon-bar icon_red bx-sm"></i><b>Mail non envoyé. Vous avez une erreur : </b></br><?= $mail->ErrorInfo ?></p>
  </div>
<?php
};





?>