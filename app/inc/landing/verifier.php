<?php
if ($url === 'verify') {

  if (isset($selector) || isset($token)) {

    try {
      $auth->confirmEmail($selector, $token);
      $erreur = "Votre adresse à été validée";
      $code = 'success';
    } catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
      $erreur = 'Token invalide';
      $code = 'danger';
    } catch (\Delight\Auth\TokenExpiredException $e) {
      $erreur = 'Token à expiré';
      $code = 'danger';
    } catch (\Delight\Auth\UserAlreadyExistsException $e) {
      $erreur = 'Cette adresse email est déjà validée';
      $code = 'muted';
    } catch (\Delight\Auth\TooManyRequestsException $e) {
      $erreur = 'Trop de requette';
      $code = 'danger';
    }
  }
}
