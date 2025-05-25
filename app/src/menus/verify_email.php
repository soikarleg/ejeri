<?php
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/vendor/autoload.php';
include $chemin . '/inc/dbconn.php';

$conn = Connexion();
$auth = new Delight\Auth\Auth($conn);
foreach ($_GET as $k => $v) {
  ${$k} = $v;
  echo '$' . $k . ' ' . $v . '</br>';
}
include $chemin . '/inc/head.php';
if (isset($selector) || isset($token)) {
  $message = "";
  try {
    $auth->confirmEmail($_GET['selector'], $_GET['token']);

    $success =  'Votre email a été validé';
  } catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
    $message = 'Token invalide';
  } catch (\Delight\Auth\TokenExpiredException $e) {
    $message = 'Token à expiré';
  } catch (\Delight\Auth\UserAlreadyExistsException $e) {
    $message = 'Cette adresse email est déjà validée.';
  } catch (\Delight\Auth\TooManyRequestsException $e) {
    $message = 'Trop de requette';
  }
}
include $chemin . '/inc/error.php';
?>
<div class="container text-center ">
  <p class="text-primary py-5">MaGESQUO</p>
  <?php
  if ($message) {
  ?>
    <p class="text-danger"><?= $message ?></p>
    <a href=" https://app.enooki.com">Retour au site</a>
  <?php
  } else {
  ?>
    <p><?= $success ?></p>
    <a href="https://app.enooki.com">Connectez-vous</a>
  <?php
  }
  ?>
</div>