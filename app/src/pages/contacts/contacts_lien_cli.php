<?php
session_start();
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$conn = new connBase();

$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];

$mail = new  MyMail('Votre espace client', 'noreply@sagaas.fr');
$val = "";
foreach ($_POST as $k => $v) {
  ${$k} = $v;
  //echo '$' . $k . '= ' . $v . '<br class=""> ';
};

$secteur = $conn->askIdcompte($secteur);
//var_dump($secteur);
$client = NomClient($idcli);
$gestion = NomSecteur($secteur);
$signataire = NomColla($iduser);

$message = file_get_contents('../txt/lien_cli.txt');
$message_aff = nl2br($message);
$message_aff = str_replace('{$client}', $client, $message_aff);
$message_aff = str_replace('{$numero}', $idcli, $message_aff);
$message_aff = str_replace('{$gestion}', $gestion, $message_aff);
$message_aff = str_replace('{$signataire}', $signataire, $message_aff);
$message_env = strEncoding($message_aff);

$message = str_replace('{$client}', $client, $message);
$message = str_replace('{$numero}', $idcli, $message);
$message = str_replace('{$gestion}', $gestion, $message);
$message = str_replace('{$signataire}', $signataire, $message);
$message = strEncoding($message);
$sujet = strEncoding('Création de votre compte client ' . $secteur['denomination']);

if ($val == "ok") {
  $aff =  $mail->sendEmail($sujet, $message_env, $email);
?>
  <p class="text-bold"><?= $aff ?></p>
<?php  } else {


?>


  <p class="mb-2">Destinataire : <?= $email ?></p>
  <div class="border-dot">
    <a href="#" class="btn btn-mag-n mr-1 mt-2 ml-2 pull-right" onclick="ajaxData('idcli=<?= $idcli ?>&email=<?= $email ?>&val=ok', '../src/pages/contacts/contacts_lien_cli.php', 'action', 'attente_target');"><i class='bx bxs-hot icon-bar bx-flxxx text-warning'></i>Envoyer le message</a>
    <p><?= $message_aff ?></p>
  </div>
<?php
}
?>