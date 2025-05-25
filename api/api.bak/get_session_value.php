<?php
//session_start();
header('Content-Type: application/json');

if (empty($_SESSION['idcompte'])) {
  echo json_encode([
    'success' => false,
    'error' => 'Session invalide ou expirée',
  ]);
} else {
  echo json_encode([
    'success' => true,
    'sessionId' => $_SESSION['idcompte'],
  ]);
}

