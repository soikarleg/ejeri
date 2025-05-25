<?php
setcookie('limit', '', time() - 3600, '/');
$hash_deconnexion = 'sortie';


if ($url === $hash_deconnexion) {
  $auth->logOut();

  session_unset();
  session_destroy();
  $erreur = '<span class="text-warning">Vous êtes déconnecté</span>';
  $code = "warning";
}
