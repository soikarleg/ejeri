<?php

namespace PHPMailer\PHPMailer;

$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/vendor/autoload.php';
include $chemin . '/inc/function.php';
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');


$mail = new PHPMailer(true);

// VARIABLES $_SESSION
session_start();
$secteur = $_SESSION['idcompte'];

// VARIABLES $_POST
foreach ($_POST as $var => $val) {
  ${$var} = $val;
  // echo "$" . $var . "=" . $val . " </br> ";
}


$dernier_car = substr($email, -1);
if ($dernier_car == ",") {
  $email = rtrim($email, ",");
}


$em = explode(',', $email);
foreach ($em as $e) {
  // echo $e . '</br>';
}


$titre = strEncoding('Adresse partagée');

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
  $mail->setFrom('noreply@sagaas.fr', '[' . $s . ']');

  foreach ($em as $e) {
    $mail->addCC($e);
  }
  //$mail->addAddress($email);     //Add a recipient
  //$mail->addAddress($msect, $nomcs);                 //Name is optional
  //$mail->addReplyTo($msect);

  // $mail->addCC('cc@example.com');
  $mail->addBCC('noreply@sagaas.fr');

  //Attachments
  // $mail->addAttachment('../ns/pdf/' . $rep . '/' . $titre . '-' . $numero . '-' . $prenom . '-' . $nom . '.pdf');         //Add attachments
  // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
  //mb_convert_encoding(
  //Content
  $mail->isHTML(true);                                  //Set email format to HTML
  $mail->Subject = strEncoding('[' . $s . '] - Adresse de ' . $nom_cli);
  $mail->Body    =  '
        <body style="background-color:#efefef">
            <div  style="padding:10px; width:70%; border:#000000 0px solid; margin:auto; background-color:#ffffff; border-radius:3px">
                <table width="100%" border="0" cellspacing="0" cellpadding="3">
                    <tr>
                        <td valign="top">
                            <p><b>' . $titre . '</b></p>
                            <p>' . strEncoding($coord) . '</p><p>' . strEncoding($commentaire) . '</p>
                        </td>

                    </tr>
                </table>
            </div>
        </body>';
  $mail->AltBody = 'Adresse : ' . strEncoding($coord) . ' ' . strEncoding($commentaire);
  $mail->send();

  foreach ($em as $e) {
    // LogA('Envoi adresse en partage', $idconn, 'Adresse de ' . $nom_cli . ' a ' . $e);
  }
?>
  <div class="card-body">
    <p class="text-success text-bold ">L'adresse a été partagée avec <?= $email ?></p>
  </div>


<?php

} catch (Exception $e) {

?>
  <div class="card-body">
    <p class="titre"><i class="bx bx-error icon-bar icon_red bx-flxxx"></i><b>Mail non envoyé. Vous avez une erreur : </b></br><?= $mail->ErrorInfo ?></p>
  </div>
<?php
};





?>