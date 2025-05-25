<?php

//pretty($_COOKIE);

$expiration_session = 120; // en minutes
if (!isset($_COOKIE['limit'])) {
  $expiration = time() + $expiration_session * 60;
  setcookie('limit', 'inbound', $expiration, '/');
}

$mode = !isset($_COOKIE['mode']) ? 'nuit' : $_COOKIE['mode'];
if (!isset($mode)) {
  $expiration = time() + 3600 * 24 * 30; // Expiration dans 30 jours
  setcookie('mode', 'jour', $expiration, '/');
}

if (!isset($_COOKIE['dateprod']) or !isset($_COOKIE['weekprod'])) {
  $expiration = time() + 3600 * 24 * 30; // Expiration dans 30 jours
  $cd = date('d/m/Y');
  $cw = date('W');
  setcookie('dateprod', $cd, $expiration, '/');
  setcookie('weekprod', $cw, $expiration, '/');
}
