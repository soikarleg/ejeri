<?php

$validation_mdp = '';

if (isset($new_mdp)) {
  if (
    strlen($new_password) >= 12 &&
    preg_match('/[A-Z]/', $new_password) &&
    preg_match('/[a-z]/', $new_password) &&
    preg_match('/\d/', $new_password) &&
    preg_match('/[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/', $new_password) &&
    $new_password === $new_password_conf
  ) {
    $validation_mdp = 1;
  } else {
    $validation_mdp = 0;
  }
}


// if (isset($new_mdp)) {
//   $pattern = "/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^\w\d\s:])([^\s]){12,}$/";
//   $validation_mdp = preg_match($pattern, $new_mdp);
// }

if ($validation_mdp === 0) {
  $erreur = "Forme du mot de passe invalide<br>Veuillez renouveller votre demande";
  $code = "danger";
}
//echo $new_password_conf . ' ' . $token . ' ' . $selector;
if (isset($new_password) && $validation_mdp === 1) {

  try {
    $auth->resetPassword($selector, $token, $new_password);
    $erreur = 'Le mot de passe à été réinitialisé<br>Vous pouvez vous connecter';
    $code = "success";
  } catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
    $erreur = ('Lien de réinitialisation obsolète.<br>Veuillez renouveller votre demande.');
    $code = "warning";
  } catch (\Delight\Auth\TokenExpiredException $e) {
    $erreur = ('Lien de réinitialisation obsolète.<br>Veuillez renouveller votre demande.');
    $code = "warning";
  } catch (\Delight\Auth\ResetDisabledException $e) {
    $erreur = ('Renouvellement de mot de passe impossible');
    $code = "warning";
  } catch (\Delight\Auth\InvalidPasswordException $e) {
    $erreur = ('Mot de passe invalide');
    $code = "warning";
  } catch (\Delight\Auth\TooManyRequestsException $e) {
    $erreur = ('Trop de demande');
    $code = "danger";
  }
}
