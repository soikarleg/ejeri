<?php
if (isset($username) && isset($password)) {
  $pattern = "/^((?!\.)[\w_.-]*[^.])(@\w+)(\.\w+(\.\w+)?[^.\W])$/";
  $m = preg_match($pattern, $username);
  if ($m != 0) {
    //echo 'auth par mail ' . $username;
  } else {
    //echo 'auth par username ' . $username;
  }
  $em = '';
  try {
    $auth->loginWithUsername($username, $password, function () use (&$em) {});
    $conn = new connBase();
    $requsername = "select idcompte,id,email from users_sagaas where username='$username' limit 1";
    $username_verification = $conn->oneRow($requsername);
    $idcompte = $username_verification['idcompte'];
    $idusers = $username_verification['id'];
    $email = $username_verification['email'];
    $em = $email;
    $_SESSION['idcompte'] = $idcompte;
    $_SESSION['idusers'] = $idusers;
    if (empty($_SESSION['csrf_token'])) {
      $bit = random_int(24, 48);
      $csrf = 'ovgx' . bin2hex(random_bytes($bit));
      $_SESSION['csrf_token'] = strtoupper($csrf);
    }
    $dateheure = date('d/m/Y H:i:s');
    $conn->insertLog('Connexion', $idusers, $dateheure);
    // $expiration_session = 5;
    // $expiration = time() + $expiration_session * 60;
    // setcookie('limit', 'inboundx', $expiration, '/');
  } catch (\Delight\Auth\InvalidEmailException $e) {
    $erreur = 'Erreur de mail';
    $code = "danger";
  } catch (\Delight\Auth\InvalidPasswordException $e) {
    $erreur = 'Erreur de mot de passe';
    $code = "danger";
  } catch (\Delight\Auth\EmailNotVerifiedException $e) {
    $erreur = 'Compte non validé.</br>Vérifiez vos emails et suivez le lien de validation.</br><a href="/myre">Renvoyer un lien de validation ' . $em . '</a>';
    $code = "warning";
  } catch (\Delight\Auth\TooManyRequestsException $e) {
    $erreur = 'Trop de tentative. Blocage ' . $e;
    $code = "danger";
  } catch (\Delight\Auth\UnknownUsernameException $e) {
    $erreur = 'Compte inconnu';
    $code = "warning";
  } catch (\Delight\Auth\AmbiguousUsernameException $e) {
    $erreur = 'Plusieurs ' . $username . ' dans la base.';
    $code = "danger";
  }
}
$logged = $auth->isLoggedIn();
